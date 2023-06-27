<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project_user()
    {
        return $this->hasMany(ProjectUser::class);
    }

    public function article()
    {
        return $this->hasMany(Article::class);
    }
}
