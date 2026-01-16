<?php
use App\ViewModels\AppointmentsFormViewModel;
/** @var AppointmentsFormViewModel $vm */

$appointment = $vm->appointment;
?>



<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/appointments">&larr; Back to appointments</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-7">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center"><?= htmlspecialchars($vm->title) ?></h1>

                <?php if (!empty($vm->errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <div class="fw-semibold mb-2">Please fix the following:</div>
                        <ul class="mb-0">
                            <?php foreach ($vm->errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= htmlspecialchars($vm->action) ?>" method="post" novalidate>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="serviceId" class="form-label">Service</label>
                            <select id="serviceId" name="serviceId" class="form-select" required>
                                <option value="">-- Select service --</option>
                                <?php foreach ($vm->services as $s): ?>
                                    <option value="<?= htmlspecialchars((string)$s['id']) ?>"
                                            data-duration="<?= htmlspecialchars((string)($s['durationMinutes'] ?? 0)) ?>"
                                            <?= ((int)($appointment->serviceId ?? 0) === (int)$s['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="specialistId" class="form-label">Specialist</label>
                            <select id="specialistId" name="specialistId" class="form-select" required>
                                <option value="">-- Select specialist --</option>
                                <?php foreach ($vm->specialists as $sp): ?>
                                    <option value="<?= htmlspecialchars((string)$sp['id']) ?>"
                                            <?= ((int)($appointment->specialistId ?? 0) === (int)$sp['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sp['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="customerId" class="form-label">Customer</label>
                            <select id="customerId" name="customerId" class="form-select" required>
                                <option value="">-- Select customer --</option>
                                <?php foreach ($vm->customers as $c): ?>
                                    <option value="<?= htmlspecialchars((string)$c['id']) ?>"
                                            <?= ((int)($appointment->customerId ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="startsAt" class="form-label">Start time</label>
                            <input id="startsAt"
                                   name="startsAt"
                                   type="datetime-local"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars(
                                           !empty($appointment->startsAt)
                                                   ? date('Y-m-d\TH:i', strtotime($appointment->startsAt))
                                                   : ''
                                   ) ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="endsAtPreview" class="form-label">End time (auto)</label>
                            <input id="endsAtPreview"
                                   type="datetime-local"
                                   class="form-control"
                                   value="<?= htmlspecialchars(
                                           !empty($appointment->endsAt)
                                                   ? date('Y-m-d\TH:i', strtotime($appointment->endsAt))
                                                   : ''
                                   ) ?>"
                                   readonly
                                   disabled>
                        </div>

                        <div class="col-12">
                            <div id="durationHint" class="form-text">
                                End time is calculated automatically based on service duration.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <?= $vm->isEdit ? 'Save changes' : 'Create appointment' ?>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const serviceSelect = document.getElementById('serviceId');
        const startsAtInput = document.getElementById('startsAt');
        const endsPreview = document.getElementById('endsAtPreview');
        const hint = document.getElementById('durationHint');

        function getSelectedDurationMinutes() {
            const opt = serviceSelect.options[serviceSelect.selectedIndex];
            if (!opt) return 0;
            const mins = parseInt(opt.getAttribute('data-duration') || '0', 10);
            return Number.isFinite(mins) ? mins : 0;
        }

        function formatForDatetimeLocal(date) {
            const pad = (n) => String(n).padStart(2, '0');
            return date.getFullYear() + '-' +
                pad(date.getMonth() + 1) + '-' +
                pad(date.getDate()) + 'T' +
                pad(date.getHours()) + ':' +
                pad(date.getMinutes());
        }

        function recalcEnd() {
            const mins = getSelectedDurationMinutes();
            const startVal = startsAtInput.value;
            if (!mins || !startVal) {
                endsPreview.value = '';
                if (hint) hint.textContent = 'End time is calculated automatically based on service duration.';
                return;
            }

            const start = new Date(startVal);
            if (isNaN(start.getTime())) {
                endsPreview.value = '';
                if (hint) hint.textContent = 'Invalid start time.';
                return;
            }

            const end = new Date(start.getTime() + mins * 60000);
            endsPreview.value = formatForDatetimeLocal(end);
            if (hint) hint.textContent = 'Duration: ' + mins + ' minutes.';
        }

        serviceSelect.addEventListener('change', recalcEnd);
        startsAtInput.addEventListener('change', recalcEnd);
        startsAtInput.addEventListener('input', recalcEnd);

        recalcEnd();
    })();
</script>





