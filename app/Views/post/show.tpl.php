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

    @include('app/Views/templates/header.php')

    <div class="post-card">
        <!-- Affichage de l'image si disponible -->
        @if (!empty($post['image'])): 
            <img src="/{{ $post['image'] }}" alt="Image du post" class="post-image">
        @else: 
            <div class="no-image">Aucune image disponible pour ce post</div>
        @endif

        <h1>{{ $post['title'] }}</h1>
        <p>{{ nl2br($post['content']) }}</p>
         @if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false): 
            <!-- Affichage du fichier PDF avec style amélioré -->
            <div class="file-preview">
                <a href="/{{ $post['file_path'] }}" download class="file-link">
                    <span>Télécharger le PDF</span>
                </a>
            </div>
        @else:
            <div class="no-image">Aucun fichier n'est disponible</div>
        @endif

        <a class="back-link" href="/post/list">← Retour à la liste des posts</a>
    </div>

    @include('app/Views/templates/footer.php')

</body>
</html>
