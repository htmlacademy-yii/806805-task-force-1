<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user_roles}}`
 * - `{{%locations}}`
 */
class m201227_083804_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'user_id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull()->defaultValue(1),
            'location_id' => $this->integer()->notNull(),
            'full_name' => $this->string(64)->notNull(),
            'email' => $this->string(64)->notNull()->unique(),
            'phone' => $this->string(11)->unique(),
            'skype' => $this->string(64)->unique(),
            'messaging_contact' => $this->string(64),
            'full_address' => $this->string(255),
            'avatar_addr' => $this->string(255),
            'desc_text' => $this->text(),
            'password_key' => $this->string(255)->notNull(),
            'birth_date' => $this->date(),
            'reg_time' => $this->datetime()->notNull(),
            'activity_time' => $this->datetime()->defaultValue(0)->notNull(),
            'hide_contacts' => $this->boolean()->defaultValue(0)->notNull(),
            'hide_profile' => $this->boolean()->defaultValue(0)->notNull(),
        ]);

        // creates index for column `role_id`
        $this->createIndex(
            '{{%idx-users-role_id}}',
            '{{%users}}',
            'role_id'
        );

        // add foreign key for table `{{%user_roles}}`
        $this->addForeignKey(
            '{{%fk-users-role_id}}',
            '{{%users}}',
            'role_id',
            '{{%user_roles}}',
            'role_id',
            'CASCADE'
        );

        // creates index for column `location_id`
        $this->createIndex(
            '{{%idx-users-location_id}}',
            '{{%users}}',
            'location_id'
        );

        // add foreign key for table `{{%locations}}`
        $this->addForeignKey(
            '{{%fk-users-location_id}}',
            '{{%users}}',
            'location_id',
            '{{%locations}}',
            'location_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user_roles}}`
        $this->dropForeignKey(
            '{{%fk-users-role_id}}',
            '{{%users}}'
        );

        // drops index for column `role_id`
        $this->dropIndex(
            '{{%idx-users-role_id}}',
            '{{%users}}'
        );

        // drops foreign key for table `{{%locations}}`
        $this->dropForeignKey(
            '{{%fk-users-location_id}}',
            '{{%users}}'
        );

        // drops index for column `location_id`
        $this->dropIndex(
            '{{%idx-users-location_id}}',
            '{{%users}}'
        );

        $this->dropTable('{{%users}}');
    }
}
