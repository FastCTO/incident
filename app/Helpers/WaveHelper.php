<?php

namespace App\Helpers;

class WaveHelper
{
    public static function getWaveToken()
    {
        $tokenPath = '/home/fastcto/.wave_token';
        if (file_exists($tokenPath)) {
            $token = trim(file_get_contents($tokenPath));
            return str_replace('export WAVE_TOKEN=', '', $token);
        }
        return null;
    }
}

