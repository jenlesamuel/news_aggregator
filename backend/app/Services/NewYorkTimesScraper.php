<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NewYorkTimesScraper
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function scrape()
    {
        try {
            // Get current date and page from cache
            $currentDate = Cache::get('nyt_current_date', Carbon::now()->toDateString());
            $currentPage = Cache::get('nyt_current_page', 0);

            $response = Http::get(env('NYT_API_BASE_URL'), [
                'api-key' => env('NYT_API_KEY'),
                'page' => $currentPage,
                'begin_date' => Carbon::parse($currentDate)->format('Ymd'),
                'end_date' => Carbon::parse($currentDate)->format('Ymd'),
            ]);

            if ($response->failed()) {
                throw new \Exception(
                    "Failed to fetch data from The New York Times API: {$response->json()['message']}"
                );
            }
            
            $data = $response->json();
            $this->saveArticles($data['response']['docs']);

            // Check if there are more pages to fetch
            // A maximum of 100 pages can be paged through using this endpoint
            $totalPages = min(100, ceil($data['response']['meta']['hits'] / 10));

            // Determine whether to fetch the next page or move to the previous date
            if ($currentPage < $totalPages) {
                Cache::put('nyt_current_page', $currentPage + 1);
            } else {
                // If all pages for the current date are fetched, move to the previous date
                $previousDate = Carbon::parse($currentDate)->subDay()->toDateString();
                Cache::put('nyt_current_date', $previousDate);
                Cache::put('nyt_current_page', 1); // Reset page to 1 for the new date
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
            $firstname = $apiArticle['byline']['person'][0]['firstname'] ?? '';
            $lastname = $apiArticle['byline']['person'][0]['lastname'] ?? '';
            $author = $firstname == '' && $lastname == '' ? null : "{$firstname} {$lastname}";

            $articles[] = [
                'title' => $apiArticle['headline']['main'] ?? null,
                'content' => $apiArticle['lead_paragraph'] ?? null,
                'web_url' => $apiArticle['web_url'] ?? null,
                'author' => $author,
                'source' => $apiArticle['source'] ?? null,
                'category' => $apiArticle['section_name'] ?? null,
                'published_at' => formatDate($apiArticle['pub_date']),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->articleRepository->saveBulk($articles);
    }
}