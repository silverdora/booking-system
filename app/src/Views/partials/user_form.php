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

    <label for="password"><?= $vm->isEdit ? 'New password (leave empty to keep current)' : 'Password*' ?></label>
    <input id="password" name="password" type="password"
           autocomplete="<?= $vm->isEdit ? 'new-password' : 'new-password' ?>"
            <?= $vm->isEdit ? '' : 'required' ?>>



    <label for="phone">Phone</label>
    <input id="phone" name="phone" type="tel"
           value="<?= htmlspecialchars($user->phone ?? '') ?>">

    <?php if (($user->role ?? '') !== 'customer' && !$vm->isOwner): ?>
        <label for="salonId">Salon ID</label>
        <input id="salonId" name="salonId" type="number" min="1"
               value="<?= htmlspecialchars((string)($user->salonId ?? '')) ?>">
    <?php elseif (($user->role ?? '') !== 'customer' && $vm->isOwner): ?>
        <p><strong>Salon:</strong> Assigned to your salon</p>
    <?php endif; ?>

    <button type="submit"><?= $vm->isEdit ? 'Save changes' : 'Create user' ?></button>
</form>




