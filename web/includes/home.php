<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru-Home</title>
    <link href="../css/grid.css" type="text/css" rel="stylesheet">
    <link href="../css/home.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Camagru: Bienvenue ganache!</h1>
    <div class="row nav">
        <div class="col-xs-12 col-sm-12">
            <a id="logout" href="./includes/logout.php">Se deconnecter</a>
            <a id="gallery">Gallerie</a>
        </div>
    </div>
    <div class="row display">
        <div class="col-xs-12 col-sm-9 main">
            <video id="video"></video>
            <canvas id="canvas"></canvas>
<!--            <img src="http://placekitten.com/g/320/261" id="photo" alt="photo">-->
            <button id="startbutton" onclick="hiddenbutton()">Prendre une photo</button>
            <form method="post" id="savepicture">
                <button id="savebutton" style="display: none">Sauvegarder</button>
            </form>
        </div>
        <div class="col-xs-12 col-sm-3 col-sm-push-3 side">Side</div>
    </div>
</div>
<!--<script type="text/javascript" src="../js/home.js"></script>-->
</body>
<!--<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>-->
</html>