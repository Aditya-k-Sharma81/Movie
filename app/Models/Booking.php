<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Booking extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bookings';

    protected $fillable = [
        'user_id',
        'movie_id',
        'seats',         // Array of seat IDs (e.g., ["A-1", "A-2"])
        'ticket_count',
        'attendees',     // Array of names (e.g., ["John", "Jane"])
        'customer_email',
        'customer_phone',
        'total_price',
        'booking_date',
        'razorpay_payment_id',
        'razorpay_order_id',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
