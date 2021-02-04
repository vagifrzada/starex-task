<?php

namespace App\Tests\Unit;

use GuzzleHttp\Client;
use App\Service\Parser;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use App\Exception\ParserException;
use GuzzleHttp\Handler\MockHandler;

class ParserTest extends TestCase
{
    private string $content;

    protected function setUp(): void
    {
        $this->content = file_get_contents(__DIR__  . '/../stubs/content.html');
    }

    public function testShouldParseTwoProduct()
    {
        $parser = $this->getParser($this->content)->parse();
        $productList = $parser->getPriceList();
        $this->assertCount(2, $productList);
        $this->assertArrayHasKey('/basic-bisiklet-yaka-regular', $productList);
        $this->assertArrayHasKey('/nba-lisansli-oversize-fit', $productList);
    }

    public function testShouldThrowParseExceptionWhenProductListHtmlContentChanged()
    {
        $content = str_replace('product-list', 'something-different', $this->content);
        $parser = $this->getParser($content);
        $this->expectException(ParserException::class);
        $this->expectExceptionMessage("Html content of product list was changed.");
        $parser->parse();
    }

    public function testDoesProductContainsMarketPrice()
    {
        $parser = $this->getParser($this->content)->parse();
        $productList = $parser->getPriceList();
        $this->assertNull(
            $productList['/basic-bisiklet-yaka-regular']['prices']['market-price']
        );
        $this->assertEquals('33,99 TL',
            $productList['/nba-lisansli-oversize-fit']['prices']['market-price']
        );
    }

    private function getParser(string $response): Parser
    {
        return (new Parser(new Client(['handler' => HandlerStack::create(
            new MockHandler([new Response(200, [], $response)])
        )])));
    }
}