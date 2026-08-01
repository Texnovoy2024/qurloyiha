<?php

use yii\db\Migration;

class m260722_121520_add_access_token_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'access_token', $this->string()->unique());
        
        // Seed mock access tokens for testing
        $this->update('{{%user}}', ['access_token' => 'admin_bearer_token'], ['username' => 'admin']);
        $this->update('{{%user}}', ['access_token' => 'company_bearer_token'], ['username' => 'company1']);
        $this->update('{{%user}}', ['access_token' => 'scientist_bearer_token'], ['username' => 'scientist1']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'access_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260722_121520_add_access_token_to_user cannot be reverted.\n";

        return false;
    }
    */
}
