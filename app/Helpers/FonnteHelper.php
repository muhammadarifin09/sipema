<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FonnteHelper
{
    public static function send($target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post(env('FONNTE_URL'), [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->json();
    }
}