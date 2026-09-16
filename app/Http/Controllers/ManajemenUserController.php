<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\ExcelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ManajemenUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('pegawai')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($sub) use ($search) {
                    $sub->where('username', 'like', '%'.$search.'%')
                        ->orWhereHas('pegawai', function ($pegawai) use ($search) {
                            $pegawai->where('nama', 'like', '%'.$search.'%')
                                ->orWhere('unit', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->orderBy('username')
            ->paginate(10)
            ->withQueryString();

        return view('manajemen-user.index', compact('users'));
    }

    public function create()
    {
        $roles = User::ROLES;

        return view('manajemen-user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ], [], [
            'username' => 'username',
            'password' => 'password',
            'role' => 'role',
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('manajemen-user.index')
            ->with('success', 'User berhasil dibuat!');
    }

    public function show(string $id)
    {
        $user = User::with(['pegawai', 'pengajuan'])->findOrFail($id);

        return view('manajemen-user.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = User::ROLES;

        return view('manajemen-user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ], [], [
            'username' => 'username',
            'password' => 'password',
            'role' => 'role',
        ]);

        $user->username = $validated['username'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('manajemen-user.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        if ($user->pengajuan()->exists()) {
            return back()->with('error', 'User tidak dapat dihapus karena masih memiliki data pengajuan!');
        }

        $user->delete();

        return redirect()->route('manajemen-user.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Export seluruh data user beserta profil pegawainya ke Excel.
     */
    public function exportExcel()
    {
        $users = User::with('pegawai')->orderBy('id')->get();

        $headings = [
            'No', 'ID User', 'Username', 'Role', 'Nama Pegawai', 'Tempat Lahir',
            'Tanggal Lahir', 'Alamat', 'Jabatan', 'Unit', 'Status',
        ];

        $rows = $users->values()->map(function (User $user, int $index) {
            $pegawai = $user->pegawai;

            return [
                $index + 1,
                $user->id,
                $user->username,
                strtoupper($user->role),
                $pegawai?->nama ?? '-',
                $pegawai?->tempat ?? '-',
                $pegawai?->tanggal_lahir ? $pegawai->tanggal_lahir->format('d/m/Y') : '-',
                $pegawai?->alamat ?? '-',
                $pegawai?->jabatan ?? '-',
                $pegawai?->unit ?? '-',
                $pegawai ? 'Terhubung' : 'Belum Terhubung',
            ];
        })->all();

        $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Data User');

        return ExcelHelper::download($spreadsheet, 'data_users_'.date('Y-m-d').'.xlsx');
    }
}