<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ScientistProfile model representing "{{%scientist_profile}}" table.
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $academic_degree
 * @property string|null $institution
 * @property string|null $specialization
 * @property string|null $bio
 * @property string|null $phone
 * @property string|null $cv_file
 * @property string|null $dob
 * @property string|null $academic_title
 * @property string|null $photo
 * @property string|null $skills
 * @property string|null $portfolio
 * @property string|null $publications
 * @property string|null $certificates
 *
 * @property User $user
 */
class ScientistProfile extends ActiveRecord
{
    /**
     * File upload attribute
     */
    public $uploaded_photo = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%scientist_profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'first_name', 'last_name'], 'required'],
            [['id'], 'integer'],
            [['bio', 'skills', 'portfolio', 'publications', 'certificates'], 'string'],
            [['first_name', 'last_name', 'academic_degree', 'institution', 'specialization', 'phone', 'cv_file', 'dob', 'academic_title', 'photo'], 'string', 'max' => 255],
            [['uploaded_photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'first_name' => \Yii::t('app', 'First Name'),
            'last_name' => \Yii::t('app', 'Last Name'),
            'academic_degree' => \Yii::t('app', 'Academic Degree'),
            'institution' => \Yii::t('app', 'Institution'),
            'specialization' => \Yii::t('app', 'Specialization'),
            'bio' => \Yii::t('app', 'Biography'),
            'phone' => \Yii::t('app', 'Phone'),
            'cv_file' => \Yii::t('app', 'CV File'),
            'dob' => 'Date of Birth',
            'academic_title' => 'Academic Title',
            'photo' => 'Profile Photo',
            'uploaded_photo' => 'Profile Photo',
            'skills' => 'Skills',
            'portfolio' => 'Project Portfolio',
            'publications' => 'Publications / Research Papers',
            'certificates' => 'Academic Certificates / Achievements',
        ];
    }

    /**
     * Get user relation
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id']);
    }

    /**
     * Get scientist full name
     */
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
