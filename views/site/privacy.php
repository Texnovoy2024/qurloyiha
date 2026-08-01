<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Privacy Policy - Antigravity';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container py-5 animate-fade-in">
    <div class="glass-card p-5">
        <h1 class="fw-bold text-gradient mb-4">Privacy Policy</h1>
        <p class="text-secondary small">Last Updated: July 22, 2026</p>
        <hr class="my-4">
        
        <h4 class="fw-bold text-primary mt-4">1. Information We Collect</h4>
        <p class="text-secondary">
            We collect personal information that you provide to us when you register on the platform, such as company names, STIR (Tax ID), full names, email addresses, phone numbers, date of birth, academic degrees, and files uploaded.
        </p>
        
        <h4 class="fw-bold text-primary mt-4">2. How We Use Your Information</h4>
        <p class="text-secondary">
            We use the information collected to facilitate the collaboration process between construction companies and scientists, to verify user credentials, and to send system notifications regarding proposal status or new challenges.
        </p>
        
        <h4 class="fw-bold text-primary mt-4">3. Data Security</h4>
        <p class="text-secondary">
            We implement standard security measures, including password hashing and secure session cookies, to ensure the protection and privacy of user accounts and uploaded intellectual documents.
        </p>
        
        <div class="mt-5">
            <?= Html::a('Back to Home', ['/site/index'], ['class' => 'btn btn-primary rounded-pill px-4']) ?>
        </div>
    </div>
</div>
