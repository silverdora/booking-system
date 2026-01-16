<?php

use App\ViewModels\UserDetailViewModel;

/** @var UserDetailViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$user = $vm->user;
$rolePlural = $vm->role . 's';
?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none"
       href="/users/<?= htmlspecialchars($rolePlural) ?>">&larr; Back to <?= htmlspecialchars($rolePlural) ?></a>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
            <div>
                <h1 class="h4 mb-1">
                    <?= htmlspecialchars($user->firstName . ' ' . $user->lastName) ?>
                </h1>
                <div class="text-light opacity-75">
                    <?= htmlspecialchars(ucfirst((string)$user->role)) ?>
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

        <hr>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="fw-semibold">Email</div>
                <div><?= htmlspecialchars($user->email) ?></div>
            </div>

            <?php if (!empty($user->phone)) : ?>
                <div class="col-12 col-md-6">
                    <div class="fw-semibold">Phone</div>
                    <div><?= htmlspecialchars($user->phone) ?></div>
                </div>
            <?php endif; ?>

            <div class="col-12 col-md-6">
                <div class="fw-semibold">Role</div>
                <div><?= htmlspecialchars((string)$user->role) ?></div>
            </div>

            <?php if (!empty($user->salonId)) : ?>
                <div class="col-12 col-md-6">
                    <div class="fw-semibold">Salon ID</div>
                    <div><?= htmlspecialchars((string)$user->salonId) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>


