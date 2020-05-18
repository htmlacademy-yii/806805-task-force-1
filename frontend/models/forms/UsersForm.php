<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

class UsersForm extends Model {

    public $categories;
    public $isAvailable;
    public $isOnLine;
    public $isFeedbacks;
    public $isFavorite;
    public $search;


    /* Элементы для формы, список чекбоксов, выпадающий спикок. */
    // $key - атрибут модели в форме 
    public static function getAttributeItems ($key) {

        /* Список чекбоксов категории. Массив 'symbol' => 'name'*/
        $categories = (new \yii\db\Query())->from('categories')->select(['name', 'symbol'])->indexBy('symbol')->column();
        $items = [
            'categories' => $categories
        ];
      
        return $items[$key];
    }

    public function attributeLabels()
    {
        return [
            'categories' => 'Категории',
            'isAvailable' => 'Сейчас свободен',
            'isOnLine' => 'Сейчас онлайн',
            'isFeedbacks' => 'Есть отзывы',
            'isFavorite' => 'В избранном',
            'search' => 'Поиск по имени',

        ];
    }

    public function rules()
    {
        return [
            [['categories', 'isAvailable', 'isOnLine', 'isFeedbacks', 'isFavorite'], 'safe'],
        ];
    }

}