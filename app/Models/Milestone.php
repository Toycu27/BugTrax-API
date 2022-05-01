<?php

namespace App\Models;

use App\Models\Bug;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    //Traits
    use HasFactory;
    use SoftDeletes;

    protected $with = [];
    protected $guarded = ['id'];

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    //Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function bugs()
    {
        return $this->hasMany(Bug::class);
    }
}
