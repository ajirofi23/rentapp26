<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'toy_id' => 'required|exists:toys,id',
            'durasi_menit' => 'required|integer|min:1',
        ]);

        $toy = \App\Models\Toy::findOrFail($request->toy_id);
        $jamMulai = Carbon::now();
        $jamSelesai = $jamMulai->copy()->addMinutes((int) $request->durasi_menit);

        $karyawan = Auth::user()->karyawan;

        if (!$karyawan) {
            return back()->with('error', 'Akun ini tidak terdaftar sebagai karyawan.');
        }

        // Calculate total price based on 15-minute intervals
        $duration = (int) $request->durasi_menit;
        $intervals = ceil($duration / 15);
        $totalHarga = $intervals * $toy->price;

        Transaksi::create([
            'karyawan_id' => $karyawan->id,
            'toy_id' => $toy->id,
            'nama_customer' => $request->nama_customer,
            'total_harga' => $totalHarga,
            'jam_mulai' => $jamMulai,
            'durasi_menit' => $request->durasi_menit,
            'jam_selesai' => $jamSelesai,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function complete(Transaksi $transaksi)
    {
        $transaksi->update(['status' => 'selesai']);
        return back()->with('success', 'Mainan telah dikembalikan.');
    }
}
