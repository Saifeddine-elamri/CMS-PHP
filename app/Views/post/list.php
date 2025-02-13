<!-- Vue pour afficher la liste des posts avec images et style -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Posts</title>
    <link rel="stylesheet" href="../public/assets/css/post.css">


</head>
<body>

    <?php include('app/Views/templates/header.php'); ?>

    <div>
        <h1>Liste des Posts</h1>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <!-- Affichage de l'image si disponible -->
                    <?php if (!empty($post['image'])): ?>
                        <img src="/<?php echo htmlspecialchars($post['image']); ?>" alt="Image du post" class="post-image">
                    <?php else: ?>
                        <div class="no-image">Aucune image disponible</div>
                    <?php endif; ?>

                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <a href="/post/<?php echo $post['id']; ?>">Voir</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
