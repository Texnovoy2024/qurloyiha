<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Notification model representing "{{%notification}}" table.
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property bool $is_read
 * @property string|null $link
 * @property int $created_at
 *
 * @property User $user
 */
class Notification extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%notification}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'title', 'message'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['message'], 'string'],
            [['is_read'], 'boolean'],
            [['title', 'link'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => Yii::t('app', 'Title'),
            'message' => Yii::t('app', 'Description'),
            'is_read' => 'Is Read',
            'link' => 'Link',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }

    /**
     * Get user relation
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Helper to create a notification for a user
     */
    public static function notify(int $userId, string $title, string $message, ?string $link = null): bool
    {
        $notification = new self();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->message = $message;
        $notification->link = $link;
        $notification->is_read = false;
        return $notification->save();
    }
}
