<?php

use App\ViewModels\AppointmentDetailViewModel;

/** @var AppointmentDetailViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$appointment = $vm->appointment;

?>

<p>
    <a href="/appointments">&larr; Back to appointments</a>
</p>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<p><strong>Salon:</strong> <?= htmlspecialchars($vm->salonName) ?></p>
<p><strong>Service:</strong> <?= htmlspecialchars($vm->serviceName) ?></p>
<p><strong>Specialist:</strong> <?= htmlspecialchars($vm->specialistName) ?></p>
<p><strong>Customer:</strong> <?= htmlspecialchars($vm->customerName) ?></p>

<p><strong>Start:</strong> <?= htmlspecialchars($vm->appointment->startsAt) ?></p>
<p><strong>End:</strong> <?= htmlspecialchars($vm->appointment->endsAt) ?></p>


<?php if ($vm->canManage) : ?>
    <p>
        <a href="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/edit">Edit</a>

    <form action="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/delete"
          method="post" style="display:inline"
          onsubmit="return confirm('Delete this appointment?');">
        <button type="submit">Cancel/Delete</button>
    </form>
    </p>
<?php endif; ?>
<?php if ($vm->isCustomer && $vm->canCancel) : ?>
    <form action="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/cancel"
          method="post"
          onsubmit="return confirm('Cancel this appointment?');">
        <button type="submit">Cancel</button>
    </form>
<?php endif; ?>



<?php require __DIR__ . '/../partials/footer.php'; ?>


