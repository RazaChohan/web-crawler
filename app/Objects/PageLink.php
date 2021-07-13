<?php

namespace App\Objects;

class PageLink
{
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var array
     */
    public $linkedPages;

    /**
     * @var array
     */
    public $assets;

    /**
     * Sitemap constructor.
     */
    public function __construct()
    {
        $this->linkedPages = [];
        $this->assets = [];
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return array
     */
    public function getLinkedPages(): array
    {
        return $this->linkedPages;
    }

    /**
     * @param array $linkedPages
     *
     * @return $this
     */
    public function setLinkedPages(array $linkedPages): self
    {
        $this->linkedPages = $linkedPages;

        return $this;
    }

    /**
     * @return array
     */
    public function getAssets(): array
    {
        return $this->assets;
    }

    /**
     * @param array $assets
     *
     * @return $this
     */
    public function setAssets(array $assets): self
    {
        $this->assets = $assets;

        return $this;
    }
}
