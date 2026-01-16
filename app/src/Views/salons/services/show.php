<?php
use App\ViewModels\SalonServices\SalonServiceDetailViewModel;
/** @var SalonServiceDetailViewModel $vm */
$service = $vm->service;
?>

<?php require __DIR__ . '/../../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none"
       href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services">&larr; Back to services</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
            <div>
                <h1 class="h4 mb-1"><?= htmlspecialchars($service->name) ?></h1>
                <div class="text-light opacity-75">
                    <?php if ($service->price !== null) : ?>
                        €<?= htmlspecialchars(number_format($service->price, 2)) ?>
                    <?php else: ?>
                        <span class="text-light opacity-50">No price</span>
                    <?php endif; ?>

                    <span class="mx-2">•</span>

                    <?php if ($service->durationMinutes !== null) : ?>
                        <?= htmlspecialchars((string)$service->durationMinutes) ?> minutes
                    <?php else: ?>
                        <span class="text-light opacity-50">No duration</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-light btn-sm"
                   href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/edit">
                    Edit
                </a>

                <form action="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/delete"
                      method="post"
                      onsubmit="return confirm('Delete this service?');"
                      class="m-0">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>

        <hr>

        <h2 class="h6 mb-2">Specialists</h2>

        <?php if (!empty($vm->specialists)) : ?>
            <ul class="list-group">
                <?php foreach ($vm->specialists as $s): ?>
                    <li class="list-group-item bg-dark border-secondary">
                        <?= htmlspecialchars($s['name']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <div class="alert alert-secondary mb-0" role="alert">
                No specialists assigned.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>


