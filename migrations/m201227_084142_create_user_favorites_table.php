<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_favorites}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m201227_084142_create_user_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_favorites}}', [
            'favorite_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'fave_user_id' => $this->integer()->notNull(),
            'is_fave' => $this->boolean()->defaultValue(1),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_favorites-user_id}}',
            '{{%user_favorites}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_favorites-user_id}}',
            '{{%user_favorites}}',
            'user_id',
            '{{%users}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `fave_user_id`
        $this->createIndex(
            '{{%idx-user_favorites-fave_user_id}}',
            '{{%user_favorites}}',
            'fave_user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_favorites-fave_user_id}}',
            '{{%user_favorites}}',
            'fave_user_id',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_favorites-user_id}}',
            '{{%user_favorites}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_favorites-user_id}}',
            '{{%user_favorites}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_favorites-fave_user_id}}',
            '{{%user_favorites}}'
        );

        // drops index for column `fave_user_id`
        $this->dropIndex(
            '{{%idx-user_favorites-fave_user_id}}',
            '{{%user_favorites}}'
        );

        $this->dropTable('{{%user_favorites}}');
    }
}
