<?php require __DIR__ . '/../partials/header.php'; ?>

<p><a href="/appointments/receptionist/create">&larr; Back</a></p>
<h1>Choose a date</h1>
<p>Step 2 — choose date</p>

<form action="/appointments/receptionist/slots" method="get">
    <input type="hidden" name="serviceId" value="<?= (int)$serviceId ?>">
    <input type="hidden" name="customerId" value="<?= (int)$customerId ?>">

    <label for="date">Date*</label>
    <input id="date" name="date" type="date" required>

    <button type="submit">Next: show slots</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>

