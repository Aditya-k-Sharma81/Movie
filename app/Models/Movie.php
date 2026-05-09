<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Movie extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'movies';

    protected $fillable = [
        'title',
        'category', // Array
        'genre', // Array
        'start_time',
        'end_time',
        'duration',
        'release_date',
        'language',
        'description',
        'poster',
        'price_normal',
        'price_premium',
        'price_vip',
        'seating_layout',
    ];
}
