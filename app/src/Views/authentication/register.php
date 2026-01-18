<?php
use App\ViewModels\RegisterViewModel;
/** @var RegisterViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <div class="card mt-5">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">Register</h1>

                <?php if ($vm->error !== ''): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($vm->error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="/register" novalidate>
                    <div class="mb-3">
                        <label for="role" class="form-label">Register as</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="customer" <?= ($vm->role ?? 'customer') === 'customer' ? 'selected' : '' ?>>Customer</option>
                            <option value="owner" <?= ($vm->role ?? 'customer') === 'owner' ? 'selected' : '' ?>>Salon owner</option>
                        </select>

                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="firstName" class="form-label">First name</label>
                            <input id="firstName" name="firstName" class="form-control" required
                                   value="<?= htmlspecialchars($vm->firstName ?? '') ?>">

                        </div>

                        <div class="col-12 col-md-6">
                            <label for="lastName" class="form-label">Last name</label>
                            <input id="lastName" name="lastName" class="form-control" required
                                   value="<?= htmlspecialchars($vm->lastName ?? '') ?>">
                        </div>
                    </div>

                    <div class="mt-3 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control" required
                               value="<?= htmlspecialchars($vm->email ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone"
                               name="phone"
                               type="tel"
                               class="form-control"
                               required
                               value="<?= htmlspecialchars($vm->phone ?? '') ?>">
                    </div>


                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" name="password" type="password" minlength="8" class="form-control" required>
                        <div class="form-text text-light">Minimum 8 characters.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Create account
                        </button>
                    </div>
                </form>

                <p class="text-center text-light mt-3 mb-0">
                    Already have an account?
                    <a href="/login" class="link-primary text-decoration-none fw-semibold">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

