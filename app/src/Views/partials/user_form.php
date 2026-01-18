<?php
/** @var \App\ViewModels\UserFormViewModel $vm */
$user = $vm->user;
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 col-lg-6">
        <div class="card mt-4">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center">
                    <?= htmlspecialchars($vm->title) ?>
                </h1>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>


                <form action="<?= htmlspecialchars($vm->action) ?>" method="post" novalidate>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="firstName" class="form-label">First name</label>
                            <input id="firstName"
                                   name="firstName"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($user->firstName ?? '') ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="lastName" class="form-label">Last name</label>
                            <input id="lastName"
                                   name="lastName"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($user->lastName ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($user->email ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">
                                <?= $vm->isEdit
                                        ? 'New password (leave empty to keep current)'
                                        : 'Password' ?>
                            </label>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   class="form-control"
                                   autocomplete="new-password"
                                    <?= $vm->isEdit ? '' : 'required' ?>>
                        </div>

                        <div class="col-12">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone"
                                   name="phone"
                                   type="tel"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($user->phone ?? '') ?>">

                        </div>

                        <?php if (($user->role ?? '') !== 'customer' && !$vm->isOwner): ?>
                            <div class="col-12">
                                <label for="salonId" class="form-label">Salon ID</label>
                                <input id="salonId"
                                       name="salonId"
                                       type="number"
                                       min="1"
                                       class="form-control"
                                       value="<?= htmlspecialchars((string)($user->salonId ?? '')) ?>">
                            </div>
                        <?php elseif (($user->role ?? '') !== 'customer' && $vm->isOwner): ?>
                            <div class="col-12">
                                <div class="alert alert-secondary mb-0" role="alert">
                                    Assigned to your salon
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <?= $vm->isEdit ? 'Save changes' : 'Create user' ?>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


