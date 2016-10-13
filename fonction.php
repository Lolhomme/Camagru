<?php
function dbConnect() {
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=camagru', $user, $pass);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    return $dbh;
}
?>