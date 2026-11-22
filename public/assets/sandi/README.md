# Data Penerjemahan Sandi Kotak 1, Sandi Kotak 2, dan Sandi Angka

Paket data ini diekspor dari proyek **SANDI QUEST** agar mudah diterapkan di aplikasi lain (Web, Mobile, Backend, maupun Desktop).

## Isi Paket

- `sandi-data.json` — Kamus data lengkap:
  - `kotak1`: Pemetaan huruf `A–Z` beserta `symbolId`, `gridType`, `row`, `col`, `xQuadrant`, `hasDot`, koordinat garis/titik (`geometry`), dan string `svg`.
  - `kotak2`: Pemetaan huruf `A–Z` beserta `symbolId`, `row`, `col`, `dotCount` (0/1/2), `hasDot`, koordinat garis/titik (`geometry`), dan string `svg`.
  - `angka`: Pemetaan `A=1` s/d `Z=26`, `decodeMap`, serta pemisah huruf (`-`) dan kata (` `).
- `svg/kotak1/` — File SVG individual `A.svg` s/d `Z.svg` dan `space.svg` untuk Sandi Kotak 1.
- `svg/kotak2/` — File SVG individual `A.svg` s/d `Z.svg` dan `space.svg` untuk Sandi Kotak 2.
- `sandi-translator.js` — Fungsi siap pakai (ESM JavaScript) untuk `encode` & `decode` ketiga sandi.

## Catatan Warna SVG

Semua SVG menggunakan `stroke="currentColor"` dan `fill="currentColor"` pada `viewBox="0 0 42 42"`, sehingga otomatis mengikuti warna teks (`color`) di CSS/UI Anda (gelap maupun terang). Jika Anda merender di Canvas/Flutter/Native, gunakan data koordinat di `geometry.lines` dan `geometry.dots`.

## Contoh Penggunaan (`sandi-translator.js`)

```js
import {
  encodeKotak1,
  decodeKotak1,
  encodeKotak2,
  decodeKotak2,
  encodeAngka,
  decodeAngka,
} from './sandi-translator.js';

// Sandi Angka
const cipherAngka = encodeAngka('PRAMUKA JAYA'); // "16-18-1-13-21-11-1 10-1-25-1"
const plainAngka = decodeAngka(cipherAngka);     // "PRAMUKA JAYA"

// Sandi Kotak 1
const symbols1 = encodeKotak1('PRAMUKA');
const text1 = decodeKotak1(symbols1);            // "PRAMUKA"

// Sandi Kotak 2
const symbols2 = encodeKotak2('SANDI');
const text2 = decodeKotak2(['k2-S', 'k2-A', 'k2-N', 'k2-D', 'k2-I']); // "SANDI"
```
