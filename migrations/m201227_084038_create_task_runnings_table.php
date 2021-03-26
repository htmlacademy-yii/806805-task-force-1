<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_runnings}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 * - `{{%users}}`
 */
class m201227_084038_create_task_runnings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_runnings}}', [
            'running_id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'contractor_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-task_runnings-task_id}}',
            '{{%task_runnings}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-task_runnings-task_id}}',
            '{{%task_runnings}}',
            'task_id',
            '{{%tasks}}',
            'task_id',
            'CASCADE'
        );

        // creates index for column `contractor_id`
        $this->createIndex(
            '{{%idx-task_runnings-contractor_id}}',
            '{{%task_runnings}}',
            'contractor_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-task_runnings-contractor_id}}',
            '{{%task_runnings}}',
            'contractor_id',
            '{{%users}}',
            'user_id',
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
            '{{%fk-task_runnings-task_id}}',
            '{{%task_runnings}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-task_runnings-task_id}}',
            '{{%task_runnings}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-task_runnings-contractor_id}}',
            '{{%task_runnings}}'
        );

        // drops index for column `contractor_id`
        $this->dropIndex(
            '{{%idx-task_runnings-contractor_id}}',
            '{{%task_runnings}}'
        );

        $this->dropTable('{{%task_runnings}}');
    }
}
