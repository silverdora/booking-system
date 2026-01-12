<?php
/** @var \App\Models\SalonModel $salon */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit salon</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<p><a href="/salons/<?= htmlspecialchars((string)$salon->id) ?>">&larr; Back to salon</a></p>
<h1>Edit salon</h1>

<form action="/salons/<?= htmlspecialchars((string)$salon->id) ?>/edit" method="post">
    <label for="name">Name*</label>
    <input id="name" name="name" required value="<?= htmlspecialchars($salon->name) ?>">

    <label for="type">Type</label>
    <input id="type" name="type" value="<?= htmlspecialchars($salon->type) ?>">

    <label for="address">Address*</label>
    <input id="address" name="address" required value="<?= htmlspecialchars($salon->address) ?>">

    <label for="city">City*</label>
    <input id="city" name="city" required value="<?= htmlspecialchars($salon->city) ?>">

    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel" value="<?= htmlspecialchars($salon->phone) ?>">

    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="<?= htmlspecialchars($salon->email) ?>">

    <button type="submit">Save changes</button>
</form>
</body>
</html>
