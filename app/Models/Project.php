<?php

namespace App\Models;

use App\Models\Bug;
use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    //Traits
    use HasFactory;
    use SoftDeletes;

    protected $with = [];
    protected $guarded = [
        'id',
        'created_at',
        'updated_at', 
        'deleted_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d\TH:i',
        'updated_at' => 'datetime:Y-m-d\TH:i',
        'deleted_at' => 'datetime:Y-m-d\TH:i',
    ];

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    //Relations
    public function bugs()
    {
        return $this->hasMany(Bug::class);
    }
}
