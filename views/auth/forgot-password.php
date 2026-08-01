<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Forgot Password');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Login'), 'url' => ['login']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-light bg-white p-4 my-5">
            <h2 class="text-center mb-3 fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>
            <p class="text-center text-secondary small mb-4"><?= Yii::t('app', 'Enter your email address and we will send you instructions to reset your password.') ?></p>

            <?= Html::beginForm(['/auth/forgot-password'], 'post', ['id' => 'forgot-password-form']) ?>

            <div class="mb-4">
                <label for="email-input" class="form-label"><?= Yii::t('app', 'Email Address') ?></label>
                <?= Html::textInput('email', '', [
                    'id' => 'email-input',
                    'class' => 'form-control form-control-sm',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => Yii::t('app', 'Enter registered email')
                ]) ?>
            </div>

            <div class="d-grid gap-2">
                <?= Html::submitButton(Yii::t('app', 'Send Reset Link'), ['class' => 'btn btn-primary btn-sm']) ?>
            </div>

            <?= Html::endForm() ?>

            <hr class="my-4">

            <div class="text-center small">
                <a href="<?= Url::to(['login']) ?>" class="fw-bold text-decoration-none"><?= Yii::t('app', 'Back to Login') ?></a>
            </div>
        </div>
    </div>
</div>
