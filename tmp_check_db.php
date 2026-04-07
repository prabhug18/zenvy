<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        $name = array_values((array)$table)[0];
        echo "- $name\n";
    }

    echo "\nStatus of 'cache' table:\n";
    $status = DB::select("SHOW TABLE STATUS LIKE 'cache'");
    print_r($status);

    echo "\nTrying to select from 'cache':\n";
    $res = DB::table('cache')->limit(1)->get();
    print_r($res);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
