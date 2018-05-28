<!DOCTYPE html>
<html>
    <body>
        <main>
            <h1>Error</h1>
            <p><?php echo $error; ?></p>
            <p> <?php debug_print_backtrace(); ?></p>
        </main>
    </body>
</html>
