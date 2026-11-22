<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    /**
     * Generate Data URI (SVG base64) for embedding directly into <img src="...">
     */
    public static function generateDataUri(string $data, int $scale = 5): string
    {
        $options = new QROptions([
            'scale' => $scale,
            'addQuietzone' => true,
            'quietzoneSize' => 2,
            'outputBase64' => true,
        ]);

        return (new QRCode($options))->render($data);
    }

    /**
     * Generate raw SVG string
     */
    public static function generateSvg(string $data, int $scale = 5): string
    {
        $options = new QROptions([
            'scale' => $scale,
            'addQuietzone' => true,
            'quietzoneSize' => 2,
            'outputBase64' => false,
        ]);

        return (new QRCode($options))->render($data);
    }
}
