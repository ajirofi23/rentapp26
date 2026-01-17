<?php

namespace App\Http\Controllers;

use App\Models\Toy;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $dailyRevenue = Transaksi::whereDate('created_at', $today)->sum('total_harga');
        $monthlyRevenue = Transaksi::whereBetween('created_at', [$thisMonth, Carbon::now()])->sum('total_harga');
        $yearlyRevenue = Transaksi::whereBetween('created_at', [$thisYear, Carbon::now()])->sum('total_harga');

        // Revenue Trend for last 7 days
        $revenueTrend = Transaksi::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('owner.dashboard', compact('dailyRevenue', 'monthlyRevenue', 'yearlyRevenue', 'revenueTrend'));
    }

    public function performa(Request $request)
    {
        $filter = $request->get('filter', 'daily'); // daily, monthly, yearly

        $query = Karyawan::with([
            'transaksi' => function ($q) use ($filter) {
                if ($filter == 'daily')
                    $q->whereDate('created_at', Carbon::today());
                if ($filter == 'monthly')
                    $q->whereMonth('created_at', Carbon::now()->month);
                if ($filter == 'yearly')
                    $q->whereYear('created_at', Carbon::now()->year);
            }
        ]);

        $karyawanStats = $query->get()->map(function ($k) {
            return [
                'nama' => $k->nama,
                'total_transaksi' => $k->transaksi->count(),
                'total_pendapatan' => $k->transaksi->sum('total_harga')
            ];
        });

        // Trend for Chart: Revenue per day for last 7 days per karyawan
        $days = collect();
        for ($i = 6; $i >= 0; $i--)
            $days->push(Carbon::now()->subDays($i)->format('Y-m-d'));

        $chartData = Karyawan::all()->map(function ($k) use ($days) {
            $data = $days->map(function ($day) use ($k) {
                return Transaksi::where('karyawan_id', $k->id)
                    ->whereDate('created_at', $day)
                    ->sum('total_harga');
            });
            return [
                'label' => $k->nama,
                'data' => $data
            ];
        });

        return view('owner.performa', compact('karyawanStats', 'chartData', 'days', 'filter'));
    }

    public function riwayat(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $query = Transaksi::with(['toy', 'karyawan']);

        if ($filter == 'daily')
            $query->whereDate('created_at', Carbon::today());
        if ($filter == 'monthly')
            $query->whereMonth('created_at', Carbon::now()->month);
        if ($filter == 'yearly')
            $query->whereYear('created_at', Carbon::now()->year);

        $riwayat = $query->orderBy('created_at', 'DESC')->get();

        return view('owner.riwayat', compact('riwayat', 'filter'));
    }

    public function toys()
    {
        $toys = Toy::all();
        return view('owner.toys', compact('toys'));
    }

    public function karyawan()
    {
        $karyawans = Karyawan::with('user')->get();
        return view('owner.karyawan', compact('karyawans'));
    }

    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'karyawan'
        ]);

        Karyawan::create([
            'user_id' => $user->id,
            'nama' => $request->name
        ]);

        return back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function destroyKaryawan(User $user)
    {
        $user->delete();
        return back()->with('success', 'Karyawan berhasil dihapus.');
    }
}
