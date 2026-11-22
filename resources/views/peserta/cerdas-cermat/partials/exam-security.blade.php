{{-- Exam Security: Anti-Screenshot, Anti-Copy-Paste, Anti-Refresh, Tab Lock (SweetAlert Based) --}}
<style>
    /* Prevent text selection */
    body.exam-secure {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
        -webkit-touch-callout: none !important;
    }

    /* Allow selection only inside input/textarea for answering */
    body.exam-secure input,
    body.exam-secure textarea,
    body.exam-secure select {
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
        user-select: text !important;
    }
</style>

<script>
(function() {
    'use strict';

    // ==========================================
    // SERVER VIOLATION REPORTING (fire-and-forget)
    // ==========================================
    const reportUrl = '{{ route("peserta.cerdas-cermat.report-violation") }}';
    const csrfToken = '{{ csrf_token() }}';

    function getCurrentRound() {
        // Try to detect current round from the page URL
        const path = window.location.pathname;
        if (path.includes('round-3')) return 3;
        if (path.includes('round-2')) return 2;
        return 1;
    }

    function reportToServer(violationType, description) {
        fetch(reportUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                violation_type: violationType,
                round: getCurrentRound(),
                description: description
            })
        }).catch(() => {}); // silent fail
    }

    // ==========================================
    // 1. ANTI-SELECT (CSS class on body)
    // ==========================================
    document.body.classList.add('exam-secure');

    // ==========================================
    // 2. ANTI-COPY-PASTE & CONTEXT MENU
    // ==========================================
    const blockedEvents = ['copy', 'cut', 'paste', 'contextmenu'];

    blockedEvents.forEach(eventName => {
        document.addEventListener(eventName, function(e) {
            // Allow paste inside input/textarea for answering? No, still block for security.
            if (eventName === 'paste' && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA')) {
                e.preventDefault();
                reportToServer('paste', 'Paste di kolom jawaban');
                showSecurityAlert(
                    'Paste Tidak Diizinkan!',
                    'Anda tidak diperbolehkan paste teks pada halaman ujian. Ketik jawaban Anda secara manual.',
                    'warning'
                );
                return;
            }
            e.preventDefault();

            let msg = '';
            let vtype = eventName === 'contextmenu' ? 'context_menu' : eventName;
            switch(eventName) {
                case 'copy': msg = 'Menyalin teks tidak diperbolehkan selama ujian berlangsung.'; break;
                case 'cut': msg = 'Memotong teks tidak diperbolehkan selama ujian berlangsung.'; break;
                case 'paste': msg = 'Menempel teks tidak diperbolehkan selama ujian berlangsung.'; break;
                case 'contextmenu': msg = 'Klik kanan tidak diperbolehkan selama ujian berlangsung.'; break;
            }
            reportToServer(vtype, msg);
            showSecurityAlert('Aksi Diblokir!', msg, 'warning');
        });
    });

    // ==========================================
    // 3. ANTI-SCREENSHOT & DEV TOOLS
    // ==========================================
    document.addEventListener('keydown', function(e) {
        // PrintScreen
        if (e.key === 'PrintScreen' || e.keyCode === 44) {
            e.preventDefault();
            reportToServer('screenshot', 'Menekan tombol PrintScreen');
            showSecurityAlert(
                'Screenshot Diblokir! 📸',
                'Mengambil screenshot tidak diperbolehkan selama ujian berlangsung. Tindakan ini telah dicatat.',
                'error'
            );
            return false;
        }

        // Ctrl+P (Print), Ctrl+S, Ctrl+U, etc.
        if (e.ctrlKey && (e.key === 'p' || e.key === 's' || e.key === 'u')) {
            e.preventDefault();
            showSecurityAlert('Aksi Diblokir!', 'Tindakan ini tidak diperbolehkan selama ujian.', 'warning');
            return false;
        }

        // DevTools
        if ((e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || e.key === 'F12') {
            e.preventDefault();
            reportToServer('devtools', 'Mencoba membuka Developer Tools');
            showSecurityAlert('Aksi Diblokir!', 'Membuka alat pengembang tidak diperbolehkan selama ujian.', 'warning');
            return false;
        }
    });

    // ==========================================
    // 4. ANTI-REFRESH WARNING
    // ==========================================
    window.addEventListener('beforeunload', function(e) {
        if (window._examFormSubmitting) return;
        e.preventDefault();
        e.returnValue = 'Anda yakin ingin meninggalkan halaman ujian?';
        return e.returnValue;
    });

    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'examForm') {
            window._examFormSubmitting = true;
        }
    });

    // ==========================================
    // 5. TIERED ANTI-CHEAT 2.0 (Toleransi Bertingkat)
    // ==========================================
    let violationCount = 0;
    let isLocked = false;
    let resizeTimer = null;

    function handleExamViolation(violationType, description) {
        if (isLocked || window._examFormSubmitting) return;

        fetch(reportUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                violation_type: violationType,
                round: getCurrentRound(),
                description: description
            })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            violationCount = data.warning_count;

            if (data.is_locked || violationCount >= 2) {
                // PELANGGARAN KE-2: LOCK TOTAL (FREEZE SCREEN MERAH)
                isLocked = true;
                showLockedModal();
            } else {
                // PELANGGARAN KE-1: WARNING MODAL KUNING (WAKTU TETAP BERJALAN)
                showWarningModal();
            }
        })
        .catch(() => {});
    }

    function showWarningModal() {
        if (typeof Swal === 'undefined') return;

        Swal.fire({
            icon: 'warning',
            title: '⚠️ PERINGATAN 1: JANGAN TINGGALKAN UJIAN!',
            html: `
                <div class="text-left text-sm text-gray-700 space-y-2">
                    <p>Sistem mendeteksi Anda meninggalkan jendela ujian atau melakukan tindakan terlarang.</p>
                    <div class="p-3 bg-amber-50 border-l-4 border-amber-500 rounded text-xs text-amber-900 font-semibold">
                        <strong>Perhatian:</strong> Waktu ujian tetap berjalan normal. Namun, jika terjadi <strong>1 kali pelanggaran lagi</strong>, layar ujian Anda akan <strong>DIBEKUKAN / DIKUNCI TOTAL</strong> dan membutuhkan pembukaan langsung dari Dewan Juri.
                    </div>
                </div>
            `,
            confirmButtonText: 'Saya Mengerti, Lanjutkan Ujian',
            confirmButtonColor: '#D97706',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'rounded-3xl shadow-2xl border-2 border-amber-400',
                title: 'text-lg font-black text-amber-900',
            }
        });
    }

    function showLockedModal() {
        if (typeof Swal === 'undefined') return;

        Swal.fire({
            title: '🔒 UJIAN DIKUNCI OLEH ANTI-CHEAT',
            html: `
                <div class="text-center">
                    <div class="inline-block p-3 rounded-full bg-red-100 text-red-600 mb-3">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V9a2 2 0 00-2-2M5 13V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Anda telah melakukan pelanggaran batas toleransi (2 kali).<br>
                        Silakan panggil <strong>Dewan Juri</strong> di pos pengawas untuk membuka kunci ujian Anda atau masukkan kode buka kunci di bawah ini.
                    </p>
                </div>
            `,
            input: 'text',
            inputPlaceholder: 'Kode Unlock 6 Digit...',
            inputAttributes: {
                maxlength: 6,
                autocapitalize: 'off',
                autocorrect: 'off',
                style: 'text-align: center; font-family: monospace; font-size: 1.5rem; letter-spacing: 0.3em;'
            },
            confirmButtonText: 'Verifikasi Kode',
            confirmButtonColor: '#DC2626',
            showCancelButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: true,
            padding: '2rem',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border-4 border-red-600',
                title: 'text-xl font-black text-red-900',
                input: 'mt-2 border-2 border-red-200 focus:border-red-500 rounded-xl'
            },
            showLoaderOnConfirm: true,
            preConfirm: async (code) => {
                if (!code) {
                    Swal.showValidationMessage('Masukkan kode unlock dari Juri.');
                    return false;
                }
                try {
                    const res = await fetch('{{ route("peserta.cerdas-cermat.verify-unlock-code") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ code: code })
                    });
                    const data = await res.json();
                    if (!data.success) {
                        throw new Error(data.message || 'Kode verifikasi salah.');
                    }
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message);
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                isLocked = false;
                violationCount = 0;
                Swal.fire({
                    title: 'Ujian Dibuka! ✅',
                    text: 'Kunci telah dilepas. Silakan lanjutkan ujian dengan tertib.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                });
            }
        });
    }

    // Detection: Visibility & Focus
    document.addEventListener('visibilitychange', function() {
        if (document.hidden && !isLocked) {
            handleExamViolation('tab_switch', 'Meninggalkan tab ujian');
        }
    });

    window.addEventListener('blur', function() {
        setTimeout(() => {
            if (!document.hasFocus() && !isLocked) {
                handleExamViolation('tab_switch', 'Kehilangan fokus tab browser');
            }
        }, 400);
    });

    // Mobile / Tablet resize buffer delay 1s to prevent virtual keyboard false alarms
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // Buffer delay 1 second expired, ignore keyboard open/close
        }, 1000);
    });

    // Global listener if opened via WebSocket
    window.addEventListener('exam-unlocked', function() {
        isLocked = false;
        violationCount = 0;
        if (typeof Swal !== 'undefined' && Swal.isVisible()) {
            Swal.close();
            Swal.fire({
                title: 'Dibuka oleh Juri! 🔓',
                text: 'Dewan juri telah membuka kunci ujian Anda secara instan.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });

    // ==========================================
    // UTILITY: Show Security Alert
    // ==========================================
    function showSecurityAlert(title, message, icon) {
        if (isLocked) return;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonColor: '#D97706',
                confirmButtonText: 'Mengerti',
                allowOutsideClick: false
            });
        }
    }

})();
</script>
