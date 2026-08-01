<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $confirm_password;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['old_password', 'new_password', 'confirm_password'], 'required'],
            ['new_password', 'string', 'min' => 8, 'max' => 32],
            ['new_password', 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', 'message' => Yii::t('app', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.')],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Passwords do not match.'],
            ['old_password', 'validateOldPassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'old_password' => 'Current Password',
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm New Password',
        ];
    }

    /**
     * Validates current password.
     */
    public function validateOldPassword(string $attribute, ?array $params): void
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if ($user instanceof User) {
                if (!$user->validatePassword($this->old_password)) {
                    $this->addError($attribute, 'Incorrect current password.');
                }
            } else {
                $this->addError($attribute, 'User is not logged in.');
            }
        }
    }

    /**
     * Changes password.
     */
    public function changePassword(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $user->setPassword($this->new_password);
        if ($user->save(false)) {
            AuditLog::log('Password Change', "Password changed successfully.");
            return true;
        }
        return false;
    }
}
