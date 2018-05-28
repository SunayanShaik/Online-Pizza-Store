<?php

// Use $supply vs. $undelivered_orders to find newly delivered orders
// Credit their new supplies to inventory and delete such orders from 
// the undelivered_orders table
// $supply: set of orders from server
function record_deliveries($db, $supply, $undelivered_orders) {
    $delivered_orders = array();  // build set of delivered orders
    for ($i = 0; $i < count($supply); $i++) {
        $orderid = $supply[$i]['orderID'];
        $delivered = $supply[$i]['delivered'];
        if ($delivered) {
            $delivered_orders[$orderid] = $supply[$i];  // remember order by id
        }
    }
    error_log('supply: ' . print_r($supply, true));
    error_log('delivered: ' . print_r($delivered_orders, true));
    // match delivered supply order with previously undelivered order
    for ($j = 0; $j < count($undelivered_orders); $j++) {
        $orderID = $undelivered_orders[$j]['orderID'];
        error_log('undel order ' . print_r($undelivered_orders[$j], true));
        if (array_key_exists($orderID, $delivered_orders)) {
            error_log("found delivered order $orderID");
            delete_from_uo($db, $orderID);
            $order = $delivered_orders[$orderID];
            replenish_flour_inventory($db, $order['items'][0]['quantity']);
            replenish_cheese_inventory($db, $order['items'][1]['quantity']);
            break;
        }
    }
}

// Check $inventory, and order enough to aim for 150 units of both flour and cheese 
function order_supplies($db, $httpClient, $inventory, $base_url) {
    if ($inventory[0]['quantity'] < 150) {
        $flour_required_qty = 150 - $inventory[0]['quantity'];
        $flour_unit_bags = 1;
        $flour_order_qty = 0;
        // this can be done by division...but this is fine
        // $flour_unit_bags = int_div($flour_required_qty -1, 40) + 1; 
        while ($flour_order_qty < $flour_required_qty) {
            $flour_order_qty = 40 * $flour_unit_bags;
            $flour_unit_bags += 1;
        }
    } else {
        $flour_order_qty = 0;
    }

    if ($inventory[1]['quantity'] < 150) {
        $cheese_required_qty = 150 - $inventory[1]['quantity'];
        $cheese_unit_containers = 1;
        $cheese_order_qty = 0;
        // this can be done by division...
        while ($cheese_order_qty < $cheese_required_qty) {
            $cheese_order_qty = 20 * $cheese_unit_containers;
            $cheese_unit_containers += 1;
        }
    } else {
        $cheese_order_qty = 0;
    }

    if (($flour_order_qty > 0) || ($cheese_order_qty > 0)) {

        $item1 = array('productID' => 11, 'quantity' => $flour_order_qty);
        $item2 = array('productID' => 12, 'quantity' => $cheese_order_qty);
        $order = array('customerID' => 1, 'items' => array($item1, $item2));
        error_log('posting supply order ');

        $location = post_order($httpClient, $base_url, $order);

        error_log("get back location = $location");
        $parts = explode('/', $location);
        $order_id = $parts[count($parts) - 1];  // last part
        error_log("new supply order id $order_id");
        // add to undelivered orders table
        insert_to_uo($db, $order_id, $flour_order_qty, $cheese_order_qty);
    }
}
