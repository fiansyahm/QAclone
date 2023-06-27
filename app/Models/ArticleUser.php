<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionaires()
    {
        return $this->hasMany(ArticleUserQuestionaire::class);
    }
}
