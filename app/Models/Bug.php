<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\File;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bug extends Model
{
    //Traits
    use HasFactory;
    use SoftDeletes;

    public static $sortable = [
        'id',
        'status_id',
        'priority_id',
        'difficulty_id',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $with = [];
    protected $guarded = [
        'id', 
        'created_at',
        'updated_at', 
        'deleted_at', 
        'created_by', 
        'modified_by'
    ];

    protected $dates = [
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'end_date' => 'datetime:Y-m-d\TH:i',
        'created_at' => 'datetime:Y-m-d\TH:i',
        'updated_at' => 'datetime:Y-m-d\TH:i',
        'deleted_at' => 'datetime:Y-m-d\TH:i',
    ];

    //Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
