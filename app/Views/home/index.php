<!-- home/index.php -->
<h1>Bienvenue sur notre CMS !</h1>

<?php if ($message): ?>
    <div class="alert"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<h2>Derniers articles</h2>
<ul>
    <?php foreach ($posts as $post): ?>
        <li>
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p><?= htmlspecialchars($post['content']) ?></p>
        </li>
    <?php endforeach; ?>
</ul>
