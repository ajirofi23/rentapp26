@extends('layouts.app')

@section('content')
    <div style="display: flex; align-items: center; justify-content: center; min-height: 80vh;">
        <div class="card" style="width: 100%; max-width: 400px; padding: 2.5rem;">
            <div class="text-center mb-4">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏎️</div>
                <h1 style="margin-bottom: 0.5rem;">Selamat Datang</h1>
                <p>Masuk ke panel RentApp Anda</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Email Kantor</label>
                    <input type="email" name="email" required class="form-control" placeholder="nama@rentapp.test"
                        autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" required class="form-control" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary w-full" style="height: 48px; margin-top: 1rem;">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-4"
                style="background: var(--bg-input); padding: 1rem; border-radius: 8px; font-size: 0.8rem; border: 1px dashed var(--border-color);">
                <p style="font-weight: 600; margin-bottom: 0.25rem;">Akun Demo:</p>
                <div class="flex justify-between" style="margin-bottom: 0.25rem;">
                    <span>Owner:</span>
                    <code>owner@rentapp.test</code>
                </div>
                <div class="flex justify-between">
                    <span>Karyawan:</span>
                    <code>budi@rentapp.test</code>
                </div>
                <p style="margin-top: 0.5rem; color: var(--text-muted);">Sandi: <code>password</code></p>
            </div>
        </div>
    </div>
@endsection