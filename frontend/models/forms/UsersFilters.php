<?php

namespace frontend\models\forms;

use frontend\models\db\Users;
use frontend\models\db\UserSpecializations;
use frontend\models\db\UserFavorites;
use frontend\models\db\Tasks;
use frontend\models\db\TaskRunnings;
use yii;
use yii\base\Model;
use yii\db\Query;

class UsersFilters 
{   // $usersId - либо тип массив или тип объект (запрос класса Query)
    public function getUsers($usersId) : array
    {
        // Запрос данных всех пользователей-исполнителей с подзапросом id всех исполнителей
        $users = Users::find()
            ->where(['IN', 'id_user', $usersId])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user')
            ->all()
        ;

        return $users;
    }

    /* Получение исполнителей */
    // По заданию Пользователь является исполнитель, у которого есть специализация user_specializations, те выбираем уникальные user_id
    // Нужно удалить тех пользователей, если пользователь стал Заказчиком, даже если у него есть специализация
    // те проверяем что пользователь не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running
    public function getContractors(?Model $usersForm = null) : array 
    {
        // Запрос id действующие Заказчики 
        $customers = new Query;
        $customers->select(['customer_id'])->from('tasks')
            ->distinct()
            ->where(['status_id' => '1'])
            ->orWhere(['status_id' => '3'])
        ;

        // Запрос id все Исполнители при первой загрузке без фильтров.
        // С использованием подзапроса удаляем id действующих заказчиков из исполнителей
        $contractors = new Query;
        $contractors->select(['user_id'])
            ->distinct()
            ->from('user_specializations')
            ->where(['NOT IN', 'user_id', $customers])
        ;

        // если форма не отправлена
        if ($usersForm === null) {
            return $this->getUsers($contractors);
        }

        /* Фильтры, если форма отправлена */
        
        /* Фильтр Категории. Добавление условия в запрос. Атрибут пуст или из формы или по умолчанию */
        $contractors->andFilterWhere(['IN', 'category_id', $usersForm->categories]); 

        /* Фильтр Сейчас свободен. true = сейчас свободен */
        // В таблице task_runnings есть задания которым были назначены исполнители, связь один к одному от задания к исполнителю
        // Запрос id исполнителей из tasks_runnings, если задания выполняются status_id = 3 из tasks
        // Добавление условия в запрос - исключаем пользователи с заданиями в статусе исполняются
        if ($usersForm->isAvailable) {
            $filters = (new Query)->select('contractor_id')->from('tasks t')
                ->join('INNER JOIN', 'task_runnings tr', 'tr.task_running_id = t.id_task')
                ->where(['status_id' => '3'])
            ;
            $contractors->andWhere(['NOT IN', 'user_id', $filters]);
        }

        /* Фильтр Сейчас онлайн. true = свободен */
        if ($usersForm->isOnLine) {
            $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s'); // формат БД
            $filters = (new Query)->select('id_user')->from('users')
                ->where(['>', 'activity_time', $datePoint])
            ;
            $contractors->andWhere(['IN', 'user_id', $filters]);
        }
        
        /* Фильтр. Есть отзывы. true = есть */
        if ($usersForm->isFeedbacks) {
            $filters = (new Query)->select(['user_rated_id'])->distinct()->from('feedbacks');
            $contractors->andWhere(['IN', 'user_id', $filters]);
        }

        /* Фильтр. В избранном */
        if ($usersForm->isFavorite) {
            $currentUser = 1; // !!!Пример
            $filters = (new Query)->select('favorite_id')->from('user_favorites')
                ->where(['user_id' => $currentUser])
            ;
            $contractors->andWhere(['IN', 'user_id', $filters]);
        }

        return $this->getUsers($contractors);
    }

    /* Рейтинг пользователей */
    // Запрос данные о рейтинге (значит есть рейтинг) пользователей при загрузке без фильтров, с подзапросом id всех исполнителей 
    public function getRatings(array $usersId) : array 
    {
        $rating = new Query();
        $rating = $rating
            ->select([
                'user_rated_id', 
                'count(user_rated_id) as num_feedbacks', 
                'sum(point) as sum_point', 
                'sum(point)/count(user_rated_id) as avg_point'
            ])
            ->from('feedbacks')
            ->where(['IN', 'user_rated_id', $usersId])
            ->groupBy('user_rated_id')
            ->indexBy('user_rated_id')
            ->all()
        ;

        return $rating;
    }
}