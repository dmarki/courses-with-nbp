<?php

require '../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands

$application->run();

$nbp = 'https://www.nbp.pl/kursy/xml/dir.txt';

$rateFiles = explode("\n", file_get_contents($nbp));

$rateFiles = array_filter($rateFiles, function($hash) {
    return $hash[0] === 'a';
});

$rateFiles = array_slice($rateFiles, -7);


foreach ($rateFiles as $file) {
    $file = trim($file);
    $fileName = "http://www.nbp.pl/kursy/xml/{$file}.xml";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $fileName);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    $xmlstr = curl_exec($ch);
    curl_close($ch);

    $currencyArray[] = prepareCurrencyRates($xmlstr);
}


function prepareCurrencyRates($rates) {
    $new = simplexml_load_string($rates);

    // Convert into json
    $con = json_encode($new);

    // Convert into associative array
    $newArr = json_decode($con, true);

    return $newArr;
}
