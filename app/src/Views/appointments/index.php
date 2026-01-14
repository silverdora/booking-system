<?php

use App\ViewModels\AppointmentsViewModel;

/** @var AppointmentsViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$role = strtolower(trim((string)($_SESSION['user']['role'] ?? '')));
$isCustomer = ($role === 'customer');
?>

<?php if (!$isCustomer && $vm->salonId !== null) : ?>
    <p>
        <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>">&larr; Back to salon</a>
    </p>
<?php endif; ?>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<p>
    <?php if ($isCustomer) : ?>
        <a href="/salons">Book new appointment</a>
    <?php else : ?>
        <a href="/appointments/create">Create appointment</a>
    <?php endif; ?>
</p>

<?php if (!empty($vm->appointments)) : ?>
    <ul>
        <?php foreach ($vm->appointments as $appointment) : ?>
            <li>
                <strong>
                    <a href="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>">
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

                <?php if (!$isCustomer) : ?>
                    <p>
                        <a href="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/edit">Edit</a>

                    <form action="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/delete"
                          method="post" style="display:inline"
                          onsubmit="return confirm('Delete this appointment?');">
                        <button type="submit">Delete</button>
                    </form>
                    </p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No appointments found.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>


