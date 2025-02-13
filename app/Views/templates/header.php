<?php
// header.php
?>

<style>
/* Global Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

header {
    background-color: #333;
    width:100%;
    color: white;
    padding: 20px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

nav {
    text-align: center;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

nav ul li {
    margin: 0 30px;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    transition: color 0.3s ease, text-decoration 0.3s ease;
}

nav ul li a:hover {
    text-decoration: underline;
    color: #4CAF50;  /* Light Green for hover effect */
}

/* Main Content */
main {
    flex: 1;
    padding: 20px;
    background-color: #fff;
    box-sizing: border-box;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
}

/* Style for the container */
.container h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 20px;
}

.container p {
    font-size: 1.2rem;
    line-height: 1.6;
    color: #555;
}

.container a {
    color: #4CAF50;
    text-decoration: none;
}

.container a:hover {
    text-decoration: underline;
}

/* Footer Styles */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    margin-top: auto;
}

footer p {
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    header {
        padding: 15px 0;
    }

    nav ul {
        flex-direction: column;
        padding: 0;
    }

    nav ul li {
        margin: 10px 0;
    }

    .container {
        padding: 15px;
    }

    .container h1 {
        font-size: 2rem;
    }

    .container p {
        font-size: 1rem;
    }
}
</style>
<header>
    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="/post/create">Créer un article</a></li>
            <li><a href="/post/list">Les posts</a></li>
            <li><a href="/about">À propos</a></li>
            <li><a href="/contact">Contact</a></li>

            <?php if (isset($_SESSION['user'])): ?>
                <!-- Si l'utilisateur est connecté, affiche son nom et un lien de déconnexion -->
                <li><a href="#">Bonjour, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li><a href="/logout">Se déconnecter</a></li>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté, affiche les liens de connexion et d'inscription -->
                <li><a href="/login">Se connecter</a></li>
                <li><a href="/register">S'inscrire</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
