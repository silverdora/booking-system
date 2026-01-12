<?php
use App\ViewModels\RegisterViewModel;
/** @var RegisterViewModel $vm */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($vm->title) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<h1>Register</h1>

<?php if ($vm->error !== ''): ?>
    <p style="color:red;"><?= htmlspecialchars($vm->error) ?></p>
<?php endif; ?>

<form method="post" action="/register">

    <label for="role">Register as*</label>
    <select id="role" name="role" required>
        <option value="customer">Customer</option>
        <option value="owner">Salon owner</option>
    </select>

    <label for="firstName">First name*</label>
    <input id="firstName" name="firstName" required>

    <label for="lastName">Last name*</label>
    <input id="lastName" name="lastName" required>

    <label for="email">Email*</label>
    <input id="email" name="email" type="email" required>

    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel">

    <label for="password">Password* (min 8 chars)</label>
    <input id="password" name="password" type="password" minlength="8" required>

    <button type="submit">Create account</button>
</form>

<p>
    Already have an account? <a href="/login">Login</a>
</p>
</body>
</html>
