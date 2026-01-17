<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function karyawan()
    {
        // Get active transactions (those that haven't 'finished' or just all for today)
        // For the countdown, we need transactions where jam_selesai > now, OR we just show all active ones.
        // Let's assume 'status' field usage or just time based. 
        // Migration had 'status', let's use it.

        $transaksis = Transaksi::with('toy')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $toys = \App\Models\Toy::where('is_active', true)->get();

        return view('karyawan.dashboard', compact('transaksis', 'toys'));
    }

    public function owner()
    {
        $toys = \App\Models\Toy::all();

        // Reporting Statistics
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $dailyRevenue = Transaksi::whereDate('created_at', $today)->sum('total_harga');
        $monthlyRevenue = Transaksi::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_harga');
        $yearlyRevenue = Transaksi::whereYear('created_at', Carbon::now()->year)->sum('total_harga');

        $karyawanStats = \App\Models\Karyawan::with([
            'transaksi' => function ($q) use ($today) {
                $q->whereDate('created_at', $today);
            }
        ])->get()->map(function ($karyawan) {
            return [
                'nama' => $karyawan->nama,
                'total_transaksi' => $karyawan->transaksi->count(),
                'total_pendapatan' => $karyawan->transaksi->sum('total_harga'),
            ];
        });

        $recentTransactions = Transaksi::with(['toy', 'karyawan'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('owner.dashboard', compact(
            'toys',
            'dailyRevenue',
            'monthlyRevenue',
            'yearlyRevenue',
            'karyawanStats',
            'recentTransactions'
        ));
    }
}
