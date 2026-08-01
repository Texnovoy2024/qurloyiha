<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\models\CompanyProfile;
use app\models\ScientistProfile;
use app\models\AuditLog;
use app\models\Notification;

class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'update'],
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
     * Action to switch language. Supports guests (cookies) and authenticated users (DB).
     */
    public function actionChangeLanguage(string $lang)
    {
        $supported = ['uz', 'ru', 'en'];
        if (in_array($lang, $supported, true)) {
            // Set language in cookie
            $cookie = new Cookie([
                'name' => 'language',
                'value' => $lang,
                'expire' => time() + 3600 * 24 * 365, // 1 year
            ]);
            Yii::$app->response->cookies->add($cookie);

            // Update user DB record if authenticated
            if (!Yii::$app->user->isGuest) {
                /** @var \app\models\User $user */
                $user = Yii::$app->user->identity;
                $user->language = $lang;
                $user->save(false);
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Language changed successfully.'));
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * View profile details.
     */
    public function actionIndex()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;
        return $this->render('index', [
            'user' => $user,
        ]);
    }

    /**
     * Update profile details.
     */
    public function actionUpdate()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;

        if ($user->role === User::ROLE_COMPANY) {
            $profile = $user->companyProfile ?: new CompanyProfile(['id' => $user->id]);
            if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
                // Generate welcome/update notification
                Notification::notify($user->id, 'Profile Updated', 'Your company profile details were updated successfully.', '/profile/index');
                AuditLog::log('Profile Update', "Updated company profile details.");

                Yii::$app->session->setFlash('success', Yii::t('app', 'Profile updated successfully.'));
                return $this->redirect(['index']);
            }

            return $this->render('update-company', [
                'user' => $user,
                'profile' => $profile,
            ]);
        }

        if ($user->role === User::ROLE_SCIENTIST) {
            $profile = $user->scientistProfile ?: new ScientistProfile(['id' => $user->id]);
            if ($profile->load(Yii::$app->request->post()) && $profile->save()) {
                // Generate welcome/update notification
                Notification::notify($user->id, 'Profile Updated', 'Your scientist profile details were updated successfully.', '/profile/index');
                AuditLog::log('Profile Update', "Updated scientist profile details.");

                Yii::$app->session->setFlash('success', Yii::t('app', 'Profile updated successfully.'));
                return $this->redirect(['index']);
            }

            return $this->render('update-scientist', [
                'user' => $user,
                'profile' => $profile,
            ]);
        }

        throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
    }
}
