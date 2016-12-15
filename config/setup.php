<?php

include 'database.php';

    try {
        $db = new PDO($DB_DSN_SETUP, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print 'Connection failed: ' . $e->getMessage() . PHP_EOL;
        die();
    }

    $database = explode(';\n', file_get_contents('camagru.sql'));
    foreach ($database as $request)
    {
        $request = trim($request);
        if (!empty($request))
        {
            try
            {
                $db->query($request);
            }
            catch (PDOException $e)
            {
                echo 'Error sql : '. $e->getMessage().PHP_EOL;
                $error = TRUE;
            }
        }
    }
?>