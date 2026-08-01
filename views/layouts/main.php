<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

$this->render('_head');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
<head>
    <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?= $this->render('_header') ?>

<main id="main" class="flex-grow-1" role="main">
    <?php if (Yii::$app->user->isGuest): ?>
        <div class="container py-4">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <div class="mb-3">
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'],
                        'options' => ['class' => 'breadcrumb bg-white border border-light p-2 px-3 rounded-3 small shadow-sm']
                    ]) ?>
                </div>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    <?php else: ?>
        <div class="container-xl py-4">
            <div class="row g-4">
                <!-- Sidebar -->
                <aside class="col-lg-3 col-md-4">
                    <div class="sidebar-card p-3">
                        <nav class="nav flex-column gap-1">
                            <a class="nav-link <?= Yii::$app->controller->id === 'dashboard' ? 'active' : '' ?>" href="<?= Url::to(['/dashboard/index']) ?>">
                                <i class="bi bi-grid-fill"></i> <?= Yii::t('app', 'Dashboard') ?>
                            </a>
                            <a class="nav-link <?= Yii::$app->controller->id === 'problem' ? 'active' : '' ?>" href="<?= Url::to(['/problem/index']) ?>">
                                <i class="bi bi-building-fill"></i> <?= Yii::t('app', 'Problems') ?>
                            </a>
                            <a class="nav-link <?= Yii::$app->controller->id === 'profile' || Yii::$app->controller->id === 'auth' ? 'active' : '' ?>" href="<?= Url::to(['/profile/index']) ?>">
                                <i class="bi bi-person-fill-gear"></i> <?= Yii::t('app', 'Settings') ?>
                            </a>
                        </nav>
                    </div>
                </aside>
                
                <!-- Main Content Panel -->
                <div class="col-lg-9 col-md-8">
                    <?php if (!empty($this->params['breadcrumbs'])): ?>
                        <div class="mb-3">
                            <?= Breadcrumbs::widget([
                                'links' => $this->params['breadcrumbs'],
                                'options' => ['class' => 'breadcrumb bg-white border border-light p-2 px-3 rounded-3 small shadow-sm']
                            ]) ?>
                        </div>
                    <?php endif ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
