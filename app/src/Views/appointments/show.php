<?php

use App\ViewModels\AppointmentDetailViewModel;

/** @var AppointmentDetailViewModel $vm */
$title = $vm->title;

require __DIR__ . '/../partials/header.php';

$appointment = $vm->appointment;

?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/appointments">&larr; Back to appointments</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-start">
            <div>
                <h1 class="h4 mb-1"><?= htmlspecialchars($vm->title) ?></h1>
            </div>

            <?php if ($vm->canManage) : ?>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-light btn-sm"
                       href="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/edit">
                        Edit
                    </a>

                    <form action="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/delete"
                          method="post"
                          class="m-0"
                          onsubmit="return confirm('Delete this appointment?');">
                        <button type="submit" class="btn btn-danger btn-sm">
                            Cancel/Delete
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <hr>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="fw-semibold">Salon</div>
                <div><?= htmlspecialchars($vm->salonName) ?></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="fw-semibold">Service</div>
                <div><?= htmlspecialchars($vm->serviceName) ?></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="fw-semibold">Specialist</div>
                <div><?= htmlspecialchars($vm->specialistName) ?></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="fw-semibold">Customer</div>
                <div><?= htmlspecialchars($vm->customerName) ?></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="fw-semibold">Start</div>
                <div><?= htmlspecialchars($vm->appointment->startsAt) ?></div>
            </div>

            <div class="col-12 col-md-6">
                <div class="fw-semibold">End</div>
                <div><?= htmlspecialchars($vm->appointment->endsAt) ?></div>
            </div>
        </div>

        <?php if ($vm->isCustomer && $vm->canCancel) : ?>
            <hr>
            <form action="/appointments/<?= htmlspecialchars((string)$appointment->id) ?>/cancel"
                  method="post"
                  class="m-0"
                  onsubmit="return confirm('Cancel this appointment?');">
                <button type="submit" class="btn btn-warning">
                    Cancel appointment
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>



