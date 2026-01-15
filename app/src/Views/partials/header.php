<?php
/** @var string $title */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Framework\Authentication;

$user = Authentication::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Booking System') ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

<?php if ($user): ?>
    <nav style="margin-bottom: 1rem;">
        <?php if (($user['role'] ?? '') === 'customer'): ?>
            <a href="/profile">My profile</a>
        <?php endif; ?>

        <form action="/logout" method="post" style="display:inline">
            <button type="submit">Logout</button>
        </form>
    </nav>
<?php endif; ?>


