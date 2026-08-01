<?php

declare(strict_types=1);

namespace app\services;

use app\models\Notification;

/**
 * Service class for system notifications management.
 */
class NotificationService
{
    /**
     * Dispatch notification to a user.
     */
    public static function send(int $userId, string $title, string $message, ?string $link = null): bool
    {
        return Notification::notify($userId, $title, $message, $link);
    }
}
