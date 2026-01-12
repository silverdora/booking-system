<?php /** @var string $title */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Booking System') ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<?php if (isset($_SESSION['user'])): ?>
    <form action="/logout" method="post" style="display:inline">
        <button type="submit">Logout</button>
    </form>
<?php endif; ?>
