<?php

namespace App\Console\Commands;

use App\Services\ScrapingManager;
use Illuminate\Console\Command;

class ScrapeNews extends Command
{   
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news from multiple endpoints and persist to database';

    /**
     * Execute the console command.
     */
    public function handle(ScrapingManager $scrapingManager)
    {
        $this->info('Scraping initiated');

        $scrapingManager->launchScrapers();

        $this->info('Scraping complete');
    }
}
