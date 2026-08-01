<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ProblemFile model representing "{{%problem_file}}" table.
 *
 * @property int $id
 * @property int $problem_id
 * @property string $file_name
 * @property string $file_path
 * @property int $file_size
 * @property string $file_type
 * @property int $uploaded_at
 *
 * @property Problem $problem
 */
class ProblemFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%problem_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['problem_id', 'file_name', 'file_path', 'file_size', 'file_type', 'uploaded_at'], 'required'],
            [['problem_id', 'file_size', 'uploaded_at'], 'integer'],
            [['file_name', 'file_path', 'file_type'], 'string', 'max' => 255],
            [['problem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Problem::class, 'targetAttribute' => ['problem_id' => 'id']],
        ];
    }

    /**
     * Get problem relation
     */
    public function getProblem()
    {
        return $this->hasOne(Problem::class, ['id' => 'problem_id']);
    }
}
