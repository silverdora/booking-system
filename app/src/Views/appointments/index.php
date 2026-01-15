<?php

use App\ViewModels\AppointmentsViewModel;

/** @var AppointmentsViewModel $vm */
$title = $vm->title;?>

<?php require __DIR__ . '/../partials/header.php'; ?>



<?php if ($vm->showBackToSalonLink && $vm->salonId !== null) : ?>
    <p>
        <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>">&larr; Back to salon</a>
    </p>
<?php endif; ?>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<?php if ($vm->canCreate && $vm->primaryActionUrl && $vm->primaryActionText) : ?>
    <p><a href="<?= htmlspecialchars($vm->primaryActionUrl) ?>"><?= htmlspecialchars($vm->primaryActionText) ?></a></p>
<?php endif; ?>



<?php if (!$vm->isCustomer) : ?>
    <?php
    $view = $vm->viewMode ?? 'week';
    $date = $vm->baseDate ?? date('Y-m-d');

    $staffUrl = $vm->ownerLinks['staff'] ?? '';
    $servicesUrl = $vm->ownerLinks['services'] ?? '';
    $editSalonUrl = $vm->ownerLinks['editSalon'] ?? '';

    $schedule = $vm->schedule ?? [];
    $days = $vm->days ?? [];
    $times = $vm->times ?? [];
    ?>

    <div style="margin: 12px 0; padding: 10px; border: 1px solid #ddd;">
        <div>
            <a href="/appointments?view=day&date=<?= htmlspecialchars($date) ?>"
               style="<?= $view === 'day' ? 'font-weight:bold;' : '' ?>">Day</a>
            |
            <a href="/appointments?view=week&date=<?= htmlspecialchars($date) ?>"
               style="<?= $view === 'week' ? 'font-weight:bold;' : '' ?>">Week</a>

            <form method="get" action="/appointments" style="display:inline-block; margin-left: 12px;">
                <input type="hidden" name="view" value="<?= htmlspecialchars($view) ?>">
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
                <button type="submit">Go</button>
            </form>
        </div>

        <?php if (!empty($vm->ownerLinks)) : ?>
            <div style="margin-top: 8px;">
                <a href="<?= htmlspecialchars($staffUrl) ?>">Staff</a> |
                <a href="<?= htmlspecialchars($servicesUrl) ?>">Services</a> |
                <a href="<?= htmlspecialchars($editSalonUrl) ?>">Edit salon</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (count($vm->appointments) === 0) : ?>
        <p>No appointments found for selected period.</p>
    <?php else : ?>
        <table border="1" cellspacing="0" cellpadding="6" style="width:100%; border-collapse:collapse;">
            <thead>
            <tr>
                <th style="width:80px;">Time</th>
                <?php foreach ($days as $day) : ?>
                    <th><?= htmlspecialchars($day) ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($times as $time) : ?>
                <tr>
                    <td><strong><?= htmlspecialchars($time) ?></strong></td>

                    <?php foreach ($days as $day) : ?>
                        <td style="vertical-align:top; min-width:200px;">
                            <?php foreach (($schedule[$day][$time] ?? []) as $item) : ?>
                                <?php $a = $item->appointment; ?>
                                <?php
                                $id = (int)$a->id;
                                $start = date('H:i', strtotime((string)$a->startsAt));
                                $end   = date('H:i', strtotime((string)$a->endsAt));
                                ?>

                                <div style="border:1px solid #ccc; padding:6px; margin-bottom:6px;">
                                    <div><strong><?= htmlspecialchars($start) ?>–<?= htmlspecialchars($end) ?></strong></div>
                                    <div><?= htmlspecialchars($item->serviceName) ?> — #<?= htmlspecialchars((string)$a->id) ?></div>
                                    <div>Specialist: <?= htmlspecialchars($item->specialistName) ?></div>
                                    <div>Customer: <?= htmlspecialchars($item->customerName) ?></div>

                                    <div style="margin-top:4px;">
                                        <a href="/appointments/<?= htmlspecialchars((string)$a->id) ?>">Open</a>

                                        <?php if ($vm->canManage) : ?>
                                            | <a href="/appointments/<?= htmlspecialchars((string)$a->id) ?>/edit">Edit</a>

                                            <form action="/appointments/<?= htmlspecialchars((string)$a->id) ?>/delete"
                                                  method="post" style="display:inline"
                                                  onsubmit="return confirm('Delete this appointment?');">
                                                <button type="submit">Cancel/Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>


<?php else : ?>


    <?php if (count($vm->appointments) > 0) : ?>
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
                        <?= htmlspecialchars((string)$a->startsAt) ?> → <?= htmlspecialchars((string)$a->endsAt) ?>
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

                    <?php if ($vm->isCustomer && $vm->canCancel) : ?>
                        <form action="/appointments/<?= htmlspecialchars((string)$a->id) ?>/cancel"
                              method="post" style="display:inline"
                              onsubmit="return confirm('Cancel this appointment?');">
                            <button type="submit">Cancel</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No appointments found.</p>
    <?php endif; ?>

<?php endif; ?>


<?php require __DIR__ . '/../partials/footer.php'; ?>
