<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="CMS">
    <meta name="author" content="Votre nom ou entreprise">
    <title>Se connecter</title>

    <!-- Fichiers CSS -->
    <link rel="stylesheet" href="public/assets/css/login.css">

    <!-- Favicon pour l'onglet du navigateur -->
    <link rel="icon" href="public/assets/images/favicon.ico" type="image/x-icon">
</head>
<body>

<?php include('app/Views/templates/header.php'); ?>

<main>


    <!-- Formulaire de connexion -->
    <form action="/login" method="POST" class="login-form">
        <h2>Connexion</h2>
        
        <div class="form-group">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" placeholder="Votre nom d'utilisateur" required autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required autocomplete="current-password">
        </div>
        @if (!empty($errorMessage)): 
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        @endif
        <div class="form-group">
            <button type="submit" class="submit-btn">Se connecter</button>
        </div>

        <p class="form-footer">Pas de compte ? <a href="/register">Inscrivez-vous ici</a>.</p>
    </form>
</main>

<?php include('app/Views/templates/footer.php'); ?>


</body>
</html>
