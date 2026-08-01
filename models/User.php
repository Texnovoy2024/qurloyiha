<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model representing "{{%user}}" table.
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $role
 * @property int $status
 * @property string $language
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $access_token
 *
 * @property CompanyProfile|null $companyProfile
 * @property ScientistProfile|null $scientistProfile
 * @property Problem[] $favoriteProblems
 * @property Favorite[] $favorites relation
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    public const ROLE_COMPANY = 'company';
    public const ROLE_SCIENTIST = 'scientist';
    public const ROLE_ADMIN = 'administrator';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            [['username', 'email', 'access_token'], 'trim'],
            [['access_token'], 'default', 'value' => null],
            ['email', 'email'],
            [['username', 'email', 'access_token'], 'unique'],
            [['access_token'], 'string', 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['language', 'default', 'value' => 'uz'],
            ['language', 'in', 'range' => ['uz', 'ru', 'en']],
            ['role', 'in', 'range' => [self::ROLE_COMPANY, self::ROLE_SCIENTIST, self::ROLE_ADMIN]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): static|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null): static|null
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): static|null
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Get associated company profile
     */
    public function getCompanyProfile()
    {
        return $this->hasOne(CompanyProfile::class, ['id' => 'id']);
    }

    /**
     * Get associated scientist profile
     */
    public function getScientistProfile()
    {
        return $this->hasOne(ScientistProfile::class, ['id' => 'id']);
    }
    /**
     * Get user favorite bookmarks
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id']);
    }

    /**
     * Get bookmarked problems
     */
    public function getFavoriteProblems()
    {
        return $this->hasMany(Problem::class, ['id' => 'problem_id'])->via('favorites');
    }
    /**
     * Check if user is admin
     */
    public function getIsAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is company
     */
    public function getIsCompany(): bool
    {
        return $this->role === self::ROLE_COMPANY;
    }

    /**
     * Check if user is scientist
     */
    public function getIsScientist(): bool
    {
        return $this->role === self::ROLE_SCIENTIST;
    }
}
