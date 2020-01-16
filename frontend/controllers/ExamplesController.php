<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use yii\web\NotFoundHttpException;


class ExamplesController extends Controller
{
    public function actionIndex() 
    {
        date_default_timezone_set('Europe/Moscow');
        
        // $tasks = Tasks::find()->joinWith(['category c', 'location l', 'status s'])
        //     ->where('end_date < NOW()') // сравнение с sql временем
        //     //->where('end_date < :curTime', ['curTime' => date('Y-m-d H:i:s', time())]) // пример сравнение с временем в php 
        //     ->andWhere(['s.symbol' => 'STATUS_NEW'])
        //     ->orderBy(['add_time' => SORT_DESC])
        //     ->limit(3)
        //     ->all(); // в верстке преобразовать в запись вида 4 часа назад
            
        // if (!$tasks) {
        //     throw new NotFoundHttpException("Задание с ID $id не найдено");
        // }

        // Примеры - данные как объекты
        // $tasks = Tasks::findAll(['status_id' => 1]);
        // $tasks = Tasks::find()->where(['status_id' => 1])->all();
        // $tasks = Tasks::find()->where(['status_id' => 1])->orderBy('id_task')->limit(3)->all();
        // $tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->all();
        // $tasks = Tasks::find()->joinWith('category c', 'location')->where(['c.symbol' => 'neo'])->orderBy(['add_time' => SORT_ASC])->limit(3)->all();
        $tasks = Tasks::find()->where(['<=', 'id_task', 5])->all();
        // $tasks = Tasks::find()->where('id_task <= 5')->all();
        // $tasks = Tasks::find()->where('id_task <=' . '5')->all();
   
        // Примеры - c одним значением
        // $task = Tasks::find()->where(['id_task' => '3'])->limit(1)->one();
        // $task = Tasks::find()->where(['id_task' => $id])->joinWith('category')->limit(1)->asArray()->one();
        // $task = Tasks::find()->where(['id_task' => 3])->joinWith('category')->limit(1)->one();
        // $task = Tasks::find()->where(['status_id' => 3])->joinWith('category')->limit(1)->one();

        return $this->render('index', ['tasks' => $tasks]);

    }
 
    public function actionArr($id) {

        // Примеры - данные как массивы
        // $tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->asArray()->all();
        
        // печать примеров как массив
        foreach($tasks as $task) {
            print_r($task); // как массивы
        }
        print('<hr>');
        $task = Tasks::findOne($id);

        print('<pre>');

        print_r($task);
        print('<hr>');

        // print_r ($task->category);
        print('<hr>');
        // print_r ($task->location);
        print('<hr>');
        // print_r ($task->status);
        print('<hr>');
        //print_r ($task->customer);

        // @property TaskRunnings[] $taskRunnings
        // print_r ($task->taskRunnings[0]->task_running_id);
        print('</pre>');

        return $this->render('view', ['task' => $task]);

    }

}
