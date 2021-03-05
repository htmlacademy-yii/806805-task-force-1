<?php

namespace frontend\controllers;

use yii\web\Controller;

class UtilsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('actions');
    }
}