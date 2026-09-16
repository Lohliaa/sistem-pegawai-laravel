<?php

namespace App\Http\Controllers;

use App\Models\StatusKepegawaian;
use Illuminate\Http\Request;

class KinerjaStatusController extends Controller
{
    public function index()
    {
        $statuses = StatusKepegawaian::orderBy('nama_status')->paginate(10);

        return view('kinerja-status.index', compact('statuses'));
    }

    public function create()
    {
        return view('kinerja-status.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_status' => 'required|string|max:50|unique:status_kepegawaian,nama_status',
        ], [], ['nama_status' => 'nama status']);

        StatusKepegawaian::create($validated);

        return redirect()->route('kinerja-status.index')
            ->with('success', 'Status kepegawaian berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $status = StatusKepegawaian::findOrFail($id);

        return view('kinerja-status.show', compact('status'));
    }

    public function edit(string $id)
    {
        $status = StatusKepegawaian::findOrFail($id);

        return view('kinerja-status.edit', compact('status'));
    }

    public function update(Request $request, string $id)
    {
        $status = StatusKepegawaian::findOrFail($id);

        $validated = $request->validate([
            'nama_status' => 'required|string|max:50|unique:status_kepegawaian,nama_status,'.$status->id,
        ], [], ['nama_status' => 'nama status']);

        $status->update($validated);

        return redirect()->route('kinerja-status.index')
            ->with('success', 'Status kepegawaian berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $status = StatusKepegawaian::findOrFail($id);
        $status->delete();

        return redirect()->route('kinerja-status.index')
            ->with('success', 'Status kepegawaian berhasil dihapus!');
    }
}