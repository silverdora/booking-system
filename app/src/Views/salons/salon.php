<?php
/** @var \App\ViewModels\SalonDetailViewModel $vm */
$salon = $vm->salon;
?>

<?php require __DIR__ . '/../partials/header.php'; ?>
<?php if (($user['role'] ?? '') === 'сustomer'): ?>
<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/salons">&larr; Back to all salons</a>
</div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
            <div>
                <h1 class="h3 mb-1"><?= htmlspecialchars($salon->name) ?></h1>
                <div class="text-light opacity-75">
                    <?= htmlspecialchars($salon->type) ?> · <?= htmlspecialchars($salon->city) ?>
                </div>

            </div>

            <div class="d-flex gap-2">
                <a class="btn btn-primary btn-sm" href="/salons/<?= (int)$salon->id ?>/book">Book an appointment</a>

                <?php if (($user['role'] ?? '') === 'owner'): ?>
                    <a class="btn btn-outline-light btn-sm" href="/salons/<?= htmlspecialchars((string)$salon->id) ?>/edit">
                        Edit
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <hr>

        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="fw-semibold">Address</div>
                <div><?= htmlspecialchars($salon->address) ?>, <?= htmlspecialchars($salon->city) ?></div>
            </div>

            <?php if (!empty($salon->phone)) : ?>
                <div class="col-12 col-lg-6">
                    <div class="fw-semibold">Phone</div>
                    <div><?= htmlspecialchars($salon->phone) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($salon->email)) : ?>
                <div class="col-12 col-lg-6">
                    <div class="fw-semibold">Email</div>
                    <div><?= htmlspecialchars($salon->email) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($salon->description)) : ?>
                <div class="col-12">
                    <div class="fw-semibold mb-1">Description</div>
                    <div><?= nl2br(htmlspecialchars($salon->description)) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (($user['role'] ?? '') === 'owner'): ?>
            <hr>
            <form action="/salons/<?= htmlspecialchars((string)$salon->id) ?>/delete"
                  method="post"
                  onsubmit="return confirm('Delete this salon?');"
                  class="d-inline">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>



