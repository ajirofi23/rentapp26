@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="grid md-cols-3 mb-4">
        <div class="card">
            <p class="form-label" style="text-transform: uppercase; color: var(--text-muted); font-size: 0.7rem;">HARI INI
            </p>
            <h2 style="font-size: 2rem; color: var(--primary);">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h2>
        </div>
        <div class="card">
            <p class="form-label" style="text-transform: uppercase; color: var(--text-muted); font-size: 0.7rem;">BULAN INI
            </p>
            <h2 style="font-size: 2rem; color: var(--success);">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h2>
        </div>
        <div class="card">
            <p class="form-label" style="text-transform: uppercase; color: var(--text-muted); font-size: 0.7rem;">TAHUN INI
            </p>
            <h2 style="font-size: 2rem; color: var(--warning);">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="card">
        <h3 class="mb-4">Tren Pendapatan (7 Hari Terakhir)</h3>
        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueTrend->pluck('date')) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($revenueTrend->pluck('total')) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection