<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Terms of Service - Antigravity';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-5 animate-fade-in">
    <div class="glass-card p-5">
        <h1 class="fw-bold text-gradient mb-4">Terms of Service</h1>
        <p class="text-secondary small">Last Updated: July 22, 2026</p>
        <hr class="my-4">
        
        <h4 class="fw-bold text-primary mt-4">1. Intermediary Role Only</h4>
        <p class="text-secondary">
            Antigravity acts solely as a virtual intermediary to connect construction companies with scientific researchers and academic professionals. We do not participate in, nor assume responsibility for, any financial transactions, project executions, contract negotiations, or intellectual property disputes arising outside the platform.
        </p>
        
        <h4 class="fw-bold text-primary mt-4">2. Intellectual Property</h4>
        <p class="text-secondary">
            Scientists retain all intellectual property rights to their submitted solution proposals until a formal transfer of rights is agreed upon by both parties through offline legal contracts.
        </p>
        
        <h4 class="fw-bold text-primary mt-4">3. Prohibited Activity</h4>
        <p class="text-secondary">
            Users are strictly prohibited from publishing false information, copying other scientists' intellectual proposals, or uploading malicious files. Violations will result in account termination and blocking by system administrators.
        </p>
        
        <div class="mt-5">
            <?= Html::a('Back to Home', ['/site/index'], ['class' => 'btn btn-primary rounded-pill px-4']) ?>
        </div>
    </div>
</div>
