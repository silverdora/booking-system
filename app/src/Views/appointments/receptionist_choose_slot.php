<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none"
       href="/appointments/receptionist/date?serviceId=<?= (int)$serviceId ?>&customerId=<?= (int)$customerId ?>">
        &larr; Back to date
    </a>
</div>

<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-2">Choose a slot</h1>
        <div class="text-muted-dark mb-3">Step 3 — choose slot</div>

        <div class="d-flex gap-2 mb-4">
            <span class="badge text-bg-success">1</span>
            <span class="badge text-bg-success">2</span>
            <span class="badge text-bg-primary">3</span>
        </div>

        <?php if (empty($specialistsWithSlots)) : ?>
            <div class="alert alert-secondary mb-0" role="alert">
                No specialists are assigned to this service yet.
            </div>
        <?php endif; ?>

        <?php foreach ($specialistsWithSlots as $item): ?>
            <div class="border rounded p-3 mb-3">
                <h2 class="h6 mb-3"><?= htmlspecialchars($item['specialist']['name']) ?></h2>

                <?php if (empty($item['slots'])): ?>
                    <div class="text-body-secondary">No available slots.</div>
                <?php else: ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($item['slots'] as $slot): ?>
                            <form action="/appointments/receptionist/confirm" method="post" class="m-0">
                                <input type="hidden" name="serviceId" value="<?= (int)$serviceId ?>">
                                <input type="hidden" name="customerId" value="<?= (int)$customerId ?>">
                                <input type="hidden" name="specialistId" value="<?= (int)$item['specialist']['id'] ?>">
                                <input type="hidden" name="startsAt" value="<?= htmlspecialchars($slot['startsAt']) ?>">
                                <input type="hidden" name="endsAt" value="<?= htmlspecialchars($slot['endsAt']) ?>">

                                <button type="submit" class="btn btn-outline-light btn-sm">
                                    <?= htmlspecialchars(date('H:i', strtotime($slot['startsAt']))) ?>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>


