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
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                                <!-- Vérification pour afficher l'image ou le fichier PDF -->
            <?php if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') === false): ?>
                    <!-- Affichage des images -->
                    <img src="/<?php echo htmlspecialchars($post['file_path']); ?>" alt="Image du post" class="post-image">
                <?php elseif (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false): ?>
                    <!-- Affichage des fichiers PDF -->
                    <div class="file-preview">
                        <a href="/<?php echo htmlspecialchars($post['file_path']); ?>" download>
                            Télécharger le PDF
                        </a>
                    </div>
                <?php else: ?>
                    <div class="no-image">Aucune image ou fichier disponible</div>
                <?php endif; ?>
                <a href="/post/<?php echo $post['id']; ?>">Voir plus de details</a>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
