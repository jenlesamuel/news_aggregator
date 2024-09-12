<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GuardianScraper
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
            $currentDate = Cache::get('guardian_current_date', Carbon::now()->toDateString());
            $currentPage = Cache::get('guardian_current_page', 1);

            $response = Http::get(env('GUARDIAN_API_BASE_URL'), [
                'from-date' => $currentDate,
                'to-date' => $currentDate,
                'page' => $currentPage,
                'api-key' => env('GUARDIAN_API_KEY'),
            ]);

            if ($response->failed()) {
                throw new \Exception(
                    "Failed to fetch data from The Guardian API: {$response->json()['message']}"
                );
            }

            $responseData = $response->json()['response'];
            $apiArticles = $responseData['results'];
            $totalPages = $responseData['pages'];

            $this->saveArticles($apiArticles);

            // Determine whether to fetch the next page or move to the previous date
            if ($currentPage < $totalPages) {
                Cache::put('guardian_current_page', $currentPage + 1);
            } else {
                // If all pages for the current date are fetched, move to the previous date
                $previousDate = Carbon::parse($currentDate)->subDay()->toDateString();
                Cache::put('guardian_current_date', $previousDate);
                Cache::put('guardian_current_page', 1); // Reset page to 1 for the new date
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
                'title' => $apiArticle['webTitle'] ?? null,
                'content' => null,
                'author' => null,
                'source' => 'Guardian News',
                'category' => $apiArticle['sectionName'] ?? null,
                'web_url' => $apiArticle['webUrl'] ?? null,
                'published_at' => formatDate($apiArticle['webPublicationDate']),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->articleRepository->saveBulk($articles);
    }
}