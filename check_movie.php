<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Movie;

$movie = Movie::first();
if ($movie) {
    $data = $movie->toArray();
    unset($data['seating_layout']); // Remove large field
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    echo "No movies found.";
}
