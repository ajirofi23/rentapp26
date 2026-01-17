@extends('layouts.app')

@section('content')
    <div class="grid md-cols-3">
        <!-- Section: Sewa Baru -->
        <div class="md-span-1">
            <div class="card">
                <h3 style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Sewa Baru
                </h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nama Customer</label>
                        <input type="text" name="nama_customer" required class="form-control" placeholder="Andi">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mainan</label>
                        <select name="toy_id" id="toy_id" required class="form-control" onchange="updateSelectedToy()">
                            <option value="" disabled selected>Pilih Mainan</option>
                            @foreach($toys as $toy)
                                <option value="{{ $toy->id }}" data-price="{{ $toy->price }}"
                                    data-category="{{ $toy->category }}">
                                    [{{ $toy->category }}] {{ $toy->code }} - Rp {{ number_format($toy->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jam Mulai</label>
                        <input type="text" value="{{ now()->setTimezone('Asia/Jakarta')->format('H:i') }}" disabled
                            class="form-control" style="opacity: 0.6; cursor: not-allowed;">
                        <small style="color: var(--text-muted); font-size: 0.75rem;">* Mengikuti waktu sistem GMT+7</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Durasi Sewa</label>
                        <div class="flex" style="gap: 0.5rem;">
                            <div style="flex: 1;">
                                <div class="flex" style="gap: 0.25rem;">
                                    <input type="number" id="input_jam" min="0" value="0" class="form-control text-center"
                                        oninput="calculateEndTime()">
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">J</span>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <div class="flex" style="gap: 0.25rem;">
                                    <input type="number" id="input_menit" min="0" value="15" step="1"
                                        class="form-control text-center" oninput="calculateEndTime()">
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">M</span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="durasi_menit" id="durasi_menit" value="15">
                    </div>

                    <div class="grid md-cols-2 mb-4" style="gap: 1rem;">
                        <div
                            style="background: var(--bg-input); padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color);">
                            <label class="form-label" style="margin-bottom: 0.25rem; font-size: 0.75rem;">Estimasi
                                Selesai</label>
                            <div id="jam_selesai_display"
                                style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">--:--</div>
                        </div>
                        <div
                            style="background: var(--bg-input); padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color);">
                            <label class="form-label" style="margin-bottom: 0.25rem; font-size: 0.75rem;">Total
                                Bayar</label>
                            <div id="price_display" style="font-size: 1.5rem; font-weight: 800; color: var(--success);">Rp 0
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="height: 50px;">
                        Mulai Durasi
                    </button>
                </form>
            </div>
        </div>

        <!-- Section: Transaksi Aktif -->
        <div class="md-span-2">
            <div class="card" style="min-height: 100%;">
                <div class="flex justify-between mb-4">
                    <h3 style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Sedang Bermain
                    </h3>
                    <span class="badge badge-success">{{ count($transaksis->where('status', 'aktif')) }} Aktif</span>
                </div>

                <div class="grid" style="gap: 1rem;">
                    @forelse($transaksis as $transaksi)
                        @php
                            $isActive = $transaksi->status === 'aktif';
                        @endphp
                        <div class="card"
                            style="padding: 1rem; border-left: 4px solid {{ $isActive ? 'var(--primary)' : 'var(--border-color)' }}; background: {{ $isActive ? 'var(--bg-card)' : 'var(--bg-input)' }};">
                            <div class="flex justify-between">
                                <div>
                                    <h4 style="font-size: 1.1rem; margin-bottom: 0.25rem;">
                                        {{ $transaksi->nama_customer }}
                                        <span style="color: var(--text-muted); font-weight: 400; font-size: 0.9rem;">
                                            ({{ $transaksi->toy->code }} - Rp
                                            {{ number_format($transaksi->total_harga, 0, ',', '.') }})
                                        </span>
                                    </h4>
                                    <div class="flex" style="gap: 1rem; font-size: 0.85rem; color: var(--text-muted);">
                                        <span>Mulai:
                                            {{ $transaksi->jam_mulai->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                                        <span>Selesai:
                                            {{ $transaksi->jam_selesai->setTimezone('Asia/Jakarta')->format('H:i') }}</span>
                                    </div>
                                </div>

                                @if($isActive)
                                    <div class="text-right">
                                        <div class="countdown-timer" data-end="{{ $transaksi->jam_selesai->timestamp * 1000 }}"
                                            data-id="{{ $transaksi->id }}" data-toy="{{ $transaksi->toy->code }}"
                                            style="font-size: 1.5rem; font-weight: 800; font-family: monospace; color: var(--primary);">
                                            --:--
                                        </div>
                                        <form action="{{ route('transaksi.complete', $transaksi->id) }}" method="POST"
                                            style="margin-top: 0.5rem;">
                                            @csrf
                                            <button class="btn btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                                Selesaikan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                        <span class="badge"
                                            style="background: var(--bg-body); color: var(--text-muted);">SELESAI</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center" style="padding: 3rem 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                                stroke="var(--border-color)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                style="margin-bottom: 1rem;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <p>Belum ada aktivitas hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateEndTime() {
            const jam = parseInt(document.getElementById('input_jam').value) || 0;
            const menit = parseInt(document.getElementById('input_menit').value) || 0;

            const totalMenit = (jam * 60) + menit;
            document.getElementById('durasi_menit').value = totalMenit;

            const now = new Date();
            now.setMinutes(now.getMinutes() + totalMenit);

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            document.getElementById('jam_selesai_display').innerText = `${hours}:${minutes}`;

            // Calculate and display price
            updatePrice();
        }

        function updatePrice() {
            const select = document.getElementById('toy_id');
            const selectedOption = select.options[select.selectedIndex];
            const pricePer15 = selectedOption ? parseInt(selectedOption.dataset.price) : 0;

            const totalMenit = parseInt(document.getElementById('durasi_menit').value);
            const intervals = Math.ceil(totalMenit / 15);
            const totalPrice = intervals * pricePer15;

            document.getElementById('price_display').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        }

        function updateSelectedToy() {
            calculateEndTime();
        }

        calculateEndTime();

        const notifiedTransactions = new Set();
        let audioCtx = null;
        let isAlarmActive = false;
        let speechQueue = [];

        function initAudio() {
            if (!audioCtx) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
        }

        function playBeep() {
            initAudio();
            if (!audioCtx || audioCtx.state !== 'running') return;

            const oscillator = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();

            oscillator.type = 'square';
            oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);

            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.1);
        }

        // Single global beep interval
        setInterval(() => {
            if (isAlarmActive) {
                playBeep();
                setTimeout(() => playBeep(), 200);
            }
        }, 800);

        // Voice worker: speaks from queue one by one
        setInterval(() => {
            if (speechQueue.length > 0 && ('speechSynthesis' in window) && !window.speechSynthesis.speaking) {
                const toyCode = speechQueue.shift();
                const spokenCode = toyCode.split('').map(char => char === '0' ? 'kosong' : char).join(' ');

                const msg = new SpeechSynthesisUtterance(`Waktu habis untuk Mainan ${spokenCode}. Harap berhenti.`);
                msg.lang = 'id-ID';
                msg.rate = 0.9;
                window.speechSynthesis.speak(msg);
            }
        }, 2000);

        setInterval(() => {
            const timers = document.querySelectorAll('.countdown-timer');
            const now = Date.now();
            let anyExpired = false;

            timers.forEach(timer => {
                const endTime = parseInt(timer.dataset.end);
                const distance = endTime - now;
                const toyCode = timer.dataset.toy;
                const id = timer.dataset.id;

                if (distance < 0) {
                    anyExpired = true;
                    timer.innerHTML = "WAKTU HABIS";
                    timer.style.color = "var(--danger)";

                    if (!notifiedTransactions.has(id)) {
                        speechQueue.push(toyCode);
                        notifiedTransactions.add(id);
                    }
                } else {
                    const hours = Math.floor(distance / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let display = "";
                    if (hours > 0) display += String(hours).padStart(2, '0') + ":";
                    display += String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');

                    timer.innerHTML = display;
                    timer.style.color = distance < (2 * 60 * 1000) ? "var(--warning)" : "var(--primary)";
                }
            });

            isAlarmActive = anyExpired;
        }, 1000);

        document.addEventListener('click', () => {
            initAudio();
            if ('speechSynthesis' in window && !window.speechSynthesis.speaking) {
                const msg = new SpeechSynthesisUtterance('');
                msg.volume = 0;
                window.speechSynthesis.speak(msg);
            }
        }, { once: true });

        document.querySelector('form').addEventListener('submit', () => initAudio());
    </script>
@endsection