<?php
/** @var \App\ViewModels\UserFormViewModel $vm */
$title = 'Edit my profile';
require __DIR__ . '/../../partials/header.php';

$user = $vm->user;
?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/profile">&larr; Back to profile</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center"><?= htmlspecialchars($title) ?></h1>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= htmlspecialchars($vm->action) ?>" method="post" novalidate>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="firstName" class="form-label">First name</label>
                            <input id="firstName" name="firstName" class="form-control" required
                                   value="<?= htmlspecialchars($user->firstName ?? '') ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="lastName" class="form-label">Last name</label>
                            <input id="lastName" name="lastName" class="form-control" required
                                   value="<?= htmlspecialchars($user->lastName ?? '') ?>">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control" required
                               value="<?= htmlspecialchars($user->email ?? '') ?>">
                    </div>

                    <div class="mt-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" name="phone" type="tel" class="form-control" required
                               value="<?= htmlspecialchars($user->phone ?? '') ?>">
                    </div>

                    <div class="mt-3">
                        <label for="password" class="form-label">New password</label>
                        <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                        <div class="form-text text-light">Leave empty to keep current password.</div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <a href="/profile" class="btn btn-outline-light">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>


