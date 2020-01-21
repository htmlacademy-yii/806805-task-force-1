<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use frontend\models\db\Tasks;
use frontend\models\db\Users;
use frontend\models\db\UserSpecializations;
use frontend\models\db\TaskRunnings;
use yii\web\NotFoundHttpException;


class UsersController extends Controller
{

    public function d($value) {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
    }

    public function actionIndex() 
    {
        // Получение пользователей
        // По заданию Пользователь является исполнитель, у которого есть специализация user_specializations, те выбираем уникальные user_id
        // Нужно удалить тех пользователей, если пользователь стал Заказчиком, даже если у него есть специализация
        // те проверяем что пользователь не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running

        // Все действующие Заказчики. Получаем массив со значениями user_id из user_specializations DISTINCT
        $allcustomers_id = new Query();
        $allcustomers_id->select(['customer_id'])->distinct()->from('tasks t')->where(['status_id' => '1'])->orWhere(['status_id' => '3'])
            ->all()
            // ->createCommand()->sql
            // ->queryAll();
        ;
        // Все Исполнители. Получаем массив со значениями user_id из user_specializations DISTINCT, 
        // также  Query позволяет легко делать подзапросы, здесь Удаляем id заказчиков из исполнителей
        $allcontractors_id = (new \yii\db\Query());
        $allcontractors_id->select(['user_id'])->distinct()->from('user_specializations')->where(['not in', 'user_id', $allcustomers_id])
            // ->asArray()
            ->all()
            // ->createCommand()->sql
            // ->queryAll();
        ;

        // Исполнители, которые имеют специализацию и в данный момент не Заказчики. 
        $contractors = $users = Users::find()->where(['IN', 'id_user', $allcontractors_id])->orderBy(['reg_time' => SORT_DESC])->all();

        // Рейтинг, метод getRatedFeedbacks возвратит запрос к БД, а свойство ratedFeedbacks вернет массив объектов 
        // если рейтинг есть то среднее значение sql запроса sum('point') !=0, запишем в массив $rating, где ключом будет id_user
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