<?php

namespace frontend\controllers;

use frontend\models\forms\TasksForm;
use frontend\models\forms\TasksFilters;
use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    public function actionIndex() 
    {
        $tasksForm = new TasksForm; 
        $tasksFilters = new TasksFilters;

        $tasks = [];
        if ($tasksForm->load(Yii::$app->request->post()) === false) {
            $tasks = $tasksFilters->getNewTasks();
        } else {
            $tasks = $tasksFilters->getNewTasks($tasksForm);
        }

        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
