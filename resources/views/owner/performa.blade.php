@extends('layouts.app', ['title' => 'Performa Karyawan'])

@section('content')
    <div class="flex justify-between mb-4">
        <h3>Statistik Performa</h3>
        <div class="flex" style="gap: 0.5rem;">
            <a href="?filter=daily" class="btn {{ $filter == 'daily' ? 'btn-primary' : 'btn-outline' }}">Harian</a>
            <a href="?filter=monthly" class="btn {{ $filter == 'monthly' ? 'btn-primary' : 'btn-outline' }}">Bulanan</a>
            <a href="?filter=yearly" class="btn {{ $filter == 'yearly' ? 'btn-primary' : 'btn-outline' }}">Tahunan</a>
        </div>
    </div>

    <div class="grid md-cols-3 mb-4">
        @foreach($karyawanStats as $stat)
            <div class="card">
                <h4 class="mb-2">{{ $stat['nama'] }}</h4>
                <div class="flex justify-between">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Total Sewa:</span>
                    <span style="font-weight: 700;">{{ $stat['total_transaksi'] }}</span>
                </div>
                <div class="flex justify-between mt-4">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Pendapatan:</span>
                    <span style="font-weight: 800; color: var(--primary);">Rp
                        {{ number_format($stat['total_pendapatan'], 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h3 class="mb-4">Tren Kontribusi Karyawan (7 Hari Terakhir)</h3>
        <div style="height: 350px;">
            <canvas id="performaChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('performaChart').getContext('2d');
        const colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#ec4899'];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($days) !!},
                datasets: {!! json_encode($chartData) !!}.map((item, index) => ({
                    label: item.label,
                    data: item.data,
                    borderColor: colors[index % colors.length],
                    tension: 0.4,
                    borderWidth: 2
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection