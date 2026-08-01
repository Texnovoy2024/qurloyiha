<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem[] $problems */
/** @var app\models\Category[] $categories */
/** @var string|null $search */
/** @var string|null $categoryId */
/** @var string|null $minBudget */
/** @var string|null $status */
/** @var string|null $sort */
/** @var yii\data\Pagination $pages */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Search Problems');
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
$isScientist = $user && $user->role === \app\models\User::ROLE_SCIENTIST;
$isAdmin = $user && $user->role === \app\models\User::ROLE_ADMIN;
$isCompany = $user && $user->role === \app\models\User::ROLE_COMPANY;
?>
<div class="problem-index">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="h4 mb-1 text-dark"><?= Html::encode($this->title) ?></h2>
            <p class="text-secondary small"><?= Yii::t('app', 'Browse tech, engineering, and scientific challenges posted by industrial companies and submit your solutions.') ?></p>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card border-light bg-white p-3 mb-4">
        <form method="get" action="<?= Url::to(['/problem/index']) ?>" class="row g-3">
            <div class="col-md-6">
                <label for="search-input" class="form-label"><?= Yii::t('app', 'Search Problems') ?></label>
                <input type="text" id="search-input" name="search" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Search tech challenges by keyword...') ?>" value="<?= Html::encode($search) ?>">
            </div>
            <div class="col-md-6">
                <label for="category-select" class="form-label"><?= Yii::t('app', 'Filter by Category') ?></label>
                <select id="category-select" name="category_id" class="form-select form-select-sm">
                    <option value=""><?= Yii::t('app', 'Filter by Category') ?></option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>" <?= (string)$cat->id === $categoryId ? 'selected' : '' ?>>
                            <?= Html::encode($cat->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="budget-input" class="form-label"><?= Yii::t('app', 'Min Budget') ?></label>
                <input type="number" id="budget-input" name="min_budget" class="form-control form-control-sm" placeholder="<?= Yii::t('app', 'Budget') ?>" value="<?= Html::encode($minBudget) ?>">
            </div>
            <div class="col-md-3">
                <label for="status-select" class="form-label"><?= Yii::t('app', 'Status') ?></label>
                <select id="status-select" name="status" class="form-select form-select-sm">
                    <option value=""><?= Yii::t('app', 'Filter by Status') ?></option>
                    <?php if ($isAdmin || $isCompany): ?>
                        <option value="0" <?= $status === '0' ? 'selected' : '' ?>><?= Yii::t('app', 'Draft') ?></option>
                        <option value="10" <?= $status === '10' ? 'selected' : '' ?>><?= Yii::t('app', 'Moderation') ?></option>
                        <option value="35" <?= $status === '35' ? 'selected' : '' ?>><?= Yii::t('app', 'Cancelled') ?></option>
                    <?php endif; ?>
                    <option value="20" <?= $status === '20' ? 'selected' : '' ?>><?= Yii::t('app', 'Active') ?></option>
                    <option value="25" <?= $status === '25' ? 'selected' : '' ?>><?= Yii::t('app', 'Under Evaluation') ?></option>
                    <option value="30" <?= $status === '30' ? 'selected' : '' ?>><?= Yii::t('app', 'Solved') ?></option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort-select" class="form-label"><?= Yii::t('app', 'Sort By') ?></label>
                <select id="sort-select" name="sort" class="form-select form-select-sm">
                    <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>><?= Yii::t('app', 'Latest') ?></option>
                    <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>><?= Yii::t('app', 'Oldest') ?></option>
                    <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>><?= Yii::t('app', 'Most Popular') ?></option>
                    <option value="deadline" <?= $sort === 'deadline' ? 'selected' : '' ?>><?= Yii::t('app', 'Deadline') ?></option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i> <?= Yii::t('app', 'Search') ?>
                </button>
                <a href="<?= Url::to(['/problem/index']) ?>" class="btn btn-outline-secondary btn-sm w-50 text-center">
                    <?= Yii::t('app', 'Reset') ?>
                </a>
            </div>
        </form>
    </div>

    <!-- Problems List Grid -->
    <div class="row g-4">
        <?php if (empty($problems)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-folder2-open display-4 text-secondary d-block mb-3"></i>
                <h5 class="text-secondary"><?= Yii::t('app', 'No items found.') ?></h5>
            </div>
        <?php else: ?>
            <?php foreach ($problems as $problem): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-light bg-white p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-light text-secondary text-truncate fw-semibold" style="max-width: 150px;">
                                    <?= Html::encode($problem->category->getName()) ?>
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small text-secondary"><i class="bi bi-eye"></i> <?= $problem->views_count ?></span>
                                    
                                    <!-- Favorite star toggle for Scientists -->
                                    <?php if ($isScientist): ?>
                                        <?php $isFav = \app\models\Favorite::findOne(['user_id' => $user->id, 'problem_id' => $problem->id]); ?>
                                        <a href="<?= Url::to(['/problem/favorite', 'id' => $problem->id]) ?>" 
                                           class="text-decoration-none" 
                                           data-method="post"
                                           title="<?= $isFav ? Yii::t('app', 'Remove Bookmark') : Yii::t('app', 'Bookmarks') ?>">
                                            <i class="bi <?= $isFav ? 'bi-star-fill text-warning' : 'bi-star text-secondary' ?>"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h5 class="fw-bold mb-2">
                                <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="text-decoration-none text-dark hover-primary">
                                    <?= Html::encode($problem->title) ?>
                                </a>
                            </h5>
                            
                            <p class="text-secondary small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= Html::encode(strip_tags($problem->description)) ?>
                            </p>
                        </div>
                        
                        <div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <small class="d-block text-secondary text-uppercase fw-semibold" style="font-size: 0.7rem;"><?= Yii::t('app', 'Budget') ?></small>
                                    <span class="text-primary fw-bold">
                                        <?= $problem->budget ? number_format((float)$problem->budget) . ' UZS' : 'N/A' ?>
                                    </span>
                                </div>
                                <div class="text-end">
                                    <small class="d-block text-secondary text-uppercase fw-semibold" style="font-size: 0.7rem;"><?= Yii::t('app', 'Deadline') ?></small>
                                    <span class="text-danger fw-semibold small">
                                        <?= $problem->deadline ? date('d.m.Y', $problem->deadline) : 'N/A' ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="btn btn-outline-primary btn-sm">
                                    <?= Yii::t('app', 'View Details') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination & Page Size selector -->
    <?php if (!empty($problems)): ?>
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <div>
                <form method="get" class="d-flex align-items-center gap-2">
                    <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                        <?php if ($name !== 'per-page' && $name !== 'page'): ?>
                            <?= Html::hiddenInput($name, $value) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <label class="small text-secondary text-nowrap mb-0"><?= Yii::t('app', 'Records per page:') ?></label>
                    <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                        <option value="10" <?= $pages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                        <option value="20" <?= $pages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= $pages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $pages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                    </select>
                </form>
            </div>
            <div>
                <?= \yii\bootstrap5\LinkPager::widget([
                    'pagination' => $pages,
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
</div>
