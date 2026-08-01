<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Notification;

class NotificationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * View all user notifications
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $query = Notification::find()
            ->where(['user_id' => $userId])
            ->orderBy(['created_at' => SORT_DESC]);

        $countQuery = clone $query;
        $pages = new \yii\data\Pagination([
            'totalCount' => (int)$countQuery->count(),
            'defaultPageSize' => 20,
            'pageSizeLimit' => [1, 100],
        ]);
        $notifications = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        // Mark all as read
        Notification::updateAll(['is_read' => true], ['user_id' => $userId, 'is_read' => false]);

        return $this->render('index', [
            'notifications' => $notifications,
            'pages' => $pages,
        ]);
    }
}
