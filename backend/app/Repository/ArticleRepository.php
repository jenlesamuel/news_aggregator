<?php

namespace App\Repository;
use App\Models\Article;

class ArticleRepository 
{
    public function saveBulk(array $articles) 
    {
        Article::insert($articles);
    }
}
