<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Proposal model representing "{{%proposal}}" table.
 *
 * @property int $id
 * @property int $problem_id
 * @property int $scientist_id
 * @property string $title
 * @property string $description
 * @property string $solution_details
 * @property string|null $time_offer
 * @property float|null $budget_offer
 * @property string|null $document_file
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Problem $problem
 * @property User $scientist
 * @property ScientistProfile $scientistProfile
 */
class Proposal extends ActiveRecord
{
    public const STATUS_DRAFT = 0;
    public const STATUS_SUBMITTED = 10;
    public const STATUS_EVALUATING = 20;
    public const STATUS_UNDER_REVIEW = 20;
    public const STATUS_SELECTED = 30;
    public const STATUS_ACCEPTED = 30;
    public const STATUS_REJECTED = 40;

    /**
     * File uploads helper
     */
    public $uploaded_files = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%proposal}}';
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
            [['problem_id', 'title', 'description', 'solution_details'], 'required'],
            [['problem_id', 'scientist_id', 'status'], 'integer'],
            [['description', 'solution_details'], 'string'],
            [['budget_offer'], 'number', 'min' => 0],
            [['title', 'time_offer', 'document_file'], 'string', 'max' => 255],
            [['uploaded_files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx, xls, xlsx, png, jpg, jpeg, zip', 'maxSize' => 1024 * 1024 * 50, 'maxFiles' => 10],
            [['problem_id'], 'exist', 'skipOnError' => true, 'targetClass' => Problem::class, 'targetAttribute' => ['problem_id' => 'id']],
            [['scientist_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['scientist_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'problem_id' => Yii::t('app', 'Problem Details'),
            'scientist_id' => 'Scientist ID',
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'solution_details' => 'Technical Solution Description',
            'time_offer' => 'Estimated Duration',
            'budget_offer' => 'Estimated Cost (UZS)',
            'document_file' => 'Attachments',
            'uploaded_files' => 'Proposal Documents (PDF, DOC, DOCX, XLSX, ZIP, JPG, PNG up to 50MB, Multiple files allowed)',
            'status' => 'Proposal Status',
        ];
    }

    /**
     * Get problem relation
     */
    public function getProblem()
    {
        return $this->hasOne(Problem::class, ['id' => 'problem_id']);
    }

    /**
     * Get scientist user relation
     */
    public function getScientist()
    {
        return $this->hasOne(User::class, ['id' => 'scientist_id']);
    }

    /**
     * Get scientist profile relation
     */
    public function getScientistProfile()
    {
        return $this->hasOne(ScientistProfile::class, ['id' => 'scientist_id']);
    }

    /**
     * Get proposal files relation
     */
    public function getFiles()
    {
        return $this->hasMany(ProposalFile::class, ['proposal_id' => 'id']);
    }

    /**
     * Returns list of statuses with translations
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_EVALUATING => 'Under Review',
            self::STATUS_SELECTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    /**
     * Returns status translation label
     */
    public function getStatusLabel(): string
    {
        return self::getStatusList()[$this->status] ?? (string)$this->status;
    }
}
