<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;

class AdminSignupModel extends Authenticatable
{
    protected $connection = 'mongodb';
    protected $collection = 'adminsignup';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'theatre_name',
        'theatre_pic',
        'capacity',
        'address',
        'theatre_type',
        'seating_layout',
    ];
}
