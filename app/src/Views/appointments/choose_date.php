<?php require __DIR__ . '/../partials/header.php'; ?>

<p><a href="/salons/<?= (int)$salonId ?>/book">&larr; Back to services</a></p>
<h1>Choose a date</h1>

<form action="/salons/<?= (int)$salonId ?>/book/slots" method="get">
    <input type="hidden" name="serviceId" value="<?= (int)$serviceId ?>">

    <label for="date">Date*</label>
    <input id="date" name="date" type="date" required>

    <button type="submit">Show slots</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>


