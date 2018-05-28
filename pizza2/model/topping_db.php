<?php
// topping_db.php: DB access for topping data
// the try/catch for these actions is in the caller, index.php
function add_topping($db, $topping_name)  
{
    $query = 'INSERT INTO toppings
                 (topping_name)
              VALUES
                 (:topping_name)';
    $statement = $db->prepare($query);
    $statement->bindValue(':topping_name', $topping_name);
    $statement->execute();
    $statement->closeCursor();
}

function delete_topping($db, $topping_id)  
{
    $query = 'DELETE FROM toppings
                 WHERE id = :topping_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':topping_id', $topping_id);
    $statement->execute();
    $statement->closeCursor();
}

function get_toppings($db) {
    $query = 'SELECT id, topping_name FROM toppings';
    $statement = $db->prepare($query);
    $statement->execute();
    $toppings = $statement->fetchAll();
    return $toppings;    
}
function get_topping_name($db, $topping_id) {
    $query = 'SELECT topping_name FROM toppings where id = :id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $topping_id);
    $statement->execute();
    $topping_row = $statement->fetch();
    return $topping_row['topping_name'];    
}
?>