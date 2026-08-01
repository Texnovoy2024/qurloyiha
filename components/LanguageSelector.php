<?php

declare(strict_types=1);

namespace app\components;

use Yii;
use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    /**
     * Supported languages
     */
    public array $supportedLanguages = ['uz', 'ru', 'en'];

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        $language = null;

        // 1. Check logged-in user database setting
        if ($app instanceof \yii\web\Application && !$app->user->isGuest) {
            $user = $app->user->identity;
            if (isset($user->language) && in_array($user->language, $this->supportedLanguages, true)) {
                $language = $user->language;
            }
        }

        // 2. Check browser cookie
        if ($language === null) {
            $cookie = $app->request->cookies->get('language');
            if ($cookie !== null && in_array($cookie->value, $this->supportedLanguages, true)) {
                $language = $cookie->value;
            }
        }

        // 3. Fallback to application language (configured in web.php, default 'uz')
        if ($language === null) {
            $language = $app->language;
        }

        $app->language = $language;
    }
}
