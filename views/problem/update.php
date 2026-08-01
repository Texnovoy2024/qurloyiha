<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Problem $model */
/** @var app\models\Category[] $categories */

use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Update Problem') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Problems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Problem');
?>
<div class="problem-update">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-4 text-center fw-bold text-dark h4"><?= Yii::t('app', 'Update Problem') ?></h2>
                
                <?= $this->render('_form', [
                    'model' => $model,
                    'categories' => $categories,
                ]) ?>
            </div>
        </div>
    </div>
</div>
