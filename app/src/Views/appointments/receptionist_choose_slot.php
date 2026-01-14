<?php require __DIR__ . '/../partials/header.php'; ?>

<p>
    <a href="/appointments/receptionist/date?serviceId=<?= (int)$serviceId ?>&customerId=<?= (int)$customerId ?>">
        &larr; Back to date
    </a>
</p>

<h1>Choose a slot</h1>
<p>Step 3 — choose slot</p>

<?php if (empty($specialistsWithSlots)) : ?>
    <p>No specialists are assigned to this service yet.</p>
<?php endif; ?>

<?php foreach ($specialistsWithSlots as $item): ?>
    <h2><?= htmlspecialchars($item['specialist']['name']) ?></h2>

    <?php if (empty($item['slots'])): ?>
        <p>No available slots.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($item['slots'] as $slot): ?>
                <li>
                    <form action="/appointments/receptionist/confirm" method="post" style="display:inline">
                        <input type="hidden" name="serviceId" value="<?= (int)$serviceId ?>">
                        <input type="hidden" name="customerId" value="<?= (int)$customerId ?>">
                        <input type="hidden" name="specialistId" value="<?= (int)$item['specialist']['id'] ?>">
                        <input type="hidden" name="startsAt" value="<?= htmlspecialchars($slot['startsAt']) ?>">
                        <input type="hidden" name="endsAt" value="<?= htmlspecialchars($slot['endsAt']) ?>">

                        <button type="submit">
                            <?= htmlspecialchars(date('H:i', strtotime($slot['startsAt']))) ?>
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endforeach; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>

