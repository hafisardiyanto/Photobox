<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_session_id',
        'frame_id',
        'result_path'
    ];

    public function session()
    {
        return $this->belongsTo(PhotoSession::class, 'photo_session_id');
    }

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }
}
