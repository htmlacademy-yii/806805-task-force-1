<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;

class UtilsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('actions');
    }

    public function actionActions2()
    {
        return $this->render('actions2');
    }


    public function actionArrSaver(string $tableName = null)
    {

        $tableNames = $tableName ? [$tableName] : [
            // последовательность определяется схемой и foreign key - они должны существовать при создании связи
            'locations', 
            'task_statuses', 
            'task_actions', 
            'user_roles',
            'categories',
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
        $result = [];

        foreach ($tableNames as $tableName) {

            $tableData = (new Query())->from($tableName)->all();

    // var_dump($tableData); die;

            $arrToString = "<?php" . PHP_EOL;
            $arrToString .= "$$tableName = [" . PHP_EOL;
            foreach ($tableData as $row) {
                $arrToString .= "   [";
                foreach ($row as $key => $value) {
                    if (is_array($value)) {
                        $value2 = implode(', ', $value);
                        $arrToString .= "'$key' => [$value2], ";
                    } elseif (is_int($value)) {
                        $arrToString .= "'$key' => $value, ";
                    } elseif ($value === null OR $value === '') {
                        $arrToString .= "'$key' => null, ";
                    } else {
                        $arrToString .= "'$key' => '$value', ";
                    }
                }
                $arrToString .= "]," . PHP_EOL;
            }
            $arrToString .= '];';

            $result[$tableName] = file_put_contents(dirname(dirname(__DIR__)) . "/data/arr_data/$tableName.php", $arrToString) ? 'Сохранено' : 'Не сохранено';
        }

        return $this->render('converter', ['result' => $result]);
    }
}