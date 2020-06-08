<?php

namespace frontend\controllers;

use frontend\models\forms\TasksFilters;
use frontend\models\forms\TasksForm;
use yii;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasksForm = new TasksForm();
        $tasksFilters = new TasksFilters();

        $tasks = [];
        if ($tasksForm->load(Yii::$app->request->post()) === false) {
            $tasks = $tasksFilters->getNewTasks();
        } else {
            $tasks = $tasksFilters->getNewTasks($tasksForm);
        }

        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
