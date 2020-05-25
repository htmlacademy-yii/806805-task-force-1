<?php

namespace frontend\controllers;

use yii;
use yii\db\Query;
use yii\web\Controller;
use frontend\models\db\Tasks;
use frontend\models\db\Users;
use frontend\models\db\UserSpecializations;
use frontend\models\db\Task;
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
        $customersAll = new Query;
        $customersAll->select(['customer_id'])->distinct()->from('tasks t')->where(['status_id' => '1'])->orWhere(['status_id' => '3'])
            // ->limit() // !!! This version of MySQL doesn't yet support 'LIMIT & IN/ALL/ANY/SOME subquery'
            // ->all() // Не сработает в самом запросе -нужна доп переменная $allcustomers_id = $allcustomers_id->all();
            // ->createCommand()->sql // показать sql-выражение, Не сработает в самом запросе -нужна доп переменная
            // ->createCommand()->queryAll() // аналогично ->all(), Не сработает в самом запросе -нужна доп переменная
        ;

        // Все Исполнители. Получаем массив со значениями user_id из user_specializations DISTINCT, 
        // также  Query позволяет легко делать подзапросы, здесь Удаляем id заказчиков из исполнителей
        $contractorsAll = new \yii\db\Query;
        $contractorsAll->select(['user_id'])->distinct()->from('user_specializations')
            ->where(['not in', 'user_id', $customersAll])
            // ->all() // Не сработает в самом запросе -нужна доп переменная  $allcustomers_id = $allcustomers_id->all();
            // ->createCommand()->sql // показать sql-выражение, Не сработает в самом запросе -нужна доп переменная
            // ->createCommand()->queryAll() // аналогично ->all(), Не сработает в самом запросе -нужна доп переменная
        ;

        // Исполнители, которые имеют специализацию и в данный момент не Заказчики. 
        $usersAll = Users::find()->where(['IN', 'id_user', $contractorsAll])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user');
        
        /* Фильтр Категории. массив пуст или id_task из формы */
        $filters = $contractorsAll->andFilterWhere(['IN', 'category_id', $usersForm->categories]); 
        $usersAll->andFilterWhere(['IN', 'id_user', $filters]); 
            // ****
            // print_r($filters);
            // print_r($users->all());

        /* Фильтр Сейчас свободен. null или 1 */
        // SELECT MAX(id_task_running), `contractor_id` FROM `task_runnings` GROUP BY `task_running_id`; // Выборка id правильная, но остальное не правильно, чтобы выдовал предупреждение включаем настройку БД
        // Настройка БД SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
        // select contractor_id from task_runnings where id_task_running IN (SELECT MAX(id_task_running) FROM `task_runnings` GROUP BY `task_running_id`);        
        if($usersForm->isAvailable) {
            $filtersSub = TaskRunnings::find()->select(['MAX(id_task_running)'])->groupBy('task_running_id');
            $filters = TaskRunnings::find()->select(['contractor_id'])->where(['IN', 'id_task_running', $filtersSub]);
            $usersAll->andWhere(['IN', 'id_user', $filters]);
        }


        
        $users = $usersAll->all();

        // Используется для фильтров как выборка рейтинга, массив со значениями id исполнителей
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