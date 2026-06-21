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
        'icon' => 'business_center',
        'items' => [
            [
                'title' => 'Data Pengguna',
                'icon' => 'group',
                'route' => 'users.index',
                'active_matches' => ['users.*', 'users.index'],
            ],
            [
                'title' => 'Penjadwalan',
                'icon' => 'calendar_month',
                'route' => 'lab',
                'active_matches' => ['lab', 'addJadwalView'],
            ],
            [
                'title' => 'Alat & Bahan',
                'icon' => 'science',
                'route' => 'alat',
                'active_matches' => ['alat', 'detailAlat'],
            ],
        ],
    ],

    [
        'section' => 'LAPORAN',
        'icon' => 'analytics',
        'items' => [
            [
                'title' => 'Peminjaman',
                'icon' => 'list_alt',
                'route' => 'laporanPeminjaman',
                'active_matches' => ['laporanPeminjaman', 'laporanView'],
            ],
            [
                'title' => 'Limbah',
                'icon' => 'delete',
                'route' => 'limbah',
                'active_matches' => ['limbah', 'tambahLimbah'],
            ],
        ],
    ],

    [
        'section' => 'PENGATURAN',
        'icon' => 'settings',
        'items' => [
            [
                'title' => 'Aktivitas Pengguna',
                'icon' => 'history',
                'route' => 'activity.logs',
                'active_matches' => ['activity.logs'],
            ],
            [
                'title' => 'Jasa Lab',
                'icon' => 'work',
                'route' => 'jaslabView',
                'active_matches' => ['jaslabView'],
            ],
            [
                'title' => 'Tentang Lab',
                'icon' => 'info',
                'route' => 'tentangLab',
                'active_matches' => ['tentangLab', 'editInfoLab'],
            ],
        ],
    ],

];
