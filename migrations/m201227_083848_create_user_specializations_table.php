<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_specializations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%categories}}`
 */
class m201227_083848_create_user_specializations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_specializations}}', [
            'specialization_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_specializations-user_id}}',
            '{{%user_specializations}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_specializations-user_id}}',
            '{{%user_specializations}}',
            'user_id',
            '{{%users}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-user_specializations-category_id}}',
            '{{%user_specializations}}',
            'category_id'
        );

        // add foreign key for table `{{%categories}}`
        $this->addForeignKey(
            '{{%fk-user_specializations-category_id}}',
            '{{%user_specializations}}',
            'category_id',
            '{{%categories}}',
            'category_id',
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
            '{{%fk-user_specializations-user_id}}',
            '{{%user_specializations}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_specializations-user_id}}',
            '{{%user_specializations}}'
        );

        // drops foreign key for table `{{%categories}}`
        $this->dropForeignKey(
            '{{%fk-user_specializations-category_id}}',
            '{{%user_specializations}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-user_specializations-category_id}}',
            '{{%user_specializations}}'
        );

        $this->dropTable('{{%user_specializations}}');
    }
}
