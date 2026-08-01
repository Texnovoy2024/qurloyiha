<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $role;
    public $accept_terms;

    // Company specific
    public $company_name;
    public $industry;
    public $website;
    public $company_description;
    public $address;
    public $company_phone;
    public $stir;
    public $responsible_name;

    // Scientist specific
    public $first_name;
    public $last_name;
    public $academic_degree;
    public $institution;
    public $specialization;
    public $bio;
    public $scientist_phone;
    public $dob;
    public $academic_title;
    public $uploaded_photo;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password', 'confirm_password', 'role', 'accept_terms'], 'required'],
            [['username', 'email'], 'trim'],
            [['username', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            ['password', 'string', 'min' => 8, 'max' => 32],
            ['password', 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', 'message' => Yii::t('app', 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.')],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('yii', 'Passwords do not match.')],
            ['role', 'in', 'range' => [User::ROLE_COMPANY, User::ROLE_SCIENTIST]],
            ['accept_terms', 'compare', 'compareValue' => 1, 'message' => 'You must accept the terms and conditions.'],

            // Unique checks
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This username has already been taken.')],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This email address has already been taken.')],

            // Company fields validation
            [['company_name', 'responsible_name'], 'required', 'when' => function ($model) {
                return $model->role === User::ROLE_COMPANY;
            }, 'whenClient' => "function (attribute, value) { return $('#signupform-role').val() === 'company'; }"],
            [['company_name', 'industry', 'website', 'address', 'company_phone', 'responsible_name'], 'string', 'max' => 255],
            [['stir'], 'string', 'max' => 32],
            [['website'], 'url'],
            [['company_description'], 'string'],

            // Scientist fields validation
            [['first_name', 'last_name'], 'required', 'when' => function ($model) {
                return $model->role === User::ROLE_SCIENTIST;
            }, 'whenClient' => "function (attribute, value) { return $('#signupform-role').val() === 'scientist'; }"],
            [['first_name', 'last_name', 'academic_degree', 'institution', 'specialization', 'scientist_phone', 'dob', 'academic_title'], 'string', 'max' => 255],
            [['bio'], 'string'],
            [['uploaded_photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
            'company_name' => Yii::t('app', 'Company Name'),
            'industry' => Yii::t('app', 'Industry'),
            'website' => Yii::t('app', 'Website'),
            'company_description' => Yii::t('app', 'Company Description'),
            'address' => Yii::t('app', 'Address'),
            'company_phone' => Yii::t('app', 'Phone'),
            'stir' => 'STIR',
            'responsible_name' => 'Responsible Person Full Name',
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'academic_degree' => Yii::t('app', 'Academic Degree'),
            'institution' => Yii::t('app', 'Institution'),
            'specialization' => Yii::t('app', 'Specialization'),
            'bio' => Yii::t('app', 'Biography'),
            'scientist_phone' => Yii::t('app', 'Phone'),
            'dob' => 'Date of Birth',
            'academic_title' => 'Academic Title (optional)',
            'uploaded_photo' => 'Profile Photo (optional)',
            'accept_terms' => 'Accept Terms and Conditions',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->role = $this->role;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->language = Yii::$app->language;
            $user->status = User::STATUS_ACTIVE;

            if ($user->save()) {
                if ($this->role === User::ROLE_COMPANY) {
                    $profile = new CompanyProfile();
                    $profile->id = $user->id;
                    $profile->company_name = $this->company_name;
                    $profile->industry = $this->industry;
                    $profile->website = $this->website;
                    $profile->description = $this->company_description;
                    $profile->address = $this->address;
                    $profile->phone = $this->company_phone;
                    $profile->stir = $this->stir;
                    $profile->responsible_name = $this->responsible_name;
                    
                    if (!$profile->save()) {
                        throw new \Exception('Failed to save company profile.');
                    }
                } elseif ($this->role === User::ROLE_SCIENTIST) {
                    $profile = new ScientistProfile();
                    $profile->id = $user->id;
                    $profile->first_name = $this->first_name;
                    $profile->last_name = $this->last_name;
                    $profile->academic_degree = $this->academic_degree;
                    $profile->institution = $this->institution;
                    $profile->specialization = $this->specialization;
                    $profile->bio = $this->bio;
                    $profile->phone = $this->scientist_phone;
                    $profile->dob = $this->dob;
                    $profile->academic_title = $this->academic_title;

                    // Handle photo upload
                    $photoInstance = UploadedFile::getInstance($this, 'uploaded_photo');
                    if ($photoInstance) {
                        $photoDir = Yii::getAlias('@webroot/uploads/profile/');
                        FileHelper::createDirectory($photoDir);
                        $photoName = md5((string)microtime(true)) . '.' . $photoInstance->extension;
                        if ($photoInstance->saveAs($photoDir . $photoName)) {
                            $profile->photo = 'uploads/profile/' . $photoName;
                        }
                    }

                    if (!$profile->save()) {
                        throw new \Exception('Failed to save scientist profile.');
                    }
                }

                // Log audit action
                AuditLog::log('Registration', "Registered new user: " . $user->username, $user->id);

                // Assign RBAC role dynamically
                $auth = Yii::$app->authManager;
                if ($auth) {
                    $role = $auth->getRole($user->role);
                    if ($role) {
                        $auth->assign($role, $user->id);
                    }
                }

                $transaction->commit();
                return $user;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Signup failed: " . $e->getMessage());
            $this->addError('username', $e->getMessage());
        }

        return null;
    }
}
