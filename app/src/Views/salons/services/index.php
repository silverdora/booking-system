<?php
use App\ViewModels\SalonServices\SalonServicesViewModel;
/** @var SalonServicesViewModel $vm */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($vm->title) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

<p><a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>">&larr; Back to salon</a></p>

<h1><?= htmlspecialchars($vm->title) ?></h1>

<p>
    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/create">Add service</a>
</p>

<?php if (!empty($vm->services)) : ?>
    <ul>
        <?php foreach ($vm->services as $service) : ?>
            <li>
                <h2>
                    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>">
                        <?= htmlspecialchars($service->name) ?>
                    </a>
                </h2>

                <?php if ($service->price !== null) : ?>
                    <p><strong>Price:</strong> €<?= htmlspecialchars(number_format($service->price, 2)) ?></p>
                <?php endif; ?>

                <?php if ($service->durationMinutes !== null) : ?>
                    <p><strong>Duration:</strong> <?= htmlspecialchars((string)$service->durationMinutes) ?> min</p>
                <?php endif; ?>

                <p>
                    <a href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/edit">Edit</a>

                <form action="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services/<?= htmlspecialchars((string)$service->id) ?>/delete"
                      method="post" style="display:inline"
                      onsubmit="return confirm('Delete this service?');">
                    <button type="submit">Delete</button>
                </form>
                </p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No services added yet.</p>
<?php endif; ?>

</body>
</html>

