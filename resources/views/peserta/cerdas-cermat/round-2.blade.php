@extends('layouts.peserta')

@section('title', 'Babak 2 - Cerdas Cermat')

@section('content')
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-scout-secondary">
            <div
                class="bg-scout-primary px-4 sm:px-6 py-3 sm:py-4 border-b border-scout-secondary flex flex-col sm:flex-row items-center sm:justify-between sticky top-0 z-20 shadow-md gap-3 sm:gap-0">
                <div class="text-center sm:text-left">
                    <h2 class="text-lg sm:text-xl font-bold text-white">Babak 2: Isian Singkat</h2>
                    <p class="text-white/90 text-xs sm:text-sm">Jawab dengan singkat dan tepat.</p>
                </div>
                <div class="text-white font-mono text-xl sm:text-2xl bg-white/20 px-4 py-2 rounded-lg font-bold w-full sm:w-auto text-center"
                    id="timer" v-pre>
                    00:00
                </div>
            </div>

            <div class="p-4 sm:p-8">
                <form action="{{ route('peserta.cerdas-cermat.round-2.submit') }}" method="POST" id="examForm">
                    @csrf

                    @foreach($questions as $index => $question)
                        <div
                            class="mb-6 sm:mb-8 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} rounded-lg border border-gray-200 p-5 sm:p-6">
                            <div class="flex items-start mb-4">
                                <span class="sr-only">Nomor</span>
                                <div
                                    class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-scout-accent text-scout-primary text-sm sm:text-base font-bold rounded-full mr-3">
                                    {{ $index + 1 }}
                                </div>
                                <div class="text-base sm:text-lg font-medium text-gray-900 leading-snug pt-0.5">
                                    {{ $question->question }}
                                </div>
                            </div>

                            <div class="ml-10 sm:ml-11">
                                <input type="text" name="answers[{{ $question->id }}]"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-scout-primary focus:ring focus:ring-scout-primary focus:ring-opacity-50 py-3 px-4 text-base"
                                    placeholder="Jawaban singkat..." required>
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs sm:text-sm text-gray-500 italic text-center sm:text-left">Pastikan semua jawaban
                            terisi sebelum klik selesai.</p>
                        <button type="button" id="submitButton"
                            class="w-full sm:w-auto bg-scout-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-scout-primary/90 transition shadow-lg flex items-center justify-center">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Selesaikan & Kirim Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Countdown Timer with Auto-Submit
            let remainingSeconds = {{ $remainingSeconds }};
            let hasSubmitted = false;

            function autoSubmitForm() {
                if (hasSubmitted) return;

                const form = document.getElementById("examForm");
                if (!form) return;

                hasSubmitted = true;

                // Remove 'required' attributes to prevent validation blocking
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => input.removeAttribute('required'));

                // Clear localStorage
                const textInputs = document.querySelectorAll('input[type="text"]');
                const storagePrefix = 'ans_r2_s{{ $session->id }}_';
                textInputs.forEach(input => {
                    localStorage.removeItem(storagePrefix + input.name);
                });
                localStorage.removeItem('round_2_submitted');

                // Create custom alert overlay
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                                                                                            position: fixed;
                                                                                            inset: 0;
                                                                                            background: rgba(0, 0, 0, 0.85);
                                                                                            backdrop-filter: blur(8px);
                                                                                            display: flex;
                                                                                            align-items: center;
                                                                                            justify-content: center;
                                                                                            z-index: 9999;
                                                                                            animation: fadeIn 0.3s ease-out;
                                                                                        `;

                overlay.innerHTML = `
                                                                                            <style>
                                                                                                @keyframes fadeIn {
                                                                                                    from { opacity: 0; }
                                                                                                    to { opacity: 1; }
                                                                                                }
                                                                                                @keyframes slideUp {
                                                                                                    from { transform: translateY(30px); opacity: 0; }
                                                                                                    to { transform: translateY(0); opacity: 1; }
                                                                                                }
                                                                                                @keyframes checkmark {
                                                                                                    0% { transform: scale(0); }
                                                                                                    50% { transform: scale(1.2); }
                                                                                                    100% { transform: scale(1); }
                                                                                                }
                                                                                            </style>
                                                                                            <div style="
                                                                                                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                                                                padding: 3rem 2.5rem;
                                                                                                border-radius: 24px;
                                                                                                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                                                                                                text-align: center;
                                                                                                max-width: 450px;
                                                                                                margin: 1rem;
                                                                                                animation: slideUp 0.4s ease-out;
                                                                                            ">
                                                                                                <div style="
                                                                                                    width: 80px;
                                                                                                    height: 80px;
                                                                                                    background: rgba(255, 255, 255, 0.2);
                                                                                                    border-radius: 50%;
                                                                                                    display: flex;
                                                                                                    align-items: center;
                                                                                                    justify-content: center;
                                                                                                    margin: 0 auto 1.5rem;
                                                                                                ">
                                                                                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" style="animation: checkmark 0.5s ease-out;">
                                                                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                                                                    </svg>
                                                                                                </div>
                                                                                                <h2 style="
                                                                                                    color: white;
                                                                                                    font-size: 1.75rem;
                                                                                                    font-weight: 800;
                                                                                                    margin: 0 0 1rem 0;
                                                                                                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                                                                                ">Waktu Habis!</h2>
                                                                                                <p style="
                                                                                                    color: rgba(255, 255, 255, 0.95);
                                                                                                    font-size: 1.05rem;
                                                                                                    margin: 0 0 2rem 0;
                                                                                                    line-height: 1.6;
                                                                                                ">Waktu pengerjaan telah selesai. Silakan kirim jawaban Anda untuk melihat hasil.<br><strong>Soal yang belum dijawab akan dianggap salah.</strong></p>
                                                                                                <button id="submitNowButton" style="
                                                                                                    background: white;
                                                                                                    color: #059669;
                                                                                                    padding: 0.875rem 2.5rem;
                                                                                                    border-radius: 12px;
                                                                                                    font-weight: 700;
                                                                                                    border: none;
                                                                                                    cursor: pointer;
                                                                                                    font-size: 1.1rem;
                                                                                                    transition: transform 0.2s;
                                                                                                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                                                                                                " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Kirim Jawaban & Lihat Hasil</button>
                                                                                            </div>
                                                                                        `;

                document.body.appendChild(overlay);

                // Submit button handler
                document.getElementById('submitNowButton').addEventListener('click', function () {
                    const currentForm = document.getElementById("examForm");
                    if (currentForm) currentForm.submit();
                });
            }

            function manualSubmitForm() {
                if (hasSubmitted) return;

                // Create custom confirmation overlay  
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                                                                    position: fixed;
                                                                    inset: 0;
                                                                    background: rgba(0, 0, 0, 0.85);
                                                                    backdrop-filter: blur(8px);
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    z-index: 9999;
                                                                    animation: fadeIn 0.3s ease-out;
                                                                `;

                overlay.innerHTML = `
                                                                    <style>
                                                                        @keyframes fadeIn {
                                                                            from { opacity: 0; }
                                                                            to { opacity: 1; }
                                                                        }
                                                                        @keyframes slideUp {
                                                                            from { transform: translateY(30px); opacity: 0; }
                                                                            to { transform: translateY(0); opacity: 1; }
                                                                        }
                                                                    </style>
                                                                    <div style="
                                                                        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                                                                        padding: 3rem 2.5rem;
                                                                        border-radius: 24px;
                                                                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                                                                        text-align: center;
                                                                        max-width: 450px;
                                                                        margin: 1rem;
                                                                        animation: slideUp 0.4s ease-out;
                                                                    ">
                                                                        <div style="
                                                                            width: 80px;
                                                                            height: 80px;
                                                                            background: rgba(255, 255, 255, 0.2);
                                                                            border-radius: 50%;
                                                                            display: flex;
                                                                            align-items: center;
                                                                            justify-content: center;
                                                                            margin: 0 auto 1.5rem;
                                                                        ">
                                                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                                                                <path d="M9 11l3 3L22 4"></path>
                                                                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                                                            </svg>
                                                                        </div>
                                                                        <h2 style="
                                                                            color: white;
                                                                            font-size: 1.75rem;
                                                                            font-weight: 800;
                                                                            margin: 0 0 1rem 0;
                                                                            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                                                        ">Kirim Jawaban Sekarang?</h2>
                                                                        <p style="
                                                                            color: rgba(255, 255, 255, 0.95);
                                                                            font-size: 1.05rem;
                                                                            margin: 0 0 2rem 0;
                                                                            line-height: 1.6;
                                                                        ">Yakin ingin mengirim jawaban? Soal yang belum dijawab akan dianggap salah.</p>
                                                                        <div style="display: flex; gap: 1rem; justify-content: center;">
                                                                            <button id="confirmSubmit" style="
                                                                                background: white;
                                                                                color: #1d4ed8;
                                                                                padding: 0.75rem 2rem;
                                                                                border-radius: 12px;
                                                                                font-weight: 700;
                                                                                border: none;
                                                                                cursor: pointer;
                                                                                font-size: 1rem;
                                                                                transition: transform 0.2s;
                                                                            " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Ya, Kirim</button>
                                                                            <button id="cancelSubmit" style="
                                                                                background: rgba(255, 255, 255, 0.2);
                                                                                color: white;
                                                                                padding: 0.75rem 2rem;
                                                                                border-radius: 12px;
                                                                                font-weight: 700;
                                                                                border: none;
                                                                                cursor: pointer;
                                                                                font-size: 1rem;
                                                                                transition: transform 0.2s;
                                                                            " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Batal</button>
                                                                        </div>
                                                                    </div>
                                                                `;

                document.body.appendChild(overlay);

                // Confirm button handler
                document.getElementById('confirmSubmit').addEventListener('click', function () {
                    const form = document.getElementById("examForm");
                    if (!form) return;

                    hasSubmitted = true;

                    // Remove 'required' attributes
                    const inputs = form.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => input.removeAttribute('required'));

                    // Clear localStorage
                    const textInputs = document.querySelectorAll('input[type="text"]');
                    const storagePrefix = 'ans_r2_s{{ $session->id }}_';
                    textInputs.forEach(input => {
                        localStorage.removeItem(storagePrefix + input.name);
                    });
                    localStorage.removeItem('round_2_submitted');

                    // Submit form
                    form.submit();
                });

                // Cancel button handler
                document.getElementById('cancelSubmit').addEventListener('click', function () {
                    document.body.removeChild(overlay);
                });
            }

            function updateTimer() {
                const timerElement = document.getElementById("timer");
                if (!timerElement) return;

                if (remainingSeconds <= 0) {
                    timerElement.innerHTML = "00:00";
                    timerElement.classList.remove('bg-white/20');
                    timerElement.classList.add('bg-red-600');

                    autoSubmitForm();
                    return;
                }

                let totalMinutes = Math.floor(remainingSeconds / 60);
                let seconds = Math.floor(remainingSeconds % 60);

                timerElement.innerHTML =
                    (totalMinutes < 10 ? "0" + totalMinutes : totalMinutes) + ":" +
                    (seconds < 10 ? "0" + seconds : seconds);

                remainingSeconds--;
            }

            setInterval(updateTimer, 1000);
            updateTimer(); // Call immediately to check if time is already up

            // Persistence Logic for Round 2
            document.addEventListener('DOMContentLoaded', function () {
                const inputs = document.querySelectorAll('input[type="text"]');
                const storagePrefix = 'ans_r2_s{{ $session->id }}_';

                inputs.forEach(input => {
                    const key = storagePrefix + input.name;
                    const savedValue = localStorage.getItem(key);
                    if (savedValue) {
                        input.value = savedValue;
                    }

                    input.addEventListener('input', function () {
                        localStorage.setItem(key, this.value);
                    });
                });

                // Use event delegation for default submit button
                document.addEventListener('click', function (e) {
                    if (e.target && (e.target.id === 'submitButton' || e.target.closest('#submitButton'))) {
                        e.preventDefault();
                        manualSubmitForm();
                    }
                });
            });
        </script>
    @endpush
@endsection