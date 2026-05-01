<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // Tambahkan user_id ke dalam fillable
    protected $fillable = [
        'user_id',
        'city_name',
        'country',
        'latitude',
        'longitude'
    ];

    // Relasi: Satu favorit dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
