<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Post</title>
    <link rel="stylesheet" href="/public/assets/css/post-edit.css">
</head>
<body>

    <?php include('app/Views/templates/header.php'); ?>

    <main class="form-container">
        <h1>Modifier le Post</h1>

        <form action="/post/edit/<?php echo $post['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Titre :</label>
                <input type="text" name="title" id="title" class="form-input" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Contenu :</label>
                <textarea name="content" id="content" class="form-input" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="file" class="form-label">Fichier (Image ou PDF) :</label>
                <input type="file" name="file" id="file" class="form-input" accept="image/*,application/pdf">
                <?php if (!empty($post['file_path'])): ?>
                    <p>Fichier actuel : <a href="/<?php echo $post['file_path']; ?>" download>Voir le fichier actuel</a></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <button type="submit" class="form-btn">Mettre à jour le Post</button>
            </div>
        </form>
    </main>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
