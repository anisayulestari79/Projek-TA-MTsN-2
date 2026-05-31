<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggaran::query();

        // Search by jenis
        if ($request->has('search')) {
            $query->where('jenis', 'like', '%' . $request->search . '%');
        }

        $pelanggaran = $query->orderBy('id')->get();

        return response()->json([
            'success' => true,
            'data' => $pelanggaran
        ]);
    }

    public function show($id)
    {
        $pelanggaran = Pelanggaran::find($id);

        if (!$pelanggaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pelanggaran
        ]);
    }
}

