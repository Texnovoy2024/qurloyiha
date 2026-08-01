<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var int $usersCount */
/** @var int $problemsCount */
/** @var int $proposalsCount */
/** @var app\models\Problem[] $pendingProblems */
/** @var app\models\Category[] $categories */
/** @var app\models\User[] $companies */
/** @var app\models\User[] $scientists */
/** @var app\models\AuditLog[] $auditLogs */
/** @var app\models\Category $newCategory */
/** @var yii\data\Pagination $companiesPages */
/** @var yii\data\Pagination $scientistsPages */
/** @var yii\data\Pagination $auditLogsPages */
/** @var array $backups */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Admin Panel');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard-admin">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 text-dark"><?= Html::encode($this->title) ?></h2>
        <div class="d-flex gap-2">
            <a href="<?= Url::to(['/dashboard/export-csv']) ?>" class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i> <?= Yii::t('app', 'Export CSV Report') ?>
            </a>
            <a href="<?= Url::to(['/dashboard/export-print']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i> <?= Yii::t('app', 'Print PDF Summary') ?>
            </a>
        </div>
    </div>

    <!-- Stats row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Total Users') ?></div>
                <div class="h3 fw-bold text-dark mb-0"><?= $usersCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Total Problems') ?></div>
                <div class="h3 fw-bold text-dark mb-0"><?= $problemsCount ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light bg-white p-3">
                <div class="text-secondary small mb-1"><?= Yii::t('app', 'Total Proposals') ?></div>
                <div class="h3 fw-bold text-dark mb-0"><?= $proposalsCount ?></div>
            </div>
        </div>
    </div>

    <!-- Main Admin Sections -->
    <div class="row g-4">
        <!-- Moderation & User Management -->
        <div class="col-lg-8">
            <!-- Problem Moderation Queue -->
            <div class="card border-light bg-white p-3 mb-4">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-hourglass-split me-2 text-warning"></i><?= Yii::t('app', 'Pending Moderation') ?></h5>
                <?php if (empty($pendingProblems)): ?>
                    <p class="text-secondary mb-0"><?= Yii::t('app', 'No items found.') ?></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Title') ?></th>
                                    <th><?= Yii::t('app', 'Company') ?></th>
                                    <th><?= Yii::t('app', 'Category') ?></th>
                                    <th class="text-end"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingProblems as $problem): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= Url::to(['/problem/view', 'id' => $problem->id]) ?>" class="fw-semibold text-decoration-none text-dark">
                                                <?= Html::encode($problem->title) ?>
                                            </a>
                                        </td>
                                        <td><span class="small text-secondary"><i class="bi bi-building me-1"></i><?= Html::encode($problem->companyProfile->company_name ?? $problem->company->username) ?></span></td>
                                        <td><span class="badge bg-light text-secondary"><?= Html::encode($problem->category->getName()) ?></span></td>
                                        <td class="text-end">
                                            <a href="<?= Url::to(['/problem/approve', 'id' => $problem->id]) ?>" class="btn btn-sm btn-success px-3 me-1" data-method="post"><?= Yii::t('app', 'Approve') ?></a>
                                            <a href="<?= Url::to(['/problem/spam', 'id' => $problem->id]) ?>" class="btn btn-sm btn-danger px-3" data-method="post"><?= Yii::t('app', 'Spam') ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- User Moderation / Blocking Tabs -->
            <div class="card border-light bg-white p-3 mb-4">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-people me-2 text-primary"></i><?= Yii::t('app', 'User Account Management') ?></h5>
                
                <ul class="nav nav-tabs mb-3" id="userManagementTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="companies-tab" data-bs-toggle="tab" data-bs-target="#companies" type="button" role="tab" aria-controls="companies" aria-selected="true">
                            <?= Yii::t('app', 'Companies') ?> (<?= count($companies) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="scientists-tab" data-bs-toggle="tab" data-bs-target="#scientists" type="button" role="tab" aria-controls="scientists" aria-selected="false">
                            <?= Yii::t('app', 'Scientists / Experts') ?> (<?= count($scientists) ?>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="userManagementTabContent">
                    <!-- Companies user list -->
                    <div class="tab-pane fade show active" id="companies" role="tabpanel" aria-labelledby="companies-tab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th><?= Yii::t('app', 'Company Name') ?></th>
                                        <th><?= Yii::t('app', 'Username') ?></th>
                                        <th><?= Yii::t('app', 'Email') ?></th>
                                        <th><?= Yii::t('app', 'Status') ?></th>
                                        <th class="text-end"><?= Yii::t('app', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($companies as $compUser): ?>
                                        <tr>
                                            <td>
                                                <strong class="d-block text-dark"><?= Html::encode($compUser->companyProfile->company_name ?? 'N/A') ?></strong>
                                                <small class="text-secondary"><?= Html::encode($compUser->companyProfile->industry ?? 'No Industry') ?></small>
                                            </td>
                                            <td><?= Html::encode($compUser->username) ?></td>
                                            <td><small><?= Html::encode($compUser->email) ?></small></td>
                                            <td>
                                                <span class="badge <?= $compUser->status === \app\models\User::STATUS_ACTIVE ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $compUser->status === \app\models\User::STATUS_ACTIVE ? Yii::t('app', 'Active') : Yii::t('app', 'Blocked') ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($compUser->status === \app\models\User::STATUS_ACTIVE): ?>
                                                    <a href="<?= Url::to(['block-user', 'id' => $compUser->id]) ?>" class="btn btn-sm btn-outline-danger px-3"><?= Yii::t('app', 'Block') ?></a>
                                                <?php else: ?>
                                                    <a href="<?= Url::to(['unblock-user', 'id' => $compUser->id]) ?>" class="btn btn-sm btn-success px-3"><?= Yii::t('app', 'Unblock') ?></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination for Companies -->
                        <?php if (!empty($companies)): ?>
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                <div>
                                    <form method="get" class="d-flex align-items-center gap-2">
                                        <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                                            <?php if ($name !== 'per-page' && $name !== 'page' && $name !== 'companies-page'): ?>
                                                <?= Html::hiddenInput($name, $value) ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                                            <option value="10" <?= $companiesPages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                                            <option value="20" <?= $companiesPages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                                            <option value="50" <?= $companiesPages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                                            <option value="100" <?= $companiesPages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                                        </select>
                                    </form>
                                </div>
                                <div>
                                    <?= \yii\bootstrap5\LinkPager::widget([
                                        'pagination' => $companiesPages,
                                        'maxButtonCount' => 3,
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

                    <!-- Scientists user list -->
                    <div class="tab-pane fade" id="scientists" role="tabpanel" aria-labelledby="scientists-tab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th><?= Yii::t('app', 'First Name') ?> / <?= Yii::t('app', 'Last Name') ?></th>
                                        <th><?= Yii::t('app', 'Username') ?></th>
                                        <th><?= Yii::t('app', 'Email') ?></th>
                                        <th><?= Yii::t('app', 'Status') ?></th>
                                        <th class="text-end"><?= Yii::t('app', 'Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scientists as $scUser): ?>
                                        <tr>
                                            <td>
                                                <strong class="d-block text-dark"><?= Html::encode($scUser->scientistProfile ? $scUser->scientistProfile->getFullName() : 'N/A') ?></strong>
                                                <small class="text-secondary"><?= Html::encode($scUser->scientistProfile->academic_degree ?? 'No degree') ?> - <?= Html::encode($scUser->scientistProfile->institution ?? 'No Inst') ?></small>
                                            </td>
                                            <td><?= Html::encode($scUser->username) ?></td>
                                            <td><small><?= Html::encode($scUser->email) ?></small></td>
                                            <td>
                                                <span class="badge <?= $scUser->status === \app\models\User::STATUS_ACTIVE ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $scUser->status === \app\models\User::STATUS_ACTIVE ? Yii::t('app', 'Active') : Yii::t('app', 'Blocked') ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($scUser->status === \app\models\User::STATUS_ACTIVE): ?>
                                                    <a href="<?= Url::to(['block-user', 'id' => $scUser->id]) ?>" class="btn btn-sm btn-outline-danger px-3"><?= Yii::t('app', 'Block') ?></a>
                                                <?php else: ?>
                                                    <a href="<?= Url::to(['unblock-user', 'id' => $scUser->id]) ?>" class="btn btn-sm btn-success px-3"><?= Yii::t('app', 'Unblock') ?></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination for Scientists -->
                        <?php if (!empty($scientists)): ?>
                            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                <div>
                                    <form method="get" class="d-flex align-items-center gap-2">
                                        <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                                            <?php if ($name !== 'per-page' && $name !== 'page' && $name !== 'scientists-page'): ?>
                                                <?= Html::hiddenInput($name, $value) ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                                            <option value="10" <?= $scientistsPages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                                            <option value="20" <?= $scientistsPages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                                            <option value="50" <?= $scientistsPages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                                            <option value="100" <?= $scientistsPages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                                        </select>
                                    </form>
                                </div>
                                <div>
                                    <?= \yii\bootstrap5\LinkPager::widget([
                                        'pagination' => $scientistsPages,
                                        'maxButtonCount' => 3,
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

            <!-- System Settings Panel -->
            <div class="card border-light bg-white p-3 mb-4">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-sliders me-2 text-primary"></i><?= Yii::t('app', 'System Configuration Settings') ?></h5>
                <?php $settingsForm = ActiveForm::begin([
                    'action' => ['/dashboard/save-settings'],
                    'method' => 'post',
                ]); ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><?= Yii::t('app', 'Site Name') ?></label>
                        <?= Html::textInput('Setting[site_name]', \app\models\Setting::getValue('site_name', 'Qurilish-loyiha.uz'), ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><?= Yii::t('app', 'Session Timeout (Seconds)') ?></label>
                        <?= Html::textInput('Setting[session_timeout]', \app\models\Setting::getValue('session_timeout', '1440'), ['class' => 'form-control', 'type' => 'number']) ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><?= Yii::t('app', 'Max File Upload (Bytes)') ?></label>
                        <?= Html::textInput('Setting[max_file_size]', \app\models\Setting::getValue('max_file_size', '52428800'), ['class' => 'form-control', 'type' => 'number']) ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><?= Yii::t('app', 'Maintenance Mode') ?></label>
                        <?= Html::dropDownList('Setting[maintenance_mode]', \app\models\Setting::getValue('maintenance_mode', '0'), [
                            '0' => Yii::t('app', 'Disabled (Online)'),
                            '1' => Yii::t('app', 'Enabled (Offline)')
                        ], ['class' => 'form-select']) ?>
                    </div>
                    <div class="col-12 text-end mt-3">
                        <?= Html::submitButton('<i class="bi bi-save me-1"></i> ' . Yii::t('app', 'Save Settings'), ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

            <!-- Audit Logs Journal list -->
            <div class="card border-light bg-white p-3">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-shield-check me-2 text-danger"></i><?= Yii::t('app', 'Platform Security Audit Logs') ?></h5>
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle mb-0 small">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Timestamp') ?></th>
                                <th><?= Yii::t('app', 'User') ?></th>
                                <th><?= Yii::t('app', 'Actions') ?></th>
                                <th><?= Yii::t('app', 'Description') ?></th>
                                <th><?= Yii::t('app', 'IP Address') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($auditLogs as $log): ?>
                                <tr>
                                    <td><span class="text-secondary font-monospace"><?= date('Y-m-d H:i:s', $log->created_at) ?></span></td>
                                    <td>
                                        <span class="fw-semibold text-dark">
                                            <?= $log->user ? Html::encode($log->user->username) : '<span class="text-secondary">Guest</span>' ?>
                                        </span>
                                    </td>
                                    <td><span class="badge bg-secondary"><?= Html::encode($log->action) ?></span></td>
                                    <td><small class="text-secondary"><?= Html::encode($log->details) ?></small></td>
                                    <td><span class="text-secondary font-monospace"><?= Html::encode($log->ip_address) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination for Audit Logs -->
                <?php if (!empty($auditLogs)): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <div>
                            <form method="get" class="d-flex align-items-center gap-2">
                                <?php foreach (Yii::$app->request->get() as $name => $value): ?>
                                    <?php if ($name !== 'per-page' && $name !== 'page' && $name !== 'audit-page'): ?>
                                        <?= Html::hiddenInput($name, $value) ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <select name="per-page" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 70px;">
                                    <option value="10" <?= $auditLogsPages->pageSize == 10 ? 'selected' : '' ?>>10</option>
                                    <option value="20" <?= $auditLogsPages->pageSize == 20 ? 'selected' : '' ?>>20</option>
                                    <option value="50" <?= $auditLogsPages->pageSize == 50 ? 'selected' : '' ?>>50</option>
                                    <option value="100" <?= $auditLogsPages->pageSize == 100 ? 'selected' : '' ?>>100</option>
                                </select>
                            </form>
                        </div>
                        <div>
                            <?= \yii\bootstrap5\LinkPager::widget([
                                'pagination' => $auditLogsPages,
                                'maxButtonCount' => 3,
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

        <!-- Quick Category Form & List -->
        <div class="col-lg-4">
            <div class="card border-light bg-white p-3 mb-4">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-tag me-2 text-success"></i><?= Yii::t('app', 'Quick Create Category') ?></h5>
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control form-control-sm'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <?= $form->field($newCategory, 'name_uz')->textInput(['placeholder' => 'Nomi (UZ)']) ?>
                <?= $form->field($newCategory, 'name_ru')->textInput(['placeholder' => 'Название (RU)']) ?>
                <?= $form->field($newCategory, 'name_en')->textInput(['placeholder' => 'Name (EN)']) ?>
                <?= $form->field($newCategory, 'description')->textarea(['rows' => 2, 'placeholder' => 'Description']) ?>

                <div class="d-grid mt-3">
                    <?= Html::submitButton('<i class="bi bi-plus-lg me-1"></i> ' . Yii::t('app', 'Add Category'), ['class' => 'btn btn-primary btn-sm']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

            <div class="card border-light bg-white p-3">
                <h5 class="card-title text-dark mb-3"><i class="bi bi-list-task me-2 text-primary"></i><?= Yii::t('app', 'Categories') ?></h5>
                <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                    <?php foreach ($categories as $cat): ?>
                        <div class="list-group-item py-2 px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold text-dark small <?= $cat->status === \app\models\Category::STATUS_INACTIVE ? 'text-decoration-line-through text-secondary' : '' ?>">
                                    <?= Html::encode($cat->getName()) ?>
                                </span>
                                <?php if ($cat->status === \app\models\Category::STATUS_INACTIVE): ?>
                                    <span class="badge bg-danger ms-1" style="font-size: 0.6rem;"><?= Yii::t('app', 'Disabled') ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="<?= Url::to(['/dashboard/toggle-category', 'id' => $cat->id]) ?>" 
                                   class="btn btn-sm <?= $cat->status === \app\models\Category::STATUS_ACTIVE ? 'btn-outline-secondary' : 'btn-outline-success' ?> py-0 px-2 rounded" 
                                   style="font-size: 0.7rem;"
                                   data-method="post">
                                    <?= $cat->status === \app\models\Category::STATUS_ACTIVE ? Yii::t('app', 'Disable') : Yii::t('app', 'Enable') ?>
                                </a>
                                <span class="badge bg-secondary rounded-pill"><?= count($cat->problems) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Database Backup and Restore Manager -->
            <div class="card border-light bg-white p-3 mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-dark mb-0"><i class="bi bi-database me-2 text-danger"></i><?= Yii::t('app', 'Database Backups') ?></h5>
                    <a href="<?= Url::to(['/dashboard/backup-create']) ?>" class="btn btn-sm btn-primary px-3" data-method="post">
                        <i class="bi bi-plus-lg me-1"></i> <?= Yii::t('app', 'Create') ?>
                    </a>
                </div>
                
                <?php if (empty($backups)): ?>
                    <p class="text-secondary small mb-0"><?= Yii::t('app', 'No database backup files found.') ?></p>
                <?php else: ?>
                    <div class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                        <?php foreach ($backups as $backup): ?>
                            <div class="list-group-item py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-monospace small text-truncate" style="max-width: 60%;" title="<?= Html::encode($backup['name']) ?>">
                                        <?= Html::encode($backup['name']) ?>
                                    </span>
                                    <span class="small text-secondary font-monospace" style="font-size: 0.75rem;">
                                        <?= number_format($backup['size'] / 1024, 1) ?> KB
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-secondary" style="font-size: 0.7rem;"><?= date('Y-m-d H:i:s', $backup['date']) ?></small>
                                    <div class="btn-group">
                                        <a href="<?= Url::to(['/dashboard/backup-restore', 'filename' => $backup['name']]) ?>" 
                                           class="btn btn-sm btn-outline-warning py-0 px-2" 
                                           style="font-size: 0.7rem;"
                                           data-method="post" 
                                           data-confirm="<?= Yii::t('app', 'Are you sure you want to restore the database to this state? All current changes will be overwritten.') ?>">
                                           <?= Yii::t('app', 'Restore') ?>
                                        </a>
                                        <a href="<?= Url::to(['/dashboard/backup-delete', 'filename' => $backup['name']]) ?>" 
                                           class="btn btn-sm btn-outline-danger py-0 px-2" 
                                           style="font-size: 0.7rem;"
                                           data-method="post" 
                                           data-confirm="<?= Yii::t('app', 'Are you sure you want to delete this backup file?') ?>">
                                           <?= Yii::t('app', 'Delete') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
