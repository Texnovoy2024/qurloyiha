<?php

use yii\db\Migration;

class m260722_064213_create_files_and_settings_tables extends Migration
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

        // 1. Create problem_file table
        $this->createTable('{{%problem_file}}', [
            'id' => $this->primaryKey(),
            'problem_id' => $this->integer()->notNull(),
            'file_name' => $this->string()->notNull(),
            'file_path' => $this->string()->notNull(),
            'file_size' => $this->integer()->notNull(),
            'file_type' => $this->string()->notNull(),
            'uploaded_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_problem_file_problem', '{{%problem_file}}', 'problem_id', '{{%problem}}', 'id', 'CASCADE', 'CASCADE');

        // 2. Create proposal_file table
        $this->createTable('{{%proposal_file}}', [
            'id' => $this->primaryKey(),
            'proposal_id' => $this->integer()->notNull(),
            'file_name' => $this->string()->notNull(),
            'file_path' => $this->string()->notNull(),
            'file_size' => $this->integer()->notNull(),
            'file_type' => $this->string()->notNull(),
            'uploaded_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_proposal_file_proposal', '{{%proposal_file}}', 'proposal_id', '{{%proposal}}', 'id', 'CASCADE', 'CASCADE');

        // 3. Create setting table
        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull()->unique(),
            'value' => $this->text(),
            'description' => $this->string(),
        ], $tableOptions);

        // 4. Seed default settings
        $this->batchInsert('{{%setting}}', ['key', 'value', 'description'], [
            ['site_name', 'Qurilish-loyiha.uz', 'Site Title name'],
            ['session_timeout', '1440', 'Session timeout in seconds'],
            ['max_file_size', '52428800', 'Maximum file upload limit in bytes (50MB)'],
            ['maintenance_mode', '0', 'Maintenance Mode (1 = Enabled, 0 = Disabled)'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');

        $this->dropForeignKey('fk_proposal_file_proposal', '{{%proposal_file}}');
        $this->dropTable('{{%proposal_file}}');

        $this->dropForeignKey('fk_problem_file_problem', '{{%problem_file}}');
        $this->dropTable('{{%problem_file}}');
    }
}
