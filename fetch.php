<?php

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/lib/tiktok.api.class.php';

use GuzzleHttp\Client;

$client = new TikTok_API();
$download_link = $client->getMedia('7394835124669467909');

echo $download_link . PHP_EOL;
