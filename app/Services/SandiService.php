<?php

namespace App\Services;

class SandiService
{
    private static ?array $dataCache = null;

    public static function getData(): array
    {
        if (self::$dataCache !== null) {
            return self::$dataCache;
        }

        $path = public_path('assets/sandi/sandi-data.json');
        if (!file_exists($path)) {
            $path = resource_path('js/data/sandi-data.json');
        }

        $json = file_exists($path) ? file_get_contents($path) : '{}';
        self::$dataCache = json_decode($json, true) ?: [];

        return self::$dataCache;
    }

    public static function normalizeType(string $type): string
    {
        $raw = strtolower(trim($type));
        return match ($raw) {
            'kotak_1', 'kotak-1', 'kotak1', 'sandi kotak 1' => 'kotak_1',
            'kotak_2', 'kotak-2', 'kotak2', 'sandi kotak 2' => 'kotak_2',
            'angka', 'sandi_angka', 'sandi angka' => 'angka',
            default => 'kotak_1',
        };
    }

    public static function typeLabel(string $type): string
    {
        return match (self::normalizeType($type)) {
            'kotak_1' => 'Sandi Kotak 1',
            'kotak_2' => 'Sandi Kotak 2',
            'angka' => 'Sandi Angka',
            default => 'Sandi Kotak 1',
        };
    }

    public static function getAssetRelativePath(string $type, string $char): string
    {
        $norm = self::normalizeType($type);
        $folder = match ($norm) {
            'kotak_1' => 'kotak-1',
            'kotak_2' => 'kotak-2',
            'angka' => 'angka',
        };

        $upper = strtoupper(trim($char));
        if ($upper === '' || $upper === 'SPACE') {
            return "/assets/sandi/{$folder}/space.svg";
        }

        if ($norm === 'angka') {
            $data = self::getData();
            if (ctype_digit($upper)) {
                $num = (int) $upper;
                return ($num >= 1 && $num <= 26)
                    ? "/assets/sandi/angka/{$num}.svg"
                    : '/assets/sandi/angka/space.svg';
            }
            $num = $data['angka']['encodeMap'][$upper] ?? null;
            return $num ? "/assets/sandi/angka/{$num}.svg" : '/assets/sandi/angka/space.svg';
        }

        if (preg_match('/^[A-Z]$/', $upper)) {
            return "/assets/sandi/{$folder}/{$upper}.svg";
        }

        return "/assets/sandi/{$folder}/space.svg";
    }

    /**
     * Render explicit-color SVG string suitable for DomPDF & HTML embedding.
     */
    public static function getPdfSafeSvg(string $type, string $char, string $color = '#0F172A'): string
    {
        $norm = self::normalizeType($type);
        $upper = strtoupper(trim($char));
        $data = self::getData();

        if ($norm === 'angka') {
            $num = ctype_digit($upper)
                ? (int) $upper
                : ($data['angka']['encodeMap'][$upper] ?? null);

            if (!$num || $num < 1 || $num > 26) {
                return '<svg viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg"></svg>';
            }

            return '<svg viewBox="0 0 42 42" width="42" height="42" xmlns="http://www.w3.org/2000/svg">'
                . '<rect x="3" y="3" width="36" height="36" rx="7" stroke="' . $color . '" stroke-width="2.8" fill="none"/>'
                . '<text x="21" y="28" text-anchor="middle" font-family="DejaVu Sans, Arial, sans-serif" font-weight="bold" font-size="18" fill="' . $color . '">' . $num . '</text>'
                . '</svg>';
        }

        $dictKey = $norm === 'kotak_1' ? 'kotak1' : 'kotak2';
        $svg = $data[$dictKey]['alphabet'][$upper]['svg'] ?? $data[$dictKey]['space']['svg'] ?? '';

        if ($svg === '') {
            $filePath = public_path(ltrim(self::getAssetRelativePath($norm, $upper), '/'));
            if (file_exists($filePath)) {
                $svg = file_get_contents($filePath);
            }
        }

        // Replace currentColor with explicit hex and provide explicit intrinsic dimensions + bold strokes for DomPDF
        $svg = str_replace('currentColor', $color, $svg);
        $svg = str_replace('stroke-width="2.8"', 'stroke-width="3.4"', $svg);
        $svg = str_replace('r="3"', 'r="3.6"', $svg);
        $svg = str_replace('<svg ', '<svg width="42" height="42" ', $svg);

        return $svg;
    }

    /**
     * Render Base64 SVG Data URI for <img src="..."> inside DomPDF.
     */
    public static function getPdfDataUri(string $type, string $char, string $color = '#0F172A'): string
    {
        $svg = self::getPdfSafeSvg($type, $char, $color);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function getAngkaNumber(string $char): ?int
    {
        $data = self::getData();
        $upper = strtoupper(trim($char));
        return $data['angka']['encodeMap'][$upper] ?? null;
    }
}
