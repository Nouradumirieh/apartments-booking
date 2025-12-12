<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{

 use HasFactory;


    protected $fillable = [
        'owner_id',
        'province_id',
        'city_id',
        'title',
        'description',
        'price',
        'images',
        'number_of_rooms',
        'number_of_bathrooms',
        'address_details',
        'status',
        'has_elevator',
        'has_balcony',
        'area',
    ];


    protected $casts = [
        'images' => 'array',
    ];














    public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}
public function bookings()
{
    return $this->hasMany(Booking::class);
}


public function province()
    {
        return $this->belongsTo(Province::class);
    }


    public function city()
    {
        return $this->belongsTo(City::class);
    }
 public function Reviews()
    {
        return $this->hasMany(Review::class);
    }
}
