<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%offers}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tasks}}`
 * - `{{%users}}`
 */
class m201227_084126_create_offers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%offers}}', [
            'offer_id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'contractor_id' => $this->integer()->notNull(),
            'price' => $this->integer()->unsigned(),
            'desc_text' => $this->text(),
            'add_time' => $this->datetime()->notNull(),
        ]);

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-offers-task_id}}',
            '{{%offers}}',
            'task_id'
        );

        // add foreign key for table `{{%tasks}}`
        $this->addForeignKey(
            '{{%fk-offers-task_id}}',
            '{{%offers}}',
            'task_id',
            '{{%tasks}}',
            'task_id',
            'CASCADE'
        );

        // creates index for column `contractor_id`
        $this->createIndex(
            '{{%idx-offers-contractor_id}}',
            '{{%offers}}',
            'contractor_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-offers-contractor_id}}',
            '{{%offers}}',
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
            '{{%fk-offers-task_id}}',
            '{{%offers}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-offers-task_id}}',
            '{{%offers}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-offers-contractor_id}}',
            '{{%offers}}'
        );

        // drops index for column `contractor_id`
        $this->dropIndex(
            '{{%idx-offers-contractor_id}}',
            '{{%offers}}'
        );

        $this->dropTable('{{%offers}}');
    }
}
