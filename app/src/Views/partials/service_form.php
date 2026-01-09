<?php
use App\ViewModels\SalonServiceFormViewModel;
/** @var SalonServiceFormViewModel $vm */
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

<h1><?= htmlspecialchars($vm->title) ?></h1>

<form action="<?= htmlspecialchars($vm->action) ?>" method="post">
    <label for="name">Name*</label>
    <input id="name" name="name" required value="<?= htmlspecialchars($service->name ?? '') ?>">

    <label for="price">Price (€)</label>
    <input id="price" name="price" type="number" step="0.01" min="0"
           value="<?= htmlspecialchars($service->price !== null ? (string)$service->price : '') ?>">

    <label for="durationMinutes">Duration (minutes)</label>
    <input id="durationMinutes" name="durationMinutes" type="number" min="0"
           value="<?= htmlspecialchars($service->durationMinutes !== null ? (string)$service->durationMinutes : '') ?>">

    <button type="submit"><?= $vm->isEdit ? 'Save changes' : 'Create service' ?></button>
</form>

</body>
</html>

