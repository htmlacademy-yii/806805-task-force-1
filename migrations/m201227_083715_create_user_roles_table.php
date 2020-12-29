<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_roles}}`.
 */
class m201227_083715_create_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_roles}}', [
            'role_id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull()->unique(),
            'const_name' => $this->string(64)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_roles}}');
    }
}
