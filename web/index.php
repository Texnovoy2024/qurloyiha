<?php

declare(strict_types=1);

defined('YII_DEBUG') or define('YII_DEBUG', (bool)(getenv('YII_DEBUG') ?: true));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'dev');

// Ensure essential runtime and asset directories exist on every request
$runtimeDir = __DIR__ . '/../runtime';
$assetsDir  = __DIR__ . '/assets';

if (!is_dir($runtimeDir)) {
    @mkdir($runtimeDir, 0777, true);
}
if (!is_dir($assetsDir)) {
    @mkdir($assetsDir, 0777, true);
}
@chmod($runtimeDir, 0777);
@chmod($assetsDir, 0777);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
