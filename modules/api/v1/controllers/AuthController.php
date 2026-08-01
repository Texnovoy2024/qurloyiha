<?php

declare(strict_types=1);

namespace app\modules\api\v1\controllers;

use Yii;
use app\models\User;
use app\models\CompanyProfile;
use app\models\ScientistProfile;

/**
 * Authentication REST API endpoints.
 */
class AuthController extends BaseApiController
{
    /**
     * User registration endpoint.
     */
    public function actionRegister(): array
    {
        $request = Yii::$app->request;
        $username = $request->post('username');
        $email = $request->post('email');
        $password = $request->post('password');
        $role = $request->post('role', User::ROLE_SCIENTIST);

        $user = new User();
        $user->username = (string)$username;
        $user->email = (string)$email;
        $user->role = (string)$role;
        $user->setPassword((string)$password);
        $user->generateAuthKey();
        $user->access_token = Yii::$app->security->generateRandomString(64);

        if ($user->validate() && $user->save()) {
            if ($role === User::ROLE_COMPANY) {
                $profile = new CompanyProfile();
                $profile->id = $user->id;
                $profile->company_name = (string)$request->post('company_name', $username . ' LLC');
                $profile->responsible_name = (string)$request->post('responsible_name', $username);
                $profile->save(false);
            } else {
                $profile = new ScientistProfile();
                $profile->id = $user->id;
                $profile->first_name = (string)$request->post('first_name', $username);
                $profile->last_name = (string)$request->post('last_name', 'Scientist');
                $profile->save(false);
            }
            return $this->success('Registration successful.', [
                'access_token' => $user->access_token,
                'role' => $user->role,
            ]);
        }
        return $this->error('Registration failed.', $user->getErrors(), 422);
    }

    /**
     * User login endpoint.
     */
    public function actionLogin(): array
    {
        $request = Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');

        $user = User::findByUsername((string)$username);
        if ($user && $user->validatePassword((string)$password)) {
            if (empty($user->access_token)) {
                $user->access_token = Yii::$app->security->generateRandomString(64);
                $user->save(false);
            }
            return $this->success('Login successful.', [
                'access_token' => $user->access_token,
                'role' => $user->role,
            ]);
        }
        return $this->error('Invalid username or password.', [], 401);
    }

    /**
     * User logout endpoint.
     */
    public function actionLogout(): array
    {
        /** @var User|null $user */
        $user = Yii::$app->user->identity;
        if ($user) {
            $user->access_token = null;
            $user->save(false);
        }
        return $this->success('Logout successful.');
    }

    /**
     * Get user profile endpoint.
     */
    public function actionProfile(): array
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $profile = ($user->role === User::ROLE_COMPANY) ? $user->companyProfile : $user->scientistProfile;
        return $this->success('Profile loaded.', [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'profile' => $profile,
        ]);
    }
}
