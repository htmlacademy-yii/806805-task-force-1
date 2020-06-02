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
        
        $tasksForm->load(Yii::$app->request->post());

        $tasksFilters = new TasksFilters;
        $tasks = $tasksFilters->getNewTasksFilters($tasksForm);

        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
