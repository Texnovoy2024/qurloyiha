<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<footer id="footer" class="mt-auto py-5 bg-white border-top border-light">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column: About -->
            <div class="col-md-4">
                <h5 class="fw-bold mb-3 text-primary">
                    Qurilish-loyiha.uz
                </h5>
                <p class="text-secondary small">
                    <?= Yii::t('app', 'Connecting construction and engineering companies with scientific experts to foster collaboration, accelerate innovation, and transfer technology.') ?>
                </p>
            </div>
            
            <!-- Middle Column: Quick Links -->
            <div class="col-md-4">
                <h5 class="fw-bold mb-3 text-dark"><?= Yii::t('app', 'Quick Links') ?></h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= Url::to(['/site/index']) ?>" class="text-secondary text-decoration-none"><?= Yii::t('app', 'Home') ?></a></li>
                    <li class="mb-2"><a href="<?= Url::to(['/problem/index']) ?>" class="text-secondary text-decoration-none"><?= Yii::t('app', 'Browse Challenges') ?></a></li>
                </ul>
            </div>
            
            <!-- Right Column: Legal -->
            <div class="col-md-4">
                <h5 class="fw-bold mb-3 text-dark"><?= Yii::t('app', 'Legal & Social') ?></h5>
                <ul class="list-unstyled small mb-3">
                    <li class="mb-2"><a href="<?= Url::to(['/site/privacy']) ?>" class="text-secondary text-decoration-none"><?= Yii::t('app', 'Privacy Policy') ?></a></li>
                    <li class="mb-2"><a href="<?= Url::to(['/site/terms']) ?>" class="text-secondary text-decoration-none"><?= Yii::t('app', 'Terms of Service') ?></a></li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4 border-light">
        
        <div class="d-flex flex-wrap justify-content-between align-items-center small text-secondary">
            <span>&copy; Qurilish-loyiha.uz <?= date('Y') ?>. <?= Yii::t('app', 'All rights reserved.') ?></span>
            <span><?= Yii::t('app', 'Made with') ?> <span class="text-danger">❤️</span> <?= Yii::t('app', 'for Construction Innovation') ?></span>
        </div>
    </div>
</footer>
