<?php

namespace frontend\controllers;

use yii;
use yii\db\Query;
use yii\web\Controller;
use frontend\models\db\Users;
use frontend\models\db\UserSpecializations;
use frontend\models\db\UserFavorites;
use frontend\models\db\Tasks;
use frontend\models\db\TaskRunnings;
use frontend\models\forms\UsersForm;
use yii\web\NotFoundHttpException;


class UsersController extends Controller
{
    public function actionIndex() 
    {
        /* Модель для формы фильтров, страница users */
        $usersForm = new UsersForm;
        /* Проверка. Если форма отправлена с именем как в модели загрузить значения формы в модель*/
        $usersForm->load(Yii::$app->request->post());

        /* Получение пользователей */
        // По заданию Пользователь является исполнитель, у которого есть специализация user_specializations, те выбираем уникальные user_id
        // Нужно удалить тех пользователей, если пользователь стал Заказчиком, даже если у него есть специализация
        // те проверяем что пользователь не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running

        /* Запрос id действующие Заказчики */
        $customers = new Query;
        $customers->select(['customer_id'])->from('tasks t')
            ->distinct()
            ->where(['status_id' => '1'])
            ->orWhere(['status_id' => '3'])
        ;

        /* Запрос id все Исполнители при первой загрузке без фильтров. */
        // С использованием подзапроса удаляем id действующих заказчиков из исполнителей
        $contractorsAll = new \yii\db\Query;
        $contractorsAll->select(['user_id'])
            ->distinct()
            ->from('user_specializations')
            ->where(['NOT IN', 'user_id', $customers])
        ;

        /* Запрос данных всех пользователей-исполнителей с подзапросом id всех исполнителей */
        $usersAll = Users::find()
            ->where(['IN', 'id_user', $contractorsAll])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user')
        ;

        /* Запись данных всех пользователей в массив при загрузке страницы, если фильтры применяется то перезаписать */
        $users = (array)  $usersAll->all(); 

        // Запрос данные о рейтинге (значит есть рейтинг) пользователей при загрузке без фильтров, с подзапросом id всех исполнителей 
        $rating = new Query();
        $rating = $rating
            ->select([
                'user_rated_id', 
                'count(user_rated_id) as num_feedbacks', 
                'sum(point) as sum_point', 
                'sum(point)/count(user_rated_id) as avg_point'
            ])
            ->from('feedbacks')
            ->where(['in', 'user_rated_id', $contractorsAll])
            ->groupBy('user_rated_id')
            ->indexBy('user_rated_id')
            ->all()
        ;

        /* Фильтры начало */
        $filters = null; 
        
        /* Фильтр Категории. Добавление условия в запрос. Атрибут пуст или из формы или по умолчанию */
        $filters = $contractorsAll->andFilterWhere(['IN', 'category_id', $usersForm->categories]); 
        $usersAll->andFilterWhere(['IN', 'id_user', $filters]); 

        /* Фильтр Сейчас свободен. true = сейчас свободен */
        /* Вариант 2 - В таблице task_runnings есть задания которым были назначены исполнители, связь один к одному от задания к исполнителю */
        // Запрос id исполнителей из tasks_runnings, если задания выполняются status_id = 3 из tasks
        // Добавление условия в запрос - исключаем пользователи с заданиями в статусе исполняются

        if ($usersForm->isAvailable) {
            $filters = (new Query)->select('contractor_id')->from('tasks t')
                ->join('INNER JOIN', 'task_runnings tr', 'tr.task_running_id = t.id_task')
                ->where(['status_id' => '3'])
            ;
            $usersAll->andWhere(['NOT IN', 'id_user', $filters]);
        }

        /* Фильтр Сейчас онлайн. true = свободен */
        // Создается точка времени полчача назад.
        // Добавление условия в запрос - пользователи у который users.activity_time > точки времени
        if ($usersForm->isOnLine) {
            $filters = $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s'); // формат БД
            $usersAll->andWhere(['>', 'activity_time', $datePoint]);
        }
        
        /* Фильтр. Есть отзывы. true = есть */
        // Создаем массив id пользователей с рейтингом из $rating
        // Добавление условия в запрос - id пользователей с рейтингом
        if ($usersForm->isFeedbacks) {
            $filters = array_keys($rating);
            $usersAll->andWhere(['IN', 'id_user', $filters]);
        }

        /* Фильтр. В избранном */
        // Запрос - найти id пользователей в избранном текщего пользователя ($currentUser добавлен как пример)
        // Добавление условия в запрос -  показ пользователей, которые были добавлены в избранное
        if ($usersForm->isFavorite) {
            $currentUser = 1; // !!!Пример
            $filters = (new Query)->select('favorite_id')->from('user_favorites')
                ->where(['user_id' => $currentUser])
            ;
            $usersAll->andWhere(['IN', 'id_user', $filters]);
        }

        // Если фильтр не null (используется), то перезаписываем $users
        if ($filters !== null) {
            $users = (array) $usersAll->all();
        }

        return $this->render('index', ['users' => $users, 'rating' => $rating, 'usersForm' => $usersForm]);
    }
}
