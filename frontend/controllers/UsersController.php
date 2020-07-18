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
        $usersFilters = new UsersFilters($sorting);

        if ($search = Yii::$app->request->post($usersForm->formName())['search']) {
            $usersForm->search = $search;
            $usersFilters->usersForm = $usersForm;
        } elseif ($usersForm->load(Yii::$app->request->post()) === true) {
            $usersFilters->usersForm = $usersForm;
        }
        $users = $usersFilters->getContractors('tasks_count', ['addRating']);

        return $this->render('index', ['users' => $users, 'usersForm' => $usersForm]);
    }
    
    public function actionView(int $id)
    {
        $user = Users::find()
            ->joinWith(['userPortfolioImages upi', 'userSpecializations us'])
            ->where(['users.user_id' => $id])
            ->one();

        $userRating = UsersFilters::getRatingMain([$id], 'one');
        
    return $this->render('view', ['user' => $user, 'userRating' => $userRating]);
    }
}
