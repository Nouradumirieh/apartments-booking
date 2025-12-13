<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

     use HasFactory;

    protected $fillable = [
        'user_id',
        'apartment_id',
        'start_date',
        'end_date',
        'requested_start_date',
    'requested_end_date',
        'status',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'requested_start_date' => 'date',
        'requested_end_date' => 'date',
    ];
   public function tenant()
{//user_id
    return $this->belongsTo(User::class, 'user_id');
}
public function apartment()
{
    return $this->belongsTo(Apartment::class);
}

}
