<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%task_statuses}}`
 * - `{{%categories}}`
 * - `{{%locations}}`
 * - `{{%users}}`
 */
class m201227_083957_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'task_id' => $this->primaryKey(),
            'status_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'location_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'title' => $this->string(128)->notNull(),
            'desc_text' => $this->text(),
            'price' => $this->integer()->unsigned()->notNull(),
            'full_address' => $this->string(255),
            'address_desc' => $this->string(255),
            'latitude' => $this->string(255),
            'longitude' => $this->string(255),
            'add_time' => $this->datetime()->notNull(),
            'end_date' => $this->datetime(),
            'is_remote' => $this->boolean()->defaultValue(1)->notNull(),
        ]);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-tasks-status_id}}',
            '{{%tasks}}',
            'status_id'
        );

        // add foreign key for table `{{%task_statuses}}`
        $this->addForeignKey(
            '{{%fk-tasks-status_id}}',
            '{{%tasks}}',
            'status_id',
            '{{%task_statuses}}',
            'status_id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-tasks-category_id}}',
            '{{%tasks}}',
            'category_id'
        );

        // add foreign key for table `{{%categories}}`
        $this->addForeignKey(
            '{{%fk-tasks-category_id}}',
            '{{%tasks}}',
            'category_id',
            '{{%categories}}',
            'category_id',
            'CASCADE'
        );

        // creates index for column `location_id`
        $this->createIndex(
            '{{%idx-tasks-location_id}}',
            '{{%tasks}}',
            'location_id'
        );

        // add foreign key for table `{{%locations}}`
        $this->addForeignKey(
            '{{%fk-tasks-location_id}}',
            '{{%tasks}}',
            'location_id',
            '{{%locations}}',
            'location_id',
            'CASCADE'
        );

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-tasks-customer_id}}',
            '{{%tasks}}',
            'customer_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-tasks-customer_id}}',
            '{{%tasks}}',
            'customer_id',
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
        // drops foreign key for table `{{%task_statuses}}`
        $this->dropForeignKey(
            '{{%fk-tasks-status_id}}',
            '{{%tasks}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-tasks-status_id}}',
            '{{%tasks}}'
        );

        // drops foreign key for table `{{%categories}}`
        $this->dropForeignKey(
            '{{%fk-tasks-category_id}}',
            '{{%tasks}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-tasks-category_id}}',
            '{{%tasks}}'
        );

        // drops foreign key for table `{{%locations}}`
        $this->dropForeignKey(
            '{{%fk-tasks-location_id}}',
            '{{%tasks}}'
        );

        // drops index for column `location_id`
        $this->dropIndex(
            '{{%idx-tasks-location_id}}',
            '{{%tasks}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-tasks-customer_id}}',
            '{{%tasks}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-tasks-customer_id}}',
            '{{%tasks}}'
        );

        $this->dropTable('{{%tasks}}');
    }
}
