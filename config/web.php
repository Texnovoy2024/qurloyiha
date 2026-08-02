<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Qurilish-loyiha.uz',
    'language' => 'uz',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\components\LanguageSelector'],
    'container' => [
        'singletons' => [
            \yii\mail\MailerInterface::class => [
                'class' => \yii\symfonymailer\Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath' => '@app/mail',
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'on beforeAction' => function ($event) {
        if (Yii::$app && Yii::$app->db) {
            try {
                if (!Yii::$app->user->isGuest) {
                    $timeout = (int)\app\models\Setting::getValue('session_timeout', '1440');
                    Yii::$app->user->authTimeout = $timeout;
                }

                $maintenance = \app\models\Setting::getValue('maintenance_mode', '0');
                if ($maintenance === '1') {
                    $isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->can('adminAccess');
                    $route = Yii::$app->requestedRoute;
                    $allowedRoutes = ['auth/login', 'auth/logout', 'dashboard/save-settings', 'site/error'];
                    if (!$isAdmin && !in_array($route, $allowedRoutes, true)) {
                        throw new \yii\web\HttpException(503, 'The platform is currently undergoing scheduled maintenance. Please check back later.');
                    }
                }
            } catch (\Exception $e) {
                if ($e instanceof \yii\web\HttpException) {
                    throw $e;
                }
                // Safe guard if db is not fully migrated
            }
        }
    },
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qurloyiha123',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => YII_ENV_PROD,
                'sameSite' => 'Lax',
            ],
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'secure' => YII_ENV_PROD,
                'sameSite' => 'Lax',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@app/rbac/items.php',
            'assignmentFile' => '@app/rbac/assignments.php',
            'ruleFile' => '@app/rbac/rules.php',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => \yii\mail\MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'assetManager' => [
            'basePath' => dirname(__DIR__) . '/web/assets',
            'baseUrl'  => '/assets',
            'appendTimestamp' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'dashboard' => 'dashboard/index',
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'signup' => 'auth/signup',
                'signup/<role>' => 'auth/signup',
                'problems' => 'problem/index',
                'problems/create' => 'problem/create',
                'problems/<id:\d+>' => 'problem/view',
                'problems/<id:\d+>/update' => 'problem/update',
                'problems/<id:\d+>/delete' => 'problem/delete',
                'problems/<id:\d+>/propose' => 'proposal/create',
                'proposals/<id:\d+>' => 'proposal/view',
                'proposals/<id:\d+>/select' => 'proposal/select',
                'proposals/<id:\d+>/status' => 'proposal/update-status',
                'profile' => 'profile/index',
                'profile/update' => 'profile/update',
                'notifications' => 'notification/index',
                'change-language/<lang:[a-z]{2}>' => 'profile/change-language',
                'privacy' => 'site/privacy',
                'terms' => 'site/terms',
                'docs' => 'site/docs',
                
                // REST API v1 Routing Rules
                'POST api/v1/auth/register' => 'api/v1/auth/register',
                'POST api/v1/auth/login' => 'api/v1/auth/login',
                'POST api/v1/auth/logout' => 'api/v1/auth/logout',
                'GET api/v1/profile' => 'api/v1/auth/profile',
                
                'GET api/v1/problems' => 'api/v1/problem/index',
                'GET api/v1/problem/view/<id:\d+>' => 'api/v1/problem/view',
                'GET api/v1/search' => 'api/v1/problem/index',
                
                'GET api/v1/categories' => 'api/v1/category/index',
                'GET api/v1/category/view/<id:\d+>' => 'api/v1/category/view',
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
