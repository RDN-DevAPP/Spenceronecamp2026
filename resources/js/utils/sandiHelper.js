import sandiData from '../data/sandi-data.json';

/**
 * Normalize cipher type identifier into 'kotak-1' | 'kotak-2' | 'angka'
 */
export function normalizeCipherType(type) {
    const raw = String(type ?? '').trim().toLowerCase();
    if (raw === 'kotak_1' || raw === 'kotak-1' || raw === 'kotak1' || raw === 'sandi kotak 1') {
        return 'kotak-1';
    }
    if (raw === 'kotak_2' || raw === 'kotak-2' || raw === 'kotak2' || raw === 'sandi kotak 2') {
        return 'kotak-2';
    }
    if (raw === 'angka' || raw === 'sandi angka' || raw === 'sandi_angka') {
        return 'angka';
    }
    return 'kotak-1';
}

export function getCipherTypeLabel(type) {
    const norm = normalizeCipherType(type);
    if (norm === 'kotak-1') return 'Sandi Kotak 1';
    if (norm === 'kotak-2') return 'Sandi Kotak 2';
    return 'Sandi Angka';
}

/**
 * Normalize plaintext to uppercase A-Z and single spaces
 */
export function normalizePlainText(text) {
    return String(text ?? '')
        .toUpperCase()
        .replace(/[^A-Z0-9\s]/g, '')
        .replace(/\s+/g, ' ');
}

/**
 * Map a single character (A-Z, space, or 1-26 for angka) to the extracted SVG asset path
 */
export function getCipherAssetPath(type, char) {
    const normType = normalizeCipherType(type);
    const rawChar = String(char ?? '');

    if (rawChar === ' ' || rawChar.toLowerCase() === 'space') {
        return `/assets/sandi/${normType}/space.svg`;
    }

    const upper = rawChar.trim().toUpperCase();

    if (normType === 'angka') {
        // Support direct numeric 1..26 or letter A..Z -> 1..26
        if (/^\d+$/.test(upper)) {
            const num = parseInt(upper, 10);
            if (num >= 1 && num <= 26) {
                return `/assets/sandi/angka/${num}.svg`;
            }
        }
        if (/^[A-Z]$/.test(upper) && sandiData.angka.encodeMap[upper]) {
            return `/assets/sandi/angka/${sandiData.angka.encodeMap[upper]}.svg`;
        }
        return `/assets/sandi/angka/space.svg`;
    }

    if (/^[A-Z]$/.test(upper)) {
        return `/assets/sandi/${normType}/${upper}.svg`;
    }

    return `/assets/sandi/${normType}/space.svg`;
}

/**
 * Get complete cipher metadata (SVG markup, geometry, symbolId, assetPath, numericCode)
 */
export function getCipherMeta(type, char) {
    const normType = normalizeCipherType(type);
    const rawChar = String(char ?? '');

    if (rawChar === ' ' || rawChar.toLowerCase() === 'space') {
        return {
            type: normType,
            char: ' ',
            isSpace: true,
            symbolId: 'space',
            numericCode: null,
            assetPath: getCipherAssetPath(normType, ' '),
            svg: sandiData.kotak1.space.svg,
        };
    }

    const upper = rawChar.trim().toUpperCase();

    if (normType === 'angka') {
        let letter = upper;
        let num = null;
        if (/^\d+$/.test(upper)) {
            const n = parseInt(upper, 10);
            if (n >= 1 && n <= 26) {
                num = n;
                letter = sandiData.angka.decodeMap[String(n)] ?? '?';
            }
        } else if (/^[A-Z]$/.test(upper)) {
            num = sandiData.angka.encodeMap[upper] ?? null;
        }

        return {
            type: 'angka',
            char: letter,
            isSpace: num === null,
            symbolId: num ? `angka-${num}` : 'space',
            numericCode: num,
            assetPath: getCipherAssetPath('angka', num ?? ' '),
            svg: null,
        };
    }

    const dictKey = normType === 'kotak-1' ? 'kotak1' : 'kotak2';
    const entry = sandiData[dictKey]?.alphabet?.[upper];

    return {
        type: normType,
        char: upper,
        isSpace: !entry,
        symbolId: entry?.symbolId ?? 'space',
        numericCode: null,
        assetPath: getCipherAssetPath(normType, upper),
        svg: entry?.svg ?? sandiData[dictKey].space.svg,
    };
}

/**
 * Encode plaintext string into an array of character token metadata for rendering
 */
export function translateToCipherTokens(type, plainText) {
    const normType = normalizeCipherType(type);
    const cleaned = String(plainText ?? '')
        .toUpperCase()
        .replace(/[^A-Z\s]/g, '');

    return cleaned.split('').map((ch, idx) => ({
        index: idx,
        ...getCipherMeta(normType, ch),
    }));
}

/**
 * Encode plaintext to Sandi Angka string e.g. "PRAMUKA JAYA" -> "16-18-1-13-21-11-1 10-1-25-1"
 */
export function encodeAngkaString(plainText) {
    const cleaned = String(plainText ?? '')
        .toUpperCase()
        .replace(/[^A-Z\s]/g, '')
        .replace(/\s+/g, ' ')
        .trim();

    if (!cleaned) return '';

    return cleaned
        .split(' ')
        .map((word) =>
            word
                .split('')
                .map((ch) => sandiData.angka.encodeMap[ch] ?? '')
                .filter(Boolean)
                .join('-')
        )
        .join(' ');
}

/**
 * Decode Sandi Angka string e.g. "16-18-1-13-21-11-1" -> "PRAMUKA"
 */
export function decodeAngkaString(cipherText) {
    return String(cipherText ?? '')
        .trim()
        .split(/\s+/)
        .map((word) =>
            word
                .split('-')
                .filter(Boolean)
                .map((tok) => {
                    const n = parseInt(tok, 10);
                    if (Number.isNaN(n) || n < 1 || n > 26) return '?';
                    return sandiData.angka.decodeMap[String(n)] ?? '?';
                })
                .join('')
        )
        .join(' ')
        .trim();
}

export { sandiData };
