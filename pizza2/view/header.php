 <!-- Note from eoneil: 
   $app_path is now set in main.php, included in all controllers
 -->
    
<!DOCTYPE html>
<html> 
<head>
    <title>My Pizza Shop</title>
    <link rel="stylesheet" type="text/css"
           href="<?php echo $app_path . 'main.css'?>">
</head>

<body>
    <aside>
    <img src="<?php echo $app_path?>images/pizzapie.jpg" alt="Pizza">
    </aside>
        
    <header><h1>My Pizza Shop<br></h1></header>
    <aside>
        <br>
        <a href="<?php echo $app_path?>">Home</a>
        <br><br>
        <a href="<?php echo $app_path?>pizza/">Student Orders</a>
    </aside>
