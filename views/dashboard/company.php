<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem[] $problems */
/** @var int $problemsCount */
/** @var int $activeProblemsCount */
/** @var int $closedProblemsCount */
/** @var int $proposalsCount */
/** @var int $selectedProposalsCount */
/** @var app\models\Proposal[] $recentProposals */
/** @var yii\data\Pagination $problemsPages */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;
$profile = Yii::$app->user->identity->companyProfile;
?>
<div class="dashboard-company">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark"><?= Html::encode($profile ? $profile->company_name : Yii::t('app', 'Company Dashboard')) ?></h2>
        <a href="<?= Url::to(['/problem/create']) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> <?= Yii::t('app', 'Create Problem') ?>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Total Problems') ?></div>
                <div class="h3 fw-bold text-dark mb-0"><?= $problemsCount ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Active Problems') ?></div>
                <div class="h3 fw-bold text-success mb-0"><?= $activeProblemsCount ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Closed Problems') ?></div>
                <div class="h3 fw-bold text-secondary mb-0"><?= $closedProblemsCount ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Selected Proposals') ?></div>
                <div class="h3 fw-bold text-primary mb-0"><?= $selectedProposalsCount ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Managed Challenges list -->
        <div class="col-lg-8">
            <div class="card border-light bg-white p-3 mb-4">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-folder2-open me-2 text-primary"></i><?= Yii::t('app', 'Managed Challenges') ?></h5>
                <?php if (empty($problems)): ?>
                    <p class="text-secondary mb-0"><?= Yii::t('app', 'No items found.') ?></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Title') ?></th>
                                    <th><?= Yii::t('app', 'Category') ?></th>
                                    <th><?= Yii::t('app', 'Status') ?></th>
                                    <th class="text-center"><?= Yii::t('app', 'Views') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($problems as $problem): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                                <?= Html::encode($problem->title) ?>
                                            </a>
                                        </td>
                                        <td><span class="badge bg-light text-secondary"><?= Html::encode($problem->category->getName()) ?></span></td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($problem->status === \app\models\Problem::STATUS_ACTIVE) $badgeClass = 'bg-success';
                                            elseif ($problem->status === \app\models\Problem::STATUS_MODERATION) $badgeClass = 'bg-warning text-dark';
                                            elseif ($problem->status === \app\models\Problem::STATUS_SOLVED) $badgeClass = 'bg-info text-white';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $problem->getStatusLabel() ?></span>
                                        </td>
                                        <td class="text-center text-secondary"><?= $problem->views_count ?></td>
                                        <td class="text-end">
                                            <?php if ($problem->status !== \app\models\Problem::STATUS_SOLVED): ?>
                                                <a href="<?= Url::to(['/problem/update', 'id' => $problem->id]) ?>" class="btn btn-sm btn-outline-secondary me-1" title="<?= Yii::t('app', 'Edit') ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="btn btn-sm btn-outline-primary" title="<?= Yii::t('app', 'View') ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (!empty($problems)): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <div>
                                <form method="get" class="d-flex align-items-center gap-2">
                                    <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                                        <?php if ($name !== 'per-page' && $name !== 'page' && $name !== 'problems-page'): ?>
                                            <?= Html::hiddenInput($name, $value) ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <label class="small text-secondary text-nowrap mb-0"><?= Yii::t('app', 'Records per page:') ?></label>
                                    <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                                        <option value="10" <?= $problemsPages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                                        <option value="20" <?= $problemsPages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                                        <option value="50" <?= $problemsPages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                                        <option value="100" <?= $problemsPages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                                    </select>
                                </form>
                            </div>
                            <div>
                                <?= \yii\bootstrap5\LinkPager::widget([
                                    'pagination' => $problemsPages,
                                    'maxButtonCount' => 5,
                                    'options' => ['class' => 'pagination pagination-sm mb-0'],
                                    'linkContainerOptions' => ['class' => 'page-item'],
                                    'linkOptions' => ['class' => 'page-link'],
                                    'disabledPageCssClass' => 'disabled',
                                    'activePageCssClass' => 'active',
                                ]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Proposals received -->
        <div class="col-lg-4">
            <div class="card border-light bg-white p-3">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-lightbulb me-2 text-success"></i><?= Yii::t('app', 'Proposals') ?> (<?= $proposalsCount ?>)</h5>
                <?php if (empty($recentProposals)): ?>
                    <p class="text-secondary mb-0"><?= Yii::t('app', 'No items found.') ?></p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentProposals as $proposal): ?>
                            <a href="<?= Url::to(['/proposal/view', 'id' => $proposal->id]) ?>" class="list-group-item list-group-item-action py-3 px-0 border-0 border-bottom">
                                <div class="d-flex w-100 justify-content-between mb-1">
                                    <h6 class="mb-0 text-dark fw-semibold"><?= Html::encode($proposal->title) ?></h6>
                                    <small class="text-secondary"><?= date('d.m.Y', $proposal->created_at) ?></small>
                                </div>
                                <p class="mb-2 text-secondary small"><?= Yii::t('app', 'Problem Details') ?>: <?= Html::encode($proposal->problem->title) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-secondary"><i class="bi bi-person me-1"></i><?= Html::encode($proposal->scientistProfile->getFullName()) ?></span>
                                    <span class="badge bg-light text-primary"><?= $proposal->getStatusLabel() ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
