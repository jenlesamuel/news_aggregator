<?php

namespace App\Providers;

use App\Repository\ArticleRepository;
use App\Services\GuardianScraper;
use App\Services\NewsApiScraper;
use App\Services\NewYorkTimesScraper;
use App\Services\ScrapingManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ScrapingManager::class, function() {
            $articleRepository = new ArticleRepository();

            return new ScrapingManager([
                new NewsApiScraper($articleRepository),
                new NewYorkTimesScraper($articleRepository),
                new GuardianScraper($articleRepository),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
