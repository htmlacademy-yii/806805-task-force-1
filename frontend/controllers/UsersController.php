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

    public function actionIndex() 
    {

        // Пользователи являются Исполнителями, если они не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running
        // Находим уникальные id заказчиков customer_id где Task_status=new и Task_status=running
        // Построитель запросов позволяет группировать и оставить в запросе только группируемые поля
        $customer_tasks = new Query();
        $customer_tasks->select(['customer_id'])->from('tasks t')->where(['status_id' => '1'])->orWhere(['status_id' => '3'])->groupBy('customer_id');
        $customers_id = array_column($customer_tasks->all(), 'customer_id');

        // Если нужно получить всех имеющихся пользователей кроме Текущих заказчиков. в качестве значений id всех пользователей (заказчики и исполнители)  
        // $allusers_id = array_keys(Users::find()->indexBy('id_user')->asArray()->all());

        // По заданию Не все пользователи исполнители, а только те у которых есть специализация, те они есть в модель userSpecializations и уникальные
        $allcontractors_id = new Query();
        $allcontractors_id->select(['user_id'])->from('user_specializations u')->groupBy('user_id');
        $allcontractors_id = array_column($allcontractors_id->all(), 'user_id');

        // Находим id исполнителей (удаляем id заказчика из массива всех пользователей-исполнителей) и подставляем их в запрос
        $contractors = $users = Users::find()->where(['IN', 'id_user', array_diff($allcontractors_id, $customers_id)])->orderBy(['reg_time' => SORT_DESC])->all();

        // Рейтинг содержится в запросе модели user->getRatedFeedbacks, если рейтинг есть то среднее значение sql запроса sum('point') !=0
        // Создаем массив ключ-Id пользователя, среднее рейтинга - в значении
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