<?php

use App\ViewModels\UsersViewModel;

/** @var UsersViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$rolePlural = $vm->role . 's'; // URL convention: customers/owners/receptionists/specialists
?>
<p>
    <a href="/users/customers">Customers</a> |
    <a href="/users/owners">Owners</a> |
    <a href="/users/receptionists">Receptionists</a> |
    <a href="/users/specialists">Specialists</a>
</p>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<p>
    <a href="/users/<?= htmlspecialchars($rolePlural) ?>/create">Create <?= htmlspecialchars($vm->role) ?></a>
</p>

<?php if (!empty($vm->users)) : ?>
    <ul>
        <?php foreach ($vm->users as $user) : ?>
            <li>
                <strong>
                    <a href="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>">
                        <?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?>
                    </a>
                </strong>
                <div><?= htmlspecialchars($user->email) ?></div>

                <p>
                    <a href="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>/edit">Edit</a>

                <form action="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>/delete"
                      method="post" style="display:inline"
                      onsubmit="return confirm('Delete this user?');">
                    <button type="submit">Delete</button>
                </form>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No <?= htmlspecialchars($rolePlural) ?> found.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>

