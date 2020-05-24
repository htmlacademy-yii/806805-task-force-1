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
        return 'TasksForm'; // Имя формы при отправке в представлении, по умолчанию соответствует имени модели. 
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

    /* Элементы для формы, список чекбоксов, выпадающий спикок. */
    // $key - имя атрибут модели в форме 
    public static function getAttributeItems (string $key) : array {

        /* Список чекбоксов категории. Массив 'id_category' => 'name'*/
        $categories = (new \yii\db\Query())->from('categories')->select(['name', 'id_category'])->indexBy('id_category')->orderBy('id_category')->column();

        /* Массив. Элементы для формы. */
        $items = [
            /* Выпадающий список, период времени */
            'dateInterval' => [
                'day' => 'За день',
                'week' => 'За неделю',
                'month' => 'За месяц'
            ],
            /* Список чекбоксов категории */
            'categories' => $categories,
        ];
      
        return $items[$key];
    }

    public function defaultValues () : void {
        
        $defaults = [
            'categories' => [1, 2],
            'isOffers' => null,
            'isRemote' => 1,
            'dateInterval' => 'week',
            'search' => null,
        ];

        $this->attributes = $defaults;
    }
}