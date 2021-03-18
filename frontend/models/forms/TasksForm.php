<?php

namespace frontend\models\forms;

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
        return [
            'categories' => 'Категории',
            'isOffers' => 'Без откликов',
            'isRemote' => 'Удаленная работа',
            'dateInterval' => 'Период',
            'search' => 'Поиск по названию',
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'isOffers', 'isRemote', 'dateInterval', 'search'], 'safe'],
            ['isOffers', 'default', 'value' => '0'],
            ['isRemote', 'default', 'value' => '1'],
        ];
    }

    /* Списки для полей формы согласно имени атрибута */
    public static function getAttributeItems(string $attributeName): ?array
    {
        $items['categories'] = (new \yii\db\Query())
            ->from('categories')
            ->select(['title', 'category_id'])
            ->indexBy('category_id')
            ->orderBy('category_id')
            ->column();

        /* выпадающий список период времени*/
        $items['dateInterval'] = [
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц',
            ];

        return $items[$attributeName] ?? null;
    }
}
