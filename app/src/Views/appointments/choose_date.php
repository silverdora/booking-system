<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/salons/<?= (int)$salonId ?>/book">&larr; Back to services</a>
</div>

<div class="card">
    <div class="card-body">
        <h1 class="h4 mb-3">Choose a date</h1>

        <div class="d-flex gap-2 mb-4">
            <span class="badge text-bg-success">1</span>
            <span class="badge text-bg-primary">2</span>
            <span class="badge text-bg-secondary">3</span>
        </div>

        <form action="/salons/<?= (int)$salonId ?>/book/slots" method="get" class="row g-3">
            <input type="hidden" name="serviceId" value="<?= (int)$serviceId ?>">

            <div class="col-12 col-md-6">
                <label for="date" class="form-label">Date</label>
                <input id="date" name="date" type="date" class="form-control" required>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    Show slots
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
