 <!-- Note from eoneil: 
    Note that it isn't good programming practice to have such specific 
    strings as 'cs637/eoneil' embedded in code, but PHP doesn't make
    it easy to do better. We will tackle this problem in the next project.
    The "app_path" is location of the project relative to the
    web server's document root.
 -->
 <!-- CHANGE xxxxx to your own topcat username-->
<?php $app_path = "/cs637/xxxxx/pizza1/"; ?>
    
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
