<?php

use yii\db\Migration;

class m260722_062055_add_functional_requirements_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // 1. Add columns to company_profile
        $this->addColumn('{{%company_profile}}', 'stir', $this->string(32));
        $this->addColumn('{{%company_profile}}', 'responsible_name', $this->string());

        // 2. Add columns to scientist_profile
        $this->addColumn('{{%scientist_profile}}', 'dob', $this->string(64));
        $this->addColumn('{{%scientist_profile}}', 'academic_title', $this->string());
        $this->addColumn('{{%scientist_profile}}', 'photo', $this->string());

        // 3. Add columns to problem
        $this->addColumn('{{%problem}}', 'expected_result', $this->text());
        $this->addColumn('{{%problem}}', 'attachment_file', $this->string());

        // 4. Create favorite table
        $this->createTable('{{%favorite}}', [
            'user_id' => $this->integer()->notNull(),
            'problem_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_favorite', '{{%favorite}}', ['user_id', 'problem_id']);
        $this->addForeignKey('fk_favorite_user', '{{%favorite}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_favorite_problem', '{{%favorite}}', 'problem_id', '{{%problem}}', 'id', 'CASCADE', 'CASCADE');

        // 5. Create audit_log table
        $this->createTable('{{%audit_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'action' => $this->string()->notNull(),
            'details' => $this->text(),
            'ip_address' => $this->string(64),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_audit_log_user', '{{%audit_log}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_audit_log_user', '{{%audit_log}}');
        $this->dropTable('{{%audit_log}}');

        $this->dropForeignKey('fk_favorite_problem', '{{%favorite}}');
        $this->dropForeignKey('fk_favorite_user', '{{%favorite}}');
        $this->dropTable('{{%favorite}}');

        $this->dropColumn('{{%problem}}', 'attachment_file');
        $this->dropColumn('{{%problem}}', 'expected_result');

        $this->dropColumn('{{%scientist_profile}}', 'photo');
        $this->dropColumn('{{%scientist_profile}}', 'academic_title');
        $this->dropColumn('{{%scientist_profile}}', 'dob');

        $this->dropColumn('{{%company_profile}}', 'responsible_name');
        $this->dropColumn('{{%company_profile}}', 'stir');
    }
}
