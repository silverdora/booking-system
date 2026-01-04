<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($salon['name']) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<p><a href="/salons">&larr; Back to all salons</a></p>

<h1><?= htmlspecialchars($salon['name']) ?></h1>

<p><strong>Type:</strong> <?= htmlspecialchars($salon['type']) ?></p>
<p><strong>Address:</strong>
    <?= htmlspecialchars($salon['address']) ?>,
    <?= htmlspecialchars($salon['city']) ?>
</p>

<?php if (!empty($salon['phone'])) : ?>
    <p><strong>Phone:</strong> <?= htmlspecialchars($salon['phone']) ?></p>
<?php endif; ?>

<?php if (!empty($salon['email'])) : ?>
    <p><strong>Email:</strong> <?= htmlspecialchars($salon['email']) ?></p>
<?php endif; ?>

<?php if (!empty($salon['description'])) : ?>
    <h2>Description</h2>
    <p><?= nl2br(htmlspecialchars($salon['description'])) ?></p>
<?php endif; ?>
</body>
</html>

