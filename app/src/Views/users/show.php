<?php

use App\ViewModels\UserDetailViewModel;

/** @var UserDetailViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$user = $vm->user;
$rolePlural = $vm->role . 's';
?>
<p>
    <a href="/users/<?= htmlspecialchars($rolePlural) ?>">&larr; Back to <?= htmlspecialchars($rolePlural) ?></a>
</p>

<h1><?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?></h1>

<p><strong>Role:</strong> <?= htmlspecialchars($user->role) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>

<?php if (!empty($user->phone)) : ?>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user->phone) ?></p>
<?php endif; ?>

<?php if (!empty($user->salonId)) : ?>
    <p><strong>Salon ID:</strong> <?= htmlspecialchars((string)$user->salonId) ?></p>
<?php endif; ?>

<p>
    <a href="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>/edit">Edit</a>
</p>

<form action="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>/delete"
      method="post"
      onsubmit="return confirm('Delete this user?');">
    <button type="submit">Delete</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>

