<?php

declare(strict_types=1);

namespace app\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed in params
 */
class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params): bool
    {
        if (isset($params['problem'])) {
            return (string)$params['problem']->company_id === (string)$user;
        }
        if (isset($params['proposal'])) {
            return (string)$params['proposal']->scientist_id === (string)$user;
        }
        return false;
    }
}
