<?php

namespace App\Console\Commands;

use App\Objects\LinkInfo;
use App\Objects\SiteMap;
use App\Observers\CrawlObserver;
use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlSubdomains;

class CrawlerCommand extends Command
{
    const URL_ARGUMENT = 'url';
    const DEPTH_OPTION = 'depth';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:crawl {' . self::URL_ARGUMENT . '} {--' . self::DEPTH_OPTION . '=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls pages of website to specific depth and prints the site map';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $siteMap = new SiteMap();
        $depthLimit = $this->option(self::DEPTH_OPTION);
        $url = $this->argument(self::URL_ARGUMENT);
        $this->info('Crawling link started....');
        Crawler::create()
            ->setCrawlObserver(new CrawlObserver($siteMap))
            ->setCrawlProfile(new CrawlSubdomains(pathinfo($url, PATHINFO_DIRNAME)))
            ->setMaximumDepth($depthLimit)
            ->ignoreRobots()
            ->startCrawling($url);

        $this->outputSiteMap($siteMap);
    }

    /**
     * @param SiteMap $siteMap
     */
    private function outputSiteMap(SiteMap $siteMap)
    {
        foreach($siteMap->getPageLinks() as $pageLinks) {
            $this->info("\nPage: " . $pageLinks->getBaseUrl());
            $this->info("\n........................ Linked pages ........................\n");
            /**
             * @var $pageLink LinkInfo
             */
            foreach ($pageLinks->getLinkedPages() as $pageLink) {
                $this->info(' --> ' . $pageLink->getText() . ' -- ' . $pageLink->getLink());
            }
            $this->info("\n........................ Page assets ........................\n");
            /**
             * @var $asset LinkInfo
             */
            foreach ($pageLinks->getAssets() as $asset) {
                $this->info(' --> ' . $asset->getLink());
            }
        }
    }
}
