<?php

namespace frontend\controllers;

use yii\web\Controller;

use frontend\models\Company;
use frontend\models\Contact;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ContactsController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->db->open(); // проверка, что параметры подключения к БД установлены верно

        return $this->render('index');
    }
}
