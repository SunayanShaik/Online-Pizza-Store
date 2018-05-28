<?php

function decrease_inventory($db)
{
    $query = 'UPDATE inventory SET quantity = quantity-1';
    $statement = $db->prepare($query);
    $statement->execute();
    $statement->closeCursor();
}

function get_inventory_details($db)
{
    $query = 'SELECT * FROM inventory';
    $statement = $db->prepare($query);
    $statement->execute();    
    $inventory = $statement->fetchAll();
    $statement->closeCursor();
    return $inventory;
}
function get_undelivered_orders($db) {
    $query = 'SELECT * FROM undelivered_orders';
    $statement = $db->prepare($query);    
    $statement->execute();
    $undelivered_orders = $statement->fetchAll();
    $statement->closeCursor();   
    return $undelivered_orders;
}

function get_inventory($db) {
    $query = 'SELECT * FROM inventory';
    $statement = $db->prepare($query);    
    $statement->execute();
    $inventory = $statement->fetchAll();
    $statement->closeCursor();   
    return $inventory;
}
function get_start_inventory($db) {
    $query = 'SELECT * FROM start_inventory';
    $statement = $db->prepare($query);    
    $statement->execute();
    $inventory = $statement->fetchAll();
    $statement->closeCursor();   
    return $inventory;
}

function save_inventory($db) {
    $query = 'delete from start_inventory;'
            . 'insert into start_inventory SELECT * FROM inventory';
    $statement = $db->prepare($query);    
    $statement->execute();
    $statement->closeCursor();   
}
function update_inventory($db, $n){
    $query = 'UPDATE inventory SET quantity= quantity -:n';    
    $statement = $db->prepare($query);
    $statement->bindValue(':n', $n);
    $statement->execute();    
    $statement->closeCursor();    
}

function delete_from_uo($db, $orderID) {    
    $query = 'DELETE FROM undelivered_orders WHERE orderID = :order_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $orderID);
    $statement->execute();
    $statement->closeCursor();
}

function insert_to_uo($db, $orderID, $flour_qty, $cheese_qty) {
   // echo "in insert_to_uo: $orderID, $flour_qty, $cheese_qty";
    $query = 'INSERT INTO undelivered_orders VALUES (:order_id, :flour, :cheese)';
    $statement = $db->prepare($query);
    $statement->bindValue(':order_id', $orderID);   
    $statement->bindValue(':flour', $flour_qty);  
    $statement->bindValue(':cheese', $cheese_qty);  
    $statement->execute();    
    $statement->closeCursor();
}

function replenish_flour_inventory($db, $n) {    
    $query = 'UPDATE inventory SET quantity= quantity +:n where productName=:productName';    
    $statement = $db->prepare($query);
    $statement->bindValue(':n', $n);
    $statement->bindValue(':productName', 'flour');
    $statement->execute();    
    $statement->closeCursor();
    
}

function replenish_cheese_inventory($db, $n) {    
    $query = 'UPDATE inventory SET quantity= quantity +:n where productName=:productName';    
    $statement = $db->prepare($query);
    $statement->bindValue(':n', $n);
    $statement->bindValue(':productName', 'cheese');
    $statement->execute();    
    $statement->closeCursor();
}

