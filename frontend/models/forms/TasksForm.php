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

    public function formName() {
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
            ['isOffers', 'default', 'value' => '1'],
            ['isRemote', 'default', 'value' => '1'],
            ['dateInterval', 'default', 'value' => 'week'],
        ];
    }

    /* Элементы для полей формы согласно имени атрибута*/
    public static function getFieldItemsForAttributeByName (string $name) : array 
    {
        /* Категории - список чекбоксов Массив id_category - name */
        $categories = (new \yii\db\Query())
            ->from('categories')
            ->select(['name', 'id_category'])
            ->indexBy('id_category')
            ->orderBy('id_category')
            ->column();

        $items = 
        [
            /* Выпадающий список, период времени */
            'dateInterval' => [
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц'
            ],
            'categories' => $categories,
        ];
      
        return $items[$name];
    }
}