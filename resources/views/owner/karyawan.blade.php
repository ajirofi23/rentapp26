@extends('layouts.app', ['title' => 'Manajemen Karyawan'])

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid md-cols-3">
        <div class="md-span-2">
            <div class="card">
                <h3 class="mb-4">Daftar Akun Karyawan</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                                <th style="padding: 1rem;">Nama</th>
                                <th style="padding: 1rem;">Email Login</th>
                                <th style="padding: 1rem;">Dibuat</th>
                                <th style="padding: 1rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawans as $k)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 1rem; font-weight: 600;">{{ $k->nama }}</td>
                                    <td style="padding: 1rem;">{{ $k->user->email }}</td>
                                    <td style="padding: 1rem; font-size: 0.85rem; color: var(--text-muted);">
                                        {{ $k->created_at->format('d M Y') }}</td>
                                    <td style="padding: 1rem; text-align: right;">
                                        <form action="{{ route('owner.karyawan.destroy', $k->user_id) }}" method="POST"
                                            onsubmit="return confirm('Hapus karyawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline"
                                                style="color: var(--danger); border-color: var(--danger); padding: 0.4rem 0.8rem;">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="md-span-1">
            <div class="card">
                <h3 class="mb-4">Tambah Karyawan Baru</h3>
                <form action="{{ route('owner.karyawan.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" required class="form-control" placeholder="Contoh: Ahmad">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email Login</label>
                        <input type="email" name="email" required class="form-control" placeholder="ahmad@rentapp.test">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-control" placeholder="Min. 6 Karakter">
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Daftarkan Karyawan</button>
                </form>
            </div>
        </div>
    </div>
@endsection