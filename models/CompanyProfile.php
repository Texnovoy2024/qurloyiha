<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * CompanyProfile model representing "{{%company_profile}}" table.
 *
 * @property int $id
 * @property string $company_name
 * @property string|null $industry
 * @property string|null $website
 * @property string|null $description
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $logo
 * @property string|null $stir
 * @property string|null $responsible_name
 * @property string|null $organization_type
 *
 * @property User $user
 */
class CompanyProfile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%company_profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'company_name'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string'],
            [['company_name', 'industry', 'website', 'address', 'phone', 'logo', 'responsible_name', 'organization_type'], 'string', 'max' => 255],
            [['stir'], 'string', 'max' => 32],
            [['website'], 'url'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'company_name' => \Yii::t('app', 'Company Name'),
            'industry' => \Yii::t('app', 'Industry'),
            'website' => \Yii::t('app', 'Website'),
            'description' => \Yii::t('app', 'Company Description'),
            'address' => \Yii::t('app', 'Address'),
            'phone' => \Yii::t('app', 'Phone'),
            'logo' => \Yii::t('app', 'Logo'),
            'stir' => 'STIR',
            'responsible_name' => 'Responsible Person Full Name',
            'organization_type' => 'Organization Type',
        ];
    }

    /**
     * Get user relation
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id']);
    }
}
