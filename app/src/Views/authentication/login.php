<?php
use App\ViewModels\LoginViewModel;
/** @var LoginViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>
<h1>Login</h1>

<?php if ($vm->error !== ''): ?>
    <p style="color:red;"><?= htmlspecialchars($vm->error) ?></p>
<?php endif; ?>

<form method="post" action="/login">
    <label for="email">Email*</label>
    <input id="email" name="email" type="email" required>

    <label for="password">Password*</label>
    <input id="password" name="password" type="password" required>

    <button type="submit">Login</button>
</form>

<p>
    No account? <a href="/register">Register</a>
</p>

</body>
</html>

