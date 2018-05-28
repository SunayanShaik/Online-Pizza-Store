<?php include '../view/header.php'; ?>
<main>
    <section>
        <h1>Topping List</h1>
        <table>
            <tr>
                <th>Topping Name</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($toppings as $topping) : ?>
                <tr>
                    <td><?php echo $topping['topping_name']; ?></td>
                    <td>TODO: delete button </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>
        <a href=".?action=show_add_form">Add Topping</a>
        </p>
    </section>
</main>
<?php include '../view/footer.php'; 
