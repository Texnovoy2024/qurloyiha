<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem $model */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Problems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
$isOwner = $user && $model->company_id === $user->id;
$isScientist = $user && $user->role === \app\models\User::ROLE_SCIENTIST;
$isAdmin = $user && $user->role === \app\models\User::ROLE_ADMIN;
?>
<div class="problem-view">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <span class="badge bg-primary px-3 py-2"><?= Html::encode($model->category->getName()) ?></span>
                    <span class="text-secondary small"><i class="bi bi-eye"></i> <?= $model->views_count ?> <?= Yii::t('app', 'Views') ?></span>
                </div>

                <h1 class="fw-bold mb-4 h3 text-dark"><?= Html::encode($model->title) ?></h1>
                
                <h6 class="fw-bold text-primary mb-2"><?= Yii::t('app', 'Description') ?></h6>
                <p class="text-secondary mb-4 text-justify" style="white-space: pre-wrap;"><?= Html::encode($model->description) ?></p>

                <?php if (!empty($model->requirements)): ?>
                    <h6 class="fw-bold text-primary mb-2"><?= Yii::t('app', 'Requirements') ?></h6>
                    <p class="text-secondary mb-4 text-justify" style="white-space: pre-wrap;"><?= Html::encode($model->requirements) ?></p>
                <?php endif; ?>

                <!-- Expected Result Section -->
                <?php if (!empty($model->expected_result)): ?>
                    <h6 class="fw-bold text-primary mb-2"><?= Yii::t('app', 'Expected Scientific / Technical Result') ?></h6>
                    <p class="text-secondary mb-4 text-justify" style="white-space: pre-wrap;"><?= Html::encode($model->expected_result) ?></p>
                <?php endif; ?>

                <!-- Problem Attachment Files -->
                <?php if (!empty($model->files) || $model->attachment_file): ?>
                    <hr class="my-4">
                    <h6 class="fw-bold text-primary mb-3"><?= Yii::t('app', 'Specifications & Supporting Documents') ?></h6>
                    <div class="list-group">
                        <?php if ($model->attachment_file): ?>
                            <?php 
                            $ext = strtolower(pathinfo($model->attachment_file, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true);
                            $isPdf = ($ext === 'pdf');
                            ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between bg-light border p-3 rounded mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-arrow-down fs-4 me-3 text-secondary"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= Yii::t('app', 'Specification Attachment') ?></h6>
                                        <small class="text-secondary"><?= Yii::t('app', 'Primary specification sheet') ?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($isImage): ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="previewImage('<?= Url::to('@web/' . $model->attachment_file) ?>', 'Specification Attachment')">
                                            <?= Yii::t('app', 'Preview') ?>
                                        </button>
                                    <?php elseif ($isPdf): ?>
                                        <a href="<?= Url::to('@web/' . $model->attachment_file) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <?= Yii::t('app', 'Preview') ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= Url::to('@web/' . $model->attachment_file) ?>" class="btn btn-sm btn-outline-primary" download>
                                        <?= Yii::t('app', 'Download') ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php foreach ($model->files as $file): ?>
                            <?php 
                            $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true);
                            $isPdf = ($ext === 'pdf');
                            ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between bg-light border p-3 rounded mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-check fs-4 me-3 text-secondary"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= Html::encode($file->file_name) ?></h6>
                                        <small class="text-secondary"><?= Yii::t('app', 'Size') ?>: <?= number_format($file->file_size / 1024, 1) ?> KB | <?= Yii::t('app', 'Type') ?>: <?= Html::encode($file->file_type) ?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($isImage): ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="previewImage('<?= Url::to('@web/' . $file->file_path) ?>', '<?= Html::encode($file->file_name) ?>')">
                                            <?= Yii::t('app', 'Preview') ?>
                                        </button>
                                    <?php elseif ($isPdf): ?>
                                        <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <?= Yii::t('app', 'Preview') ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= Url::to('@web/' . $file->file_path) ?>" class="btn btn-sm btn-outline-primary" download>
                                        <?= Yii::t('app', 'Download') ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Admin Action Buttons -->
                <?php if ($isAdmin && $model->status === \app\models\Problem::STATUS_MODERATION): ?>
                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= Url::to(['/problem/approve', 'id' => $model->id]) ?>" class="btn btn-sm btn-success px-4" data-method="post"><?= Yii::t('app', 'Approve and Publish') ?></a>
                        <a href="<?= Url::to(['/problem/spam', 'id' => $model->id]) ?>" class="btn btn-sm btn-danger px-4" data-method="post"><?= Yii::t('app', 'Mark as Spam') ?></a>
                    </div>
                <?php endif; ?>

                <!-- Company Owner Actions -->
                <?php if ($isOwner): ?>
                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <?php if ($model->status !== \app\models\Problem::STATUS_ARCHIVED && $model->status !== \app\models\Problem::STATUS_SOLVED): ?>
                            <a href="<?= Url::to(['archive', 'id' => $model->id]) ?>" class="btn btn-sm btn-warning text-dark px-4" data-method="post" data-confirm="<?= Yii::t('app', 'Are you sure you want to archive this problem?') ?>"><?= Yii::t('app', 'Archive') ?></a>
                        <?php endif; ?>
                        <?php if ($model->status !== \app\models\Problem::STATUS_SOLVED): ?>
                            <a href="<?= Url::to(['update', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-primary px-4"><?= Yii::t('app', 'Edit') ?></a>
                            <a href="<?= Url::to(['delete', 'id' => $model->id]) ?>" class="btn btn-sm btn-danger px-4" data-method="post" data-confirm="<?= Yii::t('app', 'Are you sure you want to delete this problem?') ?>"><?= Yii::t('app', 'Delete') ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- List of received proposals for Company Owner -->
            <?php if ($isOwner): ?>
                <div class="card border-light bg-white p-3 mt-4">
                    <h5 class="card-title text-dark mb-3"><i class="bi bi-lightbulb me-2 text-success"></i><?= Yii::t('app', 'Proposals Received') ?></h5>
                    <?php if (empty($model->proposals)): ?>
                        <p class="text-secondary mb-0"><?= Yii::t('app', 'No items found.') ?></p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th><?= Yii::t('app', 'Title') ?></th>
                                        <th><?= Yii::t('app', 'Candidate') ?></th>
                                        <th><?= Yii::t('app', 'Budget Offer') ?></th>
                                        <th><?= Yii::t('app', 'Proposal Status') ?></th>
                                        <th class="text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($model->proposals as $prop): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= Url::to(['/proposal/view', 'id' => $prop->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                                    <?= Html::encode($prop->title) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="small text-secondary fw-semibold">
                                                    <i class="bi bi-person me-1"></i><?= Html::encode($prop->scientistProfile->getFullName()) ?>
                                                </span>
                                            </td>
                                            <td class="text-primary fw-bold">
                                                <?= $prop->budget_offer ? number_format((float)$prop->budget_offer) . ' UZS' : 'N/A' ?>
                                            </td>
                                            <td>
                                                <?php
                                                $bClass = 'bg-secondary';
                                                if ($prop->status === \app\models\Proposal::STATUS_SELECTED) $bClass = 'bg-success';
                                                elseif ($prop->status === \app\models\Proposal::STATUS_EVALUATING) $bClass = 'bg-warning text-dark';
                                                elseif ($prop->status === \app\models\Proposal::STATUS_REJECTED) $bClass = 'bg-danger';
                                                ?>
                                                <span class="badge <?= $bClass ?>"><?= $prop->getStatusLabel() ?></span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= Url::to(['/proposal/view', 'id' => $prop->id]) ?>" class="btn btn-sm btn-outline-primary">
                                                    <?= Yii::t('app', 'View Details') ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar / Details Card -->
        <div class="col-lg-4">
            <div class="card border-light bg-white p-3 mb-4">
                <h6 class="fw-bold mb-3 text-dark"><?= Yii::t('app', 'Challenge Summary') ?></h6>
                
                <div class="mb-3">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Budget') ?></small>
                    <span class="fw-bold text-primary"><?= $model->budget ? number_format((float)$model->budget) . ' UZS' : Yii::t('app', 'Negotiable') ?></span>
                </div>
                
                <div class="mb-3">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Deadline') ?></small>
                    <span class="fw-semibold text-danger"><?= $model->deadline ? date('d.m.Y', $model->deadline) : Yii::t('app', 'No deadline') ?></span>
                </div>
                
                <div class="mb-3">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Status') ?></small>
                    <span class="badge bg-light text-secondary mt-1 border"><?= $model->getStatusLabel() ?></span>
                </div>

                <div class="mb-4">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Posted By') ?></small>
                    <span class="fw-semibold text-dark d-block mt-1"><i class="bi bi-building me-1"></i><?= Html::encode($model->companyProfile->company_name ?? 'N/A') ?></span>
                    <span class="small text-secondary text-truncate d-block" style="font-size: 0.8rem;"><?= Html::encode($model->companyProfile->industry ?? '') ?></span>
                </div>

                <!-- Bookmark Favorite button and Submit Proposal actions for Scientists -->
                <?php if ($isScientist): ?>
                    <div class="row g-2">
                        <?php $isFav = \app\models\Favorite::findOne(['user_id' => $user->id, 'problem_id' => $model->id]); ?>
                        <div class="col-12">
                            <a href="<?= Url::to(['/problem/favorite', 'id' => $model->id]) ?>" class="btn w-100 btn-sm <?= $isFav ? 'btn-warning' : 'btn-outline-warning' ?>" data-method="post">
                                <i class="bi <?= $isFav ? 'bi-star-fill' : 'bi-star' ?> me-1"></i> <?= $isFav ? Yii::t('app', 'Bookmarked') : Yii::t('app', 'Bookmarks') ?>
                            </a>
                        </div>
                        <?php if ($model->status === \app\models\Problem::STATUS_ACTIVE): ?>
                            <div class="col-12 mt-2">
                                <a href="<?= Url::to(['/proposal/create', 'id' => $model->id]) ?>" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-lightbulb me-1"></i> <?= Yii::t('app', 'Submit Proposal') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card border-light bg-light p-3">
                <small class="text-secondary text-justify d-block">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> <strong><?= Yii::t('app', 'Note: Contracts and payments are handled outside the platform.') ?></strong>
                    <?= Yii::t('app', 'Our platform is only intended to facilitate academic and industrial collaboration.') ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Modal for image preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="previewModalLabel"><?= Yii::t('app', 'File Preview') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="previewImg" src="" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(src, name) {
    document.getElementById('previewImg').src = src;
    document.getElementById('previewModalLabel').textContent = name;
    var myModal = new bootstrap.Modal(document.getElementById('previewModal'));
    myModal.show();
}
</script>
