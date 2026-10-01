<?php

namespace App\Http\Controllers;

use App\Models\SopKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SopKepegawaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sops = SopKepegawaian::when($request->filled('search'), function ($query) use ($request) {
            $search = $request->input('search');
            $query->where('judul_sop', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('sop-kepegawaian.index', compact('sops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sop-kepegawaian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_sop' => 'required|string|max:255',
            'dokumen' => 'required|file|mimes:pdf|max:10240',
        ], [
            'judul_sop.required' => 'Judul SOP wajib diisi.',
            'judul_sop.max' => 'Judul SOP tidak boleh lebih dari 255 karakter.',
            'dokumen.required' => 'Dokumen PDF wajib diunggah.',
            'dokumen.mimes' => 'File harus berformat PDF.',
            'dokumen.max' => 'Ukuran file tidak boleh lebih dari 10 MB.',
        ]);

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('sop_kepegawaian', 'public');
            $validated['dokumen'] = $path;
        }

        $validated['terakhir_diperbarui'] = Carbon::now();

        SopKepegawaian::create($validated);

        return redirect()->route('sop-kepegawaian.index')
            ->with('success', 'SOP Kepegawaian berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sop = SopKepegawaian::findOrFail($id);

        return view('sop-kepegawaian.show', compact('sop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sop = SopKepegawaian::findOrFail($id);

        return view('sop-kepegawaian.edit', compact('sop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sop = SopKepegawaian::findOrFail($id);

        $validated = $request->validate([
            'judul_sop' => 'required|string|max:255',
            'dokumen' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'judul_sop.required' => 'Judul SOP wajib diisi.',
            'judul_sop.max' => 'Judul SOP tidak boleh lebih dari 255 karakter.',
            'dokumen.mimes' => 'File harus berformat PDF.',
            'dokumen.max' => 'Ukuran file tidak boleh lebih dari 10 MB.',
        ]);

        if ($request->hasFile('dokumen')) {
            // Delete old file
            if ($sop->dokumen) {
                Storage::disk('public')->delete($sop->dokumen);
            }
            $path = $request->file('dokumen')->store('sop_kepegawaian', 'public');
            $validated['dokumen'] = $path;
        }

        $validated['terakhir_diperbarui'] = Carbon::now();

        $sop->update($validated);

        return redirect()->route('sop-kepegawaian.index')
            ->with('success', 'SOP Kepegawaian berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sop = SopKepegawaian::findOrFail($id);

        // Delete file
        if ($sop->dokumen) {
            Storage::disk('public')->delete($sop->dokumen);
        }

        $sop->delete();

        return redirect()->route('sop-kepegawaian.index')
            ->with('success', 'SOP Kepegawaian berhasil dihapus!');
    }

    /**
     * Download/View the PDF document
     */
    public function downloadPdf(string $id)
    {
        $sop = SopKepegawaian::findOrFail($id);

        if (!$sop->dokumen || !Storage::disk('public')->exists($sop->dokumen)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($sop->dokumen));
    }
}
