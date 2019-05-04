<?php

use Spatie\Browsershot\Browsershot;

require dirname(__DIR__).'/vendor/autoload.php';

$pathToChrome = '/usr/bin/google-chrome-stable';
$pathToImage=__DIR__.'/example.png';

$url ='https://scratch-potherca.c9users.io/';
$url ='https://www.jebentzelfeen.website/';
$url .='foo';
$url .='?background-color=lime';

Browsershot::url($url)
    ->windowSize(500, 500)
    // ->setChromePath($pathToChrome)
    // ->fit(Manipulations::FIT_CONTAIN, 200, 200)
    ->save($pathToImage)
;

header('Content-Type: image/png');
readfile($pathToImage);
