<?php
require '/Users/alaulom/http/MyWebSite/camagru/config/database.php';

try {
    //42
    $db = new PDO($DB_DSN42, $DB_USER, $DB_PASSWORD);
    //HOME
//    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print 'Connection failed: ' . $e->getMessage() . PHP_EOL;
    die();
}
?>