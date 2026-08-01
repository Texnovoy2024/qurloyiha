<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-light bg-white p-4 my-5">
            <h2 class="text-center mb-3 fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>
            <p class="text-center text-secondary small mb-4"><?= Yii::t('app', 'Note: Contracts and payments are handled outside the platform.') ?></p>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control form-control-sm'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'admin, company1, scientist1...']) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'admin123, company123, scientist123...']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"form-check mb-3\">{input} {label}</div>\n{error}",
                'class' => 'form-check-input',
                'labelOptions' => ['class' => 'form-check-label'],
            ]) ?>

            <div class="d-grid gap-2">
                <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-sm', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <hr class="my-4">

            <div class="text-center small">
                <span class="text-secondary"><?= Yii::t('app', 'New to Qurilish-loyiha.uz?') ?></span>
                <a href="<?= \yii\helpers\Url::to(['/auth/signup']) ?>" class="fw-bold ms-1 text-decoration-none"><?= Yii::t('app', 'Signup') ?></a>
            </div>
        </div>
    </div>
</div>
