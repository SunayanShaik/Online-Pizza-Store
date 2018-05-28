<?php
// the try/catch for these actions is in the caller, index.php

function add_size($db, $size_name)  
{
    $query = 'INSERT INTO sizes
                 (size_name)
              VALUES
                 (:size_name)';
    $statement = $db->prepare($query);
    $statement->bindValue(':size_name', $size_name);
    $statement->execute();
    $statement->closeCursor();
}

function delete_size($db, $size_id)  
{
    $query = 'DELETE FROM sizes
                 WHERE id = :size_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':size_id', $size_id);
    $statement->execute();
    $statement->closeCursor();
}

function get_sizes($db) {
    $query = 'SELECT id, size_name FROM sizes';
    $statement = $db->prepare($query);
    $statement->execute();
    $sizes = $statement->fetchAll();
    return $sizes;    
}

?>