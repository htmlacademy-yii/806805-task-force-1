<?php

namespace frontend\models\db;

use yii\base\NotSupportedException;
use yii\db\Query;

/**
 * This is the model class for table "users".
 *
 * Дополнительные поля с помощью подзапросов и дополнений запроса
 * @property int $feedbacks_count
 * @property int $sum_point
 * @property int $avg_point
 * @property int $specialties_count
 * @property int $tasks_count
 */
class UsersMain extends Users
{
    const SETTING_QUERY = 'asQuery';
    const SETTING_ARRAY = 'asArray';

    const SORTBY_DATE_COL = 'reg_time';
    const SORTBY_RAITINGS_COL = 'avg_point';
    const SORTBY_DEALS_COL = 'tasks_count';
    const SORTBY_POPS_COL = 'pop_count';

    const ADDON_SPECIALTIES = 'addSpecialties';
    const ADDON_RATINGS = 'addRatings';
    const ADDON_DEALS = 'addDeals';
    const ADDON_POPS = 'addPops';

    public $feedbacks_count;
    public $sum_point;
    public $avg_point;
    public $specialties_count;
    public $tasks_count;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'feedbacks_count',
                'sum_point',
                'avg_point',
                'specialties_count',
                'tasks_count',
            ], 'safe'],
        ];
    }

    /**
     * Названия колонок для сортировки
     */
    public static function getSortingColumns(): array
    {
        $sortingColumns = [
            1 => self::SORTBY_DATE_COL,
            2 => self::SORTBY_RAITINGS_COL,
            3 => self::SORTBY_DEALS_COL,
            4 => self::SORTBY_POPS_COL,
        ];

        return $sortingColumns;
    }

    /**
     * Настройки ретурна для getContractorsMain()
     */
    public static function getContractorSettings()
    {
        return [
            self::SETTING_QUERY, // set вернуть как объект
            self::SETTING_ARRAY, // set ввиде массива
        ];
    }

    /**
     * Дополнения (имена) для запроса getContractorsMain()
     */
    public static function getContractorAddons()
    {
        return [
            self::ADDON_SPECIALTIES, // addon добавляет колонку количество специализаций
            self::ADDON_RATINGS, // addon добавляет колонки рейтинга
            self::ADDON_DEALS, // addon добавляет колонку количество сделок
            self::ADDON_POPS, // addon добавляет колонку количество просмотров страницы (пока не требуется)
        ];
    }

    /**
     * Обработчик $selectColumns.
     */
    public static function getSelectFormat(string $selectColumns = '*'): array
    {
        $formatedColumns = $selectColumns ? explode(', ', $selectColumns) : ['*'];
        $attributes = (new Users)->attributes();

        if ($formatedColumns !== ['*']) {
            $formatedColumns = array_intersect($formatedColumns, $attributes);

            foreach ($formatedColumns as $column) {
                if (in_array($column, $attributes) === false) {
                    throw new NotSupportedException('Такого атрибута исполнителей не существует');
                }
            }
        }

        $formatedColumns = (array_map(function ($val) {
            return 'u.' . $val;
        }, $formatedColumns));

        return $formatedColumns;
    }

    /**
     * Исполнители - пользователи со специализациями 3 и более, не являются заказчиками
     *
     * @param string $selectColumns стандартные колонки таблицы. По умолчанию * (выбрать все).
     * Например. 'user_id' - вернет только IDs. 'user_id, full_name' - вернет массив объектов
     * @param array $settings настройки return.
     * Например.  ['asQuery', 'asArray']
     * @param array $userIDs простой массив с ID исполнителя(ей).
     *
     * @return mixed query, array
     */
    public static function getContractorsMain(string $selectColumns, array $settings = [], array $userIDs = [])
    {
        // атрибуты таблицы
        $formatedColumns = self::getSelectFormat($selectColumns);

        // настройки ретурн
        foreach ($settings as $setting) {
            if (!in_array($setting, self::getContractorSettings())) {
                throw new NotSupportedException('Настройка для исполнителей не существует');
            }
        }

        // Подзапрос активные заказчики
        $customers = self::getActiveCustomers('user_id');

        // Подзапрос количество специализаций
        $specialtiesCount = (new Query())
            ->select('count(us.user_id)')
            ->from('user_specializations us')
            ->where('us.user_id = u.user_id'); // !!! работает только в строковом формате для подзапроса в where

        // Исполнители - главный запрос
        $contractors = self::find()
            ->select($formatedColumns)
            ->from('users u')
            ->where(['NOT IN', 'u.user_id', $customers]) // основное условие, не заказчики, постоянное
            ->andWhere(['>=', $specialtiesCount, '1']) // основное условие, категорий>=3 (=1 тест), постоянное
            ->groupBy('u.user_id'); // Группировка всех данных!!!

        $contractors->andFilterWhere(['IN', 'u.user_id', $userIDs]);

        if (in_array(self::SETTING_ARRAY, $settings)) {
            $contractors = $contractors->asArray();
        }

        if (in_array(self::SETTING_QUERY, $settings)) {
            return $contractors;
        }

        return $selectColumns === 'user_id' ? $contractors->column() : $contractors->all();
    }

    /**
     * Addons, дополнения для запроса
     */
    public static function addContractorAddons(object $contractors, array $addons): object
    {
        $availableAddons = self::getContractorAddons();
        $addons = array_unique($addons);

        foreach ($addons as $addon) {
            if (!in_array($addon, $availableAddons)) {
                throw new NotSupportedException('Аддон для исполнителей не существует');
            }
        }

        // количество специализаций
        if (in_array(self::ADDON_SPECIALTIES, $addons)) {
            $contractors
                ->addSelect(['count(us.user_id) AS specialties_count'])
                ->join('LEFT JOIN', 'user_specializations us', 'us.user_id = u.user_id');
        }

        // рейтинги исполнителей
        if (in_array(self::ADDON_RATINGS, $addons)) {
            $contractors
                ->addSelect([
                    'count(f2.recipient_id) AS feedbacks_count',
                    'sum(f2.point_num) AS sum_point',
                    'sum(f2.point_num)/count(f2.recipient_id) AS ' . self::SORTBY_RAITINGS_COL,
                ])
                ->joinWith('feedbacks f2');
        }

        // количество заданий (сделок) исполнителя
        if (in_array(self::ADDON_DEALS, $addons)) {
            $subquery = (new Query())
                ->select([self::SORTBY_DEALS_COL => 'count(contractor_id)'])
                ->from(
                    (new Query())
                        ->from(['task_runnings tr'])
                        ->where('tr.contractor_id = u.user_id')
                        ->union((new Query())
                                ->from(['task_failings tf'])
                                ->where('tf.contractor_id = u.user_id')
                            , true)
                );

            $contractors
                ->addSelect([self::SORTBY_DEALS_COL => $subquery]);
        }

        return $contractors;
    }

    /**
     * Колонки с рейтингом исполнителей
     */
    public static function getContractorRatings(array $userIDs = []): array
    {
        $ratings = (new Query())
            ->select([
                'recipient_id',
                'count(recipient_id) AS num_feedbacks',
                'sum(point_num) AS sum_point',
                'sum(point_num)/count(recipient_id) AS avg_point',
            ])
            ->from('feedbacks')
            ->andFilterWhere(['IN', 'recipient_id', $userIDs])
            ->groupBy('recipient_id')
            ->orderBy(['avg_point' => SORT_DESC])
            ->indexBy('recipient_id')
            ->all();

        return $ratings;
    }

    /**
     * Количество заданий исполнителей
     */
    public static function getContractorDeals(array $contractorIDs = []): array
    {
        $runnings_count = (new Query())
            ->from(['task_runnings tr'])
            ->filterWhere(['IN', 'tr.contractor_id', $contractorIDs]);

        $failings_count = (new Query())
            ->from(['task_failings tf'])
            ->filterWhere(['IN', 'tf.contractor_id', $contractorIDs]);

        // Объединение запросов заданий из таблиц task_runnings и task_failings
        $contractor_tasks = $runnings_count->union($failings_count, true);

        $tasks_count = (new Query())
            ->select(['u.user_id', self::SORTBY_DEALS_COL => 'count(contractor_id)'])
            ->from(['users u'])
            ->join('LEFT JOIN', [$contractor_tasks], 'contractor_id = u.user_id') // LEFT JOIN позволяет получить 0 при отсутствии записи вместо пусто
            ->filterWhere(['IN', 'u.user_id', $contractorIDs])
            ->groupBy(['u.user_id'])
            ->indexBy('user_id')
            ->all();

        return $tasks_count;
    }

    /**
     * Заказчики с активными задачами - все данные или ID
     */
    public static function getActiveCustomers(string $selectColumns = '*'): array
    {
        $formatedColumns = self::getSelectFormat($selectColumns);

        $customers = self::find()
            ->select($formatedColumns)
            ->from('users u')
            ->joinWith([
                'customerTasks ct',
            ])
            ->where(['ct.status_id' => '1'])
            ->orWhere(['ct.status_id' => '3']);

        return $selectColumns === 'user_id' ? $customers->column() : $customers->all();
    }

    /**
     * Заказчики - не являются исполнителями
     *
     * @param string $selectColumns стандартные колонки таблицы. По умолчанию * (выбрать все).
     * Например. 'user_id' - вернет только простой массив IDs. 'user_id, full_name' - вернет массив объектов
     * @param array $settings настройки return.
     * Например.  ['asQuery', 'asArray']
     * @param array $userIDs простой массив с ID исполнителя(ей).
     *
     * @return mixed query, array
     */

    public static function getCustomersMain(string $selectColumns, array $settings = [], array $userIDs = [])
    {
        $formatedColumns = self::getSelectFormat($selectColumns);

        // настройки ретурн
        foreach ($settings as $setting) {
            if (!in_array($setting, self::getContractorSettings())) {
                throw new NotSupportedException('Настройка для заказчика не существует');
            }
        }
        // Подзапрос - не исполнители (массив IDs)
        $contractors = self::getContractorsMain('user_id');
        
        // Заказчики - главный запрос
        $customers = self::find()
            ->select($formatedColumns)
            ->from('users u')
            ->where(['NOT IN', 'u.user_id', $contractors]);

        $customers->andFilterWhere(['IN', 'u.user_id', $userIDs]);

        if (in_array(self::SETTING_ARRAY, $settings)) {
            $customers = $customers->asArray();
        }

        if (in_array(self::SETTING_QUERY, $settings)) {
            return $customers;
        }

        return $selectColumns === 'user_id' ? $customers->column() : $customers->all();
    }
}
