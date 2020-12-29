<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m201227_084339_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'message_id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'mess_text' => $this->text()->notNull(),
            'add_time' => $this->datetime()->notNull(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-messages-task_id}}',
            '{{%messages}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-messages-task_id}}',
            '{{%messages}}',
            'task_id',
            '{{%tasks}}',
            'task_id',
            'CASCADE'
        );

        // creates index for column `sender_id`
        $this->createIndex(
            '{{%idx-messages-sender_id}}',
            '{{%messages}}',
            'sender_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-messages-sender_id}}',
            '{{%messages}}',
            'sender_id',
            '{{%users}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `receiver_id`
        $this->createIndex(
            '{{%idx-messages-receiver_id}}',
            '{{%messages}}',
            'receiver_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-messages-receiver_id}}',
            '{{%messages}}',
            'receiver_id',
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
            '{{%fk-messages-task_id}}',
            '{{%messages}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-messages-task_id}}',
            '{{%messages}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-messages-sender_id}}',
            '{{%messages}}'
        );

        // drops index for column `sender_id`
        $this->dropIndex(
            '{{%idx-messages-sender_id}}',
            '{{%messages}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-messages-receiver_id}}',
            '{{%messages}}'
        );

        // drops index for column `receiver_id`
        $this->dropIndex(
            '{{%idx-messages-receiver_id}}',
            '{{%messages}}'
        );

        $this->dropTable('{{%messages}}');
    }
}
