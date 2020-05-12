<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use yii\web\NotFoundHttpException;

use frontend\models\forms\TaskForm;

class TasksController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return true;
    }

    public function actionIndex() 
    {
        $this->enableCsrfValidation = false;
        
        $tasks = Tasks::find()->where(['status_id' => 1])->joinWith('category')->orderBy(['add_time' => SORT_DESC])->all(); 
        $taskForm = new TaskForm;
        $taskForm->attributes = [1111111,2222222,333333333,444444,'search' => '5555555', 666666];
        // echo $taskForm->search; 
        return $this->render('index', ['tasks' => $tasks, 'taskForm' => $taskForm]);
    }
}
