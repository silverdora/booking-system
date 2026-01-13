<?php require __DIR__ . '/../partials/header.php'; ?>

<p><a href="/salons/<?= (int)$salonId ?>">&larr; Back to salon</a></p>
<h1>Choose a service</h1>

<?php if (!empty($services)) : ?>
    <ul>
        <?php foreach ($services as $s) : ?>
            <li>
                <a href="/salons/<?= (int)$salonId ?>/book/date?serviceId=<?= (int)$s['id'] ?>">
                    <?= htmlspecialchars($s['name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No services found.</p>
<?php endif; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>


