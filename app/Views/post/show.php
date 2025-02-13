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
        <?php if (!empty($post['image'])): ?>
            <img src="/<?php echo htmlspecialchars($post['image']); ?>" alt="Image du post" class="post-image">
        <?php else: ?>
            <div class="no-image">Aucune image disponible pour ce post</div>
        <?php endif; ?>

        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        
        <a class="back-link" href="/post/list">← Retour à la liste des posts</a>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
