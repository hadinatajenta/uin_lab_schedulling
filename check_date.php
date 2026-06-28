<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$v = Validator::make(['tanggal_jadwal' => '2026-07-01'], ['tanggal_jadwal' => 'after_or_equal:today']);
var_dump($v->passes(), $v->errors()->all());
