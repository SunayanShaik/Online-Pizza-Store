<?php
// Client-side REST, for pizza2 project
require_once('../util/main.php');
// use Composer autoloader, so we don't have to require Guzzle PHP files
require '../vendor/autoload.php';

// Now have $app_path, path from doc root to parent directory
// $app_path is of form /cs637/user/proj2/pizza2
// We want URL say http://topcat.cs.umb.edu/cs637/user/proj2/proj2_server/rest for REST service
// So drop "pizza2" from $app_path, add /proj2_server/rest
echo 'app_path: ' . $app_path . '<br>';
$spot = strpos($app_path, 'pizza2');
$part = substr($app_path, 0, $spot);
$base_url = $_SERVER['SERVER_NAME'] . $part . 'proj2_server/rest';
echo 'base_url: ' . $base_url . '<br>';

// Instantiate Guzzle HTTP client
//$httpClient = new \GuzzleHttp\Client(['ssl.certificate_authority'=>'c:\cs\cs637\googleapi.crt']);
$httpClient = new \GuzzleHttp\Client();
$url = 'http://' . $base_url . '/day/';
echo 'POST day = 3 to ' . $url . '<br>';
$fp = fopen('php://temp', 'r+');
error_log('...... restclient: POST day = 3 to ' . $url);
try {
    $response = $httpClient->request('POST', $url, ['json' => 3]);
    $status = $response->getStatusCode();
} catch (Exception $e) {
    $status = 'POST failed, error = ' . $e . ' at line ' . __LINE__ . 
            ' of ' . __FILE__;
    error_log($status);
    include '../errors/error.php';  // Note new error.php code that handles Exceptions
}
?>
Post of day result: <?php echo $status ?> <br>
<?php
echo 'GET of day to ' . $url . '<br>';
error_log('...... restclient: GET day');
try {
    $response = $httpClient->get($url);
    echo '<br>Back from GET: day= ' . $response->getBody() . ' (wrong until server coded right)';
} catch (Exception $e) {
    include '../errors/error.php'; 
}
$product_id = 1;
$url = 'http://' . $base_url . '/products/' . $product_id;
echo '<br>GET of product 1 to ' . $url . '<br>';
error_log('...... restclient: GET product');
try {
    $response = $httpClient->get($url);
    $prodJson = $response->getBody()->getContents();  // as StreamInterface, then string
    echo 'Returned result of GET of product 1: <br>';
    print_r($prodJson);
    echo '<br> After json_decode:<br>';
    $product = json_decode($prodJson, true);
    print_r($product);
} catch (Exception $e) {
    include '../errors/error.php'; 
}
?>

<p> Now POST it back, but change productCode to avoid uniqueness constraint </p>
<?php
$product_id = 177;
$url = 'http://' . $base_url . '/products/';
$product['productID'] = $product_id;
$product['productCode'] = 'strat02';  // works only once per each value

error_log('...... restclient: POST product');
try {
    // Guzzle does the json_encode for us--
    $response2 = $httpClient->request('POST', $url, ['json' => $product]);
    $location2 = $response2->getHeader('Location');
    $status2 = $response2->getStatusCode();
} catch (Exception $e) {
  include '../errors/error.php'; 
}

if (isset($status2)) {
    echo "POST of product result: status = $status2 <br>";
    echo "Location = ";
    echo var_dump($location2) . '<br>';
}
?>
<!-- Location: array(1) { [0]=> string(65)
  "http://localhost/cs637/eoneil/proj2/proj2_server/rest/products/30" } -->
<br> If error is 400 Bad Request, it is probably because of constraint violation 
<br> on the unique column productCode preventing insert on the server side
<br> If so, you can fix it by changing productCode to a new value in the 
restclient/index.php code
<?php
// Added to show how to use https: with Guzzle
$url = 'https://www.googleapis.com/youtube/v3/search/?part=snippet&key=AIzaSyBQO-iohmqMxcUz5VniB0YbgxfNH3adlVU&q=space';
error_log('...... restclient: GET to youtube');
try {
    $response = $httpClient->get($url,['verify'=>'c:\cs\cs637\ca-bundle.crt']);
    $respJson = $response->getBody()->getContents();  // as StreamInterface, then string
    echo 'Returned result of GET to youtube';
    print_r($respJson);
    echo '<br> After json_decode:<br>';
    $respAA = json_decode($respJson, true);
    print_r($respAA);
} catch (Exception $e) {
    include '../errors/error.php'; 
}
?>
