<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;

$isCompany = $user->role === \app\models\User::ROLE_COMPANY;
$isScientist = $user->role === \app\models\User::ROLE_SCIENTIST;
?>
<div class="profile-index">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div class="d-flex align-items-center">
                        <?php if ($isScientist && $user->scientistProfile && $user->scientistProfile->photo): ?>
                            <img src="<?= Url::to('@web/' . $user->scientistProfile->photo) ?>" alt="Photo" class="rounded-circle me-3 border" style="width: 55px; height: 55px; object-fit: cover;">
                        <?php endif; ?>
                        <h2 class="fw-bold mb-0 text-dark h4"><i class="bi bi-person me-2"></i><?= Html::encode($this->title) ?></h2>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= Url::to(['/auth/change-password']) ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-key me-1"></i> <?= Yii::t('app', 'Change Password') ?>
                        </a>
                        <a href="<?= Url::to(['/profile/update']) ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i> <?= Yii::t('app', 'Edit Profile') ?>
                        </a>
                    </div>
                </div>

                <div class="row g-3 text-dark">
                    <div class="col-md-6">
                        <small class="text-secondary d-block"><?= Yii::t('app', 'Username') ?></small>
                        <span class="fw-semibold"><?= Html::encode($user->username) ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-secondary d-block"><?= Yii::t('app', 'Email') ?></small>
                        <span class="fw-semibold"><?= Html::encode($user->email) ?></span>
                    </div>

                    <?php if ($isCompany): ?>
                        <?php $cp = $user->companyProfile; ?>
                        <div class="col-12"><hr class="my-2"></div>
                        
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Company Name') ?></small>
                            <span class="fw-bold text-primary"><?= Html::encode($cp->company_name ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Responsible Person') ?></small>
                            <span class="fw-semibold"><?= Html::encode($cp->responsible_name ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'STIR (TIN)') ?></small>
                            <span class="fw-semibold"><?= Html::encode($cp->stir ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Organization Type') ?></small>
                            <span class="fw-semibold"><?= Html::encode($cp->organization_type ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Industry') ?></small>
                            <span class="fw-semibold"><?= Html::encode($cp->industry ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Website') ?></small>
                            <span class="d-block">
                                <?php if ($cp->website): ?>
                                    <a href="<?= Html::encode($cp->website) ?>" target="_blank" class="text-decoration-none fw-semibold"><i class="bi bi-link-45deg me-1"></i><?= Html::encode($cp->website) ?></a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Phone') ?></small>
                            <span class="fw-semibold"><?= Html::encode($cp->phone ?? 'N/A') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Address') ?></small>
                            <span class="fw-semibold"><?= Html::encode($cp->address ?? 'N/A') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Company Description') ?></small>
                            <p class="text-secondary text-justify mt-1 mb-0" style="white-space: pre-wrap;"><?= Html::encode($cp->description ?? 'N/A') ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($isScientist): ?>
                        <?php $sp = $user->scientistProfile; ?>
                        <div class="col-12"><hr class="my-2"></div>
                        
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Name') ?></small>
                            <span class="fw-bold text-primary"><?= Html::encode($sp ? $sp->getFullName() : 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Academic Degree / Title') ?></small>
                            <span class="fw-semibold">
                                <?= Html::encode($sp->academic_degree ?? '') ?> 
                                <?= $sp->academic_title ? ' - ' . Html::encode($sp->academic_title) : '' ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Date of Birth') ?></small>
                            <span class="fw-semibold"><?= Html::encode($sp->dob ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Institution') ?></small>
                            <span class="fw-semibold"><?= Html::encode($sp->institution ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Specialization') ?></small>
                            <span class="fw-semibold"><?= Html::encode($sp->specialization ?? 'N/A') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Phone') ?></small>
                            <span class="fw-semibold"><?= Html::encode($sp->phone ?? 'N/A') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Biography') ?></small>
                            <p class="text-secondary text-justify mt-1 mb-0" style="white-space: pre-wrap;"><?= Html::encode($sp->bio ?? 'N/A') ?></p>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Skills') ?></small>
                            <p class="text-secondary text-justify mt-1 mb-0" style="white-space: pre-wrap;"><?= Html::encode($sp->skills ?? 'N/A') ?></p>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Publications / Research Papers') ?></small>
                            <p class="text-secondary text-justify mt-1 mb-0" style="white-space: pre-wrap;"><?= Html::encode($sp->publications ?? 'N/A') ?></p>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Certificates & Achievements') ?></small>
                            <p class="text-secondary text-justify mt-1 mb-0" style="white-space: pre-wrap;"><?= Html::encode($sp->certificates ?? 'N/A') ?></p>
                        </div>
                        <div class="col-12">
                            <small class="text-secondary d-block"><?= Yii::t('app', 'Project Portfolio') ?></small>
                            <p class="text-secondary text-justify mt-1 mb-0" style="white-space: pre-wrap;"><?= Html::encode($sp->portfolio ?? 'N/A') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
