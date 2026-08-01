<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Category model representing "{{%category}}" table.
 *
 * @property int $id
 * @property string $name_uz
 * @property string $name_ru
 * @property string $name_en
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 *
 * @property Problem[] $problems
 */
class Category extends ActiveRecord
{
    public const STATUS_ACTIVE = 10;
    public const STATUS_INACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category}}';
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
            [['name_uz', 'name_ru', 'name_en'], 'required'],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['status'], 'default', 'value' => 1],
            [['name_uz', 'name_ru', 'name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name_uz' => Yii::t('app', 'Category Name (UZ)'),
            'name_ru' => Yii::t('app', 'Category Name (RU)'),
            'name_en' => Yii::t('app', 'Category Name (EN)'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Get problems relation
     */
    public function getProblems()
    {
        return $this->hasMany(Problem::class, ['category_id' => 'id']);
    }

    /**
     * Get translated name of category
     */
    public function getName(): string
    {
        $lang = Yii::$app->language;
        if ($lang === 'ru') {
            return $this->name_ru;
        }
        if ($lang === 'en') {
            return $this->name_en;
        }
        return $this->name_uz;
    }
}
