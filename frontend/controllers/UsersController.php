<?php

namespace frontend\controllers;

use frontend\models\forms\UsersForm;
use frontend\models\forms\UsersFilters;
use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class UsersController extends Controller
{
    public function actionIndex() 
    {
        $usersForm = new UsersForm;
        
        $usersForm->load(Yii::$app->request->post());

        $usersFilters = new UsersFilters;
        $users = $usersFilters->getContractorsFilters($usersForm);
        $rating = $usersFilters->getRatings(array_column($users, 'id_user'));

        return $this->render('index', ['users' => $users, 'rating' => $rating, 'usersForm' => $usersForm]);
    }
}
