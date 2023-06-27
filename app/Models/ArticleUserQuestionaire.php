<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleUserQuestionaire extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function articleUser()
    {
        return $this->belongsTo(ArticleUser::class);
    }

    public function questionaire()
    {
        return $this->belongsTo(Questionaire::class);
    }
}
