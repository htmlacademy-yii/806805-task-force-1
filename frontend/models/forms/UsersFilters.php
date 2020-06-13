<?php

namespace frontend\models\forms;

use frontend\models\db\Users;
use yii;
use yii\base\Model;
use yii\db\Query;

class UsersFilters
{
    public $users;
    public $rating;
    public $deals;

    /* Данные выбранных пользователей и Сортировка по умолчанию (время регистрации) */
    // $userIds - либо тип массив или тип объект (запрос класса Query)
    public function getUsers($userIds): array
    {
        // Запрос данных всех пользователей-исполнителей с подзапросом id всех исполнителей
        $this->users = Users::find()
            ->where(['IN', 'id_user', $userIds])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user')
            ->all();

        return $this->users;
    }

    /* Выборка исполнителей (contractors) и Фильтры формы */
    // По заданию Пользователь является исполнитель, у которого есть специализация user_specializations, те выбираем уникальные user_id
    // Нужно удалить тех пользователей, если пользователь стал Заказчиком, даже если у него есть специализация
    // те проверяем что пользователь не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running
    public function getContractors(Model $usersForm = null): array
    {
        // Запрос id действующие Заказчики
        $customers = new Query();
        $customers
            ->select(['customer_id'])
            ->from('tasks')
            ->distinct()
            ->where(['status_id' => '1'])
            ->orWhere(['status_id' => '3']);

        // Запрос id все Исполнители при первой загрузке без фильтров.
        // С использованием подзапроса удаляем id действующих заказчиков из исполнителей
        $contractors = new Query();
        $contractors
            ->select(['user_id'])
            ->distinct()
            ->from('user_specializations')
            ->where(['NOT IN', 'user_id', $customers]);

        // если форма не отправлена
        if ($usersForm === null) {
            return $this->getUsers($contractors);
        }

        /* Фильтры, если форма отправлена */

        /* Фильтр поиск по полю название задания. нужен Fulltext index в БД */
        // Специальные символы для полнотекстового поиска удаляются из строки поиска
        // Словам добавляется в конце специальный символ * для полнотекстового поиска
        // Полнотексовый поиск выполняется правильно только в соответствии с первыми буквами слова
        // Согласно ТЗ, поиск сбрасывает другие фильтры -
        if ($search = $usersForm->search) {
            $symbol = ['+', '-', '*', '<', '>', '~', '@', '(', ')', '"', '"'];
            $saveSearch = trim(str_replace($symbol, ' ', $search));
            $words = array_filter(explode(' ', $saveSearch));
            $logicWords = array_map(function ($value) {return $value . '*';}, $words);
            $logicSearch = implode(' ', $logicWords);

            $contractorsBySearch = new Query();
            $contractorsBySearch
                ->select(['id_user'])
                ->distinct()
                ->from('users')
                ->where(['IN', 'id_user', $contractors])
                ->andWhere("MATCH(users.name) AGAINST ('$logicSearch' IN BOOLEAN MODE)");

            return $this->getUsers($contractorsBySearch);
        }

        /* Фильтр Категории. Добавление условия в запрос. Атрибут пуст или из формы или по умолчанию */
        $contractors->andFilterWhere(['IN', 'category_id', $usersForm->categories]);

        /* Фильтр Сейчас свободен. true = сейчас свободен */
        // В таблице task_runnings есть задания которым были назначены исполнители, связь один к одному от задания к исполнителю
        // Запрос id исполнителей из tasks_runnings, если задания выполняются status_id = 3 из tasks
        // Добавление условия в запрос - исключаем пользователи с заданиями в статусе исполняются
        if ($usersForm->isAvailable) {
            $filters = (new Query())->select('contractor_id')->from('tasks t')
                ->join('INNER JOIN', 'task_runnings tr', 'tr.task_running_id = t.id_task')
                ->where(['status_id' => '3']);
            $contractors->andWhere(['NOT IN', 'user_id', $filters]);
        }

        /* Фильтр Сейчас онлайн. true = свободен */
        if ($usersForm->isOnLine) {
            $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s'); // формат БД
            $filters = (new Query())->select('id_user')->from('users')
                ->where(['>', 'activity_time', $datePoint]);
            $contractors->andWhere(['IN', 'user_id', $filters]);
        }

        /* Фильтр. Есть отзывы. true = есть */
        if ($usersForm->isFeedbacks) {
            $filters = (new Query())->select(['user_rated_id'])->distinct()->from('feedbacks');
            $contractors->andWhere(['IN', 'user_id', $filters]);
        }

        /* Фильтр. В избранном */
        if ($usersForm->isFavorite) {
            $currentUser = 1; // !!!Пример
            $filters = (new Query)->select('favorite_id')->from('user_favorites')
                ->where(['user_id' => $currentUser]);
            $contractors->andWhere(['IN', 'user_id', $filters]);
        }

        return $this->getUsers($contractors);
    }

    /* Рейтинг выбранных пользователей */
    // Запрос данные о рейтинге из таблицы (значит есть рейтинг) пользователей
    public function getRating(array $userIds = null): array
    {
        ($userIds !== null) ?: $userIds = array_keys($this->users);

        $this->rating = (new Query())
            ->select([
                'user_rated_id',
                'count(user_rated_id) as num_feedbacks',
                'sum(point) as sum_point',
                'sum(point)/count(user_rated_id) as avg_point',
            ])
            ->from('feedbacks')
            ->where(['IN', 'user_rated_id', $userIds])
            ->groupBy('user_rated_id')
            ->orderBy(['avg_point' => SORT_DESC])
            ->indexBy('user_rated_id')
            ->all();

        return $this->rating;
    }

    /* Количество сделок выбранных пользователей */
    public function getDeals(array $userIds = null): array
    {
        ($userIds !== null) ?: $userIds = array_keys($this->users);

        $this->deals = (new Query())
            ->select([
                'contractor_id',
                'count(contractor_id) AS num_tasks',
            ])
            ->from('task_runnings')
            ->where(['IN', 'contractor_id', $userIds])
            ->groupBy('contractor_id')
            ->orderBy(['num_tasks' => SORT_DESC])
            ->indexBy('contractor_id')
            ->all();

        return $this->deals;
    }

    /* Сортировка согласно строки запроса $sorting или параметра действия контроллера (при использовании ЧПУ) */
    // По умолчанию сортировка задана в методе getUsers
    public function getSortedUsers(?string $type): array
    {
        switch ($type) {
            case 'rating':
                ($this->rating !== null) ?: $this->getRating();
                return array_replace($this->rating, $this->users);
            case 'deals':
                ($this->deals !== null) ?: $this->getDeals();
                return array_replace($this->deals, $this->users);
            case 'popularity':
                // тело конструкции
                return $this->users;
            default:
                // Если тип сортировки не передан ($type пуст) или не соответствует, то сортировка по умолчанию
                return $this->users;
        }
    }
}
