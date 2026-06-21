<?php

return [

    [
        'section' => 'GENERAL',
        'items' => [
            [
                'title' => 'Dashboard',
                'icon' => 'dashboard',
                'route' => 'dashboard',
                'active_matches' => ['dashboard'],
            ],
        ],
    ],

    [
        'section' => 'MANAJEMEN LAB',
        'items' => [
            [
                'title' => 'Data Pengguna',
                'icon' => 'person',
                'route' => 'users.index',
                'active_matches' => ['users.*', 'users.index'],
            ],
            [
                'title' => 'Penjadwalan',
                'icon' => 'calendar',
                'route' => 'lab',
                'active_matches' => ['lab', 'addJadwalView'],
            ],
            [
                'title' => 'Alat & Bahan',
                'icon' => 'beaker',
                'route' => 'alat',
                'active_matches' => ['alat', 'detailAlat'],
            ],
        ],
    ],

    [
        'section' => 'LAPORAN',
        'items' => [
            [
                'title' => 'Peminjaman',
                'icon' => 'clipboard-document-list',
                'route' => 'laporanPeminjaman',
                'active_matches' => ['laporanPeminjaman', 'laporanView'],
            ],
            [
                'title' => 'Limbah',
                'icon' => 'trash',
                'route' => 'limbah',
                'active_matches' => ['limbah', 'tambahLimbah'],
            ],
        ],
    ],

    [
        'section' => 'PENGATURAN',
        'items' => [
            [
                'title' => 'Aktivitas Pengguna',
                'icon' => 'clock',
                'route' => 'activity.logs',
                'active_matches' => ['activity.logs'],
            ],
            [
                'title' => 'Jasa Lab',
                'icon' => 'briefcase',
                'route' => 'jaslabView',
                'active_matches' => ['jaslabView'],
            ],
            [
                'title' => 'Tentang Lab',
                'icon' => 'information-circle',
                'route' => 'tentangLab',
                'active_matches' => ['tentangLab', 'editInfoLab'],
            ],
        ],
    ],

];
