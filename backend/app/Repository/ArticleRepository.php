<?php

namespace App\Repository;
use App\Models\Article;
use App\Models\Preference;

class ArticleRepository 
{   
    public function getArticles(?string $keyword, ?string $category, ?string $source, ?string $date, int $page)
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

    public function getUserArticles(?Preference $preference, $page)
    {
        $query = Article::query();

        if (!empty($preference->sources)) {
            $query->whereIn('source', $preference->sources);
        }

        if (!empty($preference->authors)) {
            $query->whereIn('author', $preference->authors);
        }

        if (!empty($preference->categories)) {
            $query->whereIn('category', $preference->categories);
        }

        return $query->paginate(15, ['*'], 'page', $page);
    }
}
