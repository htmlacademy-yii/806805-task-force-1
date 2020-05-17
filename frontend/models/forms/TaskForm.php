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

        // Вариант 1. Массив 'symbol' => 'name'
        // $categories = \frontend\models\db\Categories::find()->select(['symbol', 'name'])->asArray()->all();
        // $categories = array_column($categories, 'name', 'symbol');

        // Вариант 2. Массив 'symbol' => 'name'
        // $categories = \frontend\models\db\Categories::find()->select(['name', 'symbol'])->indexBy('symbol')->asArray()->column();
        
        // Вариант 3. Массив 'symbol' => 'name'
        $categories = (new \yii\db\Query())->from('categories')->select(['name', 'symbol'])->indexBy('symbol')->column();

        $items = [
            'dateInterval' => [
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц'
            ],
            'categories' => $categories
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