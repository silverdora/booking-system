<?php
/** @var \App\ViewModels\SalonsViewModel $vm */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salons</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<h1>Salons</h1>

<p><a href="/salons/create">Add a salon</a></p>

<?php if (!empty($vm->salons)) : ?>
    <ul>
        <?php foreach ($vm->salons as $salon) : ?>
            <li>
                <h2>
                    <a href="/salons/<?= htmlspecialchars((string)$salon->id) ?>">
                        <?= htmlspecialchars($salon->name) ?>
                    </a>
                </h2>
                <p><?= htmlspecialchars($salon->type) ?></p>
                <p><?= htmlspecialchars($salon->city) ?></p>
            </li>
            <form action="/salons/<?= htmlspecialchars((string)$salon->id) ?>/delete" method="post"
                  onsubmit="return confirm('Delete this salon?');"
                  style="display:inline">
                <button type="submit">Delete</button>
            </form>
            <a href="/salons/<?= htmlspecialchars((string)$salon->id) ?>/edit">Edit</a>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No salons have been registered yet.</p>
<?php endif; ?>
</body>
</html>


