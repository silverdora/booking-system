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

    <button type="submit">
        <?= $vm->isEdit ? 'Save changes' : 'Create appointment' ?>
    </button>
</form>


