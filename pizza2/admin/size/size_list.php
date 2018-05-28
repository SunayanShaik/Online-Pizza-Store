<?php include '../../view/header.php'; ?>
<main>
    <section>
        <h1>Sizes List</h1>
        <table>
            <tr>
                <th>Size Name</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($sizes as $size) : ?>
                <tr>
                    <td><?php echo $size['size_name']; ?></td>
                    <td><form action="." method="post">
                            <input type="hidden" name="action"
                                   value="delete_size">
                            <input type="hidden" name="size_id"
                                   value="<?php echo $size['id']; ?>">        
                            <input type="submit" value="Delete">
                        </form></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p>
            <a href=".?action=show_add_form">Add Size</a>
        </p>
    </section>
</main>
<?php include '../../view/footer.php'; ?>

