<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Tasks;
use yii\web\NotFoundHttpException;


class BrowseController extends Controller
{
    public function actionIndex() 
    {
        $tasks = Tasks::find()->orderBy(['task_id' => SORT_ASC])->asArray()->all();

        //return $this->render('index', ['messTaskId' => "Показать строки и параметры задания c ID = $id:", 'name' => $taskName]);
        return $this->render('index', ['messTaskId' => "Показать строки из таблицы:", 'tasks' => $tasks]);

    }

}
