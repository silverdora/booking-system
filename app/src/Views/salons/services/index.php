<?php
use App\ViewModels\SalonServices\SalonServicesViewModel;
/** @var SalonServicesViewModel $vm */
?>

<?php require __DIR__ . '/../../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none"
       href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>">&larr; Back to salon</a>
</div>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= htmlspecialchars($vm->title) ?></h1>

    <a class="btn btn-primary btn-sm"
       href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/create">
        Add service
    </a>
</div>

<?php if (!empty($vm->services)) : ?>
    <div class="list-group">
        <?php foreach ($vm->services as $service) : ?>
            <div class="list-group-item bg-dark border-secondary">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                    <div>
                        <div class="fw-semibold">
                            <a class="link-light text-decoration-none"
                               href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>">
                                <?= htmlspecialchars($service->name) ?>
                            </a>
                        </div>

                        <div class="small text-light opacity-75">
                            <?php if ($service->price !== null) : ?>
                                €<?= htmlspecialchars(number_format($service->price, 2)) ?>
                            <?php else: ?>
                                <span class="text-light opacity-50">No price</span>
                            <?php endif; ?>

                            <span class="mx-2">•</span>

                            <?php if ($service->durationMinutes !== null) : ?>
                                <?= htmlspecialchars((string)$service->durationMinutes) ?> min
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
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-secondary" role="alert">
        No services added yet.
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../../partials/footer.php'; ?>


