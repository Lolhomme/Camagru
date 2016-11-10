<?php

include 'config/database.php';

function dbConnect()
{

    global $DB_DSN, $DB_USER, $DB_PASSWORD;

    try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

 //       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
        print 'Connection failed: ' . $e->getMessage() . PHP_EOL;
        die();
    }
    return $dbh;
}
