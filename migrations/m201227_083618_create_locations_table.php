<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%locations}}`.
 */
class m201227_083618_create_locations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%locations}}', [
            'location_id' => $this->primaryKey(),
            'city' => $this->string(64)->notNull(),
            'latitude' => $this->string(255)->notNull(),
            'longitude' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%locations}}');
    }
}
