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
        'status'
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
