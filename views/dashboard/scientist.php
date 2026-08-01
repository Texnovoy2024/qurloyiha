<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Proposal[] $proposals */
/** @var int $proposalsCount */
/** @var int $selectedCount */
/** @var int $rejectedCount */
/** @var app\models\Problem[] $recommendedProblems */
/** @var app\models\Problem[] $favoriteProblems */
/** @var yii\data\Pagination $proposalsPages */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;
$profile = Yii::$app->user->identity->scientistProfile;
?>
<div class="dashboard-scientist">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark"><?= Html::encode($profile ? $profile->getFullName() : Yii::t('app', 'Scientist Dashboard')) ?></h2>
        <a href="<?= Url::to(['/problem/index']) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-search me-1"></i> <?= Yii::t('app', 'Search Problems') ?>
        </a>
    </div>

    <!-- Statistics widgets -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Total Proposals') ?></div>
                <div class="h3 fw-bold text-dark mb-0"><?= $proposalsCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Accepted Proposals') ?></div>
                <div class="h3 fw-bold text-success mb-0"><?= $selectedCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Rejected Proposals') ?></div>
                <div class="h3 fw-bold text-danger mb-0"><?= $rejectedCount ?></div>
            </div>
        </div>
    </div>

    <!-- Main Section Tabs (Proposals & Bookmarks) -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Navigation Tabs -->
            <ul class="nav nav-pills mb-4" id="scientistTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill me-2 px-4" id="proposals-tab" data-bs-toggle="pill" data-bs-target="#proposals" type="button" role="tab" aria-controls="proposals" aria-selected="true">
                        <?= Yii::t('app', 'My Proposals') ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="bookmarks-tab" data-bs-toggle="pill" data-bs-target="#bookmarks" type="button" role="tab" aria-controls="bookmarks" aria-selected="false">
                        <?= Yii::t('app', 'Bookmarks') ?> (<?= count($favoriteProblems) ?>)
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="scientistTabContent">
                <!-- Proposals Tab -->
                <div class="tab-pane fade show active" id="proposals" role="tabpanel" aria-labelledby="proposals-tab">
                    <div class="card border-light bg-white p-3 mb-4">
                        <h5 class="card-title text-dark mb-3"><i class="bi bi-file-earmark-text me-2 text-primary"></i><?= Yii::t('app', 'Submitted Solutions') ?></h5>
                        <?php if (empty($proposals)): ?>
                            <p class="text-secondary mb-0"><?= Yii::t('app', 'No items found.') ?></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'Title') ?></th>
                                            <th><?= Yii::t('app', 'Problem Details') ?></th>
                                            <th><?= Yii::t('app', 'Proposal Status') ?></th>
                                            <th><?= Yii::t('app', 'Time Offer') ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($proposals as $proposal): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= Url::to(['/proposal/view', 'id' => $proposal->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                                        <?= Html::encode($proposal->title) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= Url::to(['/problem/view', 'id' => $proposal->problem_id]) ?>" class="text-decoration-none text-secondary small">
                                                        <?= Html::encode($proposal->problem->title) ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php
                                                    $badgeClass = 'bg-secondary';
                                                    if ($proposal->status === \app\models\Proposal::STATUS_SELECTED) $badgeClass = 'bg-success';
                                                    elseif ($proposal->status === \app\models\Proposal::STATUS_EVALUATING) $badgeClass = 'bg-warning text-dark';
                                                    elseif ($proposal->status === \app\models\Proposal::STATUS_REJECTED) $badgeClass = 'bg-danger';
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>"><?= $proposal->getStatusLabel() ?></span>
                                                </td>
                                                <td><span class="small text-secondary"><?= Html::encode($proposal->time_offer) ?></span></td>
                                                <td class="text-end">
                                                    <a href="<?= Url::to(['/proposal/view', 'id' => $proposal->id]) ?>" class="btn btn-sm btn-outline-primary" title="<?= Yii::t('app', 'View') ?>">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination & Page Size selector -->
                            <?php if (!empty($proposals)): ?>
                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                    <div>
                                        <form method="get" class="d-flex align-items-center gap-2">
                                            <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                                                <?php if ($name !== 'per-page' && $name !== 'page' && $name !== 'proposals-page'): ?>
                                                    <?= Html::hiddenInput($name, $value) ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <label class="small text-secondary text-nowrap mb-0"><?= Yii::t('app', 'Records per page:') ?></label>
                                            <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                                                <option value="10" <?= $proposalsPages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                                                <option value="20" <?= $proposalsPages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                                                <option value="50" <?= $proposalsPages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                                                <option value="100" <?= $proposalsPages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                                            </select>
                                        </form>
                                    </div>
                                    <div>
                                        <?= \yii\bootstrap5\LinkPager::widget([
                                            'pagination' => $proposalsPages,
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

                <!-- Bookmarks Tab -->
                <div class="tab-pane fade" id="bookmarks" role="tabpanel" aria-labelledby="bookmarks-tab">
                    <div class="card border-light bg-white p-3 mb-4">
                        <h5 class="card-title text-dark mb-3"><i class="bi bi-bookmark-star me-2 text-warning"></i><?= Yii::t('app', 'Favorite Challenges') ?></h5>
                        <?php if (empty($favoriteProblems)): ?>
                            <p class="text-secondary mb-0"><?= Yii::t('app', 'No problems bookmarked yet.') ?></p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'Challenge') ?></th>
                                            <th><?= Yii::t('app', 'Category') ?></th>
                                            <th><?= Yii::t('app', 'Budget') ?></th>
                                            <th><?= Yii::t('app', 'Deadline') ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($favoriteProblems as $favProblem): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= Url::to(['/problem/view', 'id' => $favProblem->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                                        <?= Html::encode($favProblem->title) ?>
                                                    </a>
                                                </td>
                                                <td><span class="badge bg-light text-secondary"><?= Html::encode($favProblem->category->getName()) ?></span></td>
                                                <td class="text-dark fw-semibold"><?= $favProblem->budget ? number_format((float)$favProblem->budget) . ' UZS' : 'N/A' ?></td>
                                                <td class="small text-secondary"><?= $favProblem->deadline ? date('d.m.Y', $favProblem->deadline) : 'N/A' ?></td>
                                                <td class="text-end">
                                                    <a href="<?= Url::to(['/problem/favorite', 'id' => $favProblem->id]) ?>" class="btn btn-sm btn-outline-danger me-1" data-method="post" title="<?= Yii::t('app', 'Remove Bookmark') ?>">
                                                        <i class="bi bi-bookmark-x"></i>
                                                    </a>
                                                    <a href="<?= Url::to(['/problem/view', 'id' => $favProblem->id]) ?>" class="btn btn-sm btn-outline-primary" title="<?= Yii::t('app', 'View') ?>">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended active challenges -->
        <div class="col-lg-4">
            <div class="card border-light bg-white p-3">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-fire me-2 text-danger"></i><?= Yii::t('app', 'Active Problems') ?></h5>
                <?php if (empty($recommendedProblems)): ?>
                    <p class="text-secondary mb-0"><?= Yii::t('app', 'No items found.') ?></p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recommendedProblems as $problem): ?>
                            <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="list-group-item list-group-item-action py-3 px-0 border-0 border-bottom">
                                <div class="d-flex w-100 justify-content-between mb-1">
                                    <h6 class="mb-0 text-dark fw-semibold"><?= Html::encode($problem->title) ?></h6>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-light text-secondary small"><?= Html::encode($problem->category->getName()) ?></span>
                                    <span class="text-primary fw-semibold small"><?= $problem->budget ? number_format((float)$problem->budget) . ' UZS' : 'N/A' ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
