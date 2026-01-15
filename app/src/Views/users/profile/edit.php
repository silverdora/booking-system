<?php
/** @var \App\ViewModels\UserFormViewModel $vm */
$title = 'Edit my profile';
require __DIR__ . '/../../partials/header.php';

$user = $vm->user;
?>

<p><a href="/profile">&larr; Back to profile</a></p>

<h1><?= htmlspecialchars($title) ?></h1>

<form action="<?= htmlspecialchars($vm->action) ?>" method="post">
    <label for="firstName">First name*</label>
    <input id="firstName" name="firstName" required
           value="<?= htmlspecialchars($user->firstName ?? '') ?>">

    <label for="lastName">Last name*</label>
    <input id="lastName" name="lastName" required
           value="<?= htmlspecialchars($user->lastName ?? '') ?>">

    <label for="email">Email*</label>
    <input id="email" name="email" type="email" required
           value="<?= htmlspecialchars($user->email ?? '') ?>">

    <label for="phone">Phone*</label>
    <input id="phone" name="phone" type="tel" required
           value="<?= htmlspecialchars($user->phone ?? '') ?>">

    <label for="password">New password (leave empty to keep current)</label>
    <input id="password" name="password" type="password" autocomplete="new-password">

    <button type="submit">Save changes</button>
</form>

<?php require __DIR__ . '/../../partials/footer.php'; ?>

