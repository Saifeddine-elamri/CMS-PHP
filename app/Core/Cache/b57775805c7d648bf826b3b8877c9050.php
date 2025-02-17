<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/assets/css/register.css">

    <title>Inscription</title>
</head>
<body>
 

    <?php include('app/Views/templates/header.php'); ?>

    <main>
        <form action="/register" method="POST">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required>
            <br>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <br>
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <br>
            <button type="submit">S'inscrire</button>
        </form>
    </main>
  

    <?php include('app/Views/templates/footer.php'); ?>
</body>
</html>
