<?php
/** @var \App\ViewModels\UserFormViewModel $vm */
$user = $vm->user;
?>
<h1><?= htmlspecialchars($vm->title) ?></h1>

<form action="<?= htmlspecialchars($vm->action) ?>" method="post">
    <label for="firstName">First name*</label>
    <input id="firstName" name="firstName" required
           value="<?= htmlspecialchars($user->firstName ?? '') ?>">

    <label for="lastName">Last name*</label>
    <input id="lastName" name="lastName" required
           value="<?= htmlspecialchars($user->lastName ?? '') ?>">

    <label for="email">Email*</label>
    <input id="email" name="email" type="email" required
           value="<?= htmlspecialchars($user->email ?? '') ?>">

    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel"
           value="<?= htmlspecialchars($user->phone ?? '') ?>">

    <?php if (($user->role ?? '') !== 'customer'): ?>
        <label for="salonId">Salon ID</label>
        <input id="salonId" name="salonId" type="number" min="1"
               value="<?= htmlspecialchars((string)($user->salonId ?? '')) ?>">
    <?php endif; ?>

    <button type="submit"><?= $vm->isEdit ? 'Save changes' : 'Create user' ?></button>
</form>




