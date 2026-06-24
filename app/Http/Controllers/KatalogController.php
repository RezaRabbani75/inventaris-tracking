<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $barangs = Barang::when($search, function ($query, $search) {
            return $query->where('nama_barang', 'like', '%' . $search . '%')
                         ->orWhere('kategori', 'like', '%' . $search . '%');
        })
        ->latest()
        ->get();

        return view('katalog.index', compact('barangs', 'search'));
    }

    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        
        return view('katalog.show', compact('barang'));
    }
}
