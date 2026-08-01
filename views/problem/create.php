<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem $model */
/** @var app\models\Category[] $categories */

use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Create Problem');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Problems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-create">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-3 text-center fw-bold text-dark h4"><?= Html::encode($this->title) ?></h2>
                <p class="text-center text-secondary mb-4 small">
                    <?= Yii::t('app', 'Describe your engineering challenge in detail. Once submitted, it will be reviewed by administrators before going live.') ?>
                </p>
                
                <?= $this->render('_form', [
                    'model' => $model,
                    'categories' => $categories,
                ]) ?>
            </div>
        </div>
    </div>
</div>
