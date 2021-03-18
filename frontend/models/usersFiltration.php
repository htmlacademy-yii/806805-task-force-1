<?php

namespace frontend\models;

use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\db\Query;

/**
 * @property object $usersQuery
 * @property object $userFilters
 * @property object $filteredUsers
 */
class usersFiltration
{
    public $usersQuery; //$contractors, $customers, $users и тд
    public $userFilters;
    public $filteredUsers;

    public function __construct(object $usersQuery, object $userFilters)
    {
        $this->userFilters = $userFilters;
        $this->usersQuery = $usersQuery;
    }

    // временно сортировку сделать с помощью компонентов Yii
    public static function getSortings(): array
    {
        return [
            'by_reg' => 'Дате регистрации',
            'by_rating' => 'Рейтингу',
            'by_deals' => 'Числу заказов',
            'by_pop' => 'Популярности',
        ];
    }

    public function getFilteredUsers()
    {
        return $this->filteredUsers;
    }

    /**
     * Фильтрация
     */
    public function filter(): bool
    {
        $contractors = $this->usersQuery;

        // Фильтр поиск по имени, Тип Fulltext логический, поиск сбрасывает другие фильтры
        if ($search = $this->userFilters->search) {
            // удаление символов логического поиска
            $logicSearch = prepareLogicSearch($search);
            $contractors
                ->andWhere("MATCH(u.full_name) AGAINST ('$logicSearch' IN BOOLEAN MODE)");

            return !empty($this->filteredUsers = $contractors);
        }

        /* Фильтр Категории. (по умолчанию пусто) */
        $contractors->join(
            'LEFT JOIN',
            'user_specializations us',
            'us.user_id = u.user_id'
        );
        $contractors->andFilterWhere(['IN', 'us.category_id', $this->userFilters->categories]);

        /* Фильтр Сейчас свободен. true = свободен */
        if ($this->userFilters->isAvailable) {
            $filter = (new Query())
                ->select('tr.contractor_id')
                ->from('task_runnings tr')
                ->distinct()
                ->join('LEFT JOIN', 'tasks t', 't.task_id = tr.task_id')
                ->where(['t.status_id' => '3']);
            $contractors->andWhere(['NOT IN', 'u.user_id', $filter]);
        }

        /* Фильтр Сейчас онлайн. Атрибут true = онлайн */
        if ($this->userFilters->isOnLine) {
            $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s');
            $contractors->andWhere(['>', 'u.activity_time', $datePoint]);
        }

        /* Фильтр. Есть отзывы. Атрибут true = есть отзывы */
        if ($this->userFilters->isFeedbacks) {
            $filter = (new Query())
                ->select(['f.recipient_id'])
                ->distinct()->from('feedbacks f');
            $contractors->andWhere(['IN', 'u.user_id', $filter]);
        }

        /* Фильтр. В избранном. Атрибут true = в избранном */
        if ($this->userFilters->isFavorite) {
            $currentUser = 1; // !!!Пример
            $filter = (new Query)
                ->select('uf.fave_user_id')
                ->from('user_favorites uf')
                ->where(['user_id' => $currentUser]);
            $contractors->andWhere(['IN', 'u.user_id', $filter]);
        }

        return !empty($this->filteredUsers = $contractors);
    }
}
