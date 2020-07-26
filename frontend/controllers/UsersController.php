<?php

namespace frontend\controllers;

use frontend\models\forms\UsersFilters;
use frontend\models\forms\UsersForm;
use frontend\models\db\Users;

use yii;
use yii\web\Controller;

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
            'usersForm' => $usersForm
        ]);
    }

    public function actionView(int $id = null)
    {
        $user = Users::find()
            ->joinWith(['userPortfolioImages upi', 'userSpecializations us'])
            ->where(['users.user_id' => $id])
            ->one();

        // $userRating = UsersFilters::getRatingMain([$id], 'one');
        
    return $this->render('view', ['user' => $user, 'userRating' => $userRating]);
    }
}
