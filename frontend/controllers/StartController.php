<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use yii\web\NotFoundHttpException;
use frontend\models\db\Users;

class StartController extends Controller
{
    public $layout = 'start'; 
    
    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function actionIndex() 
    {
        if (Yii::$app->user->getId() === 1) {
            return $this->redirect('/tasks', 302);
        }

        return $this->render('index');
    }

    public function actionLogin() 
    {
        $user = Users::findOne(1);
        Yii::$app->user->login($user);

        return $this->redirect('/tasks', 302);
    }

    public function actionLogout() 
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
