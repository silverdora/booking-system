<?php
/** @var \App\ViewModels\SalonDetailViewModel $vm */
$salon = $vm->salon;
?>


<?php require __DIR__ . '/../partials/header.php'; ?>
<p><a href="/salons">&larr; Back to all salons</a></p>
<a href="/salons/<?= htmlspecialchars((string)$salon->id) ?>/edit">Edit</a>
<h1><?= htmlspecialchars($salon->name) ?></h1>

<a href="/salons/<?= (int)$salon->id ?>/book">Book an appointment</a>

<?php if (!empty($salon->type)) : ?>
    <p><strong>Type:</strong> <?= htmlspecialchars($salon->type) ?></p>
<?php endif; ?>

<p><strong>Address:</strong>
    <?= htmlspecialchars($salon->address) ?>,
    <?= htmlspecialchars($salon->city) ?>
</p>

<?php if (!empty($salon->phone)) : ?>
    <p><strong>Phone:</strong> <?= htmlspecialchars($salon->phone) ?></p>
<?php endif; ?>

<?php if (!empty($salon->email)) : ?>
    <p><strong>Email:</strong> <?= htmlspecialchars($salon->email) ?></p>
<?php endif; ?>

<?php if (!empty($salon->description)) : ?>
    <h2>Description</h2>
    <p><?= nl2br(htmlspecialchars($salon->description)) ?></p>
<?php endif; ?>

<p>
    <a href="/salons/<?= htmlspecialchars((string)$salon->id) ?>/edit">Edit</a>
</p>

<form action="/salons/<?= htmlspecialchars((string)$salon->id) ?>/delete" method="post"
      onsubmit="return confirm('Delete this salon?');">
    <button type="submit">Delete</button>
</form>

</body>
</html>


