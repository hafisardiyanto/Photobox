<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'status'
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }
}
