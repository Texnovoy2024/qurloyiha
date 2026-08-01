<?php

declare(strict_types=1);

namespace app\modules\api\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;

/**
 * Base Controller for REST API v1.
 */
class BaseApiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login', 'register', 'forgot-password', 'reset-password', 'categories', 'category-view', 'problems', 'problem-view', 'search'],
        ];
        return $behaviors;
    }

    /**
     * Helper to return standard success JSON.
     */
    protected function success(string $message, array $data = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Helper to return standard error JSON.
     */
    protected function error(string $message, array $errors = [], int $statusCode = 400): array
    {
        Yii::$app->response->statusCode = $statusCode;
        $response = [
            'success' => false,
            'message' => $message,
        ];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        return $response;
    }
}
