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
        'section' => 'LAB MANAGEMENT',
        'icon' => 'business_center',
        'items' => [
            [
                'title' => 'User Data',
                'icon' => 'group',
                'route' => 'users.index',
                'active_matches' => ['users.*', 'users.index'],
            ],
            [
                'title' => 'Schedules',
                'icon' => 'calendar_month',
                'route' => 'lab',
                'active_matches' => ['lab', 'addJadwalView'],
            ],
            [
                'title' => 'Equipments & Materials',
                'icon' => 'science',
                'route' => 'alat',
                'active_matches' => ['alat', 'detailAlat'],
            ],
        ],
    ],

    [
        'section' => 'REPORTS',
        'icon' => 'analytics',
        'items' => [
            [
                'title' => 'Borrowings',
                'icon' => 'list_alt',
                'route' => 'laporanPeminjaman',
                'active_matches' => ['laporanPeminjaman', 'laporanView'],
            ],
            [
                'title' => 'Wastes',
                'icon' => 'delete',
                'route' => 'wastes.index',
                'active_matches' => ['wastes.*'],
            ],
            [
                'title' => 'User Activity Logs',
                'icon' => 'history',
                'route' => 'activity.logs',
                'active_matches' => ['activity.logs'],
            ],
        ],
    ],

    [
        'section' => 'SETTINGS',
        'icon' => 'settings',
        'items' => [
            [
                'title' => 'Lab Suits',
                'icon' => 'work',
                'route' => 'jaslabView',
                'active_matches' => ['jaslabView'],
            ],
            [
                'title' => 'About Lab',
                'icon' => 'info',
                'route' => 'tentangLab',
                'active_matches' => ['tentangLab', 'editInfoLab'],
            ],
        ],
    ],

];
