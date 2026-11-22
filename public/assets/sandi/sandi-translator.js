// sandi-translator.js
// Modul penerjemah mandiri (zero-dependency) untuk Sandi Kotak 1, Sandi Kotak 2, dan Sandi Angka.
// Menggunakan aturan standar yang sama dengan aplikasi SANDI QUEST.

import sandiData from './sandi-data.json' with { type: 'json' };

/**
 * Normalisasi plaintext: huruf besar, hanya A-Z dan spasi tunggal.
 * @param {string} text
 * @returns {string}
 */
export function normaliseText(text) {
  return String(text ?? '')
    .toUpperCase()
    .replace(/[^A-Z\s]/g, '')
    .replace(/\s+/g, ' ')
    .trim();
}

// ── Sandi Kotak 1 ────────────────────────────────────────────────────────────

/**
 * Encode plaintext menjadi daftar objek simbol Sandi Kotak 1.
 * @param {string} plainText
 */
export function encodeKotak1(plainText) {
  const clean = normaliseText(plainText);
  return clean
    .split('')
    .filter((ch) => ch === ' ' || Boolean(sandiData.kotak1.alphabet[ch]))
    .map((ch) => (ch === ' ' ? sandiData.kotak1.space : sandiData.kotak1.alphabet[ch]));
}

/**
 * Decode array simbol (objek atau string symbolId seperti "k1-P" / "space") ke plaintext.
 * @param {Array<string | { symbolId: string }>} symbols
 * @returns {string}
 */
export function decodeKotak1(symbols) {
  return (symbols ?? [])
    .map((item) => {
      const id = typeof item === 'string' ? item : item?.symbolId;
      if (id === 'space') return ' ';
      return sandiData.kotak1.decodeMap[id] ?? '?';
    })
    .join('')
    .replace(/\s+/g, ' ')
    .trim();
}

// ── Sandi Kotak 2 ────────────────────────────────────────────────────────────

/**
 * Encode plaintext menjadi daftar objek simbol Sandi Kotak 2.
 * @param {string} plainText
 */
export function encodeKotak2(plainText) {
  const clean = normaliseText(plainText);
  return clean
    .split('')
    .filter((ch) => ch === ' ' || Boolean(sandiData.kotak2.alphabet[ch]))
    .map((ch) => (ch === ' ' ? sandiData.kotak2.space : sandiData.kotak2.alphabet[ch]));
}

/**
 * Decode array simbol (objek atau string symbolId seperti "k2-P" / "space") ke plaintext.
 * @param {Array<string | { symbolId: string }>} symbols
 * @returns {string}
 */
export function decodeKotak2(symbols) {
  return (symbols ?? [])
    .map((item) => {
      const id = typeof item === 'string' ? item : item?.symbolId;
      if (id === 'space') return ' ';
      return sandiData.kotak2.decodeMap[id] ?? '?';
    })
    .join('')
    .replace(/\s+/g, ' ')
    .trim();
}

// ── Sandi Angka ──────────────────────────────────────────────────────────────

/**
 * Encode plaintext menjadi Sandi Angka (contoh: "PRAMUKA JAYA" -> "16-18-1-13-21-11-1 10-1-25-1").
 * @param {string} plainText
 * @returns {string}
 */
export function encodeAngka(plainText) {
  const clean = normaliseText(plainText);
  if (!clean) return '';
  return clean
    .split(' ')
    .map((word) =>
      word
        .split('')
        .filter((ch) => /[A-Z]/.test(ch))
        .map((ch) => String(sandiData.angka.encodeMap[ch]))
        .join('-'),
    )
    .join(' ');
}

/**
 * Decode Sandi Angka menjadi plaintext (contoh: "16-18-1-13-21-11-1" -> "PRAMUKA").
 * @param {string} cipherText
 * @returns {string}
 */
export function decodeAngka(cipherText) {
  return String(cipherText ?? '')
    .split(' ')
    .map((wordNumbers) => {
      if (!wordNumbers.trim()) return '';
      return wordNumbers
        .split('-')
        .filter(Boolean)
        .map((token) => {
          const num = parseInt(token, 10);
          if (Number.isNaN(num) || num < 1 || num > 26) return '?';
          return sandiData.angka.decodeMap[String(num)] ?? '?';
        })
        .join('');
    })
    .join(' ')
    .trim();
}
