<?php 
$request_uri = $_SERVER['REQUEST_URI'];
$doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
$dirs = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($dirs); // remove last element
$project_root = implode('/', $dirs) . '/';

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '0'); // would mess up web service 
//response
ini_set('log_errors', 1);
// the following file needs to exist, be accessible to apache
// and writable (chmod 777 php-server-errors.log)
// Use an absolute file path to create just one log for the web app
ini_set('error_log', $project_root . 'php-server-errors.log');
set_include_path($project_root);
// app_path is the part of $project_root past $doc_root
$app_path = substr($project_root, strlen($doc_root));
error_log('app_path = '. $app_path);
// project uri is the part of $request_uri past $app_path, less '/'
$project_uri = substr($request_uri, strlen($app_path)-1);
error_log('project uri = '. $project_uri);
$parts = explode('/', $project_uri);
//like  /rest/product/1 ;
//    0    1     2    3    

// Get needed code
require_once('model/database.php');
require_once('model/product_db.php');
require_once('model/order_db.php');
require_once('model/day.php');

$server = $_SERVER['HTTP_HOST'];
$method = $_SERVER['REQUEST_METHOD'];
$proto = isset($_SERVER['HTTPS'])? 'https:':'http:';
$url  = $proto . '//' . $server . $request_uri;
$resource = trim($parts[2]);
if (isset($parts[3])) {
   $id = $parts[3];
}
error_log('starting REST server request, resource = '.$parts[2]. ' method='.$method);

