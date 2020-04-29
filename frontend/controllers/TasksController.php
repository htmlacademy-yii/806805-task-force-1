<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use yii\web\NotFoundHttpException;


class TasksController extends Controller
{
    public function actionIndex() 
    {
        $tasks = Tasks::find()->where(['status_id' => 1])->joinWith('category')->orderBy(['add_time' => SORT_DESC])->all(); 
        
        return $this->render('index', ['tasks' => $tasks]);
    }
}
