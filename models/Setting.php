<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Setting model representing "{{%setting}}" table.
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $description
 */
class Setting extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['key'], 'required'],
            [['value'], 'string'],
            [['key', 'description'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'key' => 'Setting Key',
            'value' => 'Setting Value',
            'description' => 'Description',
        ];
    }

    /**
     * Retrieve setting value by key.
     *
     * @param string $key Setting key
     * @param string|null $default Default value
     * @return string|null
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        $setting = self::findOne(['key' => $key]);
        return $setting ? $setting->value : $default;
    }
}
