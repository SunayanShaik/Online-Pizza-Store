<?php

require('../../util/main.php');
require('../../model/database.php');
require('../../model/day_db.php');
require('../../model/order_db.php');
require('../../model/inventory_db.php');
require('../../model/initial.php');
require('web_services.php');
require('day_helpers.php');
require '../../vendor/autoload.php';

$httpClient = new \GuzzleHttp\Client();
$spot = strpos($app_path, 'pizza2');
$part = substr($app_path, 0, $spot);
$base_url = $_SERVER['SERVER_NAME'] . $part . 'proj2_server/rest';

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list';
    }
}
$current_day = get_current_day($db);
if ($action == 'initial_db') {
    try {
        initial_db($db);
        post_day($httpClient, $base_url, 0);
        $inventory = get_inventory($db);
        error_log('inventory; '. print_r($inventory, true));
        order_supplies($db, $httpClient, $inventory, $base_url);
        header("Location: .");
    } catch (Exception $e) {
        include ('../errors/error.php');
        exit();
    }
} else if ($action == 'list') {
    try {
        $todays_orders = get_todays_orders($db, $current_day);
        $supply = get_supply_orders($httpClient, $base_url);
        $undelivered_orders = get_undelivered_orders($db);
        $inventory = get_inventory($db);
    }  catch (Exception $e) {
        include('../../errors/error.php');
        exit();
    }
    include('day_list.php');
} else if ($action == 'next_day') {
    try {  
        finish_orders_for_day($db, $current_day);
        increment_day($db);
        $current_day = $current_day + 1;
  
        post_day($httpClient, $base_url, $current_day);
        // credit now-delivered orders
        $undelivered_orders = get_undelivered_orders($db);
        $supply = get_supply_orders($httpClient,$base_url);
        record_deliveries($db, $supply, $undelivered_orders);
        
        // Using updated inventory, figure out what to order, if anything
        $inventory = get_inventory($db);
        error_log('inventory; '. print_r($inventory, true));
        order_supplies($db, $httpClient, $inventory, $base_url);
        
        // for display--day_list forwarded from here, replacing old redirect
        $undelivered_orders = get_undelivered_orders($db); // for display
        // temp: get latest supply for display (not reqd)
        $supply = get_supply_orders($httpClient,$base_url); 
        $todays_orders = get_todays_orders($db, $current_day);
        include('day_list.php');
    } catch (Exception $e) {
        include('../../errors/error.php');
    }
}
?>