<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\models\Problem;
use app\models\Proposal;
use app\models\Category;
use app\models\AuditLog;
use app\models\Setting;
use yii\helpers\FileHelper;

class DashboardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Dashboard main action.
     */
    public function actionIndex()
    {
        /** @var \app\models\User $user */
        $user = Yii::$app->user->identity;
        $userId = $user->id;

        if ($user->role === User::ROLE_COMPANY) {
            // Company Dashboard
            $problemsQuery = Problem::find()->where(['company_id' => $userId])->orderBy(['created_at' => SORT_DESC]);
            $problemsCount = (int)$problemsQuery->count();
            
            $problemsPages = new \yii\data\Pagination([
                'totalCount' => $problemsCount,
                'defaultPageSize' => 20,
                'pageSizeLimit' => [1, 100],
                'pageParam' => 'problems-page',
            ]);
            
            $problems = $problemsQuery->offset($problemsPages->offset)
                ->limit($problemsPages->limit)
                ->all();
            
            $activeProblemsCount = (int) Problem::find()->where(['company_id' => $userId, 'status' => Problem::STATUS_ACTIVE])->count();
            $closedProblemsCount = (int) Problem::find()->where(['company_id' => $userId, 'status' => [Problem::STATUS_SOLVED, Problem::STATUS_CANCELLED]])->count();

            $proposalsCount = (int) Proposal::find()
                ->joinWith('problem')
                ->where(['problem.company_id' => $userId])
                ->count();
            
            $selectedProposalsCount = (int) Proposal::find()
                ->joinWith('problem')
                ->where(['problem.company_id' => $userId, 'proposal.status' => Proposal::STATUS_SELECTED])
                ->count();
            
            $recentProposals = Proposal::find()
                ->joinWith('problem')
                ->where(['problem.company_id' => $userId])
                ->orderBy(['proposal.created_at' => SORT_DESC])
                ->limit(5)
                ->all();

            return $this->render('company', [
                'problems' => $problems,
                'problemsCount' => $problemsCount,
                'activeProblemsCount' => $activeProblemsCount,
                'closedProblemsCount' => $closedProblemsCount,
                'proposalsCount' => $proposalsCount,
                'selectedProposalsCount' => $selectedProposalsCount,
                'recentProposals' => $recentProposals,
                'problemsPages' => $problemsPages,
            ]);
        }

        if ($user->role === User::ROLE_SCIENTIST) {
            // Scientist Dashboard
            $proposalsQuery = Proposal::find()->where(['scientist_id' => $userId])->orderBy(['created_at' => SORT_DESC]);
            $proposalsCount = (int)$proposalsQuery->count();
            
            $proposalsPages = new \yii\data\Pagination([
                'totalCount' => $proposalsCount,
                'defaultPageSize' => 20,
                'pageSizeLimit' => [1, 100],
                'pageParam' => 'proposals-page',
            ]);
            
            $proposals = $proposalsQuery->offset($proposalsPages->offset)
                ->limit($proposalsPages->limit)
                ->all();
            
            $selectedCount = (int) Proposal::find()->where(['scientist_id' => $userId, 'status' => Proposal::STATUS_SELECTED])->count();
            $rejectedCount = (int) Proposal::find()->where(['scientist_id' => $userId, 'status' => Proposal::STATUS_REJECTED])->count();
            
            $recommendedProblems = Problem::find()
                ->where(['status' => Problem::STATUS_ACTIVE])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(5)
                ->all();

            $favoriteProblems = $user->favoriteProblems;

            return $this->render('scientist', [
                'proposals' => $proposals,
                'proposalsCount' => $proposalsCount,
                'selectedCount' => $selectedCount,
                'rejectedCount' => $rejectedCount,
                'recommendedProblems' => $recommendedProblems,
                'favoriteProblems' => $favoriteProblems,
                'proposalsPages' => $proposalsPages,
            ]);
        }

        if ($user->role === User::ROLE_ADMIN) {
            // Admin Dashboard
            $usersCount = (int) User::find()->count();
            $problemsCount = (int) Problem::find()->count();
            $proposalsCount = (int) Proposal::find()->count();

            $pendingProblems = Problem::find()
                ->where(['status' => Problem::STATUS_MODERATION])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();

            $categories = Category::find()->all();
            
            // List of companies & scientists for management
            $companiesQuery = User::find()->where(['role' => User::ROLE_COMPANY])->orderBy(['username' => SORT_ASC]);
            $companiesPages = new \yii\data\Pagination([
                'totalCount' => (int)$companiesQuery->count(),
                'defaultPageSize' => 20,
                'pageSizeLimit' => [1, 100],
                'pageParam' => 'companies-page',
            ]);
            $companies = $companiesQuery->offset($companiesPages->offset)
                ->limit($companiesPages->limit)
                ->all();

            $scientistsQuery = User::find()->where(['role' => User::ROLE_SCIENTIST])->orderBy(['username' => SORT_ASC]);
            $scientistsPages = new \yii\data\Pagination([
                'totalCount' => (int)$scientistsQuery->count(),
                'defaultPageSize' => 20,
                'pageSizeLimit' => [1, 100],
                'pageParam' => 'scientists-page',
            ]);
            $scientists = $scientistsQuery->offset($scientistsPages->offset)
                ->limit($scientistsPages->limit)
                ->all();

            // Fetch last 50 audit log actions with pagination
            $auditLogsQuery = AuditLog::find()->orderBy(['created_at' => SORT_DESC]);
            $auditLogsPages = new \yii\data\Pagination([
                'totalCount' => (int)$auditLogsQuery->count(),
                'defaultPageSize' => 20,
                'pageSizeLimit' => [1, 100],
                'pageParam' => 'audit-page',
            ]);
            $auditLogs = $auditLogsQuery->offset($auditLogsPages->offset)
                ->limit($auditLogsPages->limit)
                ->all();

            $newCategory = new Category();
            if ($newCategory->load(Yii::$app->request->post()) && $newCategory->save()) {
                AuditLog::log('Category Create', "Created category: " . $newCategory->name_en);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Category created.'));
                return $this->refresh();
            }

            // Fetch database backups
            $backupDir = Yii::getAlias('@webroot/backups/');
            $backups = [];
            if (is_dir($backupDir)) {
                $files = scandir($backupDir);
                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                        $backups[] = [
                            'name' => $file,
                            'size' => filesize($backupDir . $file),
                            'date' => filemtime($backupDir . $file),
                        ];
                    }
                }
                // Sort by date descending
                usort($backups, function ($a, $b) {
                    return $b['date'] <=> $a['date'];
                });
            }

            return $this->render('admin', [
                'usersCount' => $usersCount,
                'problemsCount' => $problemsCount,
                'proposalsCount' => $proposalsCount,
                'pendingProblems' => $pendingProblems,
                'categories' => $categories,
                'companies' => $companies,
                'scientists' => $scientists,
                'auditLogs' => $auditLogs,
                'newCategory' => $newCategory,
                'backups' => $backups,
                'companiesPages' => $companiesPages,
                'scientistsPages' => $scientistsPages,
                'auditLogsPages' => $auditLogsPages,
            ]);
        }

        throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
    }

    /**
     * Creates a database backup.
     */
    public function actionBackupCreate()
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $db = Yii::$app->db;
        $tables = $db->createCommand('SHOW TABLES')->queryColumn();
        
        $sql = "-- Antigravity Database Backup\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $createTable = $db->createCommand("SHOW CREATE TABLE `$table`")->queryOne();
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $createTable['Create Table'] . ";\n\n";

            $rows = $db->createCommand("SELECT * FROM `$table`")->queryAll();
            if (!empty($rows)) {
                $sql .= "INSERT INTO `$table` VALUES \n";
                $rowStatements = [];
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $val) {
                        if ($val === null) {
                            $values[] = "NULL";
                        } else {
                            $values[] = $db->quoteValue($val);
                        }
                    }
                    $rowStatements[] = "(" . implode(', ', $values) . ")";
                }
                $sql .= implode(",\n", $rowStatements) . ";\n\n";
            }
        }
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $backupDir = Yii::getAlias('@webroot/backups/');
        FileHelper::createDirectory($backupDir);
        
        $fileName = 'backup_' . date('Y-m-d_H-i-s') . '_' . time() . '.sql';
        file_put_contents($backupDir . $fileName, $sql);

        AuditLog::log('Backup Create', "Created database backup: " . $fileName);
        Yii::$app->session->setFlash('success', 'Database backup created successfully: ' . $fileName);

        return $this->redirect(['index']);
    }

    /**
     * Restores database from backup file.
     */
    public function actionBackupRestore($filename)
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $backupDir = Yii::getAlias('@webroot/backups/');
        $backupPath = $backupDir . basename($filename);

        if (!file_exists($backupPath)) {
            throw new NotFoundHttpException('Backup file not found.');
        }

        $db = Yii::$app->db;
        $sqlContent = file_get_contents($backupPath);

        // Remove comments
        $sqlContent = preg_replace('/^[ \t]*--.*/m', '', $sqlContent);
        // Split by semicolon followed by newline
        $statements = preg_split('/;\s*$/m', $sqlContent);

        $transaction = $db->beginTransaction();
        try {
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if ($statement !== '') {
                    $db->createCommand($statement)->execute();
                }
            }
            $transaction->commit();
            
            AuditLog::log('Backup Restore', "Restored database from: " . $filename);
            Yii::$app->session->setFlash('success', 'Database restored successfully from: ' . $filename);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Restore failed: " . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Restore failed: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes a backup file.
     */
    public function actionBackupDelete($filename)
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $backupDir = Yii::getAlias('@webroot/backups/');
        $backupPath = $backupDir . basename($filename);

        if (file_exists($backupPath)) {
            @unlink($backupPath);
            AuditLog::log('Backup Delete', "Deleted database backup: " . $filename);
            Yii::$app->session->setFlash('success', 'Backup file deleted.');
        } else {
            Yii::$app->session->setFlash('error', 'Backup file not found.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Blocks a user (Admin only).
     */
    public function actionBlockUser(int $id)
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        if ($user->role === User::ROLE_ADMIN) {
            Yii::$app->session->setFlash('error', 'Cannot block administrator accounts.');
            return $this->redirect(['index']);
        }

        $user->status = User::STATUS_INACTIVE;
        if ($user->save(false)) {
            AuditLog::log('Block User', "Blocked user username: " . $user->username);
            Yii::$app->session->setFlash('success', "User '{$user->username}' was blocked successfully.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Unblocks a user (Admin only).
     */
    public function actionUnblockUser(int $id)
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        $user->status = User::STATUS_ACTIVE;
        if ($user->save(false)) {
            AuditLog::log('Unblock User', "Unblocked user username: " . $user->username);
            Yii::$app->session->setFlash('success', "User '{$user->username}' was unblocked successfully.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Export platform statistics to CSV format (Admin only).
     */
    public function actionExportCsv()
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $usersCount = User::find()->count();
        $companiesCount = User::find()->where(['role' => User::ROLE_COMPANY])->count();
        $scientistsCount = User::find()->where(['role' => User::ROLE_SCIENTIST])->count();
        
        $problemsCount = Problem::find()->count();
        $problemsActive = Problem::find()->where(['status' => Problem::STATUS_ACTIVE])->count();
        $problemsClosed = Problem::find()->where(['status' => Problem::STATUS_SOLVED])->count();
        
        $proposalsCount = Proposal::find()->count();
        $proposalsAccepted = Proposal::find()->where(['status' => Proposal::STATUS_SELECTED])->count();

        $csvContent = "Metric,Value\r\n";
        $csvContent .= "Total Users," . $usersCount . "\r\n";
        $csvContent .= "Total Companies," . $companiesCount . "\r\n";
        $csvContent .= "Total Scientists," . $scientistsCount . "\r\n";
        $csvContent .= "Total Problems," . $problemsCount . "\r\n";
        $csvContent .= "Active Problems (Published)," . $problemsActive . "\r\n";
        $csvContent .= "Closed Problems (Solved)," . $problemsClosed . "\r\n";
        $csvContent .= "Total Proposals Submitted," . $proposalsCount . "\r\n";
        $csvContent .= "Accepted Proposals," . $proposalsAccepted . "\r\n";

        // Record Audit log
        AuditLog::log('Export Report', "Exported statistics report to CSV.");

        Yii::$app->response->sendContentAsFile($csvContent, 'antigravity_stats_' . date('Ymd_His') . '.csv', [
            'mimeType' => 'text/csv',
            'inline' => false
        ]);
        return Yii::$app->end();
    }

    /**
     * Export platform statistics to print-friendly view (Admin only).
     */
    public function actionExportPrint()
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }
        $this->layout = false;

        $usersCount = (int) User::find()->count();
        $companiesCount = (int) User::find()->where(['role' => User::ROLE_COMPANY])->count();
        $scientistsCount = (int) User::find()->where(['role' => User::ROLE_SCIENTIST])->count();
        
        $problemsCount = (int) Problem::find()->count();
        $problemsActive = (int) Problem::find()->where(['status' => Problem::STATUS_ACTIVE])->count();
        $problemsClosed = (int) Problem::find()->where(['status' => Problem::STATUS_SOLVED])->count();
        
        $proposalsCount = (int) Proposal::find()->count();
        $proposalsAccepted = (int) Proposal::find()->where(['status' => Proposal::STATUS_SELECTED])->count();

        // Record Audit log
        AuditLog::log('Print Report', "Printed statistics report.");

        return $this->render('print_report', [
            'usersCount' => $usersCount,
            'companiesCount' => $companiesCount,
            'scientistsCount' => $scientistsCount,
            'problemsCount' => $problemsCount,
            'problemsActive' => $problemsActive,
            'problemsClosed' => $problemsClosed,
            'proposalsCount' => $proposalsCount,
            'proposalsAccepted' => $proposalsAccepted,
        ]);
    }

    /**
     * Saves system configuration settings (Admin only).
     */
    public function actionSaveSettings()
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $post = Yii::$app->request->post('Setting', []);
        foreach ($post as $key => $val) {
            $setting = Setting::findOne(['key' => $key]);
            if ($setting) {
                $setting->value = (string)$val;
                $setting->save(false);
            }
        }

        AuditLog::log('Settings Update', "Updated system configuration settings.");
        Yii::$app->session->setFlash('success', 'System configuration settings updated successfully.');
        return $this->redirect(['index']);
    }

    /**
     * Toggles a category active/disabled status (Admin only).
     */
    public function actionToggleCategory($id)
    {
        if (!Yii::$app->user->can('adminAccess')) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $category = Category::findOne((int)$id);
        if (!$category) {
            throw new NotFoundHttpException('Category not found.');
        }

        $category->status = $category->status === Category::STATUS_ACTIVE ? Category::STATUS_INACTIVE : Category::STATUS_ACTIVE;
        $category->save(false);

        AuditLog::log('Category Toggle', "Toggled status of category: " . $category->name_en);
        Yii::$app->session->setFlash('success', 'Category status toggled successfully.');

        return $this->redirect(['index']);
    }
}
