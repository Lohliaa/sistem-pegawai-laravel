<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PejabatPenilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    private function getPejabatNamesForUser($user): array
    {
        $names = [];

        if ($user->pegawai_id) {
            $pejabatDirect = PejabatPenilai::where('pegawai_id', $user->pegawai_id)->pluck('nama')->toArray();
            $names = array_merge($names, $pejabatDirect);
        }

        $pejabatViaPegawai = PejabatPenilai::whereHas('pegawai', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('nama')->toArray();
        $names = array_merge($names, $pejabatViaPegawai);

        if ($user->pegawai) {
            $names[] = $user->pegawai->nama;
            if ($user->pegawai->pejabatPenilai) {
                $names[] = $user->pegawai->pejabatPenilai->nama;
            }
        }

        return array_values(array_unique(array_filter($names)));
    }

    private function authorizePejabat(Pengajuan $pengajuan): bool
    {
        $user = Auth::user();
        if (in_array($user->role, ['kanit', 'kabid'])) {
            $namaPejabat = $this->getPejabatNamesForUser($user);
            if (!in_array($pengajuan->pimpinan_atasan, $namaPejabat)) {
                return false;
            }
        }
        return true;
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 'staf') {
            $pengajuan = Pengajuan::where('created_by', $user->id)->latest()->get();
        } elseif ($user->role == 'kanit' || $user->role == 'kabid') {
            $namaPejabat = $this->getPejabatNamesForUser($user);

            if (empty($namaPejabat)) {
                $pengajuan = Pengajuan::where('id', 0)->get(); 
            } else {
                if ($user->role == 'kanit') {
                    $pengajuan = Pengajuan::where('status', 'pending')
                        ->whereIn('pimpinan_atasan', $namaPejabat)
                        ->latest()
                        ->get();
                } else { // kabid
                    $pengajuan = Pengajuan::whereIn('status', ['pending', 'approved_kanit'])
                        ->whereIn('pimpinan_atasan', $namaPejabat)
                        ->latest()
                        ->get();
                }
            }
        } else {
            $pengajuan = Pengajuan::with('creator')->latest()->get();
        }

        return view('pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $pejabatList = PejabatPenilai::all();
        return view('pengajuan.create', compact('pejabatList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_pengajuan' => 'required|string|in:MOU,SK,Lainnya',
            'tipe_pengajuan_lainnya' => 'required_if:tipe_pengajuan,Lainnya|nullable|string|max:50',
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tanggal_tmt' => 'required|date',
            'unit' => 'required|string|max:50',
            'pimpinan_atasan' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'file_pengajuan' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($request->tipe_pengajuan === 'Lainnya' && !empty($validated['tipe_pengajuan_lainnya'])) {
            $validated['tipe_pengajuan'] = $validated['tipe_pengajuan_lainnya'];
        }

        unset($validated['tipe_pengajuan_lainnya']);

        if ($request->hasFile('file_pengajuan')) {
            $validated['file_pengajuan'] = $request->file('file_pengajuan')->store('pengajuan_files', 'public');
        }

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        Pengajuan::create($validated);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dibuat!');
    }

    public function show(Pengajuan $pengajuan)
    {
        if (!$this->authorizePejabat($pengajuan)) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $pengajuan->load(['creator', 'approverKanit', 'approverKabid']);
        return view('pengajuan.show', compact('pengajuan'));
    }

    public function riwayat()
    {
        $user = Auth::user();

        if ($user->role == "kanit" || $user->role == "kabid") {
            $namaPejabat = $this->getPejabatNamesForUser($user);

            if (empty($namaPejabat)) {
                $pengajuan = Pengajuan::where("id", 0)->get();
            } else {
                if ($user->role == "kanit") {
                    $pengajuan = Pengajuan::whereIn("status", ["approved_kanit", "approved_kabid", "rejected"])
                        ->whereIn("pimpinan_atasan", $namaPejabat)
                        ->latest()
                        ->get();
                } else {
                    $pengajuan = Pengajuan::whereIn("status", ["approved_kabid", "rejected"])
                        ->whereIn("pimpinan_atasan", $namaPejabat)
                        ->latest()
                        ->get();
                }
            }
        } else {
            $pengajuan = Pengajuan::whereIn("status", ["approved_kanit", "approved_kabid", "rejected"])
                ->latest()
                ->get();
        }

        return view("pengajuan.riwayat", compact("pengajuan"));
    }

    public function destroy(Pengajuan $pengajuan)
    {
        // Only allow delete if status is pending and user is creator
        if ($pengajuan->status == 'pending' && $pengajuan->created_by == Auth::id()) {
            $pengajuan->delete();
            return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
        }

        return back()->with('error', 'Tidak dapat menghapus pengajuan ini!');
    }

    public function approve(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();

        if (!$this->authorizePejabat($pengajuan)) {
            return back()->with('error', 'Anda tidak berhak menyetujui pengajuan ini!');
        }

        if ($user->role == 'kanit' && $pengajuan->status == 'pending') {
            $pengajuan->update([
                'status' => 'approved_kanit',
                'approved_by_kanit' => $user->id,
                'approved_date_kanit' => now(),
                'catatan_kanit' => $request->catatan,
            ]);
            return back()->with('success', 'Pengajuan berhasil disetujui!');
        }

        if ($user->role == 'kabid' && ($pengajuan->status == 'pending' || $pengajuan->status == 'approved_kanit')) {
            $pengajuan->update([
                'status' => 'approved_kabid',
                'approved_by_kabid' => $user->id,
                'approved_date_kabid' => now(),
                'catatan_kabid' => $request->catatan,
            ]);
            return back()->with('success', 'Pengajuan berhasil disetujui!');
        }

        return back()->with('error', 'Tidak dapat menyetujui pengajuan ini!');
    }

    public function reject(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();

        if (!$this->authorizePejabat($pengajuan)) {
            return back()->with('error', 'Anda tidak berhak menolak pengajuan ini!');
        }

        if (in_array($user->role, ['kanit', 'kabid']) && ($pengajuan->status == 'pending' || $pengajuan->status == 'approved_kanit')) {
            $pengajuan->update([
                'status' => 'rejected',
                'rejected_by_kanit' => $user->id,
                'rejected_date_kanit' => now(),
                'rejected_reason' => $request->alasan,
            ]);
            return back()->with('success', 'Pengajuan berhasil ditolak!');
        }

        return back()->with('error', 'Tidak dapat menolak pengajuan ini!');
    }
}

