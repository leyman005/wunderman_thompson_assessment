<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comments::class,'item_id', 'id');
    }
}
