<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentSearch extends Model
{
    protected $fillable = ['user_id', 'city_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
