<?php
$title = 'Salon staff';
require __DIR__ . '/../../partials/header.php';
?>

<p><a href="/salons/<?= htmlspecialchars((string)$salonId) ?>">&larr; Back to salon</a></p>

<h1>Staff</h1>

<p>
    <a href="/users/specialists/create">Add specialist</a> |
    <a href="/users/receptionists/create">Add receptionist</a>
</p>

<h2>Specialists</h2>
<?php if (!empty($specialists)): ?>
    <ul>
        <?php foreach ($specialists as $u): ?>
            <li>
                <a href="/users/specialists/<?= htmlspecialchars((string)$u->id) ?>">
                    <?= htmlspecialchars($u->firstName . ' ' . $u->lastName) ?>
                </a>
                (<?= htmlspecialchars($u->email) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No specialists yet.</p>
<?php endif; ?>

<h2>Receptionists</h2>
<?php if (!empty($receptionists)): ?>
    <ul>
        <?php foreach ($receptionists as $u): ?>
            <li>
                <a href="/users/receptionists/<?= htmlspecialchars((string)$u->id) ?>">
                    <?= htmlspecialchars($u->firstName . ' ' . $u->lastName) ?>
                </a>
                (<?= htmlspecialchars($u->email) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No receptionists yet.</p>
<?php endif; ?>

<?php require __DIR__ . '/../../partials/footer.php'; ?>

