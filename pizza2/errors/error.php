<!DOCTYPE html>
<html>
    <body>
        <main>
            <!-- replacing separate database_error.php:
            report on error, with banner Database or class name if it's
                an Exception -->     
            <?php
            if ($e instanceof PDOException) { // including subclasses
                $label = 'Database';
                $error_message = $e->getMessage();
            } else if ($e instanceof Exception) {
                $label = get_class($e);
                $error_message = $e->getMessage();
            } else if (is_string($e)) {
                $label = 'User';
                $error_message = $e;
            } else {
                $label = 'Unclassified';
                $error_message = 'Error not Exception or string: bug in includer or error.php';
            }
            ?>
            <h1> <?php echo $label; ?> Error</h1>
            <h3> <?php echo $error_message; ?> </h3>
            <?php
            if ($e instanceof Exception) {
                echo '<p> at line ' . $e->getLine() . ' in file ' . $e->getFile() . '</p>';
                echo '<p> full backtrace:<br>';
                echo str_replace("\n", '<br>', $e->getTraceAsString());
                echo '</p>';
            }
            ?> 
            <p> <?php debug_print_backtrace(); ?></p>
        </main>
    </body>
</html>
