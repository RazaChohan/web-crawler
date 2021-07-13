<?php

namespace App\Observers;

use App\Objects\PageLink;
use App\Objects\Sitemap;
use App\Services\LinksExtractorService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class CrawlObserver extends \Spatie\Crawler\CrawlObservers\CrawlObserver
{
    /**
     * @var LinksExtractorService
     */
    public $LinksExtractor;

    /**
     * @var PageLink
     */
    public $pageLinks;

    /**
     * @var array
     */
    public $siteMap;

    /**
     * CrawlObserver constructor.
     * @param SiteMap $siteMap
     */
    public function __construct(SiteMap $siteMap)
    {
        $this->siteMap = $siteMap;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     */
    public function willCrawl(UriInterface $url): void
    {
        $this->pageLinks = (new PageLink())->setBaseUrl($url);
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
     public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ): void {
         $linksExtractor = new LinksExtractorService();
         list($linkedPages, $pageAssets) = $linksExtractor->extractAssetsAndLinkedPages($response->getBody());
         $this->pageLinks->setLinkedPages($linkedPages);
         $this->pageLinks->setAssets($pageAssets);
         $this->siteMap->addPageLinks($this->pageLinks);
     }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawlFailed(
        UriInterface $url,
        RequestException $requestException,
        ?UriInterface $foundOnUrl = null
    ): void {
         Log::error($requestException->getMessage());
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
        $this->pageLinks = null;
        $this->siteMap = null;
        $this->LinksExtractor = null;
    }
}
