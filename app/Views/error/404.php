<!-- app/Views/error/404.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>
    <link rel="stylesheet" href="../public/assets/css/error.css"> <!-- Update with your styles -->
</head>
<body>

<?php include('app/Views/templates/header.php'); ?>

    <div class="error-container">
        <h1>Oups ! Pour accéder a cette page il faut connecter en tant qu'admin.</h1>
        <p>Il semble que la page que vous essayez de visiter n'est pas autorisé. Retournez à <a href="/">l'accueil</a>.</p>
    </div>

<?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
