<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Posts</title>
    <link rel="stylesheet" href="/public/assets/css/post.css">
</head>
<body>

    <?php include('app/Views/templates/header.php'); ?>

    <div>
        <h1>Liste des Posts</h1>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li class="li-post">
                <!-- Affichage de l'image si disponible -->
                <?php if (!empty($post['image']) && file_exists($post['image'])): ?>: 
                    <img src="/<?php echo htmlspecialchars($post['image'], ENT_QUOTES, "UTF-8"); ?>" alt="Image du post" class="post-image">
                <?php else: ?>: 
                    <div class="no-image">Aucune image disponible</div>
                <?php endif; ?>

                <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, "UTF-8"); ?></h2>
                <p><?php echo htmlspecialchars(nl2br($post['content']), ENT_QUOTES, "UTF-8"); ?></p>

                <!-- Vérification pour afficher l'image ou le fichier PDF -->
                <?php if (!empty($post['file_path'])): ?>: 
                    <?php if (strpos($post['file_path'], '.pdf') === false && file_exists($post['file_path'])): ?>: 
                        <!-- Affichage des images -->
                        <img src="/<?php echo htmlspecialchars($post['file_path'], ENT_QUOTES, "UTF-8"); ?>" alt="Image du post" class="post-image">
                    <?php elseif (strpos($post['file_path'], '.pdf') !== false): ?>: 
                        <!-- Affichage des fichiers PDF -->
                        <div class="file-preview">
                            <a href="/<?php echo htmlspecialchars($post['file_path'], ENT_QUOTES, "UTF-8"); ?>" download class="a-post">
                                Télécharger le PDF
                            </a>
                        </div>
                    <?php else: ?>: 
                        <div class="no-image">Aucun fichier n'est disponible</div>
                    <?php endif; ?>
                <?php else: ?>: 
                    <div class="no-image">Aucun fichier n'est disponible</div>
                <?php endif; ?>


                    <a href="/post/<?php echo htmlspecialchars($post['id'], ENT_QUOTES, "UTF-8"); ?>" class="a-post">Voir plus de détails</a>
                    
                    <!-- Formulaire de suppression visible uniquement pour les admins -->
                    <?php if ($isAdmin): ?>:
                        <!-- Formulaire de mise à jour visible uniquement pour les admins -->
                        <a href="/post/edit/<?php echo htmlspecialchars($post['id'], ENT_QUOTES, "UTF-8"); ?>" class="edit-btn a-post">Mettre à jour</a>
                        <form action="/post/delete/<?php echo htmlspecialchars($post['id'], ENT_QUOTES, "UTF-8"); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                            <button type="submit" class="delete-btn">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="/post/list/1" class="pagination-link">Première page</a>
                <a href="/post/list/<?php echo htmlspecialchars($currentPage - 1, ENT_QUOTES, "UTF-8"); ?>" class="pagination-link">page précédente</a>
            <?php endif; ?>

            <?php for ($page = 1; $page <= $totalPages; $page++): ?>: 
                <a href="/post/list/<?php echo htmlspecialchars($page, ENT_QUOTES, "UTF-8"); ?>" class="pagination-link" <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($page, ENT_QUOTES, "UTF-8"); ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="/post/list/<?php echo htmlspecialchars($currentPage + 1, ENT_QUOTES, "UTF-8"); ?>" class="pagination-link">Page suivante</a>
                <a href="/post/list/<?php echo htmlspecialchars($totalPages, ENT_QUOTES, "UTF-8"); ?>" class="pagination-link">Dernière page</a>
            <?php endif; ?>
        </div>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>
    <script src="../public/assets/js/delete.js"></script>
        
</body>
</html>
