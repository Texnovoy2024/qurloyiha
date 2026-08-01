<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;
use yii\web\HttpException;

$this->title = $name;
$statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;

// Emoji choice based on status code
$emoji = '⚠️';
if ($statusCode === 404) {
    $emoji = '🔍';
} elseif ($statusCode === 403) {
    $emoji = '🚫';
} elseif ($statusCode === 503) {
    $emoji = '🔧';
} elseif ($statusCode === 500) {
    $emoji = '💥';
}
?>
<div class="site-error py-5 text-center animate-fade-in">
    <div class="glass-card p-5 mx-auto shadow-lg" style="max-width: 600px; border-top: 4px solid var(--bs-warning);">
        <div class="display-1 mb-2"><?= $emoji ?></div>
        <h1 class="display-3 fw-bold text-gradient mb-3"><?= Html::encode((string)$statusCode) ?></h1>
        <h2 class="h4 fw-semibold text-dark mb-4"><?= Html::encode($message) ?></h2>
        
        <p class="text-secondary small mb-5">
            The above error occurred while the Web server was processing your request.
            If you believe this is a platform error, please contact us. Thank you.
        </p>

        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <?= Html::a('🏠 Back to Homepage', Yii::$app->homeUrl, ['class' => 'btn btn-primary rounded-pill px-4 py-2']) ?>
            <?= Html::a('📞 Contact Support', ['/site/contact'], ['class' => 'btn btn-outline-secondary rounded-pill px-4 py-2']) ?>
        </div>
    </div>
</div>