switch ($resource) {
    // Access the specified product
        case 'products':
        error_log('request at case product');
        switch ($method) {
            case 'GET':
                handle_get_product($id);
                break;
            case 'POST':
                handle_post_product($url);
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
        case 'day': 
        error_log('request at case day');
        switch ($method) {
            case 'GET':
                // get current day from DB and return it
                handle_get_day($db);
                break;
            case 'POST':
                // sets new day in DB
                handle_post_day($db);
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
        case 'orders':
        error_log('request at case orders');
        switch ($method) {
            case 'GET':
                // get information, including status of a supply order (i.e., delivered or not)
                if (!empty($id)) {
                  handle_get_order($db, $id);
                } else {
                  handle_get_orders($db);
                }
                break;
            case 'POST':
                // creates a new supply order (flour, cheese), returns new URI
                handle_post_order($db, $url);        
                break;
            default:
                $error_message = 'bad HTTP method : ' . $method;
                include_once('errors/server_error.php');
                server_error(405, $error_message);
                break;
        }
        break;
        default:
            $error_message = 'Unknown REST resource: ' . $resource;
            include_once('errors/server_error.php');
            server_error(400, $error_message);
        break;
}

function handle_get_product($product_id) {
    $product = get_product($product_id);
    $data = json_encode($product);
    error_log('hi from handle_get_product');
    echo $data;
}


function handle_post_product($url) {
    $bodyJson = file_get_contents('php://input');
    error_log('Server saw post data' . $bodyJson);
    $body = json_decode($bodyJson, true);
    try {
        $product_id = add_product($body['categoryID'], $body['productCode'], $body['productName'], $body['description'], $body['listPrice'], $body['discountPercent']);
        // return new URI in Location header
        $locHeader = 'Location: ' . $url . $product_id;
        header('Content-type: application/json');
        header($locHeader, true, 201);
        error_log('hi from handle_post_product, header = ' . $locHeader);
    } catch (PDOException $e) {
        $error_message = 'Insert failed: ' . $e->getMessage();
        include_once('errors/server_error.php');
        server_error(400, $error_message);
    }
}

function handle_get_day($db) {
    $day = get_system_day($db);
    error_log('rest server in handle_get_day, day = '. $day);
    echo $day;
}

function handle_post_day($db) {
    error_log('rest server in handle_post_day');
    $day = file_get_contents('php://input');  // just a digit string
    error_log('Server saw POSTed day = ' . $day);
    //if $day = 0 then reinitialize the orders
    if ($day === '0')
    {
        //delete the orders and orderitems
        //set the autoincrement value of table orders back to 0 
        //so that the first order id will be 1
        //set daynumber to 1
        reinitialize_orders($db);
        update_system_day($db, 1);
    }
    else 
        update_system_day($db, $day);
}

// get full information, including status of a supply order (i.e., delivered or not)
function handle_get_order($db, $order_id)
{
    $order = get_order_data($db, $order_id);
    $data = json_encode($order);
    error_log('data after getting order: '. print_r($data,true) . 'empty '. print_r(empty($data), true));
    if ($data!=null&&is_string($data)&&$data != 'null') {  // json_decode can return 'null'
        echo $data;
    }
    else
    {
        include_once('errors/server_error.php');
        $error = 'no such order: '. $order_id;
        server_error(404, $error);
    } 
}

function handle_get_orders($db)
 {
    $orders = get_orders();
    $result = array();
    foreach ($orders as $order) {
        $order_id = $order['orderID'];
        error_log("get_orders: see orderid ". $order_id);
        $out_order = get_order_data($db, $order_id);
        if ($order != NULL) {
            $result[] = $out_order;
        } else {
            // this is an internal error, just report to log
            error_log("bad order id found in handle_get_orders");
        }
    }
    $data = json_encode($result);
    echo $data;
}
// get the specified order info for return to client
function get_order_data($db, $order_id) {
    error_log('in get_order_data for id '. $order_id);
    $order = get_order_details($db, $order_id);
    $current_day = get_system_day($db);      
    $out_order = array();
    //show delivery status as "true" or "false"
    if ($order != NULL)
    {
        $out_order['orderID'] = $order['orderID'];
        $out_order['customerID'] = $order['customerID'];
        error_log('order: '. print_r($order, true));
        if ($order['delivered'] <= $current_day)
        {
            $out_order['delivered'] = "true";
        }
        else if ($order['delivered'] > $current_day) 
        {
            $out_order['delivered'] = "false";
        }
        $out_order['items'] = array();
        foreach ($order['items'] as $item) {
            $out_item = array();
            $out_item['productID'] = $item['productID'];
            $out_item['quantity'] = $item['quantity'];
            $out_order['items'][] = $out_item;
        }
        return $out_order;
    }
    else return null;
 }
function handle_post_order($db, $url)
{
    // creates a new supply order (flour, cheese), returns new URI
    $bodyJson = file_get_contents('php://input');
    error_log( 'Server saw post data' . $bodyJson);
    $body = json_decode($bodyJson, true);
    try {
        $order_date = date("Y-m-d H:i:s");
        $shipAmount = 5.00;
        $taxAmount = 0.00;
        $shipAddressID = 7;
        $cardType = 2;
        $cardNumber = '4111111111111111';
        $cardExpires = '08/2016';
        $billingAddressID = 7;
        
        $currentDay = get_system_day($db);
        error_log('$currentDay = ' . $currentDay);
        $deliveryDay = rand($currentDay + 1, $currentDay+ 2);
        
        error_log('flourID = ' . $body['items'][0]['productID']);
        error_log('cheeseID = ' . $body['items'][1]['productID']);
        error_log('quantity of flour = ' . $body['items'][0]['quantity']);
        error_log('quantity of cheese = ' . $body['items'][1]['quantity']);   
        error_log('delivery day '. $deliveryDay);
        
        $itemPrice = 10.00;
        $discountAmount = 0.00;
        
        $orderID = add_order($body['customerID'], $order_date, $deliveryDay);
        add_order_item($orderID, $body['items'][0]['productID'], $itemPrice, $discountAmount, $body['items'][0]['quantity']);
        add_order_item($orderID, $body['items'][1]['productID'], $itemPrice, $discountAmount, $body['items'][1]['quantity']);
        
        // return new URI in Location header
        $locHeader = 'Location: ' . $url . $orderID;
        header('Content-type: application/json');
        header($locHeader, true, 201);
        error_log(' handle_post_orders, header = ' . $locHeader);
    } catch (PDOException $e) {
        $error_message = 'Insert failed: ' . $e->getMessage();
        include_once('errors/server_error.php');
        server_error(400, $error_message);
    }
}

?>