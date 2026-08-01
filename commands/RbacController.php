<?php

declare(strict_types=1);

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\rbac\AuthorRule;

class RbacController extends Controller
{
    /**
     * Initializes RBAC roles and permissions.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        if (!$auth) {
            $this->stderr("AuthManager component not configured.\n");
            return Controller::EXIT_CODE_ERROR;
        }

        // Clear all previous authorization data
        $auth->removeAll();

        // Add the author ownership rule
        $rule = new AuthorRule();
        $auth->add($rule);

        // Add Permissions
        $createProblem = $auth->createPermission('createProblem');
        $createProblem->description = 'Create a problem challenge';
        $auth->add($createProblem);

        $editProblem = $auth->createPermission('editProblem');
        $editProblem->description = 'Edit any problem challenge';
        $auth->add($editProblem);

        $editOwnProblem = $auth->createPermission('editOwnProblem');
        $editOwnProblem->description = 'Edit own problem challenge';
        $editOwnProblem->ruleName = $rule->name;
        $auth->add($editOwnProblem);
        $auth->addChild($editOwnProblem, $editProblem);

        $deleteProblem = $auth->createPermission('deleteProblem');
        $deleteProblem->description = 'Delete any problem challenge';
        $auth->add($deleteProblem);

        $deleteOwnProblem = $auth->createPermission('deleteOwnProblem');
        $deleteOwnProblem->description = 'Delete own problem challenge';
        $deleteOwnProblem->ruleName = $rule->name;
        $auth->add($deleteOwnProblem);
        $auth->addChild($deleteOwnProblem, $deleteProblem);

        $submitProposal = $auth->createPermission('submitProposal');
        $submitProposal->description = 'Submit a solution proposal';
        $auth->add($submitProposal);

        $editProposal = $auth->createPermission('editProposal');
        $editProposal->description = 'Edit any solution proposal';
        $auth->add($editProposal);

        $editOwnProposal = $auth->createPermission('editOwnProposal');
        $editOwnProposal->description = 'Edit own solution proposal';
        $editOwnProposal->ruleName = $rule->name;
        $auth->add($editOwnProposal);
        $auth->addChild($editOwnProposal, $editProposal);

        $deleteProposal = $auth->createPermission('deleteProposal');
        $deleteProposal->description = 'Delete any solution proposal';
        $auth->add($deleteProposal);

        $deleteOwnProposal = $auth->createPermission('deleteOwnProposal');
        $deleteOwnProposal->description = 'Delete own solution proposal';
        $deleteOwnProposal->ruleName = $rule->name;
        $auth->add($deleteOwnProposal);
        $auth->addChild($deleteOwnProposal, $deleteProposal);

        $adminAccess = $auth->createPermission('adminAccess');
        $adminAccess->description = 'Admin panel reporting and moderation actions';
        $auth->add($adminAccess);

        // Define Roles
        $scientist = $auth->createRole('scientist');
        $auth->add($scientist);
        $auth->addChild($scientist, $submitProposal);
        $auth->addChild($scientist, $editOwnProposal);
        $auth->addChild($scientist, $deleteOwnProposal);

        $company = $auth->createRole('company');
        $auth->add($company);
        $auth->addChild($company, $createProblem);
        $auth->addChild($company, $editOwnProblem);
        $auth->addChild($company, $deleteOwnProblem);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $adminAccess);
        $auth->addChild($admin, $editProblem);
        $auth->addChild($admin, $deleteProblem);
        $auth->addChild($admin, $editProposal);
        $auth->addChild($admin, $deleteProposal);

        // Inherit permissions of other roles
        $auth->addChild($admin, $company);
        $auth->addChild($admin, $scientist);

        $this->stdout("RBAC successfully initialized!\n");
        return Controller::EXIT_CODE_NORMAL;
    }
}
