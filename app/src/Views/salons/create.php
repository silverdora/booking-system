<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create salon</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<p><a href="/salons">&larr; Back to salons</a></p>
<h1>Create a new salon</h1>

<form action="/salons/create" method="post">
    <label for="name">Name*</label>
    <input id="name" name="name" required>

    <label for="type">Type</label>
    <input id="type" name="type" placeholder="Barber / Beauty / Tattoo">

    <label for="address">Address*</label>
    <input id="address" name="address" required>

    <label for="city">City*</label>
    <input id="city" name="city" required>

    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel">

    <label for="email">Email</label>
    <input id="email" name="email" type="email">

    <button type="submit">Save salon</button>
</form>
</body>
</html>
