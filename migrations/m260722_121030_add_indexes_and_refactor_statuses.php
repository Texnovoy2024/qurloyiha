<?php

use yii\db\Migration;

class m260722_121030_add_indexes_and_refactor_statuses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. Alter category status default value to 10
        $this->alterColumn('{{%category}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
        $this->update('{{%category}}', ['status' => 10], ['status' => 1]);

        // 2. Alter problem status default value to 10
        $this->alterColumn('{{%problem}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
        // Map old STATUS_ACTIVE (20) and STATUS_MODERATION (10) to STATUS_ACTIVE (10)
        $this->update('{{%problem}}', ['status' => 10], ['status' => [10, 20]]);
        // Map old STATUS_SOLVED (30) to STATUS_SOLVED (0)
        $this->update('{{%problem}}', ['status' => 0], ['status' => 30]);

        // 3. Create regular indexes
        $this->createIndex('idx-user-role', '{{%user}}', 'role');
        $this->createIndex('idx-user-status', '{{%user}}', 'status');

        $this->createIndex('idx-problem-status', '{{%problem}}', 'status');
        $this->createIndex('idx-problem-created_at', '{{%problem}}', 'created_at');
        $this->createIndex('idx-problem-deadline', '{{%problem}}', 'deadline');

        $this->createIndex('idx-proposal-status', '{{%proposal}}', 'status');
        $this->createIndex('idx-proposal-created_at', '{{%proposal}}', 'created_at');

        // 4. Create FULLTEXT indexes
        $this->execute('ALTER TABLE {{%problem}} ADD FULLTEXT INDEX ft_problem_title_desc (title, description)');
        $this->execute('ALTER TABLE {{%proposal}} ADD FULLTEXT INDEX ft_proposal_desc (description)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('ALTER TABLE {{%proposal}} DROP INDEX ft_proposal_desc');
        $this->execute('ALTER TABLE {{%problem}} DROP INDEX ft_problem_title_desc');

        $this->dropIndex('idx-proposal-created_at', '{{%proposal}}');
        $this->dropIndex('idx-proposal-status', '{{%proposal}}');
        $this->dropIndex('idx-problem-deadline', '{{%problem}}');
        $this->dropIndex('idx-problem-created_at', '{{%problem}}');
        $this->dropIndex('idx-problem-status', '{{%problem}}');
        $this->dropIndex('idx-user-status', '{{%user}}');
        $this->dropIndex('idx-user-role', '{{%user}}');

        $this->alterColumn('{{%category}}', 'status', $this->smallInteger()->notNull()->defaultValue(1));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260722_121030_add_indexes_and_refactor_statuses cannot be reverted.\n";

        return false;
    }
    */
}
