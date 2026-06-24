<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KelolaBarangController extends Controller
{

    public function index()
    {
        $barangs = Barang::latest()->get();
        
        return view('kelola-barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('kelola-barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string',
            'total_stok'  => 'required|integer|min:1',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'nullable|string',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang ini sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required'    => 'Kategori wajib dipilih.',
            'total_stok.required'  => 'Total stok wajib diisi.',
            'foto.image'           => 'File yang diunggah harus berupa gambar.',
            'foto.max'             => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $data = $request->all();
        
        $data['stok_tersedia'] = $request->total_stok;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/barang'), $filename);
            $data['foto'] = $filename;
        }

        Barang::create($data);

        return redirect()->route('kelola-barang.index')
                         ->with('success', 'Data barang lab berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('kelola-barang.edit', compact('barang'));

        $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string',
            'total_stok'  => 'required|integer|min:1',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($barang->foto && file_exists(public_path('img/barang/' . $barang->foto))) {
                unlink(public_path('img/barang/' . $barang->foto));
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/barang'), $filename);
            $data['foto'] = $filename;
        }

        $barang->update($data);

        return redirect()->route('kelola-barang.index')
                         ->with('success', 'Data barang lab berhasil diperbarui!');
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        request()->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string',
            'total_stok'  => 'required|integer|min:1',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'nullable|string',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang ini sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required'    => 'Kategori wajib dipilih.',
            'total_stok.required'  => 'Total stok wajib diisi.',
            'foto.image'           => 'File yang diunggah harus berupa gambar.',
            'foto.max'             => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $data = $request->all();
         
        $stok_keluar = $barang->stok_dipinjam + $barang->stok_rusak + $barang->stok_diperbaiki;
        $data['stok_tersedia'] = $request->total_stok - $stok_keluar;

        if ($data['stok_tersedia'] < 0) {
            return back()->withErrors(['total_stok' => 'Total stok tidak boleh lebih sedikit dari jumlah barang yang sedang dipinjam/rusak/diperbaiki (Minimal: ' . $stok_keluar . ' unit).'])->withInput();
        }

        if ($request->hasFile('foto')) {
            if ($barang->foto && file_exists(public_path('img/barang/' . $barang->foto))) {
                unlink(public_path('img/barang/' . $barang->foto));
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/barang'), $filename);
            $data['foto'] = $filename;
        }

        $barang->update($data);

        return redirect()->route('kelola-barang.index')
                         ->with('success', 'Data perangkat Lab berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->foto && file_exists(public_path('img/barang/' . $barang->foto))) {
            unlink(public_path('img/barang/' . $barang->foto));
        }

        $barang->delete();

        return redirect()->route('kelola-barang.index')
                         ->with('success', 'Data perangkat Lab berhasil dihapus!');
    }
}