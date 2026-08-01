<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Proposal $model */
/** @var bool $isCompanyOwner */
/** @var bool $isScientistOwner */
/** @var bool $isAdmin */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => $model->problem->title, 'url' => ['/problem/view', 'id' => $model->problem_id]];
$this->params['breadcrumbs'][] = $this->title;

$isSelected = $model->status === \app\models\Proposal::STATUS_SELECTED;
?>
<div class="proposal-view">
    <div class="row g-4">
        <!-- Proposal Main details -->
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-secondary px-3 py-2"><?= Yii::t('app', 'Proposal Status') ?>: <?= $model->getStatusLabel() ?></span>
                    <span class="small text-secondary"><?= date('d.m.Y H:i', $model->created_at) ?></span>
                </div>

                <h1 class="fw-bold mb-4 h3 text-dark"><?= Html::encode($model->title) ?></h1>
                
                <h6 class="fw-bold text-primary mb-2"><?= Yii::t('app', 'Description') ?></h6>
                <p class="text-secondary mb-4 text-justify" style="white-space: pre-wrap;"><?= Html::encode($model->description) ?></p>

                <h6 class="fw-bold text-primary mb-2"><?= Yii::t('app', 'Solution Details') ?></h6>
                <p class="text-secondary mb-4 text-justify" style="white-space: pre-wrap;"><?= Html::encode($model->solution_details) ?></p>

                <!-- Scientist Portfolio data for review -->
                <div class="mt-4 mb-4 bg-light p-4 rounded border">
                    <h6 class="fw-bold mb-3 text-dark"><?= Yii::t('app', 'Candidate Academic Profile') ?></h6>
                    <div class="row g-3 small text-dark">
                        <div class="col-md-6"><strong><?= Yii::t('app', 'Institution:') ?></strong> <?= Html::encode($model->scientistProfile->institution ?? 'N/A') ?></div>
                        <div class="col-md-6"><strong><?= Yii::t('app', 'Degree:') ?></strong> <?= Html::encode($model->scientistProfile->academic_degree ?? 'N/A') ?> <?= $model->scientistProfile->academic_title ? ' - ' . Html::encode($model->scientistProfile->academic_title) : '' ?></div>
                        <div class="col-12"><strong><?= Yii::t('app', 'Biography:') ?></strong> <p class="text-secondary mt-1 mb-0"><?= Html::encode($model->scientistProfile->bio ?? 'N/A') ?></p></div>
                        <div class="col-12"><strong><?= Yii::t('app', 'Skills:') ?></strong> <p class="text-secondary mt-1 mb-0"><?= Html::encode($model->scientistProfile->skills ?? 'N/A') ?></p></div>
                        <div class="col-12"><strong><?= Yii::t('app', 'Research Publications:') ?></strong> <p class="text-secondary mt-1 mb-0"><?= Html::encode($model->scientistProfile->publications ?? 'N/A') ?></p></div>
                        <div class="col-12"><strong><?= Yii::t('app', 'Academic Certificates:') ?></strong> <p class="text-secondary mt-1 mb-0"><?= Html::encode($model->scientistProfile->certificates ?? 'N/A') ?></p></div>
                        <div class="col-12"><strong><?= Yii::t('app', 'Project Portfolio:') ?></strong> <p class="text-secondary mt-1 mb-0"><?= Html::encode($model->scientistProfile->portfolio ?? 'N/A') ?></p></div>
                    </div>
                </div>

                <!-- Supporting Documents -->
                <?php if (!empty($model->files) || $model->document_file): ?>
                    <hr class="my-4">
                    <h6 class="fw-bold text-primary mb-3"><?= Yii::t('app', 'Supporting Documents') ?></h6>
                    <div class="list-group">
                        <?php if ($model->document_file): ?>
                            <?php 
                            $ext = strtolower(pathinfo($model->document_file, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true);
                            $isPdf = ($ext === 'pdf');
                            ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between bg-light border p-3 rounded mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-arrow-down fs-4 me-3 text-secondary"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= Yii::t('app', 'Legacy Attachment') ?></h6>
                                        <small class="text-secondary"><?= Yii::t('app', 'Primary supporting document') ?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($isImage): ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="previewImage('<?= Url::to('@web/' . $model->document_file) ?>', 'Legacy Attachment')">
                                            <?= Yii::t('app', 'Preview') ?>
                                        </button>
                                    <?php elseif ($isPdf): ?>
                                        <a href="<?= Url::to('@web/' . $model->document_file) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <?= Yii::t('app', 'Preview') ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= Url::to('@web/' . $model->document_file) ?>" class="btn btn-sm btn-outline-primary" download>
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

                <!-- Evaluation Controls for Company Owner -->
                <?php if ($isCompanyOwner && !$isSelected && $model->status !== \app\models\Proposal::STATUS_REJECTED): ?>
                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <?php if ($model->status === \app\models\Proposal::STATUS_SUBMITTED): ?>
                            <a href="<?= Url::to(['/proposal/review', 'id' => $model->id]) ?>" class="btn btn-sm btn-warning text-dark px-3" data-method="post">
                                <?= Yii::t('app', 'Mark as Under Review') ?>
                            </a>
                        <?php endif; ?>
                        <a href="<?= Url::to(['/proposal/reject', 'id' => $model->id]) ?>" class="btn btn-sm btn-danger px-3" data-method="post" data-confirm="<?= Yii::t('app', 'Are you sure you want to reject this proposal?') ?>">
                            <?= Yii::t('app', 'Reject Proposal') ?>
                        </a>
                        <a href="<?= Url::to(['/proposal/select', 'id' => $model->id]) ?>" class="btn btn-sm btn-success px-3" data-method="post" data-confirm="<?= Yii::t('app', 'Are you sure you want to accept this proposal and close the challenge?') ?>">
                            <?= Yii::t('app', 'Select Solution') ?>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Scientist Owner / Admin Actions -->
                <?php if (($isScientistOwner && ($model->status === \app\models\Proposal::STATUS_DRAFT || $model->status === \app\models\Proposal::STATUS_SUBMITTED)) || $isAdmin): ?>
                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= Url::to(['/proposal/update', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-primary px-3"><?= Yii::t('app', 'Edit') ?></a>
                        <a href="<?= Url::to(['/proposal/delete', 'id' => $model->id]) ?>" class="btn btn-sm btn-danger px-3" data-method="post" data-confirm="<?= Yii::t('app', 'Are you sure you want to delete/withdraw this proposal?') ?>"><?= Yii::t('app', 'Withdraw') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Summary / Contact details -->
        <div class="col-lg-4">
            <div class="card border-light bg-white p-3 mb-4">
                <h6 class="fw-bold mb-3 text-dark"><?= Yii::t('app', 'Financial & Timeline Offer') ?></h6>
                
                <div class="mb-3">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Budget Offer') ?></small>
                    <span class="fw-bold text-primary"><?= $model->budget_offer ? number_format((float)$model->budget_offer) . ' UZS' : 'N/A' ?></span>
                </div>
                
                <div class="mb-3">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Time Offer') ?></small>
                    <span class="fw-semibold text-dark"><?= $model->time_offer ? Html::encode($model->time_offer) : 'N/A' ?></span>
                </div>

                <hr class="my-3">

                <div class="mb-2">
                    <small class="text-secondary d-block"><?= Yii::t('app', 'Problem Details') ?></small>
                    <span class="fw-semibold text-dark-emphasis d-block mt-1">
                        <a href="<?= Url::to(['/problem/view', 'id' => $model->problem_id]) ?>" class="text-decoration-none text-dark hover-primary">
                            <?= Html::encode($model->problem->title) ?>
                        </a>
                    </span>
                </div>
            </div>

            <!-- CONTACT EXCHANGE SECTION -->
            <?php if ($isSelected): ?>
                <div class="card border-success bg-success-subtle p-3 mb-4">
                    <h6 class="fw-bold text-success mb-3"><i class="bi bi-handshake me-1"></i><?= Yii::t('app', 'Partner Contact Info') ?></h6>
                    
                    <?php if ($isCompanyOwner): ?>
                        <!-- Show Scientist profile to Company -->
                        <?php $sc = $model->scientistProfile; ?>
                        <div class="mb-3 text-dark small">
                            <strong><?= Yii::t('app', 'Name:') ?></strong> <?= Html::encode($sc->getFullName()) ?><br>
                            <strong><?= Yii::t('app', 'Degree:') ?></strong> <?= Html::encode($sc->academic_degree ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Institution:') ?></strong> <?= Html::encode($sc->institution ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Specialization:') ?></strong> <?= Html::encode($sc->specialization ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Email:') ?></strong> <?= Html::encode($model->scientist->email) ?><br>
                            <strong><?= Yii::t('app', 'Phone:') ?></strong> <?= Html::encode($sc->phone ?? 'N/A') ?><br>
                        </div>
                    <?php elseif ($isScientistOwner): ?>
                        <!-- Show Company profile to Scientist -->
                        <?php $cp = $model->problem->companyProfile; ?>
                        <div class="mb-3 text-dark small">
                            <strong><?= Yii::t('app', 'Company Name:') ?></strong> <?= Html::encode($cp->company_name) ?><br>
                            <strong><?= Yii::t('app', 'Contact Person:') ?></strong> <?= Html::encode($cp->responsible_name ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Industry:') ?></strong> <?= Html::encode($cp->industry ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Website:') ?></strong> <?= Html::encode($cp->website ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Address:') ?></strong> <?= Html::encode($cp->address ?? 'N/A') ?><br>
                            <strong><?= Yii::t('app', 'Email:') ?></strong> <?= Html::encode($model->problem->company->email) ?><br>
                            <strong><?= Yii::t('app', 'Phone:') ?></strong> <?= Html::encode($cp->phone ?? 'N/A') ?><br>
                        </div>
                    <?php else: ?>
                        <!-- Admin or others -->
                        <p class="text-secondary small"><?= Yii::t('app', 'Contact details shared between the company and the scientist.') ?></p>
                    <?php endif; ?>

                    <hr class="my-3">
                    <small class="text-secondary text-justify d-block" style="font-size: 0.8rem;">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> <strong><?= Yii::t('app', 'Note: Contracts and payments are handled outside the platform.') ?></strong>
                    </small>
                </div>
            <?php endif; ?>
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
