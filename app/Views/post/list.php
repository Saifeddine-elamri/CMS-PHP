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
                <li class="li-post">
                    <!-- Affichage de l'image si disponible -->
                    <?php if (!empty($post['image'])): ?>
                        <img src="/<?php echo htmlspecialchars($post['image']); ?>" alt="Image du post" class="post-image">
                    <?php else: ?>
                        <div class="no-image">Aucune image disponible</div>
                    <?php endif; ?>


                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                                                            <!-- Vérification pour afficher l'image ou le fichier PDF -->
                    <?php if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') === false): ?>
                        <!-- Affichage des images -->
                        <img src="/<?php echo htmlspecialchars($post['file_path']); ?>" alt="Image du post" class="post-image">
                    <?php elseif (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false): ?>
                        <!-- Affichage des fichiers PDF -->
                        <div class="file-preview">
                            <a href="/<?php echo htmlspecialchars($post['file_path']); ?>" download class="a-post">
                                Télécharger le PDF
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="no-image">Aucun fichier n'est disponible</div>
                    <?php endif; ?>

                    <a href="/post/<?php echo $post['id']; ?>" class="a-post">Voir plus de détails</a>
                    
                    <!-- Formulaire de suppression visible uniquement pour les admins -->
                    <?php if ($isAdmin): ?>
                        <!-- Formulaire de mise à jour visible uniquement pour les admins -->
                        <a href="/post/edit/<?php echo $post['id']; ?>" class="edit-btn a-post">Mettre à jour</a>
                        <form action="/post/delete/<?php echo $post['id']; ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                            <button type="submit" class="delete-btn">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>
    <script src="../public/assets/js/delete.js"></script>
        
</body>
</html>
