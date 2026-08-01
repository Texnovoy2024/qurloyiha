<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Register as Company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signup'), 'url' => ['signup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-signup-company">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-4 text-center fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>
                
                <?php $form = ActiveForm::begin([
                    'id' => 'signup-company-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control form-control-sm'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <?= $form->field($model, 'role')->hiddenInput(['value' => 'company'])->label(false) ?>

                <h5 class="mb-3 border-bottom pb-2 text-primary"><?= Yii::t('app', 'Login Info') ?></h5>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'Enter username')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['type' => 'email', 'placeholder' => Yii::t('app', 'Enter email address')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Minimum 6 characters')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => Yii::t('app', 'Repeat password')]) ?>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary"><?= Yii::t('app', 'Company Info') ?></h5>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'company_name')->textInput(['placeholder' => Yii::t('app', 'e.g., Apex Builders LLC')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'responsible_name')->textInput(['placeholder' => Yii::t('app', 'Full name of person in charge')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'stir')->textInput(['placeholder' => Yii::t('app', 'Taxpayer ID (optional)')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'industry')->textInput(['placeholder' => Yii::t('app', 'e.g., Civil Engineering, Roadways')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'website')->textInput(['placeholder' => 'e.g., https://example.com']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'company_phone')->textInput(['placeholder' => 'e.g., +998 71 123 4567']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'address')->textInput(['placeholder' => Yii::t('app', 'Enter street address')]) ?>

                <?= $form->field($model, 'company_description')->textarea(['rows' => 4, 'placeholder' => Yii::t('app', 'Briefly describe your company, products, and target innovations')]) ?>

                <!-- Terms and Conditions checkbox -->
                <?= $form->field($model, 'accept_terms')->checkbox([
                    'template' => "<div class=\"form-check mb-3 mt-3\">{input} {label}</div>\n{error}",
                    'class' => 'form-check-input',
                    'labelOptions' => ['class' => 'form-check-label'],
                    'label' => Yii::t('app', 'I accept the Terms and Conditions of the platform.'),
                ]) ?>

                <div class="d-grid mt-4">
                    <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary btn-sm']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
