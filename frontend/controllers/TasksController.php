<?php

namespace frontend\controllers;

use frontend\models\db\Tasks;
use frontend\models\forms\TasksForm;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    /* Не изучено, Согласно примера академии */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return true;
    }

    public function actionIndex() 
    {
        $this->enableCsrfValidation = false; // Не изучено, Согласно примера академии
        
        /* Данные из модели Tasks с учетом жадной загрузки категорий */
        $tasks = Tasks::find()->where(['status_id' => 1])->joinWith('category')->orderBy(['add_time' => SORT_DESC])->all(); 

        /* Модель для формы Tasks */
        $tasksForm = new TasksForm;

        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
