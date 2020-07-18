<?php

namespace frontend\models\forms;

use frontend\models\db\UsersMain;
use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * @property string $sortingLabel по умолчанию getSortingDefault()
 * @property object $usersForm - при отправке
 * @property array $users
 * @property array $userIDs
 * @property array $rating
 * @property array $deals
 *
 */
class UsersFilters
{
    // sorting labels
    const SORTBY_DATE = 'by_reg';
    const SORTBY_RAITING = 'by_rating';
    const SORTBY_DEALS = 'by_deals';
    const SORTBY_POP = 'by_pop';

    public $sortingLabel; 
    public $usersForm;
    public $users;
    public $userIDs;
    public $rating;
    public $deals;

    public function __construct(?string $sorting)
    {
        $this->sortingLabel = $sorting;
        // echo 'Тест сортировка: ' . ($sorting ?: 'null') . '<br>';

        if (!in_array($sorting, $this->getSortingLabels()) && $sorting) {
            throw new NotFoundHttpException('Такой сортировки не существует');
        }
    }

    /**
     * Сортировка - все лейблы-константы
     */
    public function getSortingLabels(): array
    {
        return array_keys($this->getSortings());
    }

    /**
     * Сортировка - значение по умолчанию лейб-константа
     */
    public function getSortingDefault(): string
    {
        return self::SORTBY_DATE;
    }
    
    /**
     * Сортировка для пользователей
     */
    public function getSortings(): array
    {
        return [
            self::SORTBY_DATE => [
                'index' => 1,
                'label' => self::SORTBY_DATE, 
                'title' => 'Дате регистрации',
            ],
            self::SORTBY_RAITING => [
                'index' => 2,
                'label' => self::SORTBY_RAITING, 
                'title' => 'Рейтингу',
            ],
            self::SORTBY_DEALS => [
                'index' => 3,
                'label' => self::SORTBY_DEALS, 
                'title' => 'Числу заказов',
            ],
            self::SORTBY_POP => [
                'index' => 4,
                'label' => self::SORTBY_POP, 
                'title' => 'Популярности',
            ],
        ];
    }

    /**
     * Сортировка - название колонки, значение колонки по умолчанию
     */
    public function getSortingColumn(?string $sortingLabel): string
    {
        $indexDefault = $this->getSortings()[$this->getSortingDefault()]['index']; // время регистрации (по умолчанию)
        $sortingIndex = $this->getSortings()[$sortingLabel]['index'] ?? $indexDefault;
        
        return UsersMain::getSortingColumns()[$sortingIndex];
    }

    /**
     * IDs исполнителей
     */
    public function getUserIDs(array $users = null): ?array
    {
        $users = $users ?: $this->users;
// ТЕСТ
// var_dump($this->users); die;

        return $this->userIDs = array_column($users, 'user_id');
    }
    
    /**
     * Исполнители с информацией и связями для жадной загрузки.
     */
    public function getContractors(
        string $selectColumns = '*',
        array $params = []
    ): array
    {
// Тесты
// echo 'Исполнители точка входа: ';
// var_dump($params);
// var_dump($this->getUserIDs()); die;

        $defaultParams = ['asQuery']; // значения по умолчанию (всегда включено)
        $paramsIDs = array_unique(array_merge($defaultParams, $params));
        
        $contractors = UsersMain::getContractorsMain($selectColumns, $paramsIDs);

        // Фильтры - дополнения запроса
        if ($this->usersForm) {
            $contractors = $this->getfilterContractors($contractors, $this->usersForm);
        }
// Тесты
// echo 'Исполнители точка входа: ';
// var_dump($contractors); die;

        // Общее дополнение запроса
        $contractors
            ->joinWith([
                'taskRunnings tr1',
                'feedbacks f1',
                'userSpecializations usc1',
            ])
            // Сортировка
            ->orderBy([$this->getSortingColumn($this->sortingLabel) => SORT_DESC])
            ->indexBy('user_id'); // Ключ массива (атрибут объекта, не поле)

        return $this->users = $contractors->all();
    }

    /**
     * Фильтры формы для исполнителей
     */
    public function getfilterContractors(object $contractors, object $usersForm): object
    {
        // Фильтр поиск по имени. Тип Fulltext логический, поиск сбрасывает другие фильтры
        if ($search = $usersForm->search) {
            // удаление символов логического поиска
            $logicSearch = prepareLogicSearch($search);
            $contractors
                ->andWhere("MATCH(u.full_name) AGAINST ('$logicSearch' IN BOOLEAN MODE)");

            return $contractors;
        }

        /* Фильтр Категории. (по умолчанию пусто) */
        $contractors->join(
            'LEFT JOIN', 
            'user_specializations us', 
            'us.user_id = u.user_id'
        );
        $contractors->andFilterWhere(['IN', 'us.category_id', $usersForm->categories]);
        // echo 'Фильтр Категории: '; var_dump($contractors->all());

        /* Фильтр Сейчас свободен. true = свободен */
        if ($usersForm->isAvailable) {
            $filter = (new Query())
                ->select('tr.contractor_id')
                ->from('task_runnings tr')
                ->distinct()
                ->join('LEFT JOIN', 'tasks t', 't.task_id = tr.task_id')
                ->where(['t.status_id' => '3']);
            $contractors->andWhere(['NOT IN', 'u.user_id', $filter]);
        }

        /* Фильтр Сейчас онлайн. Атрибут true = онлайн */
        if ($usersForm->isOnLine) {
            $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s');
            $contractors->andWhere(['>', 'u.activity_time', $datePoint]);
        }

        /* Фильтр. Есть отзывы. Атрибут true = есть отзывы */
        if ($usersForm->isFeedbacks) {
            $filter = (new Query())
                ->select(['f.recipient_id'])
                ->distinct()->from('feedbacks f');
            $contractors->andWhere(['IN', 'u.user_id', $filter]);
        }

        /* Фильтр. В избранном. Атрибут true = в избранном */
        if ($usersForm->isFavorite) {
            $currentUser = 1; // !!!Пример
            $filter = (new Query)
                ->select('uf.fave_user_id')
                ->from('user_favorites uf')
                ->where(['user_id' => $currentUser]);
            $contractors->andWhere(['IN', 'u.user_id', $filter]);
        }

        // echo 'Тест Фильтры финиш: ';
        // var_dump($params);
        // var_dump($contractors->column()); die;
        
        return $contractors;
    }

    /**
     * Рейтинг пользователей
     */
    public function getRating(array $userIDs = []): array
    {
        $userIDs ?: $userIDs = array_column($this->users, 'user_id');

        return $this->rating = self::getRatingMain($userIDs);
    }

    public static function getRatingMain(array $userIDs = []): array
    {
        $rating = (new Query())
            ->select([
                'recipient_id',
                'count(recipient_id) as num_feedbacks',
                'sum(point_num) as sum_point',
                'sum(point_num)/count(recipient_id) as avg_point',
            ])
            ->from('feedbacks')
            ->andFilterWhere(['IN', 'recipient_id', $userIDs])
            ->groupBy('recipient_id')
            ->orderBy(['avg_point' => SORT_DESC])
            ->indexBy('recipient_id')
            ->all();

        return $rating;
    }

    /* Количество сделок выбранных пользователей */
    public function getContractorTasks(array $userIDs = null): array
    {
        $userIDs = $userIDs ?: $this->getUserIDs();
// ТЕСТ
// var_dump($userIDs);
        return $this->deals = UsersMain::getContractorTasks($userIDs);
    }
}
