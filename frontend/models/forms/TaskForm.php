<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

class TaskForm extends Model
{
    public $categories;
    public $isOffers;
    public $isRemote;
    public $dateInterval;
    public $search;

    public static function getAttributeItems ($key) {

        $items = [
            'dateInterval' => [
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц'
            ]
        ];

        return $items[$key];
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
        ];
    }

}