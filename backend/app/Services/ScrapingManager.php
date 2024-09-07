<?php

namespace App\Services;

class ScrapingManager 
{

    private $scrapers = [];

    public function __construct(array $scrapers) 
    {
        $this->scrapers = $scrapers;
    }

    public function launchScrapers() 
    {
        foreach ($this->scrapers as $scraper) {
            $scraper->scrape();
        }
    }
}