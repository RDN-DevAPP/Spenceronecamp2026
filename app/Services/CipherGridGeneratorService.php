<?php

namespace App\Services;

use InvalidArgumentException;

class CipherGridGeneratorService
{
    /**
     * All 8 readable directions: right, left, down, up, and 4 diagonals
     */
    private const DIRECTIONS = [
        [0, 1],   // horizontal L->R
        [1, 0],   // vertical T->B
        [1, 1],   // diagonal down-right
        [-1, 1],  // diagonal up-right
        [0, -1],  // horizontal R->L
        [-1, 0],  // vertical B->T
        [1, -1],  // diagonal down-left
        [-1, -1], // diagonal up-left
    ];

    /**
     * Generate an NxN word-search letter matrix placing all target words horizontally/vertically/diagonally.
     *
     * @param  array<int, string>  $words
     * @return array{matrix: array<int, array<int, string>>, placements: array<int, array<string, mixed>>}
     */
    public function generate(int $gridSize, array $words): array
    {
        if ($gridSize < 8 || $gridSize > 25) {
            throw new InvalidArgumentException("Ukuran grid harus antara 8x8 sampai 25x25.");
        }

        $normalizedWords = [];
        foreach ($words as $raw) {
            $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) $raw));
            if ($clean === '') {
                continue;
            }
            if (strlen($clean) > $gridSize) {
                throw new InvalidArgumentException("Kata '{$clean}' (" . strlen($clean) . " huruf) melebihi ukuran grid {$gridSize}x{$gridSize}.");
            }
            $normalizedWords[] = $clean;
        }

        $normalizedWords = array_values(array_unique($normalizedWords));

        // Sort longest words first for higher placement success rate
        usort($normalizedWords, fn(string $a, string $b) => strlen($b) <=> strlen($a));

        $maxRestarts = 25;
        for ($restart = 0; $restart < $maxRestarts; $restart++) {
            $grid = array_fill(0, $gridSize, array_fill(0, $gridSize, null));
            $placements = [];
            $allPlaced = true;

            foreach ($normalizedWords as $word) {
                $placed = $this->tryPlaceWord($grid, $gridSize, $word);
                if ($placed === null) {
                    $allPlaced = false;
                    break;
                }
                $placements[] = $placed;
            }

            if ($allPlaced) {
                // Fill remaining empty cells with random A-Z letters
                $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                for ($r = 0; $r < $gridSize; $r++) {
                    for ($c = 0; $c < $gridSize; $c++) {
                        if ($grid[$r][$c] === null) {
                            $grid[$r][$c] = $alphabet[random_int(0, 25)];
                        }
                    }
                }

                return [
                    'matrix' => $grid,
                    'placements' => $placements,
                ];
            }
        }

        throw new InvalidArgumentException(
            "Gagal menyusun seluruh kata ke dalam grid {$gridSize}x{$gridSize}. Coba perbesar ukuran grid atau kurangi jumlah kata."
        );
    }

    private function tryPlaceWord(array &$grid, int $size, string $word): ?array
    {
        $len = strlen($word);
        $directions = self::DIRECTIONS;

        for ($attempt = 0; $attempt < 250; $attempt++) {
            $dir = $directions[random_int(0, count($directions) - 1)];
            $dr = $dir[0];
            $dc = $dir[1];

            $rStart = random_int(0, $size - 1);
            $cStart = random_int(0, $size - 1);

            $rEnd = $rStart + ($len - 1) * $dr;
            $cEnd = $cStart + ($len - 1) * $dc;

            if ($rEnd < 0 || $rEnd >= $size || $cEnd < 0 || $cEnd >= $size) {
                continue;
            }

            $canPlace = true;
            for ($i = 0; $i < $len; $i++) {
                $r = $rStart + $i * $dr;
                $c = $cStart + $i * $dc;
                $existing = $grid[$r][$c];
                if ($existing !== null && $existing !== $word[$i]) {
                    $canPlace = false;
                    break;
                }
            }

            if (!$canPlace) {
                continue;
            }

            $coords = [];
            for ($i = 0; $i < $len; $i++) {
                $r = $rStart + $i * $dr;
                $c = $cStart + $i * $dc;
                $grid[$r][$c] = $word[$i];
                $coords[] = [$r, $c];
            }

            return [
                'word' => $word,
                'start' => [$rStart, $cStart],
                'end' => [$rEnd, $cEnd],
                'direction' => [$dr, $dc],
                'coords' => $coords,
            ];
        }

        return null;
    }
}
