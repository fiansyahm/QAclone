<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function article_user()
    {
        return $this->hasMany(ArticleUser::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
