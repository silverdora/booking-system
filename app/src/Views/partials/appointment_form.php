<?php
use App\ViewModels\AppointmentsFormViewModel;
/** @var AppointmentsFormViewModel $vm */

$appointment = $vm->appointment;
?>
<p>
    <a href="/appointments">
        &larr; Back to appointments
    </a>
</p>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<?php if (!empty($vm->errors)): ?>
    <div class="form-errors" role="alert">
        <p><strong>Please fix the following:</strong></p>
        <ul>
            <?php foreach ($vm->errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= htmlspecialchars($vm->action) ?>" method="post">

    <label for="serviceId">Service*</label>
    <select id="serviceId" name="serviceId" required>
        <option value="">-- Select service --</option>
        <?php foreach ($vm->services as $s): ?>
            <option value="<?= htmlspecialchars((string)$s['id']) ?>"
                    data-duration="<?= htmlspecialchars((string)($s['durationMinutes'] ?? 0)) ?>"
                    <?= ((int)($appointment->serviceId ?? 0) === (int)$s['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="specialistId">Specialist*</label>
    <select id="specialistId" name="specialistId" required>
        <option value="">-- Select specialist --</option>
        <?php foreach ($vm->specialists as $sp): ?>
            <option value="<?= htmlspecialchars((string)$sp['id']) ?>"
                    <?= ((int)($appointment->specialistId ?? 0) === (int)$sp['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($sp['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="customerId">Customer*</label>
    <select id="customerId" name="customerId" required>
        <option value="">-- Select customer --</option>
        <?php foreach ($vm->customers as $c): ?>
            <option value="<?= htmlspecialchars((string)$c['id']) ?>"
                    <?= ((int)($appointment->customerId ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="startsAt">Start time*</label>
    <input id="startsAt"
           name="startsAt"
           type="datetime-local"
           required
           value="<?= htmlspecialchars(
                   !empty($appointment->startsAt)
                           ? date('Y-m-d\TH:i', strtotime($appointment->startsAt))
                           : ''
           ) ?>">

    <label for="endsAtPreview">End time (auto)</label>
    <input id="endsAtPreview"
           type="datetime-local"
           value="<?= htmlspecialchars(
                   !empty($appointment->endsAt)
                           ? date('Y-m-d\TH:i', strtotime($appointment->endsAt))
                           : ''
           ) ?>"
           readonly
           disabled>

    <small id="durationHint">End time is calculated automatically based on service duration.</small>


    <button type="submit">
        <?= $vm->isEdit ? 'Save changes' : 'Create appointment' ?>
    </button>
</form>
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
            const startVal = startsAtInput.value; // "YYYY-MM-DDTHH:MM"
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

        // initial calculation for edit-form (pre-selected service + startsAt)
        recalcEnd();
    })();
</script>


