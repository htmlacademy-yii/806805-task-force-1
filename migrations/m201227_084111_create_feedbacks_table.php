<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%feedbacks}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%users}}`
 * - `{{%tasks}}`
 */
class m201227_084111_create_feedbacks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%feedbacks}}', [
            'feedback_id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'desc_text' => $this->text(),
            'point_num' => $this->integer()->unsigned()->notNull(),
            'add_time' => $this->datetime()->notNull(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-feedbacks-author_id}}',
            '{{%feedbacks}}',
            'author_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-feedbacks-author_id}}',
            '{{%feedbacks}}',
            'author_id',
            '{{%users}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `recipient_id`
        $this->createIndex(
            '{{%idx-feedbacks-recipient_id}}',
            '{{%feedbacks}}',
            'recipient_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-feedbacks-recipient_id}}',
            '{{%feedbacks}}',
            'recipient_id',
            '{{%users}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-feedbacks-task_id}}',
            '{{%feedbacks}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-feedbacks-task_id}}',
            '{{%feedbacks}}',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-feedbacks-author_id}}',
            '{{%feedbacks}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-feedbacks-author_id}}',
            '{{%feedbacks}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-feedbacks-recipient_id}}',
            '{{%feedbacks}}'
        );

        // drops index for column `recipient_id`
        $this->dropIndex(
            '{{%idx-feedbacks-recipient_id}}',
            '{{%feedbacks}}'
        );

        // drops foreign key for table `{{%tasks}}`
        $this->dropForeignKey(
            '{{%fk-feedbacks-task_id}}',
            '{{%feedbacks}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-feedbacks-task_id}}',
            '{{%feedbacks}}'
        );

        $this->dropTable('{{%feedbacks}}');
    }
}
