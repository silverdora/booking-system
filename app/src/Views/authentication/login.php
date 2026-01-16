<?php
use App\ViewModels\LoginViewModel;
/** @var LoginViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="card mt-5">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">Login</h1>

                <?php if ($vm->error !== ''): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($vm->error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="/login" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                                id="email"
                                name="email"
                                type="email"
                                class="form-control"
                                required
                                autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control"
                                required
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </form>

                <p class="text-center text-light mt-3 mb-0">
                    No account?
                    <a href="/register" class="link-primary text-decoration-none fw-semibold">
                        Register
                    </a>
                </p>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>


