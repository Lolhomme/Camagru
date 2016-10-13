<?php

if (!empty($_POST))
{
    echo'<pre>';
    print_r($_POST);
    $db = dbConnect();
}
else
{
    echo 'Me prends pas pour un con tu as appele le script a la main san emplir le formulaire! Connard';
}

