<?php include '../view/header.php'; ?>

<main>
    <section>
    <h1>Welcome to the Pizza Shop</h1>
    <h2><?php echo $display_error?></h2>
    
    <form  action="index.php" method="get">
        <input type="hidden" name="action" value="add_order">
        <h3>Pizza Size:</h3>
        <?php foreach ($sizes as $size) : ?>
            <input type="radio" name="pizza_size"  value="<?php echo $size['id']; ?>" required="required">
            <label><?php echo $size['size_name']; ?> </label>
        <?php endforeach; ?><br>

        <h3>Toppings:</h3>
        <?php foreach ($toppings as $topping) : ?>
            <input type="checkbox" name="pizza_topping[]"  value="<?php echo $topping['id']; ?>" >
            <label><?php echo $topping['topping_name']; ?> </label><br>
        <?php endforeach;?> <br>
        
        <label>Room No:</label>
        <select name="room" required="required">
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option <?php if ($room == $i) { echo 'selected = "selected"';}?> 
                    value="<?php echo $i; ?>" > <?php echo $i; ?> </option>
            <?php endfor; ?> 
        </select><br><br>
        
        <label>Quantity:</label> <input type='number' name="n" min="1" max="1000" value="1"><br><br>
        
        <input type="submit" value="Order Pizza" /> <br><br>
    </form>
    </section>
</main>
<?php include '../view/footer.php'; ?>