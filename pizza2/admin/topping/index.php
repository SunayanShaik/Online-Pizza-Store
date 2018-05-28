<?php

require('../../util/main.php');
require('../../model/database.php');
require('../../model/topping_db.php');

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'list_toppings';
    }
}
if ($action == 'list_toppings') {
    try {
        $toppings = get_toppings($db);
        include('topping_list.php');
    } catch (Exception $e) {
        include('../../errors/error.php');
        exit();
    }
} else if ($action == 'delete_topping') {
    $topping_id = filter_input(INPUT_POST, 'topping_id', FILTER_VALIDATE_INT);
    if ($topping_id == NULL || $topping_id == FALSE) {
        $e = "Missing or incorrect topping.";
        include('../../errors/error.php');
    } else {
        try {
            delete_topping($db, $topping_id);
        } catch (Exception $e) {
            include('../../errors/error.php');
            exit();
        }
        header("Location: .");
    }
} else if ($action == 'show_add_form') {
    include('topping_add.php');
} else if ($action == 'add_topping') {
    $topping_name = filter_input(INPUT_POST, 'topping_name');
    if ($topping_name == NULL || $topping_name == FALSE) {
        $e = "Invalid topping name. Check all fields and try again.";
        include('../../errors/error.php');
        exit();
    } else {
        try {
            add_topping($db, $topping_name);
        } catch (Exception $e) {
            include('../../errors/error.php');
            exit();
        }
        header("Location: .");
    }
} else {
    error_log('in topping controller, bad action: ' . $action);
}
?>