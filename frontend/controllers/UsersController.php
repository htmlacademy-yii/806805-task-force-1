<?php

namespace frontend\controllers;

use frontend\models\forms\UsersForm;
use frontend\models\UsersFilters;
use frontend\models\UserView;
use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UsersController extends Controller
{
    public function actionIndex(string $sorting = null)
    {
        $usersForm = new UsersForm();
        $usersFilters = new UsersFilters($sorting, $usersForm);
        $users = [];

        if ($usersForm->load(Yii::$app->request->post()) === true) {
            $users = $usersFilters->getFilterContractors();
        } else {
            $users = $usersFilters->getContractors();
        }

        $sortings = UsersFilters::getSortingTags();

        return $this->render('index', [
            'users' => $users,
            'sortings' => $sortings,
            'usersForm' => $usersForm,
        ]);
    }

    public function actionView(int $ID)
    {
        $userView = new UserView($ID);
        $user = $userView->getContractor();
        if (!$user) {
            throw new NotFoundHttpException('Исполнителя с таким ID не существует');
        }

        return $this->render('view', ['user' => $user]);
    }
}
