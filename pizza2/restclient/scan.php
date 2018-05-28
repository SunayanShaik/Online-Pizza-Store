<?php
// FYI: Sample program from Lockhart, "Modern PHP"
// Showing use of components Guzzle and CSV (comma-separated-value files) 
//   both of which are available in ../vendor
//   Note: we are only using Guzzle in proj2
// Run using command line: php scan.php urls.csv
//   using file urls.csv in this directory (URLs to test)
// 
// 1. User Composer autoloader
require '../vendor/autoload.php';

//2. Instantiate Guzzle HTTP client
$httpClient = new \GuzzleHttp\Client();

//3. Open and Iterate CSV
$csv = \League\Csv\Reader::createFromPath($argv[1]);
foreach ($csv as $csvRow) {
    try { 
        // 4. Send HTTP OPTIONS request (or GET)
        $httpResponse = $httpClient->get($csvRow[0]);
        // This shows the method itself throws on bad requests
        echo $csvRow[0] . ' ' . $httpResponse->getStatusCode() . PHP_EOL;      
        //5. Inspect HTTP response status code
        if ($httpResponse->getStatusCode() >= 400) {
            echo 'throwing...';
            throw new \Exception();
        }
    } catch (Exception $ex) {
        // 6. Send bad URLs to stdout:
        echo $csvRow[0] . ' is bad!' . PHP_EOL;
    }
}
