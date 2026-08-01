<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Problem model representing "{{%problem}}" table.
 *
 * @property int $id
 * @property int $company_id
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property string|null $requirements
 * @property float|null $budget
 * @property int|null $deadline
 * @property int $status
 * @property int $views_count
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $expected_result
 * @property string|null $attachment_file
 *
 * @property User $company
 * @property CompanyProfile $companyProfile
 * @property Category $category
 * @property Proposal[] $proposals
 */
class Problem extends ActiveRecord
{
    public const STATUS_CLOSED = 0;
    public const STATUS_SOLVED = 0;
    public const STATUS_DRAFT = 5;
    public const STATUS_ACTIVE = 10;
    public const STATUS_PUBLISHED = 10;
    public const STATUS_MODERATION = 10;
    public const STATUS_UNDER_REVIEW = 25;
    public const STATUS_ARCHIVED = 35;
    public const STATUS_CANCELLED = 40;
    public const STATUS_SPAM = 50;

    /**
     * Deadline date helper for forms
     */
    public ?string $deadline_date = null;

    /**
     * File uploads helper
     */
    public $uploaded_attachments = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%problem}}';
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
            [['category_id', 'title', 'description', 'expected_result'], 'required'],
            [['company_id', 'category_id', 'status', 'views_count'], 'integer'],
            [['description', 'requirements', 'expected_result'], 'string'],
            [['budget'], 'number', 'min' => 0],
            [['title', 'attachment_file'], 'string', 'max' => 255],
            [['deadline_date'], 'safe'],
            [['deadline'], 'integer'],
            [['uploaded_attachments'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx, xls, xlsx, png, jpg, jpeg, zip', 'maxSize' => 1024 * 1024 * 50, 'maxFiles' => 10], // 50MB limit, up to 10 files
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['company_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'category_id' => Yii::t('app', 'Category'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'requirements' => Yii::t('app', 'Requirements'),
            'expected_result' => 'Expected Result',
            'budget' => Yii::t('app', 'Budget'),
            'deadline' => Yii::t('app', 'Deadline'),
            'deadline_date' => Yii::t('app', 'Deadline'),
            'status' => Yii::t('app', 'Status'),
            'views_count' => Yii::t('app', 'Views'),
            'attachment_file' => 'Attachment',
            'uploaded_attachment' => 'Attachment File (PDF, DOCX, XLSX, JPG, PNG, ZIP up to 50MB)',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->deadline_date) {
                $this->deadline = (int) strtotime($this->deadline_date);
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind(): void
    {
        parent::afterFind();
        if ($this->deadline) {
            $this->deadline_date = date('Y-m-d', $this->deadline);
        }
    }

    /**
     * Get company user relation
     */
    public function getCompany()
    {
        return $this->hasOne(User::class, ['id' => 'company_id']);
    }

    /**
     * Get company profile relation
     */
    public function getCompanyProfile()
    {
        return $this->hasOne(CompanyProfile::class, ['id' => 'company_id']);
    }

    /**
     * Get category relation
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Get proposals relation
     */
    public function getProposals()
    {
        return $this->hasMany(Proposal::class, ['problem_id' => 'id']);
    }

    /**
     * Get problem spec files relation
     */
    public function getFiles()
    {
        return $this->hasMany(ProblemFile::class, ['problem_id' => 'id']);
    }

    /**
     * Returns list of statuses with label translations
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_DRAFT => Yii::t('app', 'Draft'),
            self::STATUS_ACTIVE => Yii::t('app', 'Published'),
            self::STATUS_SOLVED => Yii::t('app', 'Closed'),
        ];
    }

    /**
     * Returns label of current status
     */
    public function getStatusLabel(): string
    {
        return self::getStatusList()[$this->status] ?? (string)$this->status;
    }
}
