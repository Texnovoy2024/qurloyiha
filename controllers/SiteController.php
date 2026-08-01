<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\ContactForm;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent' => true,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact(): Response|string
    {
        $model = new ContactForm();

        if ($model->load($this->request->post()) && $model->contact(
            Yii::$app->mailer,
            Yii::$app->params['adminEmail'],
            Yii::$app->params['senderEmail'] ?? 'noreply@example.com',
            Yii::$app->params['senderName'] ?? 'Antigravity'
        )) {
            Yii::$app->session->setFlash(
                'success',
                'Thank you for contacting us. We will respond to you as soon as possible.',
            );

            return $this->refresh();
        }

        return $this->render('contact', ['model' => $model]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Displays privacy policy page.
     *
     * @return string
     */
    public function actionPrivacy(): string
    {
        return $this->render('privacy');
    }

    /**
     * Displays terms of service page.
     *
     * @return string
     */
    public function actionTerms(): string
    {
        return $this->render('terms');
    }

    /**
     * Displays API documentation Swagger UI.
     *
     * @return string
     */
    public function actionDocs(): string
    {
        return $this->render('docs');
    }
}
