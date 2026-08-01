<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$currentUrl = Yii::$app->request->url;

$centerItems = [
    [
        'label' => Yii::t('app', 'Home'),
        'url' => ['/site/index'],
    ],
    [
        'label' => Yii::t('app', 'Problems'),
        'url' => ['/problem/index'],
    ],
];

$rightItems = [];

if (!Yii::$app->user->isGuest) {
    $unreadCount = \app\models\Notification::find()->where(['user_id' => Yii::$app->user->id, 'is_read' => false])->count();
    
    $rightItems[] = [
        'label' => Yii::t('app', 'Dashboard'),
        'url' => ['/dashboard/index'],
    ];
    $rightItems[] = [
        'label' => Yii::t('app', 'Notifications') . ($unreadCount > 0 ? " <span class='badge bg-danger ms-1'>$unreadCount</span>" : ''),
        'url' => ['/notification/index'],
    ];
    $rightItems[] = [
        'label' => Yii::t('app', 'Profile'),
        'url' => ['/profile/index'],
    ];
    $rightItems[] = [
        'label' => Yii::t('app', 'Logout') . ' (' . Html::encode(Yii::$app->user->identity->username) . ')',
        'url' => ['/auth/logout'],
        'linkOptions' => [
            'data-method' => 'post',
            'class' => 'nav-link text-danger-hover text-danger fw-semibold',
        ],
    ];
} else {
    $rightItems[] = [
        'label' => Yii::t('app', 'Login'),
        'url' => ['/auth/login'],
    ];
    $rightItems[] = [
        'label' => Yii::t('app', 'Signup'),
        'url' => ['/auth/signup'],
    ];
}
?>
<header id="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm py-2" data-bs-theme="light">
        <div class="container-xl">
            <a class="navbar-brand text-decoration-none" href="<?= Yii::$app->homeUrl ?>">
                <span class="text-primary fw-bold">Qurilish-loyiha</span><span class="text-secondary fw-semibold">.uz</span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Center navigation -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <?php foreach ($centerItems as $item): ?>
                        <?php 
                        $itemUrl = Url::to($item['url']);
                        $isActive = ($itemUrl === $currentUrl || ($itemUrl !== '/' && strpos($currentUrl, $itemUrl) === 0));
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $isActive ? 'active' : '' ?>" href="<?= $itemUrl ?>">
                                <?= Html::encode($item['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Right navigation -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3">
                    <ul class="navbar-nav mb-2 mb-lg-0 gap-1 align-items-lg-center">
                        <?php foreach ($rightItems as $item): ?>
                            <?php 
                            $itemUrl = Url::to($item['url']);
                            $isActive = ($itemUrl === $currentUrl || ($itemUrl !== '/' && strpos($currentUrl, $itemUrl) === 0));
                            $class = isset($item['linkOptions']['class']) ? $item['linkOptions']['class'] : 'nav-link';
                            $method = isset($item['linkOptions']['data-method']) ? 'data-method="' . $item['linkOptions']['data-method'] . '"' : '';
                            ?>
                            <li class="nav-item">
                                <a class="<?= $class ?> <?= $isActive ? 'active' : '' ?>" href="<?= $itemUrl ?>" <?= $method ?>>
                                    <?= $item['label'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Language Selector -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle btn-sm px-3" type="button" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe me-1"></i> <?= strtoupper(Yii::$app->language) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item <?= Yii::$app->language === 'uz' ? 'active' : '' ?>" href="<?= Url::to(['/profile/change-language', 'lang' => 'uz']) ?>">O'zbekcha</a></li>
                            <li><a class="dropdown-item <?= Yii::$app->language === 'ru' ? 'active' : '' ?>" href="<?= Url::to(['/profile/change-language', 'lang' => 'ru']) ?>">Русский</a></li>
                            <li><a class="dropdown-item <?= Yii::$app->language === 'en' ? 'active' : '' ?>" href="<?= Url::to(['/profile/change-language', 'lang' => 'en']) ?>">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
