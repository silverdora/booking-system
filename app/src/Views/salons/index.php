<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salons</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<h1>Salons</h1>


<p><a href="/salons/create">Add a salon</a></p>

<?php if (!empty($salons)) : ?>
    <ul>
        <?php foreach ($salons as $salon) : ?>
            <li>
                <h2>
                    <!-- LINK TO DETAIL PAGE -->
                    <a href="/salons/<?= htmlspecialchars($salon['id']) ?>">
                        <?= htmlspecialchars($salon['name']) ?>
                    </a>
                </h2>
                <p><?= htmlspecialchars($salon['type']) ?></p>
                <p><?= htmlspecialchars($salon['city']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No salons have been registered yet.</p>
<?php endif; ?>
</body>
</html>

