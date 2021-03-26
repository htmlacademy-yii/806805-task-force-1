<?php

namespace frontend\controllers;

use ownsite\converting\Converting;
use ownsite\converting\ConverterCSV;
use ownsite\converting\ConverterData;
use yii\web\Controller;
use yii\db\Query;

class UtilsController extends Controller
{
    // Список всех утилит-действий
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionActions()
    {
        return $this->render('actions');
    }

    public function actionActions2()
    {
        return $this->render('actions2');
    }

    public function actionConverter()
    {
        $testFile = dirname(\Yii::getAlias('@app')) . '/data/importing/locations.csv';

        $converter = new ConverterCSV($testFile);
        $converter->importToArray();
        $test = [['save' => $converter->exportToArrfile(), 'file' => $converter->getNewFile()]];

        return $this->render('converter', [
            'test' => $test,
        ]);
    }

    public function actionConverterdata()
    {
        // последовательность определяется схемой и foreign key - они должны существовать при создании связи
        $tableNames = [
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
        foreach($tableNames as $tableName) {
            $arrData = (new Query())->from($tableName)->all();

            $converter = new ConverterData($arrData, dirname(dirname(__DIR__)) . '/data/arr_data', $tableName . '.php');
            $converter->importToArray();
            $result[] = ['save' => $converter->exportToArrfile(), 'file' => $converter->getNewFile()];
        }

        return $this->render('converter', [
            'test' => $result,
        ]);
    }

    public function actionCsvToSql()
    {
        $testFile = dirname(\Yii::getAlias('@app')) . '/data/importing/locations.csv';

        $converter = new ConverterCSV($testFile);
        $converter->importToArray();
        $test = [['save' => $converter->exportToSqlfile(), 'file' => $converter->getNewFile()]];

        return $this->render('converter', [
            'test' => $test,
        ]);
    }
}
