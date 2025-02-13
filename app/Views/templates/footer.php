<?php
// footer.php
?>

<style>
/* Footer Styles */
footer {
    background-color: #2c3e50; /* Couleur de fond plus moderne */
    color: white;
    padding: 30px 0; /* Plus d'espace intérieur */
    margin-top: auto; /* Pour coller le footer en bas de la page */
    font-family: 'Arial', sans-serif; /* Police moderne */
    border-top: 4px solid #3498db; /* Bordure supérieure colorée */
}

footer .footer-content {
    margin: 0 auto; /* Centrer le contenu */
    display: flex;
    justify-content: space-between; /* Aligner les éléments */
    align-items: center;
    flex-wrap: wrap; /* Permettre le retour à la ligne sur les petits écrans */
    padding: 0 20px; /* Espacement latéral */
}

footer p {
    margin: 0;
    font-size: 16px; /* Taille de police un peu plus grande */
    color: #ecf0f1; /* Couleur de texte plus douce */
}

footer ul {
    list-style: none; /* Supprimer les puces */
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px; /* Espacement entre les liens */
}

footer ul li {
    display: inline-block;
}

footer ul li a {
    color: #ecf0f1; /* Couleur des liens */
    text-decoration: none; /* Supprimer le soulignement */
    font-size: 14px;
    transition: color 0.3s ease, text-decoration 0.3s ease; /* Transition fluide */
}

footer ul li a:hover {
    color: #3498db; /* Changement de couleur au survol */
    text-decoration: underline; /* Soulignement au survol */
}

/* Responsive Design */
@media (max-width: 768px) {
    footer .footer-content {
        flex-direction: column; /* Empiler les éléments sur les petits écrans */
        text-align: center;
        gap: 10px;
    }

    footer ul {
        justify-content: center; /* Centrer les liens */
    }
}
</style>

<footer>
    <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> CMS Complexe. Tous droits réservés.</p>
        <ul>
            <li><a href="/privacy-policy">Politique de confidentialité</a></li>
            <li><a href="/terms-of-service">Conditions d'utilisation</a></li>
            <li><a href="/contact">Contact</a></li> <!-- Ajout d'un lien de contact -->
        </ul>
    </div>
</footer>

<!-- Scripts JS -->
