<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver Hash
    |--------------------------------------------------------------------------
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Cost factor default 12 terlalu berat untuk mesin ini (±0,6 detik per hash),
    | sehingga import Excel berisi banyak user baru mudah melampaui batas waktu
    | eksekusi. Cost 10 tetap aman namun ±6x lebih cepat. Hash lama yang dibuat
    | dengan cost 12 tetap bisa diverifikasi (bcrypt menyimpan cost di hash).
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
        'verify' => env('HASH_VERIFY', true),
        'limit' => env('BCRYPT_LIMIT', 72),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    */

    'argon' => [
        'memory' => env('ARGON_MEMORY', 65536),
        'threads' => env('ARGON_THREADS', 1),
        'time' => env('ARGON_TIME', 4),
        'verify' => env('HASH_VERIFY', true),
    ],

];