<?php

use App\ViewModels\AppointmentsViewModel;

/** @var AppointmentsViewModel $vm */
$title = $vm->title;
?>

<?php require __DIR__ . '/../partials/header.php'; ?>



<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0"><?= htmlspecialchars($vm->title) ?></h1>

    <?php if ($vm->canCreate && $vm->primaryActionUrl && $vm->primaryActionText) : ?>
        <a class="btn btn-primary btn-sm" href="<?= htmlspecialchars($vm->primaryActionUrl) ?>">
            <?= htmlspecialchars($vm->primaryActionText) ?>
        </a>
    <?php endif; ?>
</div>

<?php if (!$vm->isCustomer) : ?>
    <?php
    $view = $vm->viewMode ?? 'week';
    $date = $vm->baseDate ?? date('Y-m-d');

    $selectedDate = $date; //  YYYY-MM-DD
    $openedOnce = false;


    $staffUrl = $vm->ownerLinks['staff'] ?? '';
    $servicesUrl = $vm->ownerLinks['services'] ?? '';
    $editSalonUrl = $vm->ownerLinks['editSalon'] ?? '';

    $schedule = $vm->schedule ?? [];
    $days = $vm->days ?? [];
    $times = $vm->times ?? [];
    ?>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                <div class="btn-group" role="group" aria-label="View mode">
                    <a class="btn btn-sm <?= $view === 'day' ? 'btn-primary' : 'btn-outline-light' ?>"
                       href="/appointments?view=day&date=<?= htmlspecialchars($date) ?>">
                        Day
                    </a>
                    <a class="btn btn-sm <?= $view === 'week' ? 'btn-primary' : 'btn-outline-light' ?>"
                       href="/appointments?view=week&date=<?= htmlspecialchars($date) ?>">
                        Week
                    </a>
                </div>

                <form method="get" action="/appointments" class="d-flex gap-2 align-items-center">
                    <input type="hidden" name="view" value="<?= htmlspecialchars($view) ?>">
                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control form-control-sm">
                    <button type="submit" class="btn btn-sm btn-outline-light">Go</button>
                </form>
            </div>

            <?php if (!empty($vm->ownerLinks)) : ?>
                <hr class="my-3">
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-light btn-sm" href="<?= htmlspecialchars($staffUrl) ?>">Staff</a>
                    <a class="btn btn-outline-light btn-sm" href="<?= htmlspecialchars($servicesUrl) ?>">Services</a>
                    <a class="btn btn-outline-light btn-sm" href="<?= htmlspecialchars($editSalonUrl) ?>">Edit salon</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (count($vm->appointments) === 0) : ?>
        <div class="alert alert-secondary" role="alert">
            No appointments found for selected period.
        </div>
    <?php else : ?>

        <div class="accordion" id="appointmentsByDay">
            <?php foreach ($days as $dayIndex => $day) : ?>
                <?php
                $hasAny = false;
                foreach ($times as $time) {
                    if (!empty($schedule[$day][$time] ?? [])) { $hasAny = true; break; }
                }

                $collapseId = 'dayCollapse' . $dayIndex;
                $headingId  = 'dayHeading' . $dayIndex;
                $dayDate = null;
                if (preg_match('/\d{4}-\d{2}-\d{2}/', (string)$day, $m)) {
                    $dayDate = $m[0];
                }

                $matchesSelected = ($dayDate !== null)
                        ? ($dayDate === $selectedDate)
                        : (trim((string)$day) === trim((string)$selectedDate));

                $isOpen = (!$openedOnce && $matchesSelected);
                if ($isOpen) {
                    $openedOnce = true;
                }
                ?>

                <div class="accordion-item bg-dark border-secondary">
                    <h2 class="accordion-header" id="<?= $headingId ?>">
                        <button class="accordion-button <?= $isOpen ? '' : 'collapsed' ?> bg-dark text-light"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#<?= $collapseId ?>"
                                aria-expanded="<?= $isOpen ? 'true' : 'false' ?>"
                                aria-controls="<?= $collapseId ?>">

                        <span class="fw-semibold"><?= htmlspecialchars($day) ?></span>
                            <span class="ms-2 text-light opacity-75 small">
                                <?= $hasAny ? '' : '— No appointments' ?>
                            </span>
                        </button>
                    </h2>

                    <div id="<?= $collapseId ?>"
                         class="accordion-collapse collapse <?= $isOpen ? 'show' : '' ?>"
                         aria-labelledby="<?= $headingId ?>"
                         data-bs-parent="#appointmentsByDay">

                    <div class="accordion-body">

                            <?php if (!$hasAny): ?>
                                <div class="text-light opacity-75">No appointments for this day.</div>
                            <?php else: ?>

                                <?php foreach ($times as $time) : ?>
                                    <?php $items = $schedule[$day][$time] ?? []; ?>
                                    <?php if (empty($items)) continue; ?>

                                    <div class="mb-3">
                                        <div class="fw-semibold mb-2">
                                            <?= htmlspecialchars($time) ?>
                                        </div>

                                        <div class="d-flex flex-column gap-2">
                                            <?php foreach ($items as $item) : ?>
                                                <?php $a = $item->appointment; ?>
                                                <?php
                                                $start = date('H:i', strtotime((string)$a->startsAt));
                                                $end   = date('H:i', strtotime((string)$a->endsAt));
                                                ?>

                                                <div class="card bg-dark border-secondary">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    <?= htmlspecialchars($start) ?>–<?= htmlspecialchars($end) ?>
                                                                </div>
                                                                <div class="small text-light opacity-75">
                                                                    <?= htmlspecialchars($item->serviceName) ?> — #<?= htmlspecialchars((string)$a->id) ?>
                                                                </div>
                                                            </div>

                                                            <a class="btn btn-outline-light btn-sm"
                                                               href="/appointments/<?= htmlspecialchars((string)$a->id) ?>">
                                                                Open
                                                            </a>
                                                        </div>

                                                        <div class="small mt-2">
                                                            Specialist: <?= htmlspecialchars($item->specialistName) ?>
                                                        </div>
                                                        <div class="small">
                                                            Customer: <?= htmlspecialchars($item->customerName) ?>
                                                        </div>

                                                        <?php if ($vm->canManage) : ?>
                                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                                <a class="btn btn-outline-light btn-sm"
                                                                   href="/appointments/<?= htmlspecialchars((string)$a->id) ?>/edit">
                                                                    Edit
                                                                </a>

                                                                <form action="/appointments/<?= htmlspecialchars((string)$a->id) ?>/delete"
                                                                      method="post"
                                                                      onsubmit="return confirm('Delete this appointment?');"
                                                                      class="m-0">
                                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                                        Cancel/Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

