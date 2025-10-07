<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Project extends Model
{
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
