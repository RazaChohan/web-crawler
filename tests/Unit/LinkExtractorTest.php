<?php

namespace Tests\Unit;

use App\Objects\SiteMap;
use App\Observers\CrawlObserver;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlSubdomains;
use Tests\TestCase;

class LinkExtractorTest extends TestCase
{
    /**
     * Constants
     */
    const DEPTH = 1;
    const URL = 'https://medium.com/inside-sumup';

    /**
     * @var SiteMap
     */
    public $siteMap;

    /**
     * Setup method
     */
    public function setUp(): void
    {
        $this->siteMap = new SiteMap();

        Crawler::create()
            ->setCrawlObserver(new CrawlObserver($this->siteMap))
            ->setCrawlProfile(new CrawlSubdomains(pathinfo(self::URL, PATHINFO_DIRNAME)))
            ->setMaximumDepth(self::DEPTH)
            ->setConcurrency(2)
            ->ignoreRobots()
            ->startCrawling(self::URL);
    }

    /**
     * test page links exists
     *
     * @return void
     */
    public function testPageLinksExists()
    {
        $this->assertNotEmpty($this->siteMap->getPageLinks());
    }

    /**
     * test assets are populated
     */
    public function testIfAssetsArePopulated()
    {
        $pageLink = !empty($this->siteMap->getPageLinks()) ? $this->siteMap->getPageLinks()[0] : null;
        $this->assertNotEmpty($pageLink->getAssets());
    }

    /**
     * Test if linked pages are populated
     */
    public function testIfLinkedPagesArePopulated()
    {
        $pageLink = !empty($this->siteMap->getPageLinks()) ? $this->siteMap->getPageLinks()[0] : null;
        $this->assertNotEmpty($pageLink->getLinkedPages());
    }
}
