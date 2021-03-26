<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_portfolio_images}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 */
class m201227_083830_create_user_portfolio_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_portfolio_images}}', [
            'image_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'image_addr' => $this->string(255)->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_portfolio_images-user_id}}',
            '{{%user_portfolio_images}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_portfolio_images-user_id}}',
            '{{%user_portfolio_images}}',
            'user_id',
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
            '{{%fk-user_portfolio_images-user_id}}',
            '{{%user_portfolio_images}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_portfolio_images-user_id}}',
            '{{%user_portfolio_images}}'
        );

        $this->dropTable('{{%user_portfolio_images}}');
    }
}
