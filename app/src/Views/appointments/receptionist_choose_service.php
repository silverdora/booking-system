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
            <div class="d-flex justify-content gap-2 mt-4">
            <button id="new-form-button" class="btn btn-primary">Add new customer</button>
            </div>

            <div  id="add-new-customer-form" class="row justify-content-center" hidden="">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h2 class="h4 mb-3 text-center">
                                Add new user
                            </h2>

                            <form novalidate>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="firstName" class="form-label">First name</label>
                                        <input id="firstName"
                                               name="firstName"
                                               class="form-control"
                                               required
                                               value="">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="lastName" class="form-label">Last name</label>
                                        <input id="lastName"
                                               name="lastName"
                                               class="form-control"
                                               required
                                               value="">
                                    </div>

                                    <div class="col-12">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email"
                                               name="email"
                                               type="email"
                                               class="form-control"
                                               required
                                               value="">
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password"
                                               name="password"
                                               type="password"
                                               class="form-control"
                                               autocomplete="new-password" value="">
                                    </div>

                                    <div class="col-12">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input id="phone"
                                               name="phone"
                                               type="tel"
                                               class="form-control"
                                               value="">
                                    </div>



                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button id="add-new-customer" class="btn btn-primary">
                                        Create customer
                                    </button>
                                    <button id="cancel-new-customer" class="btn btn-light">Cancel</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
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

<script src="/assets/js/add_new_customer.js"></script>
<?php require __DIR__ . '/../partials/footer.php'; ?>



