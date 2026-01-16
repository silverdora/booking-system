<?php
/** @var \App\ViewModels\SalonsViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">Salons</h1>

</div>

<?php if (!empty($vm->salons)) : ?>
    <div class="list-group">
        <?php foreach ($vm->salons as $salon) : ?>
            <a href="/salons/<?= htmlspecialchars((string)$salon->id) ?>"
               class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between align-items-start">
                    <div>
                        <h2 class="h5 mb-1"><?= htmlspecialchars($salon->name) ?></h2>
                        <div class="text-body-secondary small">
                            <?= htmlspecialchars($salon->type) ?> · <?= htmlspecialchars($salon->city) ?>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-secondary mb-0" role="alert">
        No salons have been registered yet.
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>




