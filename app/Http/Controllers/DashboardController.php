<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        
        // Get identity data
        $identity = Pegawai::where('user_id', $user->id)->first();
        if (!$identity) {
            $identity = $user;
        }

        // Role-based data & Statistik Pegawai for all roles
        $data = [];
        $data['total_pegawai'] = Pegawai::count();
        $data['total_users'] = User::count();
        $data['pegawaiPerGender'] = Pegawai::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');
        $data['pegawaiPerBpi'] = Pegawai::select('data_bpi', DB::raw('count(*) as total'))
            ->groupBy('data_bpi')
            ->pluck('total', 'data_bpi');
        $data['pegawaiPerUnit'] = Pegawai::select('unit', DB::raw('count(*) as total'))
            ->groupBy('unit')
            ->pluck('total', 'unit');
        $data['pegawaiPerJabatan'] = Pegawai::select('jabatan', DB::raw('count(*) as total'))
            ->groupBy('jabatan')
            ->pluck('total', 'jabatan');
        $data['pegawaiPerStatusKepegawaian'] = Pegawai::with('statusKepegawaian')
            ->get()
            ->groupBy(fn($p) => $p->statusKepegawaian?->nama_status ?? 'Tidak Ada Status')
            ->map->count();
        $data['pegawaiPerPendidikan'] = Pegawai::select('pendidikan_terakhir', DB::raw('count(*) as total'))
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir');
        
        if ($role == 'admin') {
            $data['total_pengajuan'] = Pengajuan::count();
            $data['pending'] = Pengajuan::where('status', 'pending')->count();
            $data['approved'] = Pengajuan::whereIn('status', ['approved_kanit', 'approved_kabid'])->count();
            $data['rejected'] = Pengajuan::where('status', 'rejected')->count();
            $data['recent_pengajuan'] = Pengajuan::with('creator')->latest()->take(10)->get();
        } elseif ($role == 'staf') {
            $data['my_pengajuan'] = Pengajuan::where('created_by', $user->id)->count();
            $data['pending'] = Pengajuan::where('created_by', $user->id)->where('status', 'pending')->count();
            $data['approved'] = Pengajuan::where('created_by', $user->id)->whereIn('status', ['approved_kanit', 'approved_kabid'])->count();
        } elseif ($role == 'kanit') {
            $namaPejabat = [];
            if ($user->pegawai_id) {
                $namaPejabat = \App\Models\PejabatPenilai::where('pegawai_id', $user->pegawai_id)->pluck('nama')->toArray();
            }
            $pejabatViaPegawai = \App\Models\PejabatPenilai::whereHas('pegawai', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->pluck('nama')->toArray();
            $namaPejabat = array_merge($namaPejabat, $pejabatViaPegawai);
            if ($user->pegawai) {
                $namaPejabat[] = $user->pegawai->nama;
                if ($user->pegawai->pejabatPenilai) {
                    $namaPejabat[] = $user->pegawai->pejabatPenilai->nama;
                }
            }
            $namaPejabat = array_values(array_unique(array_filter($namaPejabat)));

            if (empty($namaPejabat)) {
                $data['need_approval'] = 0;
                $data['approved'] = 0;
            } else {
                $data['need_approval'] = Pengajuan::where('status', 'pending')
                    ->whereIn('pimpinan_atasan', $namaPejabat)
                    ->count();
                $data['approved'] = Pengajuan::where('status', 'approved_kanit')
                    ->whereIn('pimpinan_atasan', $namaPejabat)
                    ->count();
            }
        } elseif ($role == 'kabid') {
            $namaPejabat = [];
            if ($user->pegawai_id) {
                $namaPejabat = \App\Models\PejabatPenilai::where('pegawai_id', $user->pegawai_id)->pluck('nama')->toArray();
            }
            $pejabatViaPegawai = \App\Models\PejabatPenilai::whereHas('pegawai', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->pluck('nama')->toArray();
            $namaPejabat = array_merge($namaPejabat, $pejabatViaPegawai);
            if ($user->pegawai) {
                $namaPejabat[] = $user->pegawai->nama;
                if ($user->pegawai->pejabatPenilai) {
                    $namaPejabat[] = $user->pegawai->pejabatPenilai->nama;
                }
            }
            $namaPejabat = array_values(array_unique(array_filter($namaPejabat)));

            if (empty($namaPejabat)) {
                $data['need_approval'] = 0;
                $data['approved'] = 0;
            } else {
                $data['need_approval'] = Pengajuan::whereIn('status', ['pending', 'approved_kanit'])
                    ->whereIn('pimpinan_atasan', $namaPejabat)
                    ->count();
                $data['approved'] = Pengajuan::where('status', 'approved_kabid')
                    ->whereIn('pimpinan_atasan', $namaPejabat)
                    ->count();
            }
        }

        return view('dashboard.index', compact('identity', 'role', 'data'));
    }
}


