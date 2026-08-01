<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ChangePasswordForm;
use app\models\AuditLog;
use app\models\Notification;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'signup', 'logout', 'change-password', 'forgot-password', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'forgot-password', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/dashboard/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Write login audit log
            AuditLog::log('Login', "Logged in successfully.");

            // Assign RBAC role dynamically to the logged in user
            $auth = Yii::$app->authManager;
            if ($auth) {
                $auth->revokeAll(Yii::$app->user->id);
                /** @var \app\models\User $identity */
                $identity = Yii::$app->user->identity;
                $role = $auth->getRole($identity->role);
                if ($role) {
                    $auth->assign($role, Yii::$app->user->id);
                }
            }

            return $this->redirect(['/dashboard/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Signup action.
     */
    public function actionSignup(?string $role = null)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/dashboard/index']);
        }

        $model = new SignupForm();
        if ($role) {
            $model->role = $role;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                // Generate welcome notification
                Notification::notify(
                    $user->id,
                    'Welcome to Antigravity!',
                    'Welcome to Antigravity Construction Innovation Ecosystem. Please complete your profile to start collaborating.',
                    '/profile/index'
                );

                if (Yii::$app->user->login($user)) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Profile updated successfully.'));
                    return $this->redirect(['/dashboard/index']);
                }
            }
        }

        if ($role === 'company') {
            return $this->render('signup-company', [
                'model' => $model,
            ]);
        }

        if ($role === 'scientist') {
            return $this->render('signup-scientist', [
                'model' => $model,
            ]);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     */
    public function actionLogout(): Response
    {
        // Write logout audit log before terminating session
        AuditLog::log('Logout', "Logged out successfully.");

        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Change Password action (authenticated users).
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            // Generate notification
            Notification::notify(
                Yii::$app->user->id,
                'Password Changed',
                'Your account password has been changed successfully.',
                '/profile/index'
            );

            Yii::$app->session->setFlash('success', 'Your password has been changed successfully.');
            return $this->redirect(['/profile/index']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /**
     * Forgot Password stub.
     */
    public function actionForgotPassword()
    {
        if (Yii::$app->request->isPost) {
            $email = Yii::$app->request->post('email');
            AuditLog::log('Forgot Password Request', "Requested password reset link for email: " . $email);
            Yii::$app->session->setFlash('success', 'Password reset instructions have been sent to your email.');
            return $this->redirect(['login']);
        }

        return $this->render('forgot-password');
    }

    /**
     * Reset Password stub.
     */
    public function actionResetPassword(string $token)
    {
        // Demonstration stub
        Yii::$app->session->setFlash('info', 'Password reset token verified. Please set a new password.');
        return $this->redirect(['login']);
    }
}
