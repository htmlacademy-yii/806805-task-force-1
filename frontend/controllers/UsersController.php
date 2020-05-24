<?php

namespace frontend\controllers;

use yii;
use yii\db\Query;
use yii\web\Controller;
use frontend\models\db\Tasks;
use frontend\models\db\Users;
use frontend\models\db\UserSpecializations;
use frontend\models\db\TaskRunnings;
use frontend\models\forms\UsersForm;
use yii\web\NotFoundHttpException;


class UsersController extends Controller
{
    public function actionIndex() 
    {
        /* Модель для формы, страница Users */
        $usersForm = new UsersForm;

        /* Условие загрузка данных формы если форма отправлена*/
        if(!$usersForm->load(Yii::$app->request->post())) {
            $usersForm->defaultValues(); // Загружаем значения по умолчанию при первом запуске, те если форма не отправлена
        }; 

        /* Получение пользователей */
        // По заданию Пользователь является исполнитель, у которого есть специализация user_specializations, те выбираем уникальные user_id
        // Нужно удалить тех пользователей, если пользователь стал Заказчиком, даже если у него есть специализация
        // те проверяем что пользователь не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running

        // Все действующие Заказчики. Получаем массив со значениями user_id из tasks DISTINCT
        $allcustomers_id = new Query;
        $allcustomers_id->select(['customer_id'])->distinct()->from('tasks t')->where(['status_id' => '1'])->orWhere(['status_id' => '3'])
            // ->limit() // !!! This version of MySQL doesn't yet support 'LIMIT & IN/ALL/ANY/SOME subquery'
            // ->all() // Не сработает в самом запросе -нужна доп переменная $allcustomers_id = $allcustomers_id->all();
            // ->createCommand()->sql // показать sql-выражение, Не сработает в самом запросе -нужна доп переменная
            // ->createCommand()->queryAll() // аналогично ->all(), Не сработает в самом запросе -нужна доп переменная
        ;

        // Все Исполнители. Получаем массив со значениями user_id из user_specializations DISTINCT, 
        // также  Query позволяет легко делать подзапросы, здесь Удаляем id заказчиков из исполнителей
        $allcontractors_id = new \yii\db\Query;
        $allcontractors_id->select(['user_id'])->distinct()->from('user_specializations')
            ->where(['not in', 'user_id', $allcustomers_id])
            // ->all() // Не сработает в самом запросе -нужна доп переменная  $allcustomers_id = $allcustomers_id->all();
            // ->createCommand()->sql // показать sql-выражение, Не сработает в самом запросе -нужна доп переменная
            // ->createCommand()->queryAll() // аналогично ->all(), Не сработает в самом запросе -нужна доп переменная
        ;

        // Исполнители, которые имеют специализацию и в данный момент не Заказчики. 
        $users = Users::find()->where(['IN', 'id_user', $allcontractors_id])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user');

        /* Фильтр Категории. массив пуст или id_task из формы */
        // Дополнительное условие 
        $allcontractors_id->andFilterWhere(['IN', 'category_id', $usersForm->categories]); 
            // ****
            echo '<pre>';
            print_r($allcontractors_id->all());
            echo '</pre>';


        $users = $users->all();
        
        // Используется для фильтров как выборкарейтинга, массив со значениями id исполнителей
        $contractors = array_keys($users);

        // Рейтинг, используем Mysql функции и groupBy
        $rating = new Query();
        $rating = $rating->select(['user_rated_id', 'count(user_rated_id) as num_feedbacks', 'sum(point) as sum_point', 'sum(point)/count(user_rated_id) as avg_point'])
            ->from('feedbacks')
            ->where(['in', 'user_rated_id', $contractors])
            ->groupBy('user_rated_id')
            ->indexBy('user_rated_id')
            ->all()
        ;

        return $this->render('index', ['users' => $users, 'rating' => $rating, 'usersForm' => $usersForm]);
    }
}