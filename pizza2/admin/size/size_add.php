<?php include '../../view/header.php'; ?>
<main>
    <section>
    <h1>Add Size</h1>
    <form action="index.php" method="post">
        <input type="hidden" name="action" value="add_size">

        <label>Size Name:</label>
        <input type="text" name="size_name" />
        <br>
        <br>

        <label>&nbsp;</label>
        <input type="submit" value="Add" />
        <br>
        <br>
    </form>
    <p>
        <a href="index.php?action=list_sizes">View Size List</a>
    </p>
  </section>
<?php include '../../view/footer.php'; ?>
