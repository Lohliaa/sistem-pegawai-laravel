<?php

namespace App\Http\Controllers;

use App\Models\BahanPenilaian;
use Illuminate\Http\Request;

class KinerjaBahanController extends Controller
{
    public function index(Request $request)
    {
        $bahans = BahanPenilaian::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($sub) use ($search) {
                    $sub->where('nama_bahan', 'like', '%'.$search.'%')
                        ->orWhere('keterangan', 'like', '%'.$search.'%');
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('kinerja-bahan.index', compact('bahans'));
    }

    public function create()
    {
        return view('kinerja-bahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), [], $this->attributes());

        BahanPenilaian::create($validated);

        return redirect()->route('kinerja-bahan.index')
            ->with('success', 'Bahan penilaian berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $bahan = BahanPenilaian::findOrFail($id);

        return view('kinerja-bahan.show', compact('bahan'));
    }

    public function edit(string $id)
    {
        $bahan = BahanPenilaian::findOrFail($id);

        return view('kinerja-bahan.edit', compact('bahan'));
    }

    public function update(Request $request, string $id)
    {
        $bahan = BahanPenilaian::findOrFail($id);

        $validated = $request->validate($this->rules(), [], $this->attributes());

        $bahan->update($validated);

        return redirect()->route('kinerja-bahan.index')
            ->with('success', 'Bahan penilaian berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $bahan = BahanPenilaian::findOrFail($id);
        $bahan->delete();

        return redirect()->route('kinerja-bahan.index')
            ->with('success', 'Bahan penilaian berhasil dihapus!');
    }

    /**
     * Aturan validasi bahan penilaian.
     *
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        return [
            'nama_bahan' => 'required|string|max:100',
            'link' => 'required|url|max:255',
            'keterangan' => 'nullable|string',
        ];
    }

    /**
     * Nama atribut untuk pesan validasi.
     *
     * @return array<string, string>
     */
    private function attributes(): array
    {
        return [
            'nama_bahan' => 'nama bahan',
            'link' => 'link',
        ];
    }
}