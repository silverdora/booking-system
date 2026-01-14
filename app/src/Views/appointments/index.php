<?php

use App\ViewModels\AppointmentsViewModel;

/** @var AppointmentsViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';


?>

<?php if ($vm->showBackToSalonLink && $vm->salonId !== null) : ?>
    <p>
        <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>">&larr; Back to salon</a>
    </p>
<?php endif; ?>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<?php if ($vm->primaryActionUrl && $vm->primaryActionText) : ?>
    <p><a href="<?= htmlspecialchars($vm->primaryActionUrl) ?>"><?= htmlspecialchars($vm->primaryActionText) ?></a></p>
<?php endif; ?>

<?php if (count($vm->appointments)>0) : ?>
    <ul>
        <?php foreach ($vm->appointments as $item) : ?>
            <?php $a = $item->appointment; ?>
            <li>
                <strong>
                    <a href="/appointments/<?= htmlspecialchars((string)$a->id) ?>">
                        <?= htmlspecialchars($item->serviceName) ?> — #<?= htmlspecialchars((string)$a->id) ?>
                    </a>
                </strong>

                <div>
                    Salon: <?= htmlspecialchars($item->salonName) ?>
                </div>

                <div>
                    Specialist: <?= htmlspecialchars($item->specialistName) ?> |
                    Customer: <?= htmlspecialchars($item->customerName) ?>
                </div>

                <div>
                    <?= htmlspecialchars($a->startsAt) ?> → <?= htmlspecialchars($a->endsAt) ?>
                </div>

                <?php if ($vm->canManage) : ?>
                    <p>
                        <a href="/appointments/<?= htmlspecialchars((string)$a->id) ?>/edit">Edit</a>

                    <form action="/appointments/<?= htmlspecialchars((string)$a->id) ?>/delete"
                          method="post" style="display:inline"
                          onsubmit="return confirm('Delete this appointment?');">
                        <button type="submit">Cancel/Delete</button>
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


