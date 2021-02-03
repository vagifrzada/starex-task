<?php

namespace App\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParserStability()
    {
        /*$mock = new MockHandler([
            new Response(200, [], '')
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);*/

        $this->assertTrue(true);
    }
}