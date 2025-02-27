<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PemainController extends Controller
{
    public function index()
    {
        $pemain = Pemain::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar pemain',
            'data' => $pemain,
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'logo' => 'required|image|max:2048',
            'tgl_lahir' => 'required|date',
            'harga_pasar' => 'required|integer',
            'posisi' => 'required|in:gk,df,mf,fw',
            'negara' => 'required',
            'id_klub' => 'required|exists:klubs,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $pemain = new Pemain();
            $pemain->fill($request->all());

            if ($request->hasFile('foto')) {
                Storage::delete($pemain->foto);
                $path = $request->file('foto')->store('public/fotos');
                $pemain->foto = $path;
            }

            $pemain->save();
            return response()->json([
                'success' => true,
                'message' => 'Pemain Berhasil Ditambahkan',
                'data' => $pemain,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }

    }

    public function show($id)
    {
        try {
            $pemain = Pemain::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail Pemain',
                'data' => $pemain,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pemain Tidak Ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }

    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'logo' => 'required|image|max:2048',
            'tgl_lahir' => 'required|date',
            'harga_pasar' => 'required|integer',
            'posisi' => 'required|in:gk,df,mf,fw',
            'negara' => 'required',
            'id_klub' => 'required|exists:klubs,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $pemain = Pemain::findOrFail($id);
            $pemain->fill($request->all());

            if ($request->hasFile('foto')) {
                Storage::delete($pemain->foto);
                $path = $request->file('foto')->store('public/fotos');
                $pemain->foto = $path;
            }

            $pemain->save();
            return response()->json([
                'success' => true,
                'message' => 'Pemain Berhasil Diupdate',
                'data' => $pemain,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $pemain = Pemain::findOrFail($id);
            $pemain->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pemain Berhasil Dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pemain Tidak Ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}
