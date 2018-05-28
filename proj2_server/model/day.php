<?php
function update_system_day($db, $day)
{
    $query = '
         UPDATE systemday
         SET dayNumber = :day';
    $statement = $db->prepare($query);
    $statement->bindValue(':day', $day);
    $statement->execute();
    $statement->closeCursor();
}

function get_system_day($db)
{
    $query = 'select dayNumber from systemday';
    $statement = $db->prepare($query);
    $statement->execute();
    $current_day = $statement->fetch();
    $statement->closeCursor();
    return $current_day['dayNumber']; 
}
?>