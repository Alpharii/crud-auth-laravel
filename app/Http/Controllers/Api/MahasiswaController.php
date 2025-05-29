<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Mahasiswa::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'    => 'required|string|max:255',
            'nim'     => 'required|string|unique:mahasiswas',
            'jurusan' => 'required|string|max:255',
        ]);

        $mahasiswa = Mahasiswa::create($data);
        return response()->json($mahasiswa, 201);
    }

    public function show($id)
    {
        return Mahasiswa::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $data = $request->validate([
            'nama'    => 'sometimes|required|string|max:255',
            'nim'     => 'sometimes|required|string|unique:mahasiswas,nim,' . $id,
            'jurusan' => 'sometimes|required|string|max:255',
        ]);

        $mahasiswa->update($data);
        return response()->json($mahasiswa);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
