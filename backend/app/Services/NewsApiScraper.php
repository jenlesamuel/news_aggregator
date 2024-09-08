<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Log;

class NewsApiScraper implements ScraperInterface
{

    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository) 
    {
        $this->articleRepository = $articleRepository;
    }

    public function scrape() 
    {   
        try {
            $baseUrl = env('NEWS_API_BASE_URL');
            $url =  "{$baseUrl}everything";
            $response = Http::get($url, [
                'apiKey' => env('NEWS_API_KEY'),
                'q' => 'bitcoin',
                'page' => 1,
            ]);

            if ($response->ok()) {
                $apiArticles = $response->json()['articles'];
                $articles = [];
                $now = Carbon::now();

                foreach ($apiArticles as $apiArticle) {
                    $article = [
                        'title' => $apiArticle['title'],
                        'content' => $apiArticle['content'],
                        'author' => $apiArticle['author'],
                        'source' => $apiArticle['source']['name'],
                        'category' => $apiArticle['category'] ?? '',
                        'published_at' => $apiArticle['publishedAt'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    
                    $articles[] = $article;
                }

                $this->articleRepository->saveBulk($articles);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}