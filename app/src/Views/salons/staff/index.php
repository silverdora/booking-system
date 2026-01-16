<?php
$title = 'Salon staff';
require __DIR__ . '/../../partials/header.php';
?>

<div class="mb-3">
    <a class="link-secondary text-decoration-none"
       href="/salons/<?= htmlspecialchars((string)$salonId) ?>">&larr; Back to salon</a>
</div>

<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Staff</h1>

    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-dark btn-sm" href="/users/specialists/create">Add specialist</a>
        <a class="btn btn-outline-dark btn-sm" href="/users/receptionists/create">Add receptionist</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h5 mb-0">Specialists</h2>
                    <span class="badge text-bg-secondary"><?= (int)count($specialists ?? []) ?></span>
                </div>

                <?php if (!empty($specialists)): ?>
                    <div class="list-group">
                        <?php foreach ($specialists as $u): ?>
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                               href="/users/specialists/<?= htmlspecialchars((string)$u->id) ?>">
                                <div>
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($u->firstName . ' ' . $u->lastName) ?>
                                    </div>
                                    <div class="small ">
                                        <?= htmlspecialchars($u->email) ?>
                                    </div>
                                </div>
                                <span class="text-light opacity-75">&rarr;</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary mb-0" role="alert">
                        No specialists yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h5 mb-0">Receptionists</h2>
                    <span class="badge text-bg-secondary"><?= (int)count($receptionists ?? []) ?></span>
                </div>

                <?php if (!empty($receptionists)): ?>
                    <div class="list-group">
                        <?php foreach ($receptionists as $u): ?>
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                               href="/users/receptionists/<?= htmlspecialchars((string)$u->id) ?>">
                                <div>
                                    <div class="fw-semibold ">
                                        <?= htmlspecialchars($u->firstName . ' ' . $u->lastName) ?>
                                    </div>
                                    <div class="small ">
                                        <?= htmlspecialchars($u->email) ?>
                                    </div>
                                </div>
                                <span class="text-light opacity-75">&rarr;</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary mb-0" role="alert">
                        No receptionists yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/footer.php'; ?>


