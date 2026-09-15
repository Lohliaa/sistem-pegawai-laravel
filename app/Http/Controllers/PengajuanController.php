<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 'staf') {
            $pengajuan = Pengajuan::where('created_by', $user->id)->latest()->get();
        } elseif ($user->role == 'kanit') {
            $pengajuan = Pengajuan::where('status', 'pending')->latest()->get();
        } elseif ($user->role == 'kabid') {
            $pengajuan = Pengajuan::where('status', 'approved_kanit')->latest()->get();
        } else {
            $pengajuan = Pengajuan::with('creator')->latest()->get();
        }

        return view('pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        return view('pengajuan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_pengajuan' => 'required|in:MOU,SK',
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'tanggal_tmt' => 'required|date',
            'unit' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        Pengajuan::create($validated);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dibuat!');
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['creator', 'approverKanit', 'approverKabid']);
        return view('pengajuan.show', compact('pengajuan'));
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

        if ($user->role == 'kanit' && $pengajuan->status == 'pending') {
            $pengajuan->update([
                'status' => 'approved_kanit',
                'approved_by_kanit' => $user->id,
                'approved_date_kanit' => now(),
                'catatan_kanit' => $request->catatan,
            ]);
            return back()->with('success', 'Pengajuan berhasil disetujui!');
        }

        if ($user->role == 'kabid' && $pengajuan->status == 'approved_kanit') {
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

        if ($user->role == 'kanit' && $pengajuan->status == 'pending') {
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

