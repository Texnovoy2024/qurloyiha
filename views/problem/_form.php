<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem $model */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\Category[] $categories */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$categoryList = ArrayHelper::map($categories, 'id', function ($cat) {
    return $cat->getName();
});
?>
<?php $form = ActiveForm::begin([
    'id' => 'problem-form',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'form-label'],
        'inputOptions' => ['class' => 'form-control form-control-sm'],
        'errorOptions' => ['class' => 'invalid-feedback d-block'],
    ],
]); ?>

<?= $form->field($model, 'category_id')->dropDownList($categoryList, [
    'prompt' => Yii::t('app', 'Filter by Category'),
    'class' => 'form-select form-select-sm'
]) ?>

<?= $form->field($model, 'title')->textInput(['placeholder' => Yii::t('app', 'Summarize the core technical problem')]) ?>

<?= $form->field($model, 'description')->textarea([
    'rows' => 6,
    'placeholder' => Yii::t('app', 'Explain the physical, engineering, or structural issue in detail, including what has been tried...')
]) ?>

<?= $form->field($model, 'expected_result')->textarea([
    'rows' => 4,
    'placeholder' => Yii::t('app', 'Explain expected results, outputs, models, prototypes, or test values requested from scientists...')
]) ?>

<?= $form->field($model, 'requirements')->textarea([
    'rows' => 4,
    'placeholder' => Yii::t('app', 'List scientific constraints, required qualifications, materials, testing guidelines, etc. (optional)')
]) ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'budget')->textInput([
            'type' => 'number',
            'placeholder' => Yii::t('app', 'Enter budget in UZS (optional)')
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'deadline_date')->textInput([
            'type' => 'date',
            'value' => $model->deadline_date ?: ''
        ]) ?>
    </div>
</div>

<?= $form->field($model, 'uploaded_attachments[]')->fileInput([
    'class' => 'form-control form-control-sm',
    'multiple' => true,
])->label(Yii::t('app', 'Upload Specifications / Supporting files (Multiple files allowed)')) ?>

<?php if (!$model->isNewRecord && !empty($model->files)): ?>
    <div class="mb-3">
        <label class="form-label d-block"><?= Yii::t('app', 'Currently Uploaded Files') ?></label>
        <div class="list-group">
            <?php foreach ($model->files as $file): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center bg-light border p-2 mb-1">
                    <span>
                        <i class="bi bi-file-earmark me-1 text-secondary"></i> <a href="<?= Url::to('@web/' . $file->file_path) ?>" target="_blank" class="text-decoration-none text-dark"><?= Html::encode($file->file_name) ?></a>
                        <small class="text-secondary">(<?= number_format($file->file_size / 1024, 1) ?> KB)</small>
                    </span>
                    <a href="<?= Url::to(['/problem/delete-file', 'id' => $file->id]) ?>" 
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

<div class="d-grid mt-4">
    <?= Html::submitButton(
        $model->isNewRecord ? Yii::t('app', 'Create Problem') : Yii::t('app', 'Update Problem'),
        ['class' => 'btn btn-primary btn-sm']
    ) ?>
</div>

<?php ActiveForm::end(); ?>
