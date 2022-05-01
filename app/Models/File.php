<?php

namespace App\Models;

use App\Models\Bug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //Traits
    use HasFactory;

    protected $with = [];
    protected $guarded = ['id', 'user_id'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    //Relations
    public function bug()
    {
        return $this->belongsTo(Bug::class);
    }
}
