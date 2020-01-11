<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\db\Tasks;
//use frontend\models\db\TaskStatuses;
use yii\web\NotFoundHttpException;


class TaskController extends Controller
{
    public function actionIndex($id) 
    {
        
        $task = Tasks::findOne($id);
        //Примеры
        //$task = Tasks::find()->where(['id_task' => '3'])->limit(1)->one();
        //$task = Tasks::find()->where(['id_task' => $id])->joinWith('category')->limit(1)->asArray()->one();
        //$task = Tasks::find()->where(['id_task' => 3])->joinWith('category')->limit(1)->one();
        //$task = Tasks::find()->where(['status_id' => 3])->joinWith('category')->limit(1)->one();
        print('<pre>');

        print_r($task);
        print('<hr>');

        //print_r ($task->category);
        print('<hr>');
        //print_r ($task->location);
        print('<hr>');
        //print_r ($task->status);
        print('<hr>');
        //print_r ($task->customer);

        // @property TaskRunnings[] $taskRunnings
        //print_r ($task->taskRunnings[0]->task_running_id);
        print('</pre>');

        return $this->render('index', ['task' => $task]);

    }
 
    public function actionBrowse() {

        $tasks = Tasks::findAll(['status_id' => 1]);
        //Примеры - данные как объекты
        //$tasks = Tasks::find()->where(['status_id' => 1])->all();
        //$tasks = Tasks::find()->where(['status_id' => 1])->orderBy('id_task')->limit(3)->all();
        //$tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->all();
        $tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->all();
        //Примеры - данные как массивы
        //$tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->asArray()->all();
        

        foreach($tasks as $task) {
            print_r($task->id_task);
            //print_r($task); // как массивы
        }
        print('<hr>');

        return $this->render('browse', ['tasks' => $tasks]);

    }

}
