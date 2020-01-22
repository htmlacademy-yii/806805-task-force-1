<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii;
use yii\db\Connection;
use yii\db\Query;
use frontend\models\db\Tasks;
use frontend\models\db\Users;
use yii\web\NotFoundHttpException;


class AnytestsController extends Controller
{

    public function d($value) {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
    }

    public function actionIndex() 
    {
        $pageSets = ['title' => 'hellow world'];
        return $this->render('index', ['pageSets' => $pageSets]);
    }

    public function actionConn() {
        
        // Подключение к базе данных вручную
        $db = new yii\db\Connection([
            'dsn' => 'mysql:host=localhost;dbname=task_force',
            'username' => 'user',
            'password' => 'Universal85',
            'charset' => 'utf8',
        ]);

        // $table = Yii::$app->db->getTableSchema('categories'); // выполнение запросов с помощью подключения глобально из настроек приложения и его компонентов
        $table = $db->getTableSchema('categories'); // выполнение запросов с использованием подключения вручную, те заданного не глобально а частно.

        $this->d($table);

        $pageSets = ['title' => 'Подключение к БД'];
        return $this->render('conn', ['pageSets' => $pageSets]);
    }

    public function actionTasks() {

        date_default_timezone_set('Europe/Moscow');
        /* Пример 1 */
        // Пользователи являются Исполнителями, если они не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running
        // Находим уникальные id заказчиков customer_id где Task_status=new и Task_status=running 
        // !!! Не работает при SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
        // Устанавливается с помощью sql запроса

        // $customer_tasks = Tasks::find()
        //     ->select(['customer_id'])
        //     ->where(['status_id' => '1'])
        //     ->orWhere(['status_id' => '3'])
        //     ->groupBy('customer_id')
        //     ->indexBy('customer_id')
        //     ->asArray()->all(); 
        //     // ->createCommand()->sql // показать как sql запрос
        // ;
        // Показать sql-запрос
        // $this->d($customer_tasks); die;

        // Создаем простой массив в качестве значений id заказчиков 
        // $customers_id = array_keys($customer_tasks);

        /* Пример 2 */
        // $tasks = Tasks::find()->joinWith(['category c', 'location l', 'status s'])
        //     ->where('end_date < NOW()') // сравнение с sql временем
        //     //->where('end_date < :curTime', ['curTime' => date('Y-m-d H:i:s', time())]) // пример сравнение с временем в php 
        //     ->andWhere(['s.symbol' => 'STATUS_NEW'])
        //     ->orderBy(['add_time' => SORT_DESC])
        //     ->limit(3)
        //     ->all(); // в верстке преобразовать в запись вида 4 часа назад
            
        // if (!$tasks) {
        //     throw new NotFoundHttpException("Задание с ID $id не найдено");
        // }

        // Примеры - данные как объекты
        // $tasks = Tasks::findAll(['status_id' => 1]);
        // $tasks = Tasks::find()->where(['status_id' => 1])->all();
        // $tasks = Tasks::find()->where(['status_id' => 1])->orderBy('id_task')->limit(3)->all();
        // $tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->all();
        // $tasks = Tasks::find()->joinWith('category c', 'location')->where(['c.symbol' => 'neo'])->orderBy(['add_time' => SORT_ASC])->limit(3)->all();
        $tasks = Tasks::find()->where(['<=', 'id_task', 5])->all();
        // $tasks = Tasks::find()->where('id_task <= 5')->all();
        // $tasks = Tasks::find()->where('id_task <=' . '5')->all();
   
        // Примеры - c одним значением
        // $task = Tasks::find()->where(['id_task' => '3'])->limit(1)->one();
        // $task = Tasks::find()->where(['id_task' => $id])->joinWith('category')->limit(1)->asArray()->one();
        // $task = Tasks::find()->where(['id_task' => 3])->joinWith('category')->limit(1)->one();
        // $task = Tasks::find()->where(['status_id' => 3])->joinWith('category')->limit(1)->one();


        // Примеры - данные как массивы
        // $tasks = Tasks::find()->where(['category_id' => 3])->orderBy('id_task')->joinWith('category')->limit(3)->asArray()->all();
        
        print('<pre>');
        // печать примеров как массив
        // print('<hr>');
        // $task = Tasks::findOne($id);

        // @property TaskRunnings[] $taskRunnings
        // print_r ($task->taskRunnings[0]->task_running_id);
        print('</pre>');

        $pageSets = ['title' => 'Все Задания'];
        return $this->render('tasks', ['tasks' => $tasks, 'pageSets' => $pageSets]);

    }

    public function actionUsers() {

        // Пользователи являются Исполнителями, если они не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running
        // Находим уникальные id заказчиков customer_id где Task_status=new и Task_status=running
        // Построитель запросов позволяет группировать и оставить в запросе только группируемые поля
        $customer_tasks = new Query();
        $customer_tasks->select(['customer_id'])->from('tasks t')->where(['status_id' => '1'])->orWhere(['status_id' => '3'])->groupBy('customer_id');
        $customers_id = array_column($customer_tasks->all(), 'customer_id');

        // Если нужно получить всех имеющихся пользователей кроме Текущих заказчиков. в качестве значений id всех пользователей (заказчики и исполнители)  
        // $allusers_id = array_keys(Users::find()->indexBy('id_user')->asArray()->all());

        // Вариант 1. Получаем user_id из user_specializations groupBy('user_id') и array_column
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
        
        $pageSets = ['title' => 'Все Пользователи'];
        return $this->render('users', ['users' => $users, 'rating' => $rating, 'pageSets' => $pageSets]);
    }

    function actionRating() {
        
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
        $users = Users::find()->where(['IN', 'id_user', $allcontractors_id])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user')
            ->all();
        
        $contractors = array_keys($users); // Используется для рейтинга

        // Рейтинг, используем Mysql функции и groupBy
        $rating = new Query();
        $rating = $rating->select(['user_id', 'count(user_id) as num_feedbacks', 'sum(point) as sum_point', 'sum(point)/count(user_id) as avg_point'])
            ->from('feedbacks')
            ->where(['in', 'user_id', $contractors])
            ->groupBy('user_id')
            ->indexBy('user_id')
            ->all()
        ;

        // $this->d($rating);
        return $this->render('rating', ['users' => $users, 'rating' => $rating]);
    }
}
