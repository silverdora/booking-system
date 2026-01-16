<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/appointments/receptionist/create">&larr; Back</a>
</div>

<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-2">Choose a date</h1>
        <div class="text-muted-dark mb-3">Step 2 — choose date</div>

        <div class="d-flex gap-2 mb-4">
            <span class="badge text-bg-success">1</span>
            <span class="badge text-bg-primary">2</span>
            <span class="badge text-bg-secondary">3</span>
        </div>

        <form action="/appointments/receptionist/slots" method="get" class="row g-3" novalidate>
            <input type="hidden" name="serviceId" value="<?= (int)$serviceId ?>">
            <input type="hidden" name="customerId" value="<?= (int)$customerId ?>">

            <div class="col-12 col-md-6">
                <label for="date" class="form-label">Date</label>
                <input id="date" name="date" type="date" class="form-control" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    Next: show slots
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>


