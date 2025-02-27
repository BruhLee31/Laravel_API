<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FansController extends Controller
{
    public function index()
    {
        $fans = Fans::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar fans',
            'data' => $fans,
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_fans' => 'required',
            'klub' => 'required|array',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $fans = new Fans();
            $fans->nama_fans = $request->nama_fans;
            $fans->save();
            //lampiran banyak fans
            $fans->klub()->attach($request->klub);

            return response()->json([
                'success' => true,
                'message' => 'Data fans berhasil ditambah',
                'data' => $fans,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $fans = Fans::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Data fans ditemukan',
                'data' => $fans,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data fans tidak ditemukan',
                'data' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_fans' => 'required',
            'klub' => 'required|array',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $fans = Fans::findOrFail($id);
            $fans->nama_fans = $request->nama_fans;
            $fans->save();
            //lampiran banyak fans
            $fans->klub()->sync($request->klub);

            return response()->json([
                'success' => true,
                'message' => 'Data fans berhasil ditambah',
                'data' => $fans,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $fans = Fans::findOrFail($id);
            $fans->klub()->detach();
            $fans->delete();
            //hapus banyak klub
            return response()->json([
                'success' => true,
                'message' => 'Data fans berhasil dihapus',
                'data' => $fans,
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
