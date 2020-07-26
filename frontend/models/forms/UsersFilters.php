<?php

namespace frontend\models\forms;

use frontend\models\db\UsersMain;
use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * @property string $sorting по умолчанию getSortingDefault()
 * @property object $usersForm - при отправке
 * @property array $users
 * @property array $userIDs
 * @property array $ratings
 * @property array $deals
 *
 */
class UsersFilters
{
    // sorting labels
    const SORTBY_DATE = 'by_reg';
    const SORTBY_RAITINGS = 'by_rating';
    const SORTBY_DEALS = 'by_deals';
    const SORTBY_POPS = 'by_pop';

    public $sorting; 
    public $usersForm;
    public $users;
    public $userIDs;
    public $ratings;
    public $deals;

    public function __construct(?string $sorting, object $usersForm)
    {
        $this->sorting = $sorting;
        $this->usersForm = $usersForm;

        if (!in_array($sorting, $this->getSortingLabels()) && $sorting) {
            throw new NotFoundHttpException('Такой сортировки не существует');
        }
    }

    /**
     * Сортировка для пользователей
     */
    public static function getSortings(): array
    {
        return [
            self::SORTBY_DATE => [
                'index' => 1,
                'label' => self::SORTBY_DATE, 
                'title' => 'Дате регистрации',
            ],
            self::SORTBY_RAITINGS => [
                'index' => 2,
                'label' => self::SORTBY_RAITINGS, 
                'title' => 'Рейтингу',
            ],
            self::SORTBY_DEALS => [
                'index' => 3,
                'label' => self::SORTBY_DEALS, 
                'title' => 'Числу заказов',
            ],
            self::SORTBY_POPS => [
                'index' => 4,
                'label' => self::SORTBY_POPS, 
                'title' => 'Популярности',
            ],
        ];
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
     * Сортировка - название колонки, значение колонки по умолчанию
     */
    public function getSortingColumn(?string $sortingLabel): string
    {
        $indexDefault = $this->getSortings()[$this->getSortingDefault()]['index']; // время регистрации (по умолчанию)
        $sortingIndex = $this->getSortings()[$sortingLabel]['index'] ?? $indexDefault;
        
        return UsersMain::getSortingColumns()[$sortingIndex];
    }

    /**
     * Сортировка для пользователей
     */
    public static function getSortingTags(): array
    {
        $sortings = self::getSortings();
        array_shift($sortings);

        return $sortings;
    }

    /**
     * IDs исполнителей
     */
    public function getUserIDs(): ?array
    {
        return $this->userIDs = array_column($this->users, 'user_id');
    }
    
    /**
     * Исполнители главный запрос с информацией и связями жадной загрузки.
     */
    public function getContractorsMain(array $addons = []): object
    {
        $defaultSettings = ['asQuery']; // значения по умолчанию (всегда включено)
        $contractors = UsersMain::getContractorsMain('*', $defaultSettings);

        // Общее дополнение запроса
        $contractors
            ->joinWith([
                'taskRunnings tr1',
                'feedbacks f1',
                'userSpecializations usc1',
            ])
            ->orderBy([$this->getSortingColumn($this->sorting) => SORT_DESC]) // Сортировка
            ->indexBy('user_id'); // Ключ массива (атрибут объекта, не поле)

        // Дополнение запроса или дополнительные данные (addon)
        $defaultAddons = ['addRatings', 'addDeals']; // значения по умолчанию (всегда включено)
        $addons = array_merge($defaultAddons, $addons);

        if ($addons) {
            $contractors = UsersMain::addContractorAddons($contractors, $addons);
        }

        return $contractors;
    }

    /**
     * Исполнители с информацией и связями для жадной загрузки.
     */
    public function getContractors(array $addons = []): array
    {
        return $this->users = $this->getContractorsMain($addons)->all();
    }

    /**
     * Исполнители с фильтрами, с информацией и связями для жадной загрузки.
     */
    public function getFilterContractors(array $addons = []): array
    {
        $contractors = $this->getContractorsMain($addons);

        // Фильтр поиск по имени. Тип Fulltext логический, поиск сбрасывает другие фильтры
        if ($search = $this->usersForm->search) {
            // удаление символов логического поиска
            $logicSearch = prepareLogicSearch($search);
            $contractors
                ->andWhere("MATCH(u.full_name) AGAINST ('$logicSearch' IN BOOLEAN MODE)");

            return $this->users = $contractors->all();
        }

        /* Фильтр Категории. (по умолчанию пусто) */
        $contractors->join(
            'LEFT JOIN', 
            'user_specializations us', 
            'us.user_id = u.user_id'
        );
        $contractors->andFilterWhere(['IN', 'us.category_id', $this->usersForm->categories]);

        /* Фильтр Сейчас свободен. true = свободен */
        if ($this->usersForm->isAvailable) {
            $filter = (new Query())
                ->select('tr.contractor_id')
                ->from('task_runnings tr')
                ->distinct()
                ->join('LEFT JOIN', 'tasks t', 't.task_id = tr.task_id')
                ->where(['t.status_id' => '3']);
            $contractors->andWhere(['NOT IN', 'u.user_id', $filter]);
        }

        /* Фильтр Сейчас онлайн. Атрибут true = онлайн */
        if ($this->usersForm->isOnLine) {
            $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s');
            $contractors->andWhere(['>', 'u.activity_time', $datePoint]);
        }

        /* Фильтр. Есть отзывы. Атрибут true = есть отзывы */
        if ($this->usersForm->isFeedbacks) {
            $filter = (new Query())
                ->select(['f.recipient_id'])
                ->distinct()->from('feedbacks f');
            $contractors->andWhere(['IN', 'u.user_id', $filter]);
        }

        /* Фильтр. В избранном. Атрибут true = в избранном */
        if ($this->usersForm->isFavorite) {
            $currentUser = 1; // !!!Пример
            $filter = (new Query)
                ->select('uf.fave_user_id')
                ->from('user_favorites uf')
                ->where(['user_id' => $currentUser]);
            $contractors->andWhere(['IN', 'u.user_id', $filter]);
        }

        return $this->users = $contractors->all();
    }

    /**
     * Колонки с рейтингом исполнителей
     */
    public function getContractorRatings(): array
    {
        return $this->ratings = UsersMain::getContractorRatings($this->getUserIDs());
    }

    /**
     * Добавление колонок рейтингов к объектам исполнитель
     */
    public function addContractorRatings(): array
    {
        $ratings = $this->getContractorRatings();
        $users = &$this->users;

        foreach ($ratings as $id => $rating) {
            $users[$id]->attributes = $rating;
        }

        return $this->users;
    }
    
    /**
     * Колонка количество сделок исполнителей
     */
    public function getContractorDeals(): array
    {
        return $this->deals = UsersMain::getContractorDeals($this->getUserIDs());
    }

    /**
     * Добавление колонки количество сделок к объектам исполнитель
     */
    public function addContractorDeals(): array
    {
        $deals = $this->getContractorDeals();
        $users = &$this->users;


        foreach ($deals as $id => $deal) {
            $users[$id]->attributes = $deal;
        }

        var_dump($users); die;            

        return $this->users;
    }
}
