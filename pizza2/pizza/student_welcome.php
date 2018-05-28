<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1> Free Pizza for Students <h1>
        <h2>Available Sizes</h1>
        <ul>
            <?php foreach ($sizes as $size) : ?>
                <li class="horizontal">
                    <?php echo $size['size_name']; ?>
                </li>
                <?php endforeach; ?>
        </ul>
        <h2>Available Toppings</h1>
        <ul>
                <?php foreach ($toppings as $topping) : ?>
                <li class="horizontal">
                    <?php echo $topping['topping_name']; ?>
                </li>
                <?php endforeach; ?>
        </ul>
 
        <form  action="index.php" method="post">
            <input type="hidden" name="action" value="select_room">
            <label>Room No:</label>
            <select name="room" required="required">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option   <?php
                    if ($room == $i) {
                        echo 'selected = "selected"';
                    }
                    ?> 
                        value="<?php echo $i; ?>" > <?php echo $i; ?>
                    </option>
                <?php endfor; ?> 
            </select>
            <input  type="submit" value="Select Room" /> <br><br>
        </form>

        <?php
        if (count($room_preparing_orders) + count($room_baked_orders) == 0):
            echo 'No orders in progress for this room';
        else:
            ?>
            <h2>Orders in progress for room <?php echo $room ?></h2>

            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Room No</th>
                    <th>Toppings</th>
                    <th>Status</th>
                   
                </tr>
                 <?php foreach ($room_baked_orders as $room_order) : ?>
                 <tr>
                     <td><?php echo $room_order['id']; ?> </td>
                     <td><?php echo $room_order['room_number']; ?> </td>
   
                        <td><?php
                            $toppings = $room_order['toppings'];
                         foreach ($toppings as $t)
                                echo $t['topping'] . ' ';
                            ?></td>    
                        <td><?php echo 'Baked'; ?> </td>
                  </tr>
                  <?php endforeach; ?>
                  <?php foreach ($room_preparing_orders as $room_order) : ?>
                  <tr>
                     <td><?php echo $room_order['id']; ?> </td>
                     <td><?php echo $room_order['room_number']; ?> </td> 
                        <td><?php
                            $toppings = $room_order['toppings'];
                         foreach ($toppings as $t)
                                echo $t['topping'] . ' ';
                            ?></td> 
                        <td><?php echo 'Preparing'; ?> </td>
                  </tr>
                  <?php endforeach; ?>   
            </table>
        <?php endif; ?>
        <?php if (count($room_baked_orders) > 0): ?>
            <form action="index.php" method="get">
                <input type="hidden" name="room"
                       value="<?php echo $room; ?>">
                <input type="hidden" name="action"
                       value="update_order_status">
                <input type="submit" value="Acknowledge Delivery of Baked Pizzas">
            </form>
         <?php endif; ?>
  
        <p>
            <a href="?action=order_pizza&room=<?php echo $room; ?>"><strong>Order Pizza</strong></a>
        </p>
    </section>
</main>
<?php include '../view/footer.php'; ?>