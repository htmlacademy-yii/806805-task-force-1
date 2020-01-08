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
        // ??? не работает связь
        // $task = Tasks::find(['id' => $id])->where(['id' => $id])->joinWith('category')->asArray()->one();
        // print_r($task);

        return $this->render('index', ['task' => $task]);

    }

}
