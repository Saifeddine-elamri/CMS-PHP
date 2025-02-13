<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Post</title>
    <link rel="stylesheet" href="/public/assets/css/create.css">
    <style>
        /* Styles améliorés pour une meilleure UX */
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form-btn:hover {
            background: #218838;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include('app/Views/templates/header.php'); ?>

    <main class="form-container">
        <h1 class="form-title">Créer un Nouveau Post</h1>
        
        <!-- Ajout de enctype pour le formulaire -->
        <form action="/post/create" method="POST" class="post-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Titre :</label>
                <input type="text" name="title" id="title" class="form-input" 
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                <small class="error-message"><?= $errors['title'] ?? '' ?></small>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Contenu :</label>
                <textarea name="content" id="content" class="form-input" rows="5" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                <small class="error-message"><?= $errors['content'] ?? '' ?></small>
            </div>

            <div class="form-group">
                <label for="author_id" class="form-label">ID de l'Auteur :</label>
                <input type="number" name="author_id" id="author_id" class="form-input" 
                    value="<?= htmlspecialchars($_POST['author_id'] ?? '') ?>" required>
                <small class="error-message"><?= $errors['author_id'] ?? '' ?></small>
            </div>

            <!-- Nouveau champ pour l'image et PDF -->
            <div class="form-group">
                <label for="file" class="form-label">Fichier (Image ou PDF) :</label>
                <input type="file" name="file" id="file" class="form-input" accept="image/*,application/pdf">
                <small class="error-message"><?= $errors['file'] ?? '' ?></small>
            </div>

            <div class="form-group">
                <button type="submit" class="form-btn">Créer le Post</button>
            </div>
        </form>
    </main>

    <?php include('app/Views/templates/footer.php'); ?>
</body>
</html>
