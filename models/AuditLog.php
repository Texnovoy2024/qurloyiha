<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * AuditLog model representing "{{%audit_log}}" table.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $details
 * @property string|null $ip_address
 * @property int $created_at
 *
 * @property User|null $user
 */
class AuditLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%audit_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['action', 'created_at'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['details'], 'string'],
            [['action', 'ip_address'], 'string', 'max' => 255],
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
            'action' => 'Action',
            'details' => 'Details',
            'ip_address' => 'IP Address',
            'created_at' => 'Timestamp',
        ];
    }

    /**
     * Get user relation
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Write an audit log record
     *
     * @param string $action Action name (e.g. Login, Signup, Problem Create)
     * @param string|null $details Context details
     * @param int|null $userId Optional explicit user id (for registration logging, before session starts)
     * @return bool
     */
    public static function log(string $action, ?string $details = null, ?int $userId = null): bool
    {
        $log = new self();
        $log->user_id = $userId;
        if (!$log->user_id && Yii::$app instanceof \yii\web\Application) {
            $log->user_id = Yii::$app->user->id;
        }
        $log->action = $action;
        $log->details = $details;
        $log->ip_address = (Yii::$app instanceof \yii\web\Application) ? Yii::$app->request->userIP : '127.0.0.1';
        $log->created_at = time();
        return $log->save(false);
    }
}
