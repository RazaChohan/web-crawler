<?php

namespace App\Services;

use App\Objects\LinkInfo;
use DOMDocument;
use Psr\Http\Message\StreamInterface;

class LinksExtractorService
{
    /**
     * @param StreamInterface $requestBody
     *
     * @return array
     */
    public function extractAssetsAndLinkedPages(StreamInterface $requestBody): array
    {
        $htmlDom = new DOMDocument();
        @$htmlDom->loadHTML($requestBody);
        $linkedPages = $this->extractLinkedPages($htmlDom);
        $pageAssets = $this->extractAssets($htmlDom);

        return [$linkedPages, $pageAssets];
    }

    /**
     * @param DOMDocument $htmlDom
     *
     * @return array
     */
    private function extractAssets(DOMDocument $htmlDom) : array
    {
        $assets = [];
        $assets = $assets + $this->extraSpecificLinksFromDom($htmlDom, 'script', 'src');
        $assets = $assets + $this->extraSpecificLinksFromDom($htmlDom, 'img', 'src');
        $assets = $assets + $this->extraSpecificLinksFromDom($htmlDom, 'link', 'href');

        return $assets;
    }

    /**
     * @param DOMDocument $htmlDom
     *
     * @return array
     */
    private function extractLinkedPages(DOMDocument $htmlDom): array
    {
        return $this->extraSpecificLinksFromDom($htmlDom, 'a', 'href');
    }

    /**
     * @param DOMDocument $htmlDom
     * @param string $tagName
     * @param string $attributeName
     *
     * @return array
     */
    private function extraSpecificLinksFromDom(DOMDocument $htmlDom, string $tagName, string $attributeName): array
    {
        $extractedLinks = [];
        $links = $htmlDom->getElementsByTagName($tagName);
        foreach ($links as $link) {
            $linkText = $link->nodeValue;
            $linkHref = $link->getAttribute($attributeName);

            if (empty(trim($linkHref)) || $linkHref == '#') {
                continue;
            }

            $extractedLinks[] = (new LinkInfo())
                ->setLink($linkHref)
                ->setText($linkText);
        }

        return $extractedLinks;
    }
}
