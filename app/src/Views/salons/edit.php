<?php
/** @var \App\Models\SalonModel $salon */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none" href="/appointments">&larr; Back to salon management</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-7">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">Edit salon</h1>

                <form action="/salons/<?= htmlspecialchars((string)$salon->id) ?>/edit" method="post" novalidate>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Name</label>
                            <input id="name"
                                   name="name"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($salon->name) ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="type" class="form-label">Type</label>
                            <input id="type"
                                   name="type"
                                   class="form-control"
                                   value="<?= htmlspecialchars($salon->type) ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input id="city"
                                   name="city"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($salon->city) ?>">
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <input id="address"
                                   name="address"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($salon->address) ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone"
                                   name="phone"
                                   type="tel"
                                   class="form-control"
                                   value="<?= htmlspecialchars($salon->phone) ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   class="form-control"
                                   value="<?= htmlspecialchars($salon->email) ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

