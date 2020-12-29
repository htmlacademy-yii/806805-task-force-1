<?php

use yii\db\Migration;

/**
 * 
 */
class m201227_150000_add_data_to_all_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableNames = [
            'locations', // ok
            'task_statuses', 
            'task_actions', 
            'user_roles', // ok
            'categories', // ok
            'users', 
            'user_portfolio_images', 
            'user_specializations',
            'user_notifications', 
            'user_notification_settings', 
            'tasks', 
            'task_files', 
            'task_runnings', 
            'task_failings', 
            'feedbacks', 
            'offers', 
            'user_favorites', 
            'messages', 
        ];

        foreach ($tableNames as $tableName) {

            require dirname(__DIR__) . '/data/arr_data/' . $tableName . '.php';

            $tableData = $$tableName;
            $tableKey = array_keys($tableData[0]);

            $this->batchInsert("{{" . $tableName . "}}", $tableKey, $tableData);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // последовательность определяется схемой и foreign key - они должны существовать при создании связи
        $tableNames = [
            'locations', 
            'task_statuses', 
            'task_actions', 
            'user_roles', // ok
            'categories', // ok
            'users', 
            'user_portfolio_images', 
            'user_specializations',
            'user_notifications', 
            'user_notification_settings', 
            'tasks', 
            'task_files', 
            'task_runnings', 
            'task_failings', 
            'feedbacks', 
            'offers', 
            'user_favorites', 
            'messages', 
        ];

        foreach ($tableNames as $tableName) {

            require dirname(__DIR__) . '/data/arr_data/' . $tableName . '.php';

            $tableData = $$tableName;
            $keyName = array_key_first($tableData[0]);
            $num = count($tableData);

            $this->delete("{{" . $tableName . "}}", "[[$keyName]]<=$num", []);
        }
    }
}
