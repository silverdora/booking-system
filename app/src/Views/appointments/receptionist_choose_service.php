<?php require __DIR__ . '/../partials/header.php'; ?>

<p><a href="/appointments">&larr; Back to appointments</a></p>
<h1>Create appointment</h1>
<p>Step 1 — choose customer and service</p>

<form action="/appointments/receptionist/date" method="get">
    <label for="customerId">Customer*</label>
    <select id="customerId" name="customerId" required>
        <option value="">-- select customer --</option>
        <?php foreach ($customers as $c): ?>
            <option value="<?= (int)$c['id'] ?>">
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="serviceId">Service*</label>
    <select id="serviceId" name="serviceId" required>
        <option value="">-- select service --</option>
        <?php foreach ($services as $s): ?>
            <option value="<?= (int)$s['id'] ?>">
                <?= htmlspecialchars($s['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Next: choose date</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>

