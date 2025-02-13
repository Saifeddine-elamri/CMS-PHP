<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CMS Complexe</title>

    <!-- Fichiers CSS -->
    <link rel="stylesheet" href="public/assets/css/login.css">
</head>
<body>



<?php include('app/Views/templates/header.php'); ?>

    <main>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit">Se connecter</button>
            </div>
        </form>

        <p>Pas de compte ? <a href="/register">Inscrivez-vous ici</a>.</p>
    </main>

    <?php include('app/Views/templates/footer.php'); ?>
</body>
</html>