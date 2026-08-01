<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-signup py-4">
    <h2 class="text-center mb-4 fw-bold text-dark h3"><?= Yii::t('app', 'Signup') ?></h2>
    
    <div class="row justify-content-center g-4">
        <!-- Company Option -->
        <div class="col-md-5">
            <div class="card border-light bg-white text-center p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3"><i class="bi bi-building fs-1 text-primary"></i></div>
                    <h3 class="mb-3 h5 text-dark"><?= Yii::t('app', 'I am a Company') ?></h3>
                    <p class="text-secondary small mb-4">
                        <?= Yii::t('app', 'Publish engineering challenges, evaluate solutions, and connect with global researchers and scientific experts.') ?>
                    </p>
                </div>
                <div class="d-grid mt-4">
                    <a href="<?= Url::to(['/auth/signup', 'role' => 'company']) ?>" class="btn btn-primary btn-sm">
                        <?= Yii::t('app', 'Register as Company') ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Scientist Option -->
        <div class="col-md-5">
            <div class="card border-light bg-white text-center p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3"><i class="bi bi-mortarboard fs-1 text-primary"></i></div>
                    <h3 class="mb-3 h5 text-dark"><?= Yii::t('app', 'I am a Scientist / Expert') ?></h3>
                    <p class="text-secondary small mb-4">
                        <?= Yii::t('app', 'Browse real-world engineering problems, propose innovative scientific solutions, and collaborate with industry leaders.') ?>
                    </p>
                </div>
                <div class="d-grid mt-4">
                    <a href="<?= Url::to(['/auth/signup', 'role' => 'scientist']) ?>" class="btn btn-outline-primary btn-sm">
                        <?= Yii::t('app', 'Register as Scientist') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 small">
        <span class="text-secondary"><?= Yii::t('app', 'Already have an account?') ?></span>
        <a href="<?= Url::to(['/auth/login']) ?>" class="fw-bold ms-1 text-decoration-none"><?= Yii::t('app', 'Login') ?></a>
    </div>
</div>
