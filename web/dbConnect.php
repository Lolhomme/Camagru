<?php
include '../config/database.php';

try {
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print 'Connection failed: ' . $e->getMessage() . PHP_EOL;
    die();
}
?>