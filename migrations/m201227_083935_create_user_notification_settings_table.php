<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_notification_settings}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%user_notifications}}`
 */
class m201227_083935_create_user_notification_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_notification_settings}}', [
            'setting_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'notification_id' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_notification_settings-user_id}}',
            '{{%user_notification_settings}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_notification_settings-user_id}}',
            '{{%user_notification_settings}}',
            'user_id',
            '{{%users}}',
            'user_id',
            'CASCADE'
        );

        // creates index for column `notification_id`
        $this->createIndex(
            '{{%idx-user_notification_settings-notification_id}}',
            '{{%user_notification_settings}}',
            'notification_id'
        );

        // add foreign key for table `{{%user_notifications}}`
        $this->addForeignKey(
            '{{%fk-user_notification_settings-notification_id}}',
            '{{%user_notification_settings}}',
            'notification_id',
            '{{%user_notifications}}',
            'notification_id',
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
            '{{%fk-user_notification_settings-user_id}}',
            '{{%user_notification_settings}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_notification_settings-user_id}}',
            '{{%user_notification_settings}}'
        );

        // drops foreign key for table `{{%user_notifications}}`
        $this->dropForeignKey(
            '{{%fk-user_notification_settings-notification_id}}',
            '{{%user_notification_settings}}'
        );

        // drops index for column `notification_id`
        $this->dropIndex(
            '{{%idx-user_notification_settings-notification_id}}',
            '{{%user_notification_settings}}'
        );

        $this->dropTable('{{%user_notification_settings}}');
    }
}
