<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_statuses}}`.
 */
class m201227_083639_create_task_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_statuses}}', [
            'status_id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull()->unique(),
            'const_name' => $this->string(64)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_statuses}}');
    }
}
