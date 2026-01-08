<?php
use App\ViewModels\SalonServices\SalonServiceDetailViewModel;
/** @var SalonServiceDetailViewModel $vm */
$service = $vm->service;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($vm->title) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

<p><a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services">&larr; Back to services</a></p>

<h1><?= htmlspecialchars($service->name) ?></h1>

<?php if ($service->price !== null) : ?>
    <p><strong>Price:</strong> €<?= htmlspecialchars(number_format($service->price, 2)) ?></p>
<?php endif; ?>

<?php if ($service->durationMinutes !== null) : ?>
    <p><strong>Duration:</strong> <?= htmlspecialchars((string)$service->durationMinutes) ?> minutes</p>
<?php endif; ?>

<p>
    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/edit">Edit</a>
</p>

<form action="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/delete"
      method="post"
      onsubmit="return confirm('Delete this service?');">
    <button type="submit">Delete</button>
</form>

</body>
</html>

