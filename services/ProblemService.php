<?php

declare(strict_types=1);

namespace app\services;

use app\models\Problem;
use app\models\AuditLog;

/**
 * Service class for problem business logic encapsulation.
 */
class ProblemService
{
    /**
     * Move problem status to archived.
     */
    public static function archive(Problem $problem): bool
    {
        $problem->status = Problem::STATUS_ARCHIVED;
        if ($problem->save(false)) {
            AuditLog::log('Problem Archive', "Archived challenge: " . $problem->title);
            return true;
        }
        return false;
    }
}
