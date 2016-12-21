<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru</title>
</head>
<style>
    /*TEST AFFICHAGE IMAGES*/

    .img-nail {0
        width: 100%;
        float: left;
    }

    @media (min-width: 760px) {
        .img-nail {
            width: 30%;
        }
    }
</style>
<body>
<!--<div class="container">
    <div class="login">
        <div id="logo">
            <h1 href="index.php">Camagru</h1>
        </div>
        <form id="signinForm" method="post" action="../sign-in.php">
            <div id="log">
                <div class="box">
                    <input type="text" name="login" placeholder="Nom d'utilisateur">
                </div>
                <div class="box">
                    <input type="password" name="password" placeholder="Mot de passe">
                </div>
                <div class="box">
                    <button type="submit" name="log-in">Se connecter</button>
                </div>
            </div>
        </form>
        <div class="or">OU</div>
        <form action="../sign-up.php" method="post">
            <div class="box">
                <button type="submit">S'inscrire</button>
            </div>
        </form>
    </div>
</div>-->
<div class="gallery" style="width: 100%">
    <?php for ($i = 0; $i < 12; $i++): ?>
			<div class="img-nail">
				<img src="https://d1ra4hr810e003.cloudfront.net/media/27FB7F0C-9885-42A6-9E0C19C35242B5AC/0/D968A2D0-35B8-41C6-A94A0C5C5FCA0725/F0E9E3EC-8F99-4ED8-A40DADEAF7A011A5/dbe669e9-40be-51c9-a9a0-001b0e022be7/thul-IMG_2100.jpg" alt="cat" style="width: 100%; height: auto;">
			</div>
		<?php endfor;?>
</div>
</body>
</html>