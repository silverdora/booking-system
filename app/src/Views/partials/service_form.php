<?php
use App\ViewModels\SalonServiceFormViewModel;
/** @var SalonServiceFormViewModel $vm */
$service = $vm->service;
?>


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

    <label>Specialists*</label>

    <?php if (!empty($vm->specialistOptions)): ?>
        <ul style="list-style:none; padding-left:0;">
            <?php foreach ($vm->specialistOptions as $opt): ?>
                <?php $sid = (int)$opt['id']; ?>
                <li>
                    <label>
                        <input type="checkbox"
                               name="specialistIds[]"
                               value="<?= htmlspecialchars((string)$sid) ?>"
                                <?= $vm->isSelectedSpecialist($sid) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($opt['name']) ?>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No specialists available for this salon.</p>
    <?php endif; ?>

    <button type="submit"><?= $vm->isEdit ? 'Save changes' : 'Create service' ?></button>
</form>



