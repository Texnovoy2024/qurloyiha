<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Favorite model representing "{{%favorite}}" table.
 *
 * @property int $user_id
 * @property int $problem_id
 *
 * @property User $user
 * @property Problem $problem
 */
class Favorite extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%favorite}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'problem_id'], 'required'],
            [['user_id', 'problem_id'], 'integer'],
            [['user_id', 'problem_id'], 'unique', 'targetAttribute' => ['user_id', 'problem_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['problem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Problem::class, 'targetAttribute' => ['problem_id' => 'id']],
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
     * Get problem relation
     */
    public function getProblem()
    {
        return $this->hasOne(Problem::class, ['id' => 'problem_id']);
    }
}
