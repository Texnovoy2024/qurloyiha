<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var app\models\Notification[] $notifications */
/** @var yii\data\Pagination $pages */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-light bg-white p-4">
                <h2 class="mb-4 fw-bold text-dark h4"><i class="bi bi-bell me-2"></i><?= Html::encode($this->title) ?></h2>

                <?php if (empty($notifications)): ?>
                    <p class="text-secondary py-4 text-center"><?= Yii::t('app', 'No items found.') ?></p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="list-group-item py-3 px-0 border-0 border-bottom">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-dark"><?= Html::encode($notification->title) ?></h6>
                                    <small class="text-secondary"><?= date('d.m.Y H:i', $notification->created_at) ?></small>
                                </div>
                                <p class="mb-2 text-secondary small"><?= Html::encode($notification->message) ?></p>
                                <?php if ($notification->link): ?>
                                    <a href="<?= Url::to([$notification->link]) ?>" class="btn btn-xs btn-outline-primary px-3 mt-1 small" style="font-size: 0.8rem;">
                                        <?= Yii::t('app', 'View Details') ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination & Page Size selector -->
                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                        <div>
                            <form method="get" class="d-flex align-items-center gap-2">
                                <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                                    <?php if ($name !== 'per-page' && $name !== 'page'): ?>
                                        <?= Html::hiddenInput($name, $value) ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <label class="small text-secondary text-nowrap mb-0"><?= Yii::t('app', 'Records per page:') ?></label>
                                <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                                    <option value="10" <?= $pages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                                    <option value="20" <?= $pages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                                    <option value="50" <?= $pages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                                    <option value="100" <?= $pages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                                </select>
                            </form>
                        </div>
                        <div>
                            <?= \yii\bootstrap5\LinkPager::widget([
                                'pagination' => $pages,
                                'maxButtonCount' => 5,
                                'options' => ['class' => 'pagination pagination-sm mb-0'],
                                'linkContainerOptions' => ['class' => 'page-item'],
                                'linkOptions' => ['class' => 'page-link'],
                                'disabledPageCssClass' => 'disabled',
                                'activePageCssClass' => 'active',
                            ]) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
