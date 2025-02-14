<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Liste des Posts' }}</title>
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
    @include('templates.header')
    <div class="container">
        @yield('content')
    </div>
    @include('templates.footer')
</body>
</html>
