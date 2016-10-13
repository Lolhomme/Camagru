<?php

function dbConnect() {
    include 'database.php';

    try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $dbh;
}