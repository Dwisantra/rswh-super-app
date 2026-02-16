<?php

return [
    'name' => 'RegOnline',
    'url' => env('SIMRS_WS_URL'),
    'x_id' => env('SIMRS_WS_ID'),
    'x_pass' => env('SIMRS_WS_PASS'),
    'ref' => 'RS APP',
    'timeout' => env('SIMRS_WS_TIMEOUT', 15),
    'nik_lookup_path' => env('SIMRS_NIK_LOOKUP_PATH', '/registrasionline/rsonline/getPasienByNIK'),
];
