<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use yii\web\NotFoundHttpException;


class StartController extends Controller
{
    public $layout = 'start'; 

    public function actionIndex() 
    {
        return $this->render('index');
    }
}
