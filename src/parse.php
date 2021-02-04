<?php

use GuzzleHttp\Client;
use App\Service\Parser;

require __DIR__ . '/vendor/autoload.php';

try {
    $parser = (new Parser(new Client()))->parse();
    dd($parser->getPriceList());
} catch (Throwable $e) {
    // Log the exception.
    dd($e->getMessage());
}