<?php
use App\ViewModels\SalonServices\SalonServiceDetailViewModel;
/** @var SalonServiceDetailViewModel $vm */
$service = $vm->service;
?>

<?php require __DIR__ . '/../../partials/header.php'; ?>
<p><a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services">&larr; Back to services</a></p>

<h1><?= htmlspecialchars($service->name) ?></h1>

<?php if ($service->price !== null) : ?>
    <p><strong>Price:</strong> €<?= htmlspecialchars(number_format($service->price, 2)) ?></p>
<?php endif; ?>

<?php if ($service->durationMinutes !== null) : ?>
    <p><strong>Duration:</strong> <?= htmlspecialchars((string)$service->durationMinutes) ?> minutes</p>
<?php endif; ?>
<?php if (!empty($vm->specialists)) : ?>
    <p><strong>Specialists:</strong></p>
    <ul>
        <?php foreach ($vm->specialists as $s): ?>
            <li><?= htmlspecialchars($s['name']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p><strong>Specialists:</strong> none assigned</p>
<?php endif; ?>


<p>
    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/edit">Edit</a>
</p>

<form action="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/delete"
      method="post"
      onsubmit="return confirm('Delete this service?');">
    <button type="submit">Delete</button>
</form>

<?php require __DIR__ . '/../../partials/footer.php'; ?>

