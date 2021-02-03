<?php

use GuzzleHttp\Client;
use App\Service\Parser;

require __DIR__ . '/vendor/autoload.php';

try {
    $parser = new Parser(new Client());
    dd($parser->parse());
} catch (Throwable $e) {
    // Log the exception.
    dd($e->getMessage());
}