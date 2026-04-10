<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$courses = Modules\LMS\Models\Courses\Course::with('instructors.userable')->get(['id', 'title', 'status', 'admin_id']);
$categories = Modules\LMS\Models\Category::all();

$output = [
    'courses' => $courses->toArray(),
    'categories' => $categories->toArray(),
];

file_put_contents('db_output.json', json_encode($output, JSON_PRETTY_PRINT));
