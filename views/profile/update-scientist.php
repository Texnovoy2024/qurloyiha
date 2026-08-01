<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\ScientistProfile $profile */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Update Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="profile-update-scientist">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-4 text-center fw-bold text-dark h4"><?= Yii::t('app', 'Update Scientist Profile') ?></h2>

                <?php $form = ActiveForm::begin([
                    'id' => 'profile-scientist-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control form-control-sm'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'first_name')->textInput(['placeholder' => Yii::t('app', 'First name')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'last_name')->textInput(['placeholder' => Yii::t('app', 'Last name')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'dob')->textInput(['type' => 'date']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'academic_degree')->textInput(['placeholder' => 'e.g. PhD, DSc']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'academic_title')->textInput(['placeholder' => 'e.g. Professor, Associate Professor']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'institution')->textInput(['placeholder' => 'e.g. Academy of Sciences, TSTU']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'specialization')->textInput(['placeholder' => 'e.g. Nanotechnology, Smart Materials']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'phone')->textInput(['placeholder' => '+998 90 123 4567']) ?>
                    </div>
                </div>

                <?= $form->field($profile, 'bio')->textarea(['rows' => 4, 'placeholder' => Yii::t('app', 'Short biography...')]) ?>

                <?= $form->field($profile, 'skills')->textarea(['rows' => 3, 'placeholder' => Yii::t('app', 'List your core technical/scientific skills, separated by commas...')]) ?>

                <?= $form->field($profile, 'publications')->textarea(['rows' => 4, 'placeholder' => Yii::t('app', 'List your key scientific publications or papers...')]) ?>

                <?= $form->field($profile, 'certificates')->textarea(['rows' => 3, 'placeholder' => Yii::t('app', 'List your academic certificates or honors...')]) ?>

                <?= $form->field($profile, 'portfolio')->textarea(['rows' => 3, 'placeholder' => Yii::t('app', 'Brief details of projects or academic works you have led...')]) ?>

                <div class="d-grid mt-4">
                    <?= Html::submitButton('<i class="bi bi-save me-1"></i> ' . Yii::t('app', 'Save Changes'), ['class' => 'btn btn-primary btn-sm']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
