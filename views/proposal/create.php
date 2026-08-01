<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Proposal $model */
/** @var app\models\Problem $problem */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Submit Proposal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Problems'), 'url' => ['/problem/index']];
$this->params['breadcrumbs'][] = ['label' => $problem->title, 'url' => ['/problem/view', 'id' => $problem->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proposal-create">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-2 text-center fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>
                <h6 class="text-center text-secondary mb-4">
                    <?= Yii::t('app', 'For challenge:') ?> <strong><?= Html::encode($problem->title) ?></strong>
                </h6>

                <?php $form = ActiveForm::begin([
                    'id' => 'proposal-form',
                    'options' => ['enctype' => 'multipart/form-data'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control form-control-sm'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <?= $form->field($model, 'title')->textInput(['placeholder' => Yii::t('app', 'Summarize your proposal approach')]) ?>

                <?= $form->field($model, 'description')->textarea([
                    'rows' => 4,
                    'placeholder' => Yii::t('app', 'Provide a high-level executive summary of your proposed solution')
                ]) ?>

                <?= $form->field($model, 'solution_details')->textarea([
                    'rows' => 8,
                    'placeholder' => Yii::t('app', 'Provide complete details: academic rationale, formulas, experimental setup, materials required, and workflow...')
                ]) ?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'budget_offer')->textInput([
                            'type' => 'number',
                            'placeholder' => Yii::t('app', 'Your financial request (UZS)')
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'time_offer')->textInput([
                            'placeholder' => Yii::t('app', 'e.g., 3 Months, 1 Year')
                        ]) ?>
                    </div>
                </div>

                <?= $form->field($model, 'uploaded_files[]')->fileInput([
                    'class' => 'form-control form-control-sm',
                    'multiple' => true,
                ])->label(Yii::t('app', 'Upload Supporting Documents (Multiple files allowed)')) ?>

                <div class="row g-3 mt-4">
                    <div class="col-md-6 d-grid">
                        <?= Html::submitButton('<i class="bi bi-save me-1"></i> ' . Yii::t('app', 'Save as Draft'), [
                            'class' => 'btn btn-outline-primary btn-sm',
                            'name' => 'submit_action',
                            'value' => 'draft'
                        ]) ?>
                    </div>
                    <div class="col-md-6 d-grid">
                        <?= Html::submitButton('<i class="bi bi-send me-1"></i> ' . Yii::t('app', 'Submit Proposal'), [
                            'class' => 'btn btn-primary btn-sm',
                            'name' => 'submit_action',
                            'value' => 'submit'
                        ]) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
