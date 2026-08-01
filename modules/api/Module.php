<?php

declare(strict_types=1);

namespace app\modules\api;

/**
 * Base API module.
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\api\v1\Module',
            ],
        ];
    }
}
