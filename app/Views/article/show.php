<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
    <a href="/articles">Retour à la liste</a>
</body>
</html>