<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Role-based data
        $data = [];
        
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
            $data['need_approval'] = Pengajuan::where('status', 'pending')->count();
            $data['approved'] = Pengajuan::where('status', 'approved_kanit')->count();
        } elseif ($role == 'kabid') {
            $data['need_approval'] = Pengajuan::where('status', 'approved_kanit')->count();
            $data['approved'] = Pengajuan::where('status', 'approved_kabid')->count();
        }

        return view('dashboard.index', compact('identity', 'role', 'data'));
    }
}

