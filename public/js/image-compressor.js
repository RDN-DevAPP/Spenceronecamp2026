/**
 * Client-Side Image Compression Utility for Spencerone Camp
 * - Resizes images to max 1920x1080 (maintaining aspect ratio)
 * - Compresses JPEG/WebP to ~800KB - 1MB (quality 0.8)
 * - Returns a new File/Blob suitable for standard form upload
 */
window.compressImage = function (file, options = {}) {
    const maxWidth = options.maxWidth || 1920;
    const maxHeight = options.maxHeight || 1080;
    const quality = options.quality || 0.82;
    const mimeType = options.mimeType || 'image/jpeg';
    const onProgress = options.onProgress || function () {};

    return new Promise((resolve, reject) => {
        if (!file || !file.type.startsWith('image/')) {
            return resolve(file); // Bukan image, jangan ubah
        }

        onProgress(10, 'Membaca berkas foto...');

        const reader = new FileReader();
        reader.onload = function (e) {
            onProgress(30, 'Memuat gambar ke memori browser...');
            const img = new Image();
            img.onload = function () {
                let width = img.width;
                let height = img.height;

                // Hitung rasio resolusi proporsional
                if (width > maxWidth || height > maxHeight) {
                    if (width / height > maxWidth / maxHeight) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    } else {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                onProgress(50, `Mengompresi ke dimensi ${width}x${height}px...`);

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                onProgress(75, 'Menghasilkan berkas akhir yang ringan...');

                canvas.toBlob(
                    function (blob) {
                        if (!blob) {
                            return reject(new Error('Gagal melakukan kompresi gambar di canvas.'));
                        }

                        // Buat file baru dengan nama asli
                        const originalNameWithoutExt = file.name.replace(/\.[^/.]+$/, '');
                        const extension = mimeType === 'image/webp' ? '.webp' : '.jpg';
                        const compressedFile = new File([blob], originalNameWithoutExt + '_compressed' + extension, {
                            type: mimeType,
                            lastModified: Date.now(),
                        });

                        onProgress(100, `Kompresi selesai! Ukuran: ${(compressedFile.size / (1024 * 1024)).toFixed(2)} MB`);
                        resolve(compressedFile);
                    },
                    mimeType,
                    quality
                );
            };

            img.onerror = function () {
                reject(new Error('Gagal membaca format gambar. Pastikan file tidak rusak.'));
            };

            img.src = e.target.result;
        };

        reader.onerror = function () {
            reject(new Error('Gagal membaca berkas file gambar.'));
        };

        reader.readAsDataURL(file);
    });
};
