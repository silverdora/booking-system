<?php

use App\ViewModels\AppointmentsViewModel;

/** @var AppointmentsViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';
?>
<p>
    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>">&larr; Back to salon</a>
</p>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<p>
    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/appointments/create">Create appointment</a>
</p>

<?php if (!empty($vm->appointments)) : ?>
    <ul>
        <?php foreach ($vm->appointments as $appointment) : ?>
            <li>
                <strong>
                    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/appointments/<?= htmlspecialchars((string)$appointment->id) ?>">
                        Appointment #<?= htmlspecialchars((string)$appointment->id) ?>
                    </a>
                </strong>

                <div>
                    Service ID: <?= htmlspecialchars((string)$appointment->serviceId) ?> |
                    Specialist ID: <?= htmlspecialchars((string)$appointment->specialistId) ?> |
                    Customer ID: <?= htmlspecialchars((string)$appointment->customerId) ?>
                </div>

                <div>
                    <?= htmlspecialchars($appointment->startsAt) ?> → <?= htmlspecialchars($appointment->endsAt) ?>

                </div>



                <p>
                    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/edit">Edit</a>

                <form action="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/delete"
                      method="post" style="display:inline"
                      onsubmit="return confirm('Delete this appointment?');">
                    <button type="submit">Delete</button>
                </form>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No appointments found.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>

