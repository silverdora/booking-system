<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/appointments">&larr; Back to appointments</a>
</div>

<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-2">Create appointment</h1>
        <div class="text-muted-dark mb-3">
            Step 1 — choose customer and service
        </div>


        <div class="d-flex gap-2 mb-4">
            <span class="badge text-bg-primary">1</span>
            <span class="badge text-bg-secondary">2</span>
            <span class="badge text-bg-secondary">3</span>
        </div>

        <form action="/appointments/receptionist/date" method="get" class="row g-3" novalidate>
            <div class="col-12">
                <label for="customerId" class="form-label">Customer</label>
                <select id="customerId" name="customerId" class="form-select" required>
                    <option value="">-- select customer --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= (int)$c['id'] ?>">
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label for="serviceId" class="form-label">Service</label>
                <select id="serviceId" name="serviceId" class="form-select" required>
                    <option value="">-- select service --</option>
                    <?php foreach ($services as $s): ?>
                        <option value="<?= (int)$s['id'] ?>">
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    Next: choose date
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>


