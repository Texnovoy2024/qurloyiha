<?php

use yii\db\Migration;

class m260722_063358_add_workflow_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Add columns to company_profile
        $this->addColumn('{{%company_profile}}', 'organization_type', $this->string());

        // 2. Add columns to scientist_profile
        $this->addColumn('{{%scientist_profile}}', 'skills', $this->text());
        $this->addColumn('{{%scientist_profile}}', 'portfolio', $this->text());
        $this->addColumn('{{%scientist_profile}}', 'publications', $this->text());
        $this->addColumn('{{%scientist_profile}}', 'certificates', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%scientist_profile}}', 'certificates');
        $this->dropColumn('{{%scientist_profile}}', 'publications');
        $this->dropColumn('{{%scientist_profile}}', 'portfolio');
        $this->dropColumn('{{%scientist_profile}}', 'skills');

        $this->dropColumn('{{%company_profile}}', 'organization_type');
    }
}
