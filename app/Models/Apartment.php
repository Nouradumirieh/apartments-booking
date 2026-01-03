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
        'admin_status',
    ];


    protected $casts = [
        'images' => 'array',
    ];






Public function getImagesAttribute($value)
{
    // فك تشفير المصفوفة المخزنة في قاعدة البيانات
    $images = json_decode($value, true) ?: [];

    return array_map(function ($image) {
        // إذا كان الرابط كاملاً أصلاً (يبدأ بـ http) نتركه كما هو
        if (str_starts_with($image, 'http')) {
            return $image;
        }
        // إضافة رابط السيرفر الأساسي ومسار التخزين
        return url('storage/' . $image);
    }, $images);
}







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
    }// عدلت اسم التابع من حرف كبير لصغير بدون مااعرف وين عم يستخدم
 public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
