<?php
use App\ViewModels\SalonServiceFormViewModel;
/** @var SalonServiceFormViewModel $vm */
$service = $vm->service;
?>



<div class="mb-3">
    <a class="link-secondary text-decoration-none"
       href="/salons/<?= htmlspecialchars((string)$vm->salonId) ?>/services">&larr; Back to services</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-7">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3 text-center"><?= htmlspecialchars($vm->title) ?></h1>

                <form action="<?= htmlspecialchars($vm->action) ?>" method="post" novalidate>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Name</label>
                            <input id="name"
                                   name="name"
                                   class="form-control"
                                   required
                                   value="<?= htmlspecialchars($service->name ?? '') ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="price" class="form-label">Price (€)</label>
                            <input id="price"
                                   name="price"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   class="form-control"
                                   value="<?= htmlspecialchars($service->price !== null ? (string)$service->price : '') ?>">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="durationMinutes" class="form-label">Duration (minutes)</label>
                            <input id="durationMinutes"
                                   name="durationMinutes"
                                   type="number"
                                   min="0"
                                   class="form-control"
                                   value="<?= htmlspecialchars($service->durationMinutes !== null ? (string)$service->durationMinutes : '') ?>">
                        </div>

                        <div class="col-12">
                            <div class="fw-semibold mb-2">Specialists</div>

                            <?php if (!empty($vm->specialistOptions)): ?>
                                <div class="row g-2">
                                    <?php foreach ($vm->specialistOptions as $opt): ?>
                                        <?php $sid = (int)$opt['id']; ?>
                                        <div class="col-12 col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="spec<?= htmlspecialchars((string)$sid) ?>"
                                                       name="specialistIds[]"
                                                       value="<?= htmlspecialchars((string)$sid) ?>"
                                                        <?= $vm->isSelectedSpecialist($sid) ? 'checked' : '' ?>>
                                                <label class="form-check-label text-light" for="spec<?= htmlspecialchars((string)$sid) ?>">
                                                    <?= htmlspecialchars($opt['name']) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="form-text text-light mt-2">Select one or more specialists for this service.</div>
                            <?php else: ?>
                                <div class="alert alert-secondary mb-0" role="alert">
                                    No specialists available for this salon.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <?= $vm->isEdit ? 'Save changes' : 'Create service' ?>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>






