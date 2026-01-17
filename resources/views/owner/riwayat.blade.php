@extends('layouts.app', ['title' => 'Riwayat Sewa'])

@section('content')
    <div class="card mb-4">
        <div class="flex justify-between items-center">
            <h3>Filter Riwayat</h3>
            <div class="flex" style="gap: 0.5rem;">
                <a href="?filter=daily" class="btn {{ $filter == 'daily' ? 'btn-primary' : 'btn-outline' }}">Harian</a>
                <a href="?filter=monthly" class="btn {{ $filter == 'monthly' ? 'btn-primary' : 'btn-outline' }}">Bulanan</a>
                <a href="?filter=yearly" class="btn {{ $filter == 'yearly' ? 'btn-primary' : 'btn-outline' }}">Tahunan</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                        <th style="padding: 1rem;">Waktu</th>
                        <th style="padding: 1rem;">Customer</th>
                        <th style="padding: 1rem;">Unit</th>
                        <th style="padding: 1rem;">Durasi</th>
                        <th style="padding: 1rem;">Petugas</th>
                        <th style="padding: 1rem; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $tx)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem; font-size: 0.85rem;">
                                {{ $tx->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>
                            <td style="padding: 1rem; font-weight: 600;">{{ $tx->nama_customer }}</td>
                            <td style="padding: 1rem;">{{ $tx->toy->code }}</td>
                            <td style="padding: 1rem;">{{ $tx->durasi_menit }}m</td>
                            <td style="padding: 1rem;">{{ $tx->karyawan->nama }}</td>
                            <td style="padding: 1rem; text-align: right; font-weight: 800; color: var(--primary);">
                                Rp {{ number_format($tx->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 3rem; text-align: center; color: var(--text-muted);">Tidak ada data
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection