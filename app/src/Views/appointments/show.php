<?php

use App\ViewModels\AppointmentDetailViewModel;

/** @var AppointmentDetailViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$appointment = $vm->appointment;
$isCustomer = $vm->isCustomer;
?>

<p>
    <a href="/appointments">&larr; Back to appointments</a>
</p>

<h1><?= htmlspecialchars("Appointment #{$appointment->id}") ?></h1>

<p><strong>Start:</strong> <?= htmlspecialchars($appointment->startsAt) ?></p>
<p><strong>End:</strong> <?= htmlspecialchars($appointment->endsAt) ?></p>

<p><strong>Service ID:</strong> <?= htmlspecialchars((string)$appointment->serviceId) ?></p>
<p><strong>Specialist ID:</strong> <?= htmlspecialchars((string)$appointment->specialistId) ?></p>
<p><strong>Customer ID:</strong> <?= htmlspecialchars((string)$appointment->customerId) ?></p>

<?php if (!$isCustomer) : ?>
    <p>
        <a href="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/edit">Edit</a>
    </p>

    <form action="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/delete"
          method="post"
          onsubmit="return confirm('Delete this appointment?');">
        <button type="submit">Delete</button>
    </form>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>


