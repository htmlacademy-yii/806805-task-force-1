<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_files}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 */
class m201227_084022_create_task_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_files}}', [
            'file_id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'file_addr' => $this->string(255),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-task_files-task_id}}',
            '{{%task_files}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-task_files-task_id}}',
            '{{%task_files}}',
            'task_id',
            '{{%tasks}}',
            'task_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tasks}}`
        $this->dropForeignKey(
            '{{%fk-task_files-task_id}}',
            '{{%task_files}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-task_files-task_id}}',
            '{{%task_files}}'
        );

        $this->dropTable('{{%task_files}}');
    }
}
