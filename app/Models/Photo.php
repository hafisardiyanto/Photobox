<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_session_id',
        'image_path',
        'sequence_number'
    ];

    public function session()
    {
        return $this->belongsTo(PhotoSession::class, 'photo_session_id');
    }
}
