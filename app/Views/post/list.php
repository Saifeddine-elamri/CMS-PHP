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
                        <?php if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false): ?>
                            <!-- Affichage des fichiers PDF -->
                            <div class="file-preview">
                                <a href="/<?php echo htmlspecialchars($post['file_path']); ?>" download>
                                    Télécharger le PDF
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="no-image">Aucun fichier n'est disponible</div>
                        <?php endif; ?>
                        <a href="/post/<?php echo $post['id']; ?>">Voir plus de details</a>
                        </li>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
