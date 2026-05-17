<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'title',
        'category', // Array
        'venue',
        'start_time',
        'end_time',
        'duration',
        'event_date',
        'description',
        'poster',
        'price_normal',
        'price_premium',
        'price_vip',
        'seating_layout',
    ];
}
