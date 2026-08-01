<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use app\models\Proposal;
use app\models\Problem;
use app\models\User;
use app\models\Notification;
use app\models\AuditLog;
use app\models\ProposalFile;

class ProposalController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'select', 'reject', 'view', 'review', 'delete-file'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['submitProposal'], // Scientists only
                    ],
                    [
                        'actions' => ['update', 'delete', 'delete-file'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['select', 'reject', 'review'],
                        'allow' => true,
                        'roles' => ['createProblem'], // Companies only
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'select' => ['post'],
                    'reject' => ['post'],
                    'review' => ['post'],
                ],
            ],
        ];
    }

    /**
     * View proposal details (ownership checked).
     */
    public function actionView($id)
    {
        $model = $this->findModel((int)$id);
        $userId = Yii::$app->user->id;

        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;

        // Verify that current user is the owner of the proposal, owner of the problem, or admin
        $isScientistOwner = ($model->scientist_id === $userId);
        $isCompanyOwner = ($model->problem->company_id === $userId);
        $isAdmin = ($user->role === User::ROLE_ADMIN);

        if (!$isScientistOwner && !$isCompanyOwner && !$isAdmin) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        return $this->render('view', [
            'model' => $model,
            'isScientistOwner' => $isScientistOwner,
            'isCompanyOwner' => $isCompanyOwner,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Creates a new Proposal.
     */
    public function actionCreate($id)
    {
        $problem = Problem::findOne((int)$id);
        if (!$problem || $problem->status !== Problem::STATUS_ACTIVE) {
            throw new NotFoundHttpException('Active challenge not found.');
        }

        $model = new Proposal();
        $model->problem_id = $problem->id;
        $model->scientist_id = Yii::$app->user->id;

        // Check if user already submitted a proposal for this problem (drafts don't block submitting again, but let's check non-draft)
        $existing = Proposal::find()
            ->where(['problem_id' => $problem->id, 'scientist_id' => $model->scientist_id])
            ->andWhere(['!=', 'status', Proposal::STATUS_DRAFT])
            ->one();

        if ($existing) {
            Yii::$app->session->setFlash('warning', 'You have already submitted a proposal for this challenge.');
            return $this->redirect(['/problem/view', 'id' => $problem->id]);
        }

        // Check if there is an existing draft to resume instead of creating empty
        $draft = Proposal::findOne(['problem_id' => $problem->id, 'scientist_id' => $model->scientist_id, 'status' => Proposal::STATUS_DRAFT]);
        if ($draft) {
            return $this->redirect(['update', 'id' => $draft->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->uploaded_files = UploadedFile::getInstances($model, 'uploaded_files');
            
            // Set status based on button clicked
            $submitAction = Yii::$app->request->post('submit_action', 'submit');
            $model->status = $submitAction === 'draft' ? Proposal::STATUS_DRAFT : Proposal::STATUS_SUBMITTED;

            if ($model->validate()) {
                if ($model->save(false)) {
                    // Save uploaded files to proposal_file table
                    if ($model->uploaded_files) {
                        $uploadDir = Yii::getAlias('@webroot/uploads/proposal/');
                        FileHelper::createDirectory($uploadDir);
                        
                        foreach ($model->uploaded_files as $file) {
                            $fileName = md5(uniqid('', true)) . '.' . $file->extension;
                            $filePath = 'uploads/proposal/' . $fileName;
                            if ($file->saveAs($uploadDir . $fileName)) {
                                $proposalFile = new ProposalFile();
                                $proposalFile->proposal_id = $model->id;
                                $proposalFile->file_name = $file->name;
                                $proposalFile->file_path = $filePath;
                                $proposalFile->file_size = $file->size;
                                $proposalFile->file_type = $file->type;
                                $proposalFile->uploaded_at = time();
                                $proposalFile->save(false);
                            }
                        }
                    }

                    if ($model->status === Proposal::STATUS_SUBMITTED) {
                        // Audit log
                        AuditLog::log('Proposal Submit', "Submitted proposal ID: " . $model->id . " for problem: " . $problem->title);

                        // Notify company owner
                        /** @var \app\models\User $user */
                        $user = Yii::$app->user->identity;
                        Notification::notify(
                            $problem->company_id,
                            Yii::t('app', 'Proposal submitted successfully.'),
                            "A new solution was proposed for your challenge '{$problem->title}' by " . $user->scientistProfile->getFullName(),
                            "/proposal/view?id=" . $model->id
                        );
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Proposal submitted successfully.'));
                    } else {
                        AuditLog::log('Proposal Draft Save', "Saved proposal draft ID: " . $model->id);
                        Yii::$app->session->setFlash('success', 'Proposal saved as draft.');
                    }
                    return $this->redirect(['/dashboard/index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'problem' => $problem,
        ]);
    }

    /**
     * Updates an existing Proposal.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel((int)$id);

        // RBAC Check for Proposal Edit (Admin can edit any, Scientist owner can edit own)
        if (!Yii::$app->user->can('editProposal') && !Yii::$app->user->can('editOwnProposal', ['proposal' => $model])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Scientists cannot edit proposals after company starts reviewing them (status != submitted && status != draft)
        $isEditable = ($model->status === Proposal::STATUS_DRAFT || $model->status === Proposal::STATUS_SUBMITTED);
        if (!$isEditable && !Yii::$app->user->can('editProposal')) {
            throw new ForbiddenHttpException('You cannot modify this proposal because it is already under review by the company.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->uploaded_files = UploadedFile::getInstances($model, 'uploaded_files');
            
            // Set status based on button clicked
            $submitAction = Yii::$app->request->post('submit_action', 'submit');
            $model->status = $submitAction === 'draft' ? Proposal::STATUS_DRAFT : Proposal::STATUS_SUBMITTED;

            if ($model->validate()) {
                if ($model->save(false)) {
                    // Save uploaded files to proposal_file table
                    if ($model->uploaded_files) {
                        $uploadDir = Yii::getAlias('@webroot/uploads/proposal/');
                        FileHelper::createDirectory($uploadDir);
                        
                        foreach ($model->uploaded_files as $file) {
                            $fileName = md5(uniqid('', true)) . '.' . $file->extension;
                            $filePath = 'uploads/proposal/' . $fileName;
                            if ($file->saveAs($uploadDir . $fileName)) {
                                $proposalFile = new ProposalFile();
                                $proposalFile->proposal_id = $model->id;
                                $proposalFile->file_name = $file->name;
                                $proposalFile->file_path = $filePath;
                                $proposalFile->file_size = $file->size;
                                $proposalFile->file_type = $file->type;
                                $proposalFile->uploaded_at = time();
                                $proposalFile->save(false);
                            }
                        }
                    }

                    if ($model->status === Proposal::STATUS_SUBMITTED) {
                        AuditLog::log('Proposal Submit', "Submitted updated proposal ID: " . $model->id);
                        
                        // Notify company owner that proposal has been updated/submitted
                        Notification::notify(
                            $model->problem->company_id,
                            'Proposal Updated',
                            "Scientist " . $model->scientistProfile->getFullName() . " updated solution proposal '{$model->title}'",
                            "/proposal/view?id=" . $model->id
                        );
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Proposal submitted successfully.'));
                    } else {
                        Yii::$app->session->setFlash('success', 'Proposal draft updated.');
                    }
                    return $this->redirect(['/dashboard/index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Proposal.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel((int)$id);

        // RBAC Check for Proposal Delete (Admin can delete any, Scientist owner can delete own)
        if (!Yii::$app->user->can('deleteProposal') && !Yii::$app->user->can('deleteOwnProposal', ['proposal' => $model])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Scientists cannot delete proposals after company starts reviewing them (status != submitted && status != draft)
        $isDeletable = ($model->status === Proposal::STATUS_DRAFT || $model->status === Proposal::STATUS_SUBMITTED);
        if (!$isDeletable && !Yii::$app->user->can('deleteProposal')) {
            throw new ForbiddenHttpException('You cannot delete this proposal because it is already under review by the company.');
        }

        // Notify company owner that proposal was withdrawn (if it was already submitted)
        if ($model->status === Proposal::STATUS_SUBMITTED) {
            Notification::notify(
                $model->problem->company_id,
                'Proposal Withdrawn',
                "Proposal '{$model->title}' was withdrawn by the scientist.",
                "/dashboard/index"
            );
            AuditLog::log('Proposal Withdraw', "Withdrew proposal ID: " . $model->id);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Proposal deleted successfully.');
        return $this->redirect(['/dashboard/index']);
    }

    /**
     * Marks proposal as "Under Review" (Company only).
     */
    public function actionReview($id)
    {
        $model = $this->findModel((int)$id);
        $problem = $model->problem;

        if ($problem->company_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Set proposal as Evaluating
        $model->status = Proposal::STATUS_EVALUATING;
        $model->save(false);

        // Set problem as Under Review if not already solved/closed
        if ($problem->status === Problem::STATUS_ACTIVE) {
            $problem->status = Problem::STATUS_UNDER_REVIEW;
            $problem->save(false);
        }

        // Notify scientist
        Notification::notify(
            $model->scientist_id,
            'Proposal Under Review',
            "Your proposal for '{$problem->title}' is now under review by the company.",
            "/proposal/view?id=" . $model->id
        );

        AuditLog::log('Proposal Review Status', "Set proposal ID: " . $model->id . " under review.");
        Yii::$app->session->setFlash('success', 'Proposal status marked as Under Review.');

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Accept a Proposal (Company only, transactional).
     */
    public function actionSelect($id)
    {
        $model = $this->findModel((int)$id);
        $problem = $model->problem;

        if ($problem->company_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        if ($problem->status === Problem::STATUS_SOLVED) {
            Yii::$app->session->setFlash('warning', 'This challenge has already been solved.');
            return $this->redirect(['/proposal/view', 'id' => $model->id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Set this proposal as selected
            $model->status = Proposal::STATUS_SELECTED;
            $model->save(false);

            // Set the problem as solved
            $problem->status = Problem::STATUS_SOLVED;
            $problem->save(false);

            // Reject all other proposals for this problem
            Proposal::updateAll(
                ['status' => Proposal::STATUS_REJECTED],
                'problem_id = :problem_id AND id != :id AND status IN (:status_sub, :status_eval)',
                [
                    ':problem_id' => $problem->id,
                    ':id' => $model->id,
                    ':status_sub' => Proposal::STATUS_SUBMITTED,
                    ':status_eval' => Proposal::STATUS_EVALUATING,
                ]
            );

            // Notify scientist
            Notification::notify(
                $model->scientist_id,
                Yii::t('app', 'Proposal accepted. Contact details shared.'),
                "Congratulations! Your proposal for '{$problem->title}' was accepted. Contact details are now available.",
                "/proposal/view?id=" . $model->id
            );

            // Audit log
            AuditLog::log('Proposal Acceptance', "Accepted proposal ID: " . $model->id . " for problem: " . $problem->title);

            $transaction->commit();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Proposal accepted. Contact details shared.'));
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Proposal selection failed: " . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Action failed. Please try again.');
        }

        return $this->redirect(['/proposal/view', 'id' => $model->id]);
    }

    /**
     * Reject a Proposal (Company only).
     */
    public function actionReject($id)
    {
        $model = $this->findModel((int)$id);
        $problem = $model->problem;

        if ($problem->company_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        $model->status = Proposal::STATUS_REJECTED;
        if ($model->save(false)) {
            Notification::notify(
                $model->scientist_id,
                Yii::t('app', 'Proposal rejected.'),
                "Your proposal for '{$problem->title}' was rejected by the company.",
                "/proposal/view?id=" . $model->id
            );

            AuditLog::log('Proposal Rejection', "Rejected proposal ID: " . $model->id);
            Yii::$app->session->setFlash('info', 'Proposal rejected.');
        }

        return $this->redirect(['/proposal/view', 'id' => $model->id]);
    }

    /**
     * Deletes an uploaded proposal file.
     */
    public function actionDeleteFile($id)
    {
        $file = ProposalFile::findOne((int)$id);
        if (!$file) {
            throw new NotFoundHttpException('File not found.');
        }

        $proposal = $file->proposal;
        if (!Yii::$app->user->can('editProposal') && !Yii::$app->user->can('editOwnProposal', ['proposal' => $proposal])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Scientists cannot edit proposals after company starts reviewing them (status != submitted && status != draft)
        $isEditable = ($proposal->status === Proposal::STATUS_DRAFT || $proposal->status === Proposal::STATUS_SUBMITTED);
        if (!$isEditable && !Yii::$app->user->can('editProposal')) {
            throw new ForbiddenHttpException('You cannot modify this proposal because it is already under review by the company.');
        }

        // Delete physical file
        $physicalPath = Yii::getAlias('@webroot/' . $file->file_path);
        if (file_exists($physicalPath)) {
            @unlink($physicalPath);
        }

        $file->delete();
        Yii::$app->session->setFlash('success', 'File deleted successfully.');
        return $this->redirect(Yii::$app->request->referrer ?: ['update', 'id' => $proposal->id]);
    }

    /**
     * Finds the Proposal model based on its primary key value.
     */
    protected function findModel(int $id): Proposal
    {
        if (($model = Proposal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
