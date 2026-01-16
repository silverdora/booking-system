<?php

use App\ViewModels\UsersViewModel;

/** @var UsersViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$rolePlural = $vm->role . 's'; // URL convention: customers/owners/receptionists/specialists
?>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= htmlspecialchars($vm->title) ?></h1>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div class="btn-group" role="group" aria-label="User categories">
                <a class="btn btn-sm <?= $rolePlural === 'receptionists' ? 'btn-primary' : 'btn-outline-light' ?>"
                   href="/users/receptionists">
                    Receptionists
                </a>
                <a class="btn btn-sm <?= $rolePlural === 'specialists' ? 'btn-primary' : 'btn-outline-light' ?>"
                   href="/users/specialists">
                    Specialists
                </a>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-light btn-sm" href="/users/specialists/create">Add new specialist</a>
                <a class="btn btn-outline-light btn-sm" href="/users/receptionists/create">Add new receptionist</a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($vm->users)) : ?>
    <div class="list-group">
        <?php foreach ($vm->users as $user) : ?>
            <div class="list-group-item bg-dark border-secondary">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                    <div>
                        <div class="fw-semibold">
                            <a class="link-light text-decoration-none"
                               href="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>">
                                <?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?>
                            </a>
                        </div>
                        <div class="small text-light opacity-75">
                            <?= htmlspecialchars($user->email) ?>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-outline-light btn-sm"
                           href="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>/edit">
                            Edit
                        </a>

                        <form action="/users/<?= htmlspecialchars($rolePlural) ?>/<?= htmlspecialchars((string)$user->id) ?>/delete"
                              method="post"
                              onsubmit="return confirm('Delete this user?');"
                              class="m-0">
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-secondary" role="alert">
        No <?= htmlspecialchars($rolePlural) ?> found.
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>


