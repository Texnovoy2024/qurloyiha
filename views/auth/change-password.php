<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ChangePasswordForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Change Password');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['/profile/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-light bg-white p-4 my-5">
            <h2 class="text-center mb-4 fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>

            <?php $form = ActiveForm::begin([
                'id' => 'change-password-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'form-label'],
                    'inputOptions' => ['class' => 'form-control form-control-sm'],
                    'errorOptions' => ['class' => 'invalid-feedback d-block'],
                ],
            ]); ?>

            <?= $form->field($model, 'old_password')->passwordInput(['placeholder' => Yii::t('app', 'Enter current password')]) ?>

            <?= $form->field($model, 'new_password')->passwordInput(['placeholder' => Yii::t('app', 'Minimum 6 characters')]) ?>

            <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => Yii::t('app', 'Repeat new password')]) ?>

            <div class="d-grid gap-2 mt-4">
                <?= Html::submitButton('<i class="bi bi-save me-1"></i> ' . Yii::t('app', 'Change Password'), ['class' => 'btn btn-primary btn-sm']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
