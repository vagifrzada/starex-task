<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use App\Exception\ParserException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    private const PARSE_URL = 'https://www.defacto.com.tr/erkek-t-shirt';
    private Client $client;
    private array $priceList = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws ParserException
     */
    public function parse(): self
    {
        $crawler = new Crawler($this->getHtmlContent());

        if (($products = $crawler->filter('.product-list .product-items'))->count() === 0)
            throw new ParserException("Html content of product list was changed.");

        $products->each(function (Crawler $node) {
            if (($productInfo = $node->filter('.product-info'))->count() === 0)
                throw new ParserException("Html content of product info was changed.");
            $this->fillPriceList($productInfo);
        });

        return $this;
    }

    /**
     * @throws ParserException
     */
    private function getHtmlContent(): string
    {
        try {
            $request = $this->client->request('GET', self::PARSE_URL);
            return $request->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new ParserException(sprintf('%s is invalid or unreachable.', self::PARSE_URL));
        }
    }

    private function fillPriceList(Crawler $productInfo): void
    {
        $link = $productInfo->filter('h3.product-info-title a');
        $salePrice = $productInfo->filter('.product-info-price .sale-price');
        $marketPrice = $productInfo->filter('.product-info-price .market-price');

        $this->priceList[$link->attr('href')] = [
            'title' => $link->text(),
            'prices' => [
                'sale-price' => ($salePrice->count() ? $salePrice->text() : null),
                'market-price' => ($marketPrice->count() ? $marketPrice->text() : null)
            ],
        ];
    }

    public function getPriceList(): array
    {
        return $this->priceList;
    }
}