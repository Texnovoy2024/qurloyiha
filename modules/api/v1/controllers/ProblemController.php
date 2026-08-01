<?php

declare(strict_types=1);

namespace app\modules\api\v1\controllers;

use Yii;
use app\models\Problem;

/**
 * Problem REST API endpoints.
 */
class ProblemController extends BaseApiController
{
    /**
     * List and filter problems endpoint.
     */
    public function actionIndex(): array
    {
        $query = Problem::find()->where(['status' => Problem::STATUS_ACTIVE]);
        
        $search = Yii::$app->request->get('keyword');
        $categoryId = Yii::$app->request->get('category');
        $budget = Yii::$app->request->get('budget');
        $deadline = Yii::$app->request->get('deadline');
        $status = Yii::$app->request->get('status');

        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['like', 'title', $search],
                ['like', 'description', $search]
            ]);
        }
        if (!empty($categoryId)) {
            $query->andWhere(['category_id' => $categoryId]);
        }
        if (!empty($budget)) {
            $query->andWhere(['>=', 'budget', (float)$budget]);
        }
        if (!empty($deadline)) {
            $query->andWhere(['<=', 'deadline', $deadline]);
        }
        if (!empty($status)) {
            $query->andWhere(['status' => (int)$status]);
        }

        $problems = $query->orderBy(['created_at' => SORT_DESC])->all();
        
        $data = [];
        foreach ($problems as $problem) {
            $data[] = [
                'id' => $problem->id,
                'title' => $problem->title,
                'description' => $problem->description,
                'budget' => $problem->budget,
                'deadline' => $problem->deadline,
                'company' => $problem->companyProfile->company_name ?? 'N/A',
                'category' => $problem->category->name_en,
            ];
        }
        return $this->success('Problems loaded.', $data);
    }

    /**
     * View problem details endpoint.
     */
    public function actionView(int $id): array
    {
        $problem = Problem::findOne($id);
        if (!$problem) {
            return $this->error('Problem not found.', [], 404);
        }
        return $this->success('Problem details loaded.', [
            'id' => $problem->id,
            'title' => $problem->title,
            'description' => $problem->description,
            'requirements' => $problem->requirements,
            'expected_result' => $problem->expected_result,
            'budget' => $problem->budget,
            'deadline' => $problem->deadline,
            'company' => $problem->companyProfile,
            'category' => $problem->category,
        ]);
    }
}
