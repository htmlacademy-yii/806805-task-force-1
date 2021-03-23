<?php

namespace frontend\controllers;

use frontend\controllers\AccessController;
use frontend\models\db\Users;
use frontend\models\usersFiltration;
use frontend\models\forms\UsersForm;
use Yii;
use yii\web\NotFoundHttpException;

class UsersController extends AccessController
{
    public function actionIndex(string $sorting = null)
    {
        $userFiltersForm = new UsersForm();
        $contractorsQuery = Users::findContractors()
            ->addSelect(['*', 'taskCounter' => Users::subTaskCounter()])
            ->addSelect(['skillCounter' => Users::subSkillCounter()])
            ->addSelect(['feedbackCounter' => Users::subFeedbackCounter()])
            ->addSelect(['sumRating' => Users::subSumRating()])
            ->addSelect(['avgRating' => Users::subAvgRating()]);

        if ($userFiltersForm->load(Yii::$app->request->post()) === true) {
            $filtration = new UsersFiltration($contractorsQuery, $userFiltersForm);
            $filtration->filter();
            $contractorsQuery = $filtration->getFilteredUsers();
        }

        $sortings = Users::getSortings();
        $currentSorting = $sorting ? $sorting : Users::SORTING_REG_TIME;
        if (!in_array($currentSorting, array_keys(Users::getSortings()))) {
            throw new NotFoundHttpException('Поля сортировки не существует');
        }

        $contractors = $contractorsQuery->orderBy([$currentSorting => SORT_DESC])->all();
        // примеры получения пользователей
        $customers = Users::findCustomers()->all();
        $customersActive = Users::findCustomersActive()->all();

        return $this->render('index', [
            'users' => $contractors,
            'sortings' => $sortings,
            'usersForm' => $userFiltersForm,
            'currentSorting' => $currentSorting,
        ]);
    }

    public function actionView(int $ID)
    {
        $contractor = Users::findContractors([$ID])
            ->addSelect(['*', 'taskCounter' => Users::subTaskCounter()])
            ->addSelect(['skillCounter' => Users::subSkillCounter()])
            ->addSelect(['feedbackCounter' => Users::subFeedbackCounter()])
            ->addSelect(['sumRating' => Users::subSumRating()])
            ->addSelect(['avgRating' => Users::subAvgRating()])
            ->limit(1)
            ->one();

        if (!$contractor) {
            throw new NotFoundHttpException('Исполнителя с таким ID не существует');
        }

        return $this->render('view', ['user' => $contractor]);
    }
}