<?php else : ?>

    <?php if (count($vm->appointments) > 0) : ?>
        <div class="list-group">
            <?php foreach ($vm->appointments as $item) : ?>
                <?php $a = $item->appointment; ?>
                <div class="list-group-item bg-dark border-secondary">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">
                                <a class="link-light text-decoration-none"
                                   href="/appointments/<?= htmlspecialchars((string)$a->id) ?>">
                                    <?= htmlspecialchars($item->serviceName) ?> — #<?= htmlspecialchars((string)$a->id) ?>
                                </a>
                            </div>

                            <div class="small text-light opacity-75">
                                Salon: <?= htmlspecialchars($item->salonName) ?>
                            </div>

                            <div class="small">
                                Specialist: <?= htmlspecialchars($item->specialistName) ?>
                            </div>

                            <div class="small text-light opacity-75">
                                <?= htmlspecialchars((string)$a->startsAt) ?> → <?= htmlspecialchars((string)$a->endsAt) ?>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <?php if ($vm->canManage) : ?>
                                <a class="btn btn-outline-light btn-sm"
                                   href="/appointments/<?= htmlspecialchars((string)$a->id) ?>/edit">
                                    Edit
                                </a>

                                <form action="/appointments/<?= htmlspecialchars((string)$a->id) ?>/delete"
                                      method="post"
                                      onsubmit="return confirm('Delete this appointment?');"
                                      class="m-0">
                                    <button type="submit" class="btn btn-danger btn-sm">Cancel/Delete</button>
                                </form>
                            <?php endif; ?>

                            <?php if ($vm->isCustomer && $vm->canCancel) : ?>
                                <form action="/appointments/<?= htmlspecialchars((string)$a->id) ?>/cancel"
                                      method="post"
                                      onsubmit="return confirm('Cancel this appointment?');"
                                      class="m-0">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="alert alert-secondary" role="alert">
            No appointments found.
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>


