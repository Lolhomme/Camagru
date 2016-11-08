<?php

include 'config/database.php';

function dbConnect() {

    global $DB_DSN, $DB_USER, $DB_PASSWORD;

    try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $dbh;
}