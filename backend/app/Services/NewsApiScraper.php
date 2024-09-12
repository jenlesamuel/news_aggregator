<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
            // Get the current date and page from cache (default to today if not found)
            $currentDate = Cache::get('newsapi_current_date', Carbon::now()->toDateString());
            $currentPage = Cache::get('newsapi_current_page', 1);

            // Fetch sources
            $response = Http::get(env('NEWS_API_SOURCES_BASE_URL'), [
                'apiKey' => env('NEWS_API_KEY'),
            ]);

            $sources = 'abc-news'; // At least one source must be set to use the sources query parameter
            if ($response->successful()) {
                $sourceData = $response->json()['sources'];
                // Extract the first 20 sources and return a comma-separated list of source IDs
                // The end point to fetch articles requires a maximum of 20 sources
                $sources = collect($sourceData)->take(20)->pluck('id')->implode(',');
            }
    
            $response = Http::get(env('NEWS_API_ARTICLES_BASE_URL'), [
                'from' => $currentDate,
                'to' => $currentDate,
                'page' => $currentPage,
                'apiKey' => env('NEWS_API_KEY'),
                'sources' => $sources,
            ]);

            if ($response->failed()) {
                throw new \Exception(
                    "Failed to fetch data from The News API: {$response->json()['message']}"
                );
            }

            $responseData = $response->json();
            $apiArticles = $responseData['articles'];
            $totalPages = $responseData['totalResults'];

            $this->saveArticles($apiArticles);

            // Determine whether to fetch the next page or move to the previous date
            if ($currentPage < $totalPages) {
                Cache::put('newsapi_current_page', $currentPage + 1);
            } else {
                // If all pages for the current date are fetched, move to the previous date
                $previousDate = Carbon::parse($currentDate)->subDay()->toDateString();
                Cache::put('newsapi_current_date', $previousDate);
                Cache::put('newsapi_current_page', 1); // Reset page to 1 for the new date
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function saveArticles(array $apiArticles)
    {   
        $now = Carbon::now();
        $articles = [];

        foreach ($apiArticles as $apiArticle) {
            
            $articles[] = [
                'title' => $apiArticle['title'] ?? null,
                'content' => $apiArticle['content'] ?? null,
                'author' => $apiArticle['author'] ?? null,
                'source' => $apiArticle['source']['name'] ?? null,
                'category' => $apiArticle['category'] ?? null,
                'web_url' => $apiArticle['url'] ?? null,
                'published_at' => formatDate($apiArticle['publishedAt']),
                'created_at' => $now,
                'updated_at' => $now,
            ];

        }   

            $this->articleRepository->saveBulk($articles);
    }
    
}