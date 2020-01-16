<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use frontend\models\db\Users;
use frontend\models\db\TaskRunnings;
use yii\web\NotFoundHttpException;


class UsersController extends Controller
{

    public $title;

    public function actionIndex() 
    {
        $this->title = 'Исполнители (верстка Users.html)';

        // Пользователи являются Исполнителями, если они не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running
        // Находим уникальные id заказчиков customer_id где Task_status=new и Task_status=running
        $customer_tasks = Tasks::find()->where(['status_id' => '1'])->orWhere(['status_id' => '3'])->groupBy('customer_id')->indexBy('customer_id')->asArray()->all(); 
        // Создаем простой массив в качестве значений id заказчиков 
        $customers_id = array_keys($customer_tasks);
        // Определяем простой массив в качестве значений id всех пользователей (заказчики и исполнители)  
        $allusers_id = array_keys(Users::find()->indexBy('id_user')->asArray()->all()); 
        // Находим id исполнителей (выделяем id заказчика из массива всех пользователей) и подставляем их в запрос
        $contractors = $users = Users::find()->where(['IN', 'id_user', array_diff($allusers_id, $customers_id)])->orderBy(['reg_time' => SORT_DESC])->all();

        $rating = [];
        foreach($users as $user) {
            
            $sumPoints = $user->getRatedFeedbacks()->sum('point');

            if ($sumPoints) {
                $rating[$user->id_user] = $sumPoints / $user->getRatedFeedbacks()->count();
            }
        }

        return $this->render('index', ['users' => $users, 'rating' => $rating]);
    }

}