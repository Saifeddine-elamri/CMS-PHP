<!-- Vue pour afficher un seul post avec image et style -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="../public/assets/css/post-show.css">

</head>
<body>

    <?php include('app/Views/templates/header.php'); ?>

    <div class="post-card">
        <!-- Affichage de l'image si disponible -->
        <?php if (!empty($post['image'])): ?>: 
            <img src="/<?php echo htmlspecialchars($post['image'], ENT_QUOTES, "UTF-8"); ?>" alt="Image du post" class="post-image">
        <?php else: ?>: 
            <div class="no-image">Aucune image disponible pour ce post</div>
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($post['title'], ENT_QUOTES, "UTF-8"); ?></h1>
        <p><?php echo htmlspecialchars(nl2br($post['content']), ENT_QUOTES, "UTF-8"); ?></p>
         <?php if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false): ?>: 
            <!-- Affichage du fichier PDF avec style amélioré -->
            <div class="file-preview">
                <a href="/<?php echo htmlspecialchars($post['file_path'], ENT_QUOTES, "UTF-8"); ?>" download class="file-link">
                    <span>Télécharger le PDF</span>
                </a>
            </div>
        <?php else: ?>:
            <div class="no-image">Aucun fichier n'est disponible</div>
        <?php endif; ?>

        <a class="back-link" href="/post/list">← Retour à la liste des posts</a>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
