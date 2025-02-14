<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Posts</title>
    <link rel="stylesheet" href="/public/assets/css/post.css">
 
    <script>
        // Confirmation avant suppression
        function confirmDelete(postId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                window.location.href = '/delete/' + postId;
            }
        }
    </script>
</head>
<body>

    <?php include('app/Views/templates/header.php'); ?>

    <div>
    <h1>Liste des Posts</h1>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, "UTF-8"); ?></h2>
                <p><?php echo htmlspecialchars(nl2br($post['content']), ENT_QUOTES, "UTF-8"); ?></p>
                
                
                <?php if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') === false): ?>
                    
                    <img src="/<?php echo htmlspecialchars($post['file_path'], ENT_QUOTES, "UTF-8"); ?>" alt="Image du post" class="post-image">
                <?php elseif (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false): ?>
                    
                    <div class="file-preview">
                        <a href="/<?php echo htmlspecialchars($post['file_path'], ENT_QUOTES, "UTF-8"); ?>" download>
                            Télécharger le PDF
                        </a>
                    </div>
                <?php else: ?>
                    <div class="no-image">Aucune image ou fichier disponible</div>
                <?php endif; ?>
                
                <a href="/post/<?php echo htmlspecialchars($post['id'], ENT_QUOTES, "UTF-8"); ?>">Voir plus de détails</a>

                
                <?php if ($isAdmin): ?>
                    <a href="javascript:void(0);" class="delete-btn" onclick="confirmDelete(<?php echo htmlspecialchars($post['id'], ENT_QUOTES, "UTF-8"); ?>)">
                        Supprimer
                    </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
