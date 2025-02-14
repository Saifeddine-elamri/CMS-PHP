<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Posts</title>
    <link rel="stylesheet" href="/public/assets/css/post.css">
</head>
<body>

    @include('app/Views/templates/header.php')

    <div>
        <h1>Liste des Posts</h1>
        <ul>
            @foreach ($posts as $post)
                <li class="li-post">
                <!-- Affichage de l'image si disponible -->
                @if (!empty($post['image']) && file_exists($post['image'])): 
                    <img src="/{{ $post['image'] }}" alt="Image du post" class="post-image">
                @else: 
                    <div class="no-image">Aucune image disponible</div>
                @endif

                <h2>{{ $post['title'] }}</h2>
                <p>{{ nl2br($post['content']) }}</p>

                <!-- Vérification pour afficher l'image ou le fichier PDF -->
                @if (!empty($post['file_path'])): 
                    @if (strpos($post['file_path'], '.pdf') === false && file_exists($post['file_path'])): 
                        <!-- Affichage des images -->
                        <img src="/{{ $post['file_path']  }}" alt="Image du post" class="post-image">
                    @elif (strpos($post['file_path'], '.pdf') !== false): 
                        <!-- Affichage des fichiers PDF -->
                        <div class="file-preview">
                            <a href="/{{ $post['file_path'] }}" download class="a-post">
                                Télécharger le PDF
                            </a>
                        </div>
                    @else: 
                        <div class="no-image">Aucun fichier n'est disponible</div>
                    @endif
                @else: 
                    <div class="no-image">Aucun fichier n'est disponible</div>
                @endif


                    <a href="/post/{{ $post['id'] }}" class="a-post">Voir plus de détails</a>
                    
                    <!-- Formulaire de suppression visible uniquement pour les admins -->
                    @if ($isAdmin):
                        <!-- Formulaire de mise à jour visible uniquement pour les admins -->
                        <a href="/post/edit/{{  $post['id'] }}" class="edit-btn a-post">Mettre à jour</a>
                        <form action="/post/delete/{{ $post['id'] }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                            <button type="submit" class="delete-btn">Supprimer</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>

        <!-- Pagination -->
        <div class="pagination">
            @if ($currentPage > 1)
                <a href="/post/list/1" class="pagination-link">Première page</a>
                <a href="/post/list/{{ $currentPage - 1 }}" class="pagination-link">page précédente</a>
            @endif

            @for ($page = 1; $page <= $totalPages; $page++): 
                <a href="/post/list/{{ $page }}" class="pagination-link" <?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                    {{ $page }}
                </a>
            @endfor

            @if ($currentPage < $totalPages)
                <a href="/post/list/{{ $currentPage + 1 }}" class="pagination-link">Page suivante</a>
                <a href="/post/list/{{ $totalPages }}" class="pagination-link">Dernière page</a>
            @endif
        </div>
    </div>

    @include('app/Views/templates/footer.php')
    <script src="../public/assets/js/delete.js"></script>
        
</body>
</html>
