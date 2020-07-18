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
 * @property int $specializations_count
 * @property int $tasks_count
 */
class UsersMain extends Users
{
    const SORTBY_DATE_COL = 'reg_time';
    const SORTBY_RAITING_COL = 'avg_point';
    const SORTBY_DEALS_COL = 'tasks_count';
    const SORTBY_POP_COL = 'pop_count';
    const SUB_SPECIALTIES = 'specializations_count';
    const ADDON_RATING = 'addRating';
    const SETTING_QUERY = 'asQuery';
    const SETTING_ARRAY = 'asArray';

    public $feedbacks_count;
    public $sum_point;
    public $avg_point;
    public $specializations_count;
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
                'specializations_count', 
                'tasks_count'
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
            2 => self::SORTBY_RAITING_COL,
            3 => self::SORTBY_DEALS_COL,
            4 => self::SORTBY_POP_COL,
        ];

        return $sortingColumns;
    } 

    /**
     * Настройки для getContractorsMain()
     */
    public static function getContractorSettings()
    {
        return [
            self::SETTING_QUERY, // set вернуть как объект
            self::SETTING_ARRAY, // set объекта
        ];
    }

    /**
     * Обработчик $selectColumns. Отделяет новые атрибуты от стандартных
     */
    public static function getColumnGroups(string $selectColumns = '*'): array
    {
        $attributes = (new Users)->attributes();
        $basicColumns = ['u.*'];
        $newColumns = [];

        if ($selectColumns !== '*') {
            $basicColumns = array_intersect(explode(', ', $selectColumns), $attributes);
            $basicColumns = $basicColumns === [] ? ['u.*'] : (array_map(function ($val) {
                return 'u.' . $val;
            }, $basicColumns));
            $newColumns = array_diff(explode(', ', $selectColumns), $attributes);
        }

        foreach ($newColumns AS $key => $newColumn) {
            if (in_array($newColumn, self::getSubqueryLabels()) === false) {
                throw new NotSupportedException('Не найдено колонки и соответствующего подзапроса');
                unset($newColumns[$key]);
            }
        }

        return [$basicColumns, $newColumns];
    }

    /**
     * Обработчик параметров исполнителя $paramsIDs, отделяет массив IDs от дополнений и настроек.
     */
    public static function separateParamsIDs(array $paramsIDs = []): array
    {
        $settingLabels = self::getContractorSettings();
        $addonLabels = self::getContractorAddonLabels();

        $settings = [];
        $addons = [];
        $userIDs = [];

        $paramsIDs = array_values(array_filter($paramsIDs));
        $maxNum = count($settingLabels) + count($addonLabels) + 1; // параметры + 1 массив IDs
        if (count($paramsIDs)) {
            for ($i = 0; $i < count($paramsIDs) && $i < $maxNum; $i++) {
                switch ($param = $paramsIDs[$i]) {
                    case self::SETTING_QUERY:$settings[] = self::SETTING_QUERY; 
                        break;
                    case self::SETTING_ARRAY:$settings[] = self::SETTING_ARRAY; 
                        break;
                    case $addonLabels[1]:$addons[] = $addonLabels[1]; 
                        break;
                    default: 
                        if (is_array($param) && $param) {
                            $userIDs = $param; 
                        }
                }
            }

            if ($settings === [] && $addons === [] && $userIDs === []) {
                $userIDs = $paramsIDs;
            }
        }

        foreach ($settings AS $key => $setting) {
            if (in_array($setting, $settingLabels) === false) {
                throw new NotSupportedException('Не найдено названия настройки запроса');
                unset($setting[$key]);
            }
        }

        foreach ($addons AS $key => $addon) {
            if (in_array($addon, $addonLabels) === false) {
                throw new NotSupportedException('Не найдено названия аддона для запроса');
                unset($addon[$key]);
            }
        }
        
        return [$settings, $addons, $userIDs];
    }

    /**
     * Исполнители - пользователи со специализациями 3 и более, не являются заказчиками
     *
     * @param string $selectColumns стандартные колонки таблицы и новые колонки.
     * По умолчанию * (выбрать все). Новые колонки согласно $newAttributeValues[]
     * Пример. 'user_id' - если одно поле user_id, то вернет простой массив
     * Пример. 'user_id, specialization_count' - массив с двумя колонками
     * @param array $paramsIDs массив IDs, настройки (asQuery), дополнения (addRating).
     * Например  [1,2,3] | ['asQuery'] | ['asQuery', 'addRating', [1,2,3]]
     *
     * @return mixed query, array
     */
    public static function getContractorsMain(string $selectColumns, array $paramsIDs)
    {
        /* АТРИБУТЫ $selectColumns */
        list(
            $basicColumns, // массив
            $newColumns // массив
        ) = self::getColumnGroups($selectColumns);

        /* ПАРАМЕТРЫ И IDs $paramsIDs */
        list(
            $settings, // массив
            $addons, // массив
            $userIDs 
        ) = self::separateParamsIDs($paramsIDs);

// Тесты переменных
echo 'paramsIDs';
var_dump($paramsIDs);
echo 'basicColumns';
var_dump($basicColumns);
echo 'newColumns';
var_dump($newColumns);
echo 'settings ';
var_dump($settings);
echo 'addons ';
var_dump($addons);
echo 'IDs';
var_dump($userIDs);
// die;

        /* ГЛАВНАЯ ЧАСТЬ - основной запрос исполнители */
        $customers = self::getActiveCustomers('user_id');

        // Исполнители без заказчиков
        $specializationCount = self::getSubqueries()[self::SUB_SPECIALTIES]; // Основной подзапрос

        $contractors = self::find()
            ->select($basicColumns)
            ->from('users u')
            ->where(['NOT IN', 'u.user_id', $customers]) // основное условие, не заказчики, постоянное
            ->andWhere(['>=', $specializationCount, '1']); // основное условие, категорий>=3, постоянное

        $contractors->andFilterWhere(['IN', 'u.user_id', $userIDs]); // если есть выборка по ID
        
        if (in_array(self::SETTING_ARRAY, $settings)) {
            $contractors = $contractors->asArray();
        }

// Тесты главной части
// echo 'Result стандартный';
// var_dump($contractors->asArray()->all());
// echo '----------------------- <br><br>'; die;

        // Стандартный ретурн как массив
        if ($newColumns === [] && $addons === [] && $settings === []) {
            return $selectColumns === 'user_id' ? $contractors->column() : $contractors->all();
        }
        // Стандартный ретурн как запрос Query
        elseif ($newColumns === [] && $addons === [] && in_array(self::SETTING_QUERY, $settings)) {
            return $contractors;
        }

        /* ЧАЧТЬ 2 - дополнение для главного запроса */
        if ($addons) {
            $contractors = self::getContractorAddons($contractors, $addons);
        }

        /* ЧАСТЬ 3 - Подзапросы, дополнительные поля для исполнителей */
        if ($newColumns) {
            $subqueries = self::getSubqueries($newColumns);
            foreach ($newColumns as $newColumn) {
                $newSubquery = $subqueries[$newColumn] ?? null;
                $contractors = $contractors->addSelect([$newColumn => $newSubquery]);
            }
        }
        
// Тесты общие
// echo 'Result дополненные поля';
// var_dump($contractors->all());
// echo '----------------------- <br><br>'; die;

        return in_array(self::SETTING_QUERY, $settings) ? $contractors : $contractors->all();
    }

    /**
     * Дополнения (имена) для запроса getContractorsMain()
     */
    public static function getContractorAddonLabels()
    {
        return [
            1 => self::ADDON_RATING // addon добавляет поля рейтинга
        ];
    }
    
    /**
     * Дополнения (объекты) для запроса getContractorsMain()
     */
    public static function getContractorAddons(object $contractors, array $addons = null): object
    {
        $addonLabels = self::getContractorAddonLabels();
        $addons ?: $addons = $addonLabels;

        // рейтинг исполнителей
        if (in_array($addonLabels[1], $addons)) {

            $contractors
                ->addSelect([
                    'count(f2.recipient_id) AS feedbacks_count',
                    'sum(f2.point_num) AS sum_point',
                    'sum(f2.point_num)/count(f2.recipient_id) AS ' . self::SORTBY_RAITING_COL,
                ])
                ->joinWith('feedbacks f2')
                ->groupBy('u.user_id');
        }

        return $contractors;
    }

    /**
     * Подзапросы (имена) для новых колонок
     */
    public static function getSubqueryLabels()
    {
        return [
            self::SUB_SPECIALTIES,
            self::SORTBY_DEALS_COL,
        ];
    }
    
    /**
     * Подзапросы (объекты) для новых колонок
     */
    public static function getSubqueries(array $labels = null): array
    {
        $subqueryLabels = self::getSubqueryLabels();
        $labels = $labels ?: $subqueryLabels;
        $subqueries = [];
        
// Тесты
// echo 'Подзапросы';
// var_dump($labels);
// die;

        // расчет количества специализаций SPECIALIZATIONS_COUNT
        if (in_array(self::SUB_SPECIALTIES, $labels)) {
            $subqueries[self::SUB_SPECIALTIES] = (new Query())
                ->select('count(us.user_id)')
                ->from('user_specializations us')
                ->where('us.user_id = u.user_id'); // !!! работает только в строковом формате для подзапроса в where
        }

        if (in_array(self::SORTBY_DEALS_COL, $labels)) {
            $subqueries[self::SORTBY_DEALS_COL] = (new Query())
            ->select(['count(contractor_id)'])
            ->from(
                (new Query())
                    ->from(['task_runnings tr'])
                    ->where('tr.contractor_id = u.user_id')
                ->union((new Query())
                    ->from(['task_failings tf'])
                    ->where('tf.contractor_id = u.user_id')
                , true)
            )
            // ->where('contractor_id = u.user_id')
            ->groupBy(['u.user_id']);
        }

        return $subqueries;
    }

    /**
     * Количество задач исполнителей
     */
    public static function getContractorTasks(array $contractorIDs = null): array
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
            ->select(['u.user_id', 'count(contractor_id) AS ' . self::SORTBY_DEALS_COL])
            ->from(['users u'])
            ->join('LEFT JOIN', [$contractor_tasks], 'contractor_id = u.user_id') // LEFT JOIN позволяет получить 0 при отсутствии записи вместо пусто
            ->filterWhere(['IN', 'u.user_id', $contractorIDs]) 
            ->groupBy(['u.user_id']);

        return $tasks_count->all();
    }
    
    /**
     * Заказчики с активными задачами - все данные или ID
     */
    public static function getActiveCustomers(string $selectColumns = '*'): array
    {
        $customers = self::find()
            ->select($selectColumns)
            ->joinWith([
                'customerTasks ct',
            ])
            ->where(['ct.status_id' => '1'])
            ->orWhere(['ct.status_id' => '3'])
            ->all();

        return $selectColumns === 'user_id' ? array_column($customers, 'user_id') : $customers;
    }

}
