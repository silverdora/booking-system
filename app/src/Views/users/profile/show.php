<?php

use App\ViewModels\UserDetailViewModel;

/** @var UserDetailViewModel $vm */
$title = 'My profile';

require __DIR__ . '/../../partials/header.php';

$user = $vm->user;
?>

<h1><?= htmlspecialchars($title) ?></h1>

<p><strong>Name:</strong> <?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($user->phone) ?></p>

<p>
    <a href="/profile/edit">Edit profile</a>
</p>

<?php require __DIR__ . '/../../partials/footer.php'; ?>


