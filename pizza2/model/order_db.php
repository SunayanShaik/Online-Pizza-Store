<?php
function get_preparing_orders($db) {
    $query = 'SELECT * FROM pizza_orders where status=\'Preparing\'';
    $statement = $db->prepare($query);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor();
    return $orders;  
}
function get_baked_orders($db) {
    $query = 'SELECT * FROM pizza_orders where status=\'Baked\'';
    $statement = $db->prepare($query);
    $statement->execute(); 
    $orders = $statement->fetchAll();
    $statement->closeCursor();    
    return $orders;  
}
function get_preparing_orders_of_room($db, $room) {
    $query = 'SELECT * FROM pizza_orders where status=\'Preparing\' and room_number=:room';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor(); 
    $orders = add_toppings_to_orders($db, $orders);
    return $orders;    
}

function get_baked_orders_of_room($db, $room) {
    $query = 'SELECT * FROM pizza_orders where status=\'Baked\' and room_number=:room';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->execute();
    $orders = $statement->fetchAll();
    $statement->closeCursor(); 
    $orders1 = add_toppings_to_orders($db, $orders);     
    return $orders1;    
}
// helper to above two functions
function add_toppings_to_orders($db, $orders) {
      for ($i=0; $i<count($orders);$i++) {
        $toppings = get_orders_toppings($db, $orders[$i]['id']);
        $orders[$i]['toppings'] = $toppings; // add toppings to order 
    } 
    return $orders;
}
// helper to above function
function get_orders_toppings($db, $order_id) {
    $query = 'select topping from order_topping '
            . 'where order_id=:order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id',$order_id);
    $statement->execute();
    $toppings = $statement->fetchAll();
    $statement->closeCursor();
    return $toppings;
}
function change_to_baked($db, $id) {
    $query = 'UPDATE pizza_orders SET status=\'Baked\' WHERE status=\'Preparing\' and id=:id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $statement->closeCursor();     
}

function get_oldest_preparing_id($db) {
    $query = 'SELECT min(id) id FROM pizza_orders where status=\'Preparing\'';
    $statement = $db->prepare($query);
    $statement->execute();
    $id = $statement->fetch()['id'];
    $statement->closeCursor();
    return $id;     
}

function update_to_finished($db, $room) {
    $query = 'UPDATE pizza_orders SET status=3 WHERE status = \'Baked\' and room_number = :room';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->execute();
    $statement->closeCursor();        
}

function add_order($db, $room,$size,$current_day,$status, $topping_ids) {
    $query = 'INSERT INTO pizza_orders(room_number, size, day, status) '
            . 'VALUES (:room,(select size_name from sizes where id = :size),:current_day,:status)';
    $statement = $db->prepare($query);
    $statement->bindValue(':room',$room);
    $statement->bindValue(':size',$size);
    $statement->bindValue(':current_day',$current_day);
    $statement->bindValue(':status',$status);
    $statement->execute();
    $statement->closeCursor(); 
    foreach ($topping_ids as $t) {
        add_order_topping($db, $t);
    }
}
// helper to add_order: uses last_insert_id() to pick up auto_increment value
function add_order_topping($db, $topping_id) {
    $topping_name = get_topping_name($db, $topping_id);
    $query = 'INSERT INTO order_topping(order_id, topping) '
            . 'VALUES (last_insert_id(),:topping_name)';
    $statement = $db->prepare($query);
    $statement->bindValue(':topping_name', $topping_name);
    $statement->execute();
    $statement->closeCursor();
}
