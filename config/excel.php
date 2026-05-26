<?php

return [
    'exports' => [
        'chunk_size'       => 1000,     // rows dispatched per queued job
        'pre_calculate_formulas' => false,
        'csv' => [
            'delimiter'      => ',',
            'enclosure'      => '"',
            'line_ending'    => PHP_EOL,
        ],
    ],
    'temporary_files' => [
        'local_path'  => storage_path('framework/laravel-excel'),
        'remote_disk' => 'local',
        // 'local_path'  => storage_path('app/public'),
        // 'remote_disk' => env('EXCEL_TEMP_DISK', 's3'),
    ]
];

?>