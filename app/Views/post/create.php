<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Post</title>
    <link rel="stylesheet" href="/public/assets/css/create.css">
</head>
<body>
    <?php include('app/Views/templates/header.php'); ?>

    <main class="form-container">
        <h1 class="form-title">Créer un Nouveau Post</h1>
        
        <!-- Ajout de enctype pour le formulaire -->
        <form action="/post/create" method="POST" class="post-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Titre :</label>
                <input type="text" name="title" id="title" class="form-input" 
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                <small class="error-message"><?= $errors['title'] ?? '' ?></small>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Contenu :</label>
                <textarea name="content" id="content" class="form-input" rows="5" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                <small class="error-message"><?= $errors['content'] ?? '' ?></small>
            </div>

            <!-- Menu déroulant pour l'ID de l'auteur -->
            <div class="form-group">
                <label for="author_id" class="form-label">Choisissez l'Auteur :</label>
                <select name="author_id" id="author_id" class="form-input" required>
                    <option value="">-- Sélectionner un auteur --</option>
                    <?php foreach ($admins as $admin): ?>
                        <option value="<?= $admin['id']; ?>" <?= ($_POST['author_id'] ?? '') == $admin['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($admin['username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="error-message"><?= $errors['author_id'] ?? '' ?></small>
            </div>

            <!-- Nouveau champ pour l'image et PDF -->
            <div class="form-group">
                <label for="file" class="form-label">Fichier (Image ou PDF) :</label>
                <div class="file-upload">
                    <div class="file-drop-area">
                        <span class="file-msg">Glissez-déposez un fichier ici ou cliquez pour en sélectionner un</span>
                        <input type="file" name="file" id="file" class="file-input" accept="image/*,application/pdf">
                    </div>
                    <small class="error-message"><?= $errors['file'] ?? '' ?></small>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="form-btn">Créer le Post</button>
            </div>
        </form>
    </main>

    <?php include('app/Views/templates/footer.php'); ?>

    <script src="../public/assets/js/create.js"></script>

</body>
</html>
