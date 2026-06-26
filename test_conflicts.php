<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$conflictedIds = \DB::table('jadwals as a')
    ->join('jadwals as b', function ($join) {
        $join->on('a.tanggal_jadwal', '=', 'b.tanggal_jadwal')
             ->on('a.ruangan_id', '=', 'b.ruangan_id')
             ->whereRaw('a.id != b.id')
             ->whereRaw('a.waktu_mulai < b.waktu_selesai')
             ->whereRaw('a.waktu_selesai > b.waktu_mulai');
    })
    ->pluck('a.id')
    ->unique()
    ->toArray();

echo "Conflicted IDs: " . implode(', ', $conflictedIds) . "\n";
echo "Total: " . count($conflictedIds) . "\n";
