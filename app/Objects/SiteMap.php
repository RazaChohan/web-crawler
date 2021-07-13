<?php

namespace App\Objects;

class SiteMap
{
    /**
     * @var PageLink[]
     */
    public $pageLinks;

    /**
     * @return PageLink[]
     */
    public function getPageLinks(): array
    {
        return $this->pageLinks;
    }

    /**
     * @param PageLink[] $pageLinks
     *
     * @return SiteMap
     */
    public function setPageLinks(array $pageLinks): self
    {
        $this->pageLinks = $pageLinks;

        return $this;
    }

    /**
     * @param PageLink $pageLink
     */
    public function addPageLinks(PageLink $pageLink)
    {
        $this->pageLinks[] = $pageLink;
    }
}
