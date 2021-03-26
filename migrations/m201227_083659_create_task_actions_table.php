<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_actions}}`.
 */
class m201227_083659_create_task_actions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_actions}}', [
            'action_id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull()->unique(),
            'const_name' => $this->string(64)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_actions}}');
    }
}
