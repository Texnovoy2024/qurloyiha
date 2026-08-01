<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ProposalFile model representing "{{%proposal_file}}" table.
 *
 * @property int $id
 * @property int $proposal_id
 * @property string $file_name
 * @property string $file_path
 * @property int $file_size
 * @property string $file_type
 * @property int $uploaded_at
 *
 * @property Proposal $proposal
 */
class ProposalFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%proposal_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['proposal_id', 'file_name', 'file_path', 'file_size', 'file_type', 'uploaded_at'], 'required'],
            [['proposal_id', 'file_size', 'uploaded_at'], 'integer'],
            [['file_name', 'file_path', 'file_type'], 'string', 'max' => 255],
            [['proposal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Proposal::class, 'targetAttribute' => ['proposal_id' => 'id']],
        ];
    }

    /**
     * Get proposal relation
     */
    public function getProposal()
    {
        return $this->hasOne(Proposal::class, ['id' => 'proposal_id']);
    }
}
