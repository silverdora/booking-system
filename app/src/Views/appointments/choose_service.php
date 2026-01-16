<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/salons/<?= (int)$salonId ?>">&larr; Back to salon</a>
</div>

<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-3">Choose a service</h1>

        <div class="d-flex gap-2 mb-4">
            <span class="badge text-bg-primary">1</span>
            <span class="badge text-bg-secondary">2</span>
            <span class="badge text-bg-secondary">3</span>
        </div>

        <?php if (!empty($services)) : ?>
            <div class="list-group">
                <?php foreach ($services as $s) : ?>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                       href="/salons/<?= (int)$salonId ?>/book/date?serviceId=<?= (int)$s['id'] ?>">
                        <span><?= htmlspecialchars($s['name']) ?></span>
                        <span class="text-body-secondary">&rarr;</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="alert alert-secondary mb-0" role="alert">
                No services found.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>



