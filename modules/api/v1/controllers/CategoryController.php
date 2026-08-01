<?php

declare(strict_types=1);

namespace app\modules\api\v1\controllers;

use app\models\Category;

/**
 * Category REST API endpoints.
 */
class CategoryController extends BaseApiController
{
    /**
     * List active categories.
     */
    public function actionIndex(): array
    {
        $categories = Category::find()->where(['status' => Category::STATUS_ACTIVE])->all();
        $data = [];
        foreach ($categories as $cat) {
            $data[] = [
                'id' => $cat->id,
                'name_uz' => $cat->name_uz,
                'name_ru' => $cat->name_ru,
                'name_en' => $cat->name_en,
                'description' => $cat->description,
            ];
        }
        return $this->success('Categories loaded.', $data);
    }

    /**
     * View category details.
     */
    public function actionView(int $id): array
    {
        $cat = Category::findOne($id);
        if (!$cat) {
            return $this->error('Category not found.', [], 404);
        }
        return $this->success('Category details loaded.', [
            'id' => $cat->id,
            'name_uz' => $cat->name_uz,
            'name_ru' => $cat->name_ru,
            'name_en' => $cat->name_en,
            'description' => $cat->description,
        ]);
    }
}
