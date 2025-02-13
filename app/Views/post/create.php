<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Post</title>
    <link rel="stylesheet" href="../public/assets/css/create.css">
</head>
<body>
    <?php include('app/Views/templates/header.php'); ?>

    <main class="form-container">
        <h1 class="form-title">Créer un Nouveau Post</h1>
        
        <!-- Ajout de enctype pour le formulaire -->
        <form action="/post/create" method="POST" class="post-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Titre :</label>
                <input type="text" name="title" id="title" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Contenu :</label>
                <textarea name="content" id="content" class="form-input" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="author_id" class="form-label">ID de l'Auteur :</label>
                <input type="number" name="author_id" id="author_id" class="form-input" required>
            </div>

            <!-- Nouveau champ pour l'image et PDF -->
            <div class="form-group">
                <label for="file" class="form-label">Fichier (Image ou PDF) :</label>
                <input type="file" name="file" id="file" class="form-input" accept="image/*,application/pdf">
            </div>

            <div class="form-group">
                <button type="submit" class="form-btn">Créer le Post</button>
            </div>
        </form>
    </main>

    <?php include('app/Views/templates/footer.php'); ?>
</body>
</html>
