<?php
include 'database.php';
include 'admin_db.php';
print 'opened';
try {
add_admin('andrea@murach.com', 'Andrea', 'Smith','sesame');
} catch (PDOException $e) {
  print 'exception '. $e.getMessage();
}
