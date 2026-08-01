<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\CompanyProfile $profile */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Update Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="profile-update-company">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-4 text-center fw-bold text-dark h4"><?= Yii::t('app', 'Update Company Profile') ?></h2>

                <?php $form = ActiveForm::begin([
                    'id' => 'profile-company-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control form-control-sm'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <?= $form->field($profile, 'company_name')->textInput(['placeholder' => Yii::t('app', 'e.g., Apex Builders LLC')]) ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'responsible_name')->textInput(['placeholder' => Yii::t('app', 'Person in charge name')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'stir')->textInput(['placeholder' => Yii::t('app', 'TIN number')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'organization_type')->textInput(['placeholder' => Yii::t('app', 'e.g., Joint Stock Company, LLC, University')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'industry')->textInput(['placeholder' => Yii::t('app', 'e.g., Construction, Materials Science')]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($profile, 'website')->textInput(['placeholder' => 'https://example.com']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($profile, 'phone')->textInput(['placeholder' => '+998 71 123 4567']) ?>
                    </div>
                </div>

                <?= $form->field($profile, 'address')->textInput(['placeholder' => Yii::t('app', 'Tashkent, Chilonzor district...')]) ?>

                <?= $form->field($profile, 'description')->textarea(['rows' => 5, 'placeholder' => Yii::t('app', 'Briefly describe your company, products, and innovations')]) ?>

                <div class="d-grid mt-4">
                    <?= Html::submitButton('<i class="bi bi-save me-1"></i> ' . Yii::t('app', 'Save Changes'), ['class' => 'btn btn-primary btn-sm']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
