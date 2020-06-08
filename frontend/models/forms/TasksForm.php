<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

class TasksForm extends Model
{
    public $categories;
    public $isOffers;
    public $isRemote;
    public $dateInterval;
    public $search;

    public function formName()
    {
        return 'TasksForm';
    }

    public function attributeLabels()
    {
        return
            [
            'categories' => 'Категории',
            'isOffers' => 'Без откликов',
            'isRemote' => 'Удаленная работа',
            'dateInterval' => 'Период',
            'search' => 'Поиск по названию',
        ];
    }

    public function rules()
    {
        return
            [
            [['categories', 'isOffers', 'isRemote', 'dateInterval', 'search'], 'safe'],
            ['isOffers', 'default', 'value' => '0'],
            ['isRemote', 'default', 'value' => '1'],
        ];
    }

    /* Элементы для полей формы согласно имени атрибута */
    public static function getAttributeItems(string $attributeName): array
    {
        /* Фильтр Категории - список чекбоксов id_category - name */
        $categories = (new \yii\db\Query())
            ->from('categories')
            ->select(['name', 'id_category'])
            ->indexBy('id_category')
            ->orderBy('id_category')
            ->column();

        $items =
            [
            /* Фильтр Период времени - выпадающий список */
            'dateInterval' => [
                // 'all' => 'За все время',  // !!! "За все время", отображается как 1ая опция (задается activeField promt), значение пусто
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц',
            ],
            'categories' => $categories,
        ];

        return $items[$attributeName];
    }
}
