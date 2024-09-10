<?php

namespace App\Repository;
use App\Models\Article;

class ArticleRepository 
{   
    public function getArticles(string $keyword, string $category, string $source, string $date, int $page)
    {
        $query = Article::query();
        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                ->orWhere('content','like', "%{$keyword}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($source) {
            $query->where('source', $source);
        }

        if ($date) {
            $query->WhereDate('published_at', $date);
        }

        return $query->paginate(15, ['*'], 'page', $page);
    }

    public function saveBulk(array $articles) 
    {
        Article::insert($articles);
    }

    public function getOptions() {
       return  [
            'authors' => Article::distinct()->pluck('author'),
            'categories' => Article::distinct()->pluck('category'),
            'sources' => Article::distinct()->pluck('source'),
        ];
    }
}
