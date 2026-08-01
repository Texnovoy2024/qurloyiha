<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem[] $recentProblems */
/** @var app\models\Category[] $categories */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Qurilish-loyiha.uz - Construction Innovation Platform');

// Fetch recent 3 active problems to show on landing page
$recentProblems = \app\models\Problem::find()
    ->where(['status' => \app\models\Problem::STATUS_ACTIVE])
    ->orderBy(['created_at' => SORT_DESC])
    ->limit(3)
    ->all();

// Count stats
$companiesCount = \app\models\User::find()->where(['role' => \app\models\User::ROLE_COMPANY])->count();
$scientistsCount = \app\models\User::find()->where(['role' => \app\models\User::ROLE_SCIENTIST])->count();
$problemsCount = \app\models\Problem::find()->where(['status' => \app\models\Problem::STATUS_ACTIVE])->count();
$solvedCount = \app\models\Problem::find()->where(['status' => \app\models\Problem::STATUS_SOLVED])->count();

$categories = \app\models\Category::find()->where(['status' => 10])->all();
?>
<div class="site-index">
    <!-- Hero Section -->
    <div class="card border-light bg-white py-5 mb-5 rounded-3 text-center">
        <div class="card-body py-4">
            <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 text-uppercase fw-semibold">
                <?= Yii::t('app', 'Bridging Industry & Academia') ?>
            </span>
            <h1 class="display-5 fw-bold text-dark mb-3">
                <?= Yii::t('app', 'Accelerating Construction Innovation') ?>
            </h1>
            <p class="fs-6 text-secondary mb-4 mx-auto" style="max-width: 700px;">
                <?= Yii::t('app', 'A secure collaborative environment where construction companies publish real-world technological challenges, and scientific experts submit cutting-edge solution proposals.') ?>
            </p>
            
            <!-- Hero Search Box -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-8 col-lg-6">
                    <form method="get" action="<?= Url::to(['/problem/index']) ?>" class="input-group border rounded-pill overflow-hidden bg-white">
                        <input type="text" name="search" class="form-control border-0 ps-4 py-3" placeholder="<?= Yii::t('app', 'Search tech challenges by keyword...') ?>">
                        <button type="submit" class="btn btn-primary px-4 border-0">
                            <i class="bi bi-search"></i> <?= Yii::t('app', 'Search') ?>
                        </button>
                    </form>
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="<?= Url::to(['/problem/index']) ?>" class="btn btn-outline-secondary px-4 py-2">
                    <?= Yii::t('app', 'Browse Challenges') ?>
                </a>
                <?php if (Yii::$app->user->isGuest): ?>
                    <a href="<?= Url::to(['/auth/signup']) ?>" class="btn btn-primary px-4 py-2">
                        <?= Yii::t('app', 'Join as Expert') ?>
                    </a>
                <?php else: ?>
                    <a href="<?= Url::to(['/dashboard/index']) ?>" class="btn btn-primary px-4 py-2">
                        <?= Yii::t('app', 'Go to Dashboard') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row g-4 text-center mb-5">
        <div class="col-md-3">
            <div class="card border-light bg-white py-4">
                <div class="display-6 fw-bold text-primary mb-1"><?= $companiesCount ?></div>
                <div class="text-secondary small fw-semibold text-uppercase"><?= Yii::t('app', 'Total Companies') ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-light bg-white py-4">
                <div class="display-6 fw-bold text-primary mb-1"><?= $scientistsCount ?></div>
                <div class="text-secondary small fw-semibold text-uppercase"><?= Yii::t('app', 'Total Scientists') ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-light bg-white py-4">
                <div class="display-6 fw-bold text-primary mb-1"><?= $problemsCount ?></div>
                <div class="text-secondary small fw-semibold text-uppercase"><?= Yii::t('app', 'Published Problems') ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-light bg-white py-4">
                <div class="display-6 fw-bold text-primary mb-1"><?= $solvedCount ?></div>
                <div class="text-secondary small fw-semibold text-uppercase"><?= Yii::t('app', 'Successful Collaborations') ?></div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1 text-dark"><?= Yii::t('app', 'Browse by Category') ?></h3>
            <p class="text-secondary mb-0 small"><?= Yii::t('app', 'Filter active challenges based on your scientific specialization') ?></p>
        </div>

        <div class="row g-3 justify-content-center">
            <?php foreach ($categories as $cat): ?>
                <div class="col-md-4 col-lg-3">
                    <a href="<?= Url::to(['/problem/index', 'category_id' => $cat->id]) ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card text-center p-3 h-100 border-light bg-white">
                            <h6 class="fw-bold mb-2 text-truncate text-primary"><?= Html::encode($cat->getName()) ?></h6>
                            <span class="badge bg-light text-secondary rounded-pill w-fit mx-auto">
                                <?= count($cat->problems) ?> <?= Yii::t('app', 'Problems') ?>
                            </span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Problems Grid -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark"><?= Yii::t('app', 'Recent Challenges') ?></h3>
                <p class="text-secondary mb-0 small"><?= Yii::t('app', 'Latest industrial challenges looking for innovative scientific methods') ?></p>
            </div>
            <a href="<?= Url::to(['/problem/index']) ?>" class="text-decoration-none fw-semibold small text-primary">
                <?= Yii::t('app', 'View All Problems') ?> <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php if (empty($recentProblems)): ?>
                <div class="col-12 text-center py-4">
                    <p class="text-secondary"><?= Yii::t('app', 'No active challenges available at the moment.') ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($recentProblems as $problem): ?>
                    <div class="col-md-4">
                        <div class="card h-100 d-flex flex-column justify-content-between p-3 border-light bg-white">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-secondary small"><?= Html::encode($problem->category->getName()) ?></span>
                                    <span class="small text-secondary"><i class="bi bi-eye"></i> <?= $problem->views_count ?></span>
                                </div>
                                <h5 class="fw-bold text-dark mb-2 text-truncate">
                                    <?= Html::encode($problem->title) ?>
                                </h5>
                                <p class="text-secondary small mb-3">
                                    <?= Html::encode(mb_strimwidth(strip_tags($problem->description), 0, 100, '...')) ?>
                                </p>
                            </div>
                            <div class="border-top pt-3 mt-2">
                                <div class="d-flex justify-content-between text-secondary small mb-3">
                                    <span><?= Yii::t('app', 'Budget') ?>: <strong class="text-dark"><?= number_format((float)$problem->budget) ?> UZS</strong></span>
                                    <span><?= Yii::t('app', 'Due') ?>: <strong class="text-dark"><?= Html::encode($problem->deadline) ?></strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-secondary text-truncate" style="max-width: 50%;">
                                        <i class="bi bi-building"></i> <?= Html::encode($problem->companyProfile->company_name ?? 'Company') ?>
                                    </span>
                                    <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="btn btn-sm btn-primary px-3">
                                        <?= Yii::t('app', 'View Details') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
