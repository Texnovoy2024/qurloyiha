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
use app\models\Problem;
use app\models\Category;
use app\models\User;
use app\models\Favorite;
use app\models\Notification;
use app\models\AuditLog;
use app\models\ProblemFile;
use app\services\ProblemService;

class ProblemController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'approve', 'spam', 'favorite', 'delete-file', 'archive'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createProblem'],
                    ],
                    [
                        'actions' => ['update', 'delete', 'delete-file', 'archive'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['approve', 'spam'],
                        'allow' => true,
                        'roles' => ['adminAccess'],
                    ],
                    [
                        'actions' => ['favorite'],
                        'allow' => true,
                        'roles' => ['submitProposal'], // Scientists only
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'approve' => ['post'],
                    'spam' => ['post'],
                    'favorite' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all active problems with advanced search/filters.
     */
    public function actionIndex()
    {
        $query = Problem::find();

        // Filter active problems for guest/scientist
        /** @var \app\models\User|null $identity */
        $identity = Yii::$app->user->identity;
        
        $status = Yii::$app->request->get('status');
        $sort = Yii::$app->request->get('sort', 'latest');
        $search = Yii::$app->request->get('search');
        $categoryId = Yii::$app->request->get('category_id');
        $minBudget = Yii::$app->request->get('min_budget');

        if (Yii::$app->user->isGuest || ($identity && $identity->role === User::ROLE_SCIENTIST)) {
            $allowedStatuses = [Problem::STATUS_ACTIVE, Problem::STATUS_UNDER_REVIEW, Problem::STATUS_SOLVED];
            if (!empty($status) && in_array((int)$status, $allowedStatuses, true)) {
                $query->andWhere(['status' => (int)$status]);
            } else {
                $query->andWhere(['status' => $allowedStatuses]);
            }
        } else {
            if (!empty($status)) {
                $query->andWhere(['status' => (int)$status]);
            } else {
                $query->andWhere(['!=', 'status', Problem::STATUS_SPAM]);
            }
        }

        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['like', 'title', $search],
                ['like', 'description', $search],
                ['like', 'expected_result', $search]
            ]);
        }

        if (!empty($categoryId)) {
            $query->andWhere(['category_id' => $categoryId]);
        }

        if (!empty($minBudget)) {
            $query->andWhere(['>=', 'budget', (float)$minBudget]);
        }

        // Apply Sorting
        if ($sort === 'oldest') {
            $query->orderBy(['created_at' => SORT_ASC]);
        } elseif ($sort === 'popular') {
            $query->orderBy(['views_count' => SORT_DESC]);
        } elseif ($sort === 'deadline') {
            $query->orderBy(['deadline' => SORT_ASC]);
        } else {
            $query->orderBy(['created_at' => SORT_DESC]);
        }

        $countQuery = clone $query;
        $pages = new \yii\data\Pagination([
            'totalCount' => (int)$countQuery->count(),
            'defaultPageSize' => 20,
            'pageSizeLimit' => [1, 100],
        ]);
        $problems = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        
        // Load only Active categories (status = 1) for regular users, all for Admin
        $categoriesQuery = Category::find();
        if (Yii::$app->user->isGuest || ($identity && $identity->role !== User::ROLE_ADMIN)) {
            $categoriesQuery->andWhere(['status' => Category::STATUS_ACTIVE]);
        }
        $categories = $categoriesQuery->all();

        return $this->render('index', [
            'problems' => $problems,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'minBudget' => $minBudget,
            'status' => $status,
            'sort' => $sort,
            'pages' => $pages,
        ]);
    }

    /**
     * Displays a single Problem model.
     */
    public function actionView($id)
    {
        $model = $this->findModel((int)$id);

        // Increase views count, except when viewed by creator or admin
        /** @var \app\models\User|null $user */
        $user = Yii::$app->user->identity;
        if (Yii::$app->user->isGuest || ($user && $user->id !== $model->company_id && $user->role !== User::ROLE_ADMIN)) {
            $model->updateCounters(['views_count' => 1]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Problem model.
     */
    public function actionCreate()
    {
        $model = new Problem();
        $model->company_id = Yii::$app->user->id;
        $model->status = Problem::STATUS_MODERATION; // Moderation by default

        if ($model->load(Yii::$app->request->post())) {
            $model->uploaded_attachments = UploadedFile::getInstances($model, 'uploaded_attachments');
            
            if ($model->validate()) {
                if ($model->save(false)) {
                    // Save uploaded files to problem_file table
                    if ($model->uploaded_attachments) {
                        $uploadDir = Yii::getAlias('@webroot/uploads/problem/');
                        FileHelper::createDirectory($uploadDir);
                        
                        foreach ($model->uploaded_attachments as $file) {
                            $fileName = md5(uniqid('', true)) . '.' . $file->extension;
                            $filePath = 'uploads/problem/' . $fileName;
                            if ($file->saveAs($uploadDir . $fileName)) {
                                $problemFile = new ProblemFile();
                                $problemFile->problem_id = $model->id;
                                $problemFile->file_name = $file->name;
                                $problemFile->file_path = $filePath;
                                $problemFile->file_size = $file->size;
                                $problemFile->file_type = $file->type;
                                $problemFile->uploaded_at = time();
                                $problemFile->save(false);
                            }
                        }
                    }

                    // Audit log
                    AuditLog::log('Problem Create', "Created challenge: " . $model->title);

                    // Notify admins about new problem submission
                    $admins = User::find()->where(['role' => User::ROLE_ADMIN])->all();
                    foreach ($admins as $admin) {
                        Notification::notify(
                            $admin->id,
                            Yii::t('app', 'Pending Moderation'),
                            "New problem submitted for moderation: " . $model->title,
                            "/dashboard/index"
                        );
                    }

                    Yii::$app->session->setFlash('success', Yii::t('app', 'Problem created and sent for moderation.'));
                    return $this->redirect(['/dashboard/index']);
                }
            }
        }

        $categories = Category::find()->all();
        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Problem model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel((int)$id);

        // RBAC Check for Problem Edit (Admin can edit any, Company owner can edit own)
        if (!Yii::$app->user->can('editProblem') && !Yii::$app->user->can('editOwnProblem', ['problem' => $model])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Prevent editing solved challenges
        if ($model->status === Problem::STATUS_SOLVED) {
            throw new ForbiddenHttpException('You cannot edit a challenge that has already been solved and closed.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->uploaded_attachments = UploadedFile::getInstances($model, 'uploaded_attachments');
            
            if ($model->validate()) {
                if ($model->save(false)) {
                    // Save uploaded files to problem_file table
                    if ($model->uploaded_attachments) {
                        $uploadDir = Yii::getAlias('@webroot/uploads/problem/');
                        FileHelper::createDirectory($uploadDir);
                        
                        foreach ($model->uploaded_attachments as $file) {
                            $fileName = md5(uniqid('', true)) . '.' . $file->extension;
                            $filePath = 'uploads/problem/' . $fileName;
                            if ($file->saveAs($uploadDir . $fileName)) {
                                $problemFile = new ProblemFile();
                                $problemFile->problem_id = $model->id;
                                $problemFile->file_name = $file->name;
                                $problemFile->file_path = $filePath;
                                $problemFile->file_size = $file->size;
                                $problemFile->file_type = $file->type;
                                $problemFile->uploaded_at = time();
                                $problemFile->save(false);
                            }
                        }
                    }

                    // Audit log
                    AuditLog::log('Problem Update', "Updated challenge: " . $model->title);

                    Yii::$app->session->setFlash('success', Yii::t('app', 'Problem updated successfully.'));
                    return $this->redirect(['/dashboard/index']);
                }
            }
        }

        $categories = Category::find()->all();
        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing Problem model.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel((int)$id);

        // RBAC Check for Problem Delete (Admin can delete any, Company owner can delete own)
        if (!Yii::$app->user->can('deleteProblem') && !Yii::$app->user->can('deleteOwnProblem', ['problem' => $model])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Prevent deleting solved challenges
        if ($model->status === Problem::STATUS_SOLVED) {
            throw new ForbiddenHttpException('You cannot delete a challenge that has already been solved and closed.');
        }

        // Audit log
        AuditLog::log('Problem Delete', "Deleted challenge ID: " . $model->id);

        $model->delete();
        return $this->redirect(['/dashboard/index']);
    }

    /**
     * Approves a Problem (Admin only).
     */
    public function actionApprove($id)
    {
        $model = $this->findModel((int)$id);
        $model->status = Problem::STATUS_ACTIVE;
        if ($model->save(false)) {
            // Audit log
            AuditLog::log('Problem Approve', "Approved challenge: " . $model->title);

            // Notify creator
            Notification::notify(
                $model->company_id,
                Yii::t('app', 'Problem approved successfully.'),
                "Your problem has been approved and is now active: " . $model->title,
                "/problem/view?id=" . $model->id
            );
            Yii::$app->session->setFlash('success', Yii::t('app', 'Problem approved successfully.'));
        }
        return $this->redirect(['/dashboard/index']);
    }

    /**
     * Marks a Problem as Spam (Admin only).
     */
    public function actionSpam($id)
    {
        $model = $this->findModel((int)$id);
        $model->status = Problem::STATUS_SPAM;
        if ($model->save(false)) {
            // Audit log
            AuditLog::log('Problem Spam', "Flagged spam challenge: " . $model->title);

            // Notify creator
            Notification::notify(
                $model->company_id,
                Yii::t('app', 'Spam removed.'),
                "Your problem was flagged as spam and removed: " . $model->title,
                "/dashboard/index"
            );
            Yii::$app->session->setFlash('success', Yii::t('app', 'Spam removed.'));
        }
        return $this->redirect(['/dashboard/index']);
    }

    /**
     * Bookmarks/Favorites a Problem (Scientist only).
     */
    public function actionFavorite(int $id)
    {
        $userId = Yii::$app->user->id;
        $fav = Favorite::findOne(['user_id' => $userId, 'problem_id' => $id]);
        
        if ($fav) {
            $fav->delete();
            Yii::$app->session->setFlash('info', 'Problem removed from favorites.');
        } else {
            $newFav = new Favorite();
            $newFav->user_id = $userId;
            $newFav->problem_id = $id;
            $newFav->save(false);
            Yii::$app->session->setFlash('success', 'Problem added to favorites.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $id]);
    }

    /**
     * Deletes an uploaded problem specification file.
     */
    public function actionDeleteFile($id)
    {
        $file = ProblemFile::findOne((int)$id);
        if (!$file) {
            throw new NotFoundHttpException('File not found.');
        }

        $problem = $file->problem;
        if (!Yii::$app->user->can('editProblem') && !Yii::$app->user->can('editOwnProblem', ['problem' => $problem])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        // Delete physical file
        $physicalPath = Yii::getAlias('@webroot/' . $file->file_path);
        if (file_exists($physicalPath)) {
            @unlink($physicalPath);
        }

        $file->delete();
        Yii::$app->session->setFlash('success', 'File deleted successfully.');
        return $this->redirect(Yii::$app->request->referrer ?: ['update', 'id' => $problem->id]);
    }

    /**
     * Archives a Problem.
     */
    public function actionArchive($id)
    {
        $model = $this->findModel((int)$id);

        if (!Yii::$app->user->can('editProblem') && !Yii::$app->user->can('editOwnProblem', ['problem' => $model])) {
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        }

        if (ProblemService::archive($model)) {
            Yii::$app->session->setFlash('success', 'Problem archived successfully.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
    }

    /**
     * Finds the Problem model based on its primary key value.
     */
    protected function findModel(int $id): Problem
    {
        if (($model = Problem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
