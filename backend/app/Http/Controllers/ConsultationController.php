<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConsultationController extends Controller
{
    /**
     * Menampilkan halaman Monitoring Konsultasi BK (Untuk Admin)
     */
    public function index(Request $request)
    {
        if (!Session::has('auth_token') || !Session::has('user')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Session::get('user');

        $totalKonsultasi = Consultation::count();
        $menungguRespon  = Consultation::where('status', 'menunggu')->count();
        $konsultasiSelesai = Consultation::where('status', 'selesai')->count();

        $query = Consultation::with(['student', 'parent', 'bk'])->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('topic', 'LIKE', "%{$search}%")
                    ->orWhereHas('student', function ($qStudent) use ($search) {
                        $qStudent->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        $dataKonsultasi = $query->paginate(10)->withQueryString();

        return view('admin.admin-konsultasi', compact(
            'user',
            'totalKonsultasi',
            'menungguRespon',
            'konsultasiSelesai',
            'dataKonsultasi'
        ));
    }

    /**
     * Menampilkan halaman Detail Konsultasi (Untuk Admin)
     */
    public function show($id)
    {
        $user = Session::get('user');

        $consultation = Consultation::with(['student', 'parent', 'bk'])->findOrFail($id);

        return view('admin.konsultasi-detail', compact('user', 'consultation'));
    }

    /**
     * Memproses balasan / tanggapan dari Admin
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:5'
        ]);

        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'reply' => $request->reply,
            'status' => 'selesai'
        ]);

        return redirect()->route('admin.konsultasi.show', $id)
            ->with('success', 'Tanggapan berhasil dikirim!');
    }

    /**
     * Menutup sesi konsultasi dari sisi Admin
     */
    public function markAsComplete($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'status' => 'selesai'
        ]);

        return redirect()->route('admin.konsultasi.show', $id)
            ->with('success', 'Sesi konsultasi ditandai selesai.');
    }

    // ========================================================
    // FITUR KHUSUS GURU BK
    // ========================================================

    /**
     * Guru BK Mengirim Pesan Panggilan Baru ke Orang Tua
     */
    public function kirimDariBK(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'academic_period' => 'required|string',
            'topik' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        // Cari data siswa
        $siswa = \App\Models\Siswa::find($request->siswa_id);

        // Validasi: Pastikan siswa ini sudah punya akun Orang Tua yang terhubung
        if (!$siswa || !$siswa->ortu_id) {
            return redirect()->back()->with('error', 'Gagal dikirim: Siswa ini belum terhubung dengan akun Wali Murid manapun di dalam sistem. Minta orang tua untuk login dan mengaitkan NISN.');
        }

        // Simpan pesan konsultasi ke database
        Consultation::create([
            'parent_id'       => $siswa->ortu_id,
            'student_id'      => $siswa->id,
            'bk_id'           => \Illuminate\Support\Facades\Session::get('user.id'), // Mengambil ID Guru BK yang sedang login
            'academic_period' => $request->academic_period,
            'topic'           => $request->topik,
            'message'         => $request->pesan,
            'status'          => 'menunggu',
            'pengirim'        => 'bk' // Penanda bahwa pesan ini dari Guru BK
        ]);

        return redirect()->back()->with('success', 'Pesan panggilan/konsultasi berhasil dikirim ke Dashboard Wali Murid!');
    }

    /**
     * Guru BK Membalas Pesan dari Orang Tua
     */
    public function balasDariBK(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string'
        ]);

        $konsultasi = Consultation::findOrFail($id);

        $konsultasi->update([
            'reply'  => $request->balasan,
            'status' => 'dibalas', // Status otomatis berubah
            'bk_id'  => \Illuminate\Support\Facades\Session::get('user.id') // Mencatat ID Guru BK yang membalas
        ]);

        return redirect()->back()->with('success', 'Balasan Anda berhasil dikirim ke Wali Murid!');
    }
}
