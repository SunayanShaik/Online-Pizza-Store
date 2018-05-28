<?php

function initial_db($db) {
    $query = 'delete from order_topping;';
    $query.='delete from pizza_orders;';
    $query.='delete from sizes;';
    $query.='delete from toppings;';
    $query.='delete from pizza_sys_tab;';
    $query.='insert into pizza_sys_tab values (1);';
    $query.="insert into toppings values (1,'Pepperoni');";
    $query.="insert into sizes values (1,'small');";
    $query.='delete from undelivered_orders;';
    $query.='delete from inventory;';
    $query.="insert into inventory values (11,'flour', 100);";
    $query.="insert into inventory values (12,'cheese', 100);";

    $statement = $db->prepare($query);
    $statement->execute();

    return $statement;
}
