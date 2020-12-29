<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_failings}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 * - `{{%users}}`
 */
class m201227_084054_create_task_failings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_failings}}', [
            'failing_id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'contractor_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-task_failings-task_id}}',
            '{{%task_failings}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-task_failings-task_id}}',
            '{{%task_failings}}',
            'task_id',
            '{{%tasks}}',
            'task_id',
            'CASCADE'
        );

        // creates index for column `contractor_id`
        $this->createIndex(
            '{{%idx-task_failings-contractor_id}}',
            '{{%task_failings}}',
            'contractor_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-task_failings-contractor_id}}',
            '{{%task_failings}}',
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
            '{{%fk-task_failings-task_id}}',
            '{{%task_failings}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-task_failings-task_id}}',
            '{{%task_failings}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-task_failings-contractor_id}}',
            '{{%task_failings}}'
        );

        // drops index for column `contractor_id`
        $this->dropIndex(
            '{{%idx-task_failings-contractor_id}}',
            '{{%task_failings}}'
        );

        $this->dropTable('{{%task_failings}}');
    }
}
