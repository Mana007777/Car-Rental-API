<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
        protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'driver_license_number',
        'date_of_birth',
        'membership_level',
        'points',
    ];

        public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function reviews()
    {
        return $this->hasMany(CarReview::class);
    }

    public function reservations()
    {
        return $this->hasMany(CarReservation::class);
    }
}
