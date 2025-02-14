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
        @foreach ($posts as $post)
            <li>
                <h2>{{ $post['title'] }}</h2>
                <p>{{ nl2br($post['content']) }}</p>
                
                {{-- Vérification pour afficher l'image ou le fichier PDF --}}
                @if (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') === false)
                    {{-- Affichage des images --}}
                    <img src="/{{ $post['file_path'] }}" alt="Image du post" class="post-image">
                @elseif (!empty($post['file_path']) && strpos($post['file_path'], '.pdf') !== false)
                    {{-- Affichage des fichiers PDF --}}
                    <div class="file-preview">
                        <a href="/{{ $post['file_path'] }}" download>
                            Télécharger le PDF
                        </a>
                    </div>
                @else
                    <div class="no-image">Aucune image ou fichier disponible</div>
                @endif
                
                <a href="/post/{{ $post['id'] }}">Voir plus de détails</a>

                {{-- Bouton de suppression visible uniquement pour les admins --}}
                @if ($isAdmin)
                    <a href="javascript:void(0);" class="delete-btn" onclick="confirmDelete({{ $post['id'] }})">
                        Supprimer
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
    </div>

    <?php include('app/Views/templates/footer.php'); ?>

</body>
</html>
