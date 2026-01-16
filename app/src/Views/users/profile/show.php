<?php

use App\ViewModels\UserDetailViewModel;

/** @var UserDetailViewModel $vm */
$title = 'My profile';

require __DIR__ . '/../../partials/header.php';

$user = $vm->user;
?>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= htmlspecialchars($title) ?></h1>
    <a href="/profile/edit" class="btn btn-primary btn-sm">Edit profile</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="text-light opacity-75 small">Name</div>
                <div class="fw-semibold">
                    <?= htmlspecialchars(trim(($user->firstName ?? '') . ' ' . ($user->lastName ?? ''))) ?>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="text-light opacity-75 small">Email</div>
                <div class="fw-semibold"><?= htmlspecialchars($user->email ?? '') ?></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="text-light opacity-75 small">Phone</div>
                <div class="fw-semibold"><?= htmlspecialchars($user->phone ?? '') ?></div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>



