<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_notifications}}`.
 */
class m201227_083909_create_user_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_notifications}}', [
            'notification_id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull()->unique(),
            'label' => $this->string(64)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_notifications}}');
    }
}
