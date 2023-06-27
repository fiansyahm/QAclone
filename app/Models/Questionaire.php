<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionaire extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function article_user_questionaire()
    {
        return $this->hasMany(ArticleUserQuestionaire::class);
    }
}
