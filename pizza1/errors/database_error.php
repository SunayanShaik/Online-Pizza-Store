<!DOCTYPE html>
<html>
    <body>
        <main>
            <h1>Database Error</h1>
            <p>There was an error accessing the database.</p>
            <p>The database must be installed using database/createdb.sql</p>
            <p> (after database/dev_setup.sql on your dev system.)</p>
            <p>Error message: <?php echo $error_message; ?></p>
            <p> <?php debug_print_backtrace(); ?></p>
        </main>
    </body>
</html>
