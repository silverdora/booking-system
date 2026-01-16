<?php
/** @var string $title */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Framework\Authentication;

$isLoggedIn = Authentication::isLoggedIn();
$user = Authentication::user();
if (!is_array($user)) {
    $user = [];
}
?>
<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$isActive = function (string $path, bool $exact = true) use ($currentPath): bool {
    if ($exact) {
        return rtrim($currentPath, '/') === rtrim($path, '/');
    }
    // prefix-match: /salons matches /salons/1, /salons/create, etc.
    $p = rtrim($path, '/');
    return $p === '' ? $currentPath === '/' : (strpos(rtrim($currentPath, '/'), $p) === 0);
};

$role = isset($user['role']) ? $user['role'] : null;

$userSalonId = null;
if (isset($user['salonId'])) {
    $userSalonId = $user['salonId'];
}


$homeHref = (!$isLoggedIn || $role === 'customer') ? '/salons' : '/appointments';
$brandHref = $homeHref;
$ownerSalonProfileHref = ($isLoggedIn && $role === 'owner' && !empty($userSalonId))
        ? '/salons/' . rawurlencode((string)$userSalonId)
        : null;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Booking System') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<div class="app min-vh-100 d-flex flex-column">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm mb-3">
        <div class="container">
            <a class="navbar-brand <?= $isActive($brandHref, true) ? 'active' : '' ?>"
               href="<?= htmlspecialchars($brandHref) ?>">
                Booking System
            </a>


            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link <?= $isActive('/salons', false) ? 'active' : '' ?>" href="/salons">Salons</a>
                    </li>

                    <!-- Owner: Salon Profile -->
                    <?php if ($ownerSalonProfileHref): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $isActive($ownerSalonProfileHref, true) ? 'active' : '' ?>"
                               href="<?= htmlspecialchars($ownerSalonProfileHref) ?>">
                                Salon Profile
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Customer links -->
                    <?php if ($isLoggedIn && $role === 'customer'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $isActive('/profile', true) ? 'active' : '' ?>" href="/profile">My profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isActive('/appointments', false) ? 'active' : '' ?>" href="/appointments">My appointments</a>
                        </li>
                    <?php endif; ?>

                    <!-- Staff/Owner links: appointments -->
                    <?php if ($isLoggedIn && in_array($role, ['owner', 'specialist', 'receptionist'], true)): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $isActive('/appointments', false) ? 'active' : '' ?>" href="/appointments">Appointments</a>
                        </li>
                    <?php endif; ?>
                </ul>


                <div class="d-flex gap-2">
                    <?php if ($isLoggedIn): ?>
                        <form action="/logout" method="post" class="d-flex">
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    <?php else: ?>
                        <?php $loginActive = $isActive('/login', true); ?>
                        <?php $registerActive = $isActive('/register', true); ?>

                        <a href="/login"
                           class="btn btn-sm <?= $loginActive ? 'btn-light text-dark' : 'btn-outline-light' ?>">
                            Login
                        </a>

                        <a href="/register"
                           class="btn btn-sm <?= $registerActive ? 'btn-light text-dark' : 'btn-primary' ?>">
                            Register
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="container">