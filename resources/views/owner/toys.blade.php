@extends('layouts.app', ['title' => 'Manajemen Mainan'])

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid md-cols-3">
        <div class="md-span-2">
            <div class="card">
                <h3 class="mb-4">Daftar Mainan & Harga</h3>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color); text-align: left;">
                                <th style="padding: 1rem;">Kode</th>
                                <th style="padding: 1rem;">Kategori</th>
                                <th style="padding: 1rem;">Harga/15m</th>
                                <th style="padding: 1rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($toys as $toy)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 1rem; font-weight: 700;">{{ $toy->code }}</td>
                                    <td style="padding: 1rem;">
                                        @if($toy->category == 'B') Mobil Besar
                                        @elseif($toy->category == 'K') Mobil Kecil
                                        @else Motor @endif
                                    </td>
                                    <td style="padding: 1rem;">
                                        <form action="{{ route('owner.toys.update', $toy->id) }}" method="POST" class="flex"
                                            style="gap: 0.5rem;">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="price" value="{{ $toy->price }}" class="form-control"
                                                style="width: 110px;">
                                            <button class="btn btn-outline" style="padding: 0.4rem 0.8rem;">Update</button>
                                        </form>
                                    </td>
                                    <td style="padding: 1rem; text-align: right;">
                                        <span class="badge {{ $toy->is_active ? 'badge-success' : 'badge-warning' }}">
                                            {{ $toy->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
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
                <h3 class="mb-4">Tambah Unit Baru</h3>
                <form action="{{ route('owner.toys.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Kode Unit</label>
                        <input type="text" name="code" required class="form-control" placeholder="Contoh: B006">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Kategori</label>
                        <select name="category" required class="form-control">
                            <option value="B">Mobil Besar</option>
                            <option value="K">Mobil Kecil</option>
                            <option value="M">Motor</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Harga Dasar (/15m)</label>
                        <input type="number" name="price" required class="form-control" placeholder="20000">
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Simpan Mainan</button>
                </form>
            </div>
        </div>
    </div>
@endsection