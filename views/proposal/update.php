<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Proposal $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Update Proposal') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => $model->problem->title, 'url' => ['/problem/view', 'id' => $model->problem_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="proposal-update">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-3 text-center fw-bold text-dark h4"><?= Yii::t('app', 'Update Proposal') ?></h2>
                <h6 class="text-center text-secondary mb-4">
                    <?= Yii::t('app', 'For challenge:') ?> <strong><?= Html::encode($model->problem->title) ?></strong>
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
                
                <?php if (!empty($model->files)): ?>
                    <div class="mb-3 mt-2">
                        <label class="form-label d-block"><?= Yii::t('app', 'Currently Uploaded Files') ?></label>
                        <div class="list-group">
                            <?php foreach ($model->files as $file): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center bg-light border p-2 mb-1">
                                    <span>
                                        <i class="bi bi-file-earmark me-1 text-secondary"></i> <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank" class="text-decoration-none text-dark"><?= Html::encode($file->file_name) ?></a>
                                        <small class="text-secondary">(<?= number_format($file->file_size / 1024, 1) ?> KB)</small>
                                    </span>
                                    <a href="<?= Url::to(['/proposal/delete-file', 'id' => $file->id]) ?>" 
                                       class="btn btn-sm btn-outline-danger py-0 px-2 rounded" 
                                       data-method="post" 
                                       data-confirm="<?= Yii::t('app', 'Are you sure you want to delete this file?') ?>">
                                       <?= Yii::t('app', 'Delete') ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($model->document_file): ?>
                    <div class="mt-2 small text-secondary mb-3">
                        <?= Yii::t('app', 'Legacy attachment:') ?> <a href="<?= Html::encode(Yii::getAlias('@web/' . $model->document_file)) ?>" target="_blank" class="text-decoration-none"><?= basename($model->document_file) ?></a>
                    </div>
                <?php endif; ?>

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
