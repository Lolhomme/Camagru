<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Camagru-Home</title>
    <link href="../css/grid.css" type="text/css" rel="stylesheet">
<!--    <link href="../css/home.css" type="text/css" rel="stylesheet">-->
</head>
<body>
<div class="container">
    <h1>Camagru: Bienvenue ganache!</h1>
    <div class="col-xs-12 col-sm-4 nav">
        <a href="./includes/logout.php">Se deconnecter</a>
        <a>Gallerie</a>
    </div>
    <div class="col-xs-12" style="height: 20px"></div>
    <div id="main" class="col-xs-12 col-sm-8">
        <video id="video"></video>
        <canvas id="canvas"></canvas>
        <img src="http://placekitten.com/g/320/261" id="photo" alt="photo">
        <button id="startbutton">Prendre une photo</button>
    </div>
    <div class="col-xs-12 col-sm-2 col-sm-push-2">Side</div>
</div>
<script type="text/javascript" src="../js/home.js"></script>
</body>
</html>