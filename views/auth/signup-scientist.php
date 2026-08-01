<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Register as Scientist');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signup'), 'url' => ['signup']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-signup-scientist">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-4 text-center fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>
                
                <?php $form = ActiveForm::begin([
                    'id' => 'signup-scientist-form',
                    'options' => ['enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control form-control-sm'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <?= $form->field($model, 'role')->hiddenInput(['value' => 'scientist'])->label(false) ?>

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

                <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary"><?= Yii::t('app', 'Academic & Professional Information') ?></h5>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'first_name')->textInput(['placeholder' => Yii::t('app', 'e.g., Alisher')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'last_name')->textInput(['placeholder' => Yii::t('app', 'e.g., Usmanov')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'dob')->textInput(['type' => 'date', 'placeholder' => Yii::t('app', 'Date of Birth')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'academic_degree')->textInput(['placeholder' => 'e.g., DSc, PhD, MSc']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'academic_title')->textInput(['placeholder' => 'e.g., Professor, Associate Professor (optional)']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'institution')->textInput(['placeholder' => 'e.g., Tashkent State Technical University']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'specialization')->textInput(['placeholder' => 'e.g., Concrete Nano-additives, Smart Pavements']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'scientist_phone')->textInput(['placeholder' => 'e.g., +998 90 987 6543']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'uploaded_photo')->fileInput(['class' => 'form-control form-control-sm']) ?>

                <?= $form->field($model, 'bio')->textarea(['rows' => 4, 'placeholder' => Yii::t('app', 'Describe your academic background, areas of expertise, and key research achievements')]) ?>

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
