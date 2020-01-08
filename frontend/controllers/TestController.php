<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\Tasks;
use yii\web\NotFoundHttpException;


class ViewController extends Controller
{
    public function actionIndex($id) 
    {

        $task = Tasks::findOne($id);
        //пример $tasks = Tasks::find()->where()->orderBy(['name' => SORT_ASC])->asArray()->all();
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }
        //return $this->render('index', ['messTaskId' => "Показать строки и параметры задания c ID = $id:", 'name' => $taskName]);
        return $this->render('index', ['mess' => "Показать строки и параметры задания c ID = $id:", 'task' => $task]);

    }

    public function actionBrowse() // по умолчанию копия actionIndex()
    {
        return $this->actionIndex();
    }
}
