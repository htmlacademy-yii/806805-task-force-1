<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

class TaskForm extends Model
{
    public $categories;
    public $isOffers;
    public $isRemote;
    public $date_interval;
    public $search;

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'isOffers' => 'Без откликов',
            'isRemote' => 'Удаленная работа',
            'date_interval' => 'Период',
            'search' => 'Поиск по названию',
        ];
    }

    public function rules()
    {
        return [
            [['categories', 'isOffers', 'isRemote', 'date_interval', 'search'], 'safe'],
        ];
    }

}