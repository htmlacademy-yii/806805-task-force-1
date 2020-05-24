<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

class UsersForm extends Model 
{
    public $categories;
    public $isAvailable;
    public $isOnLine;
    public $isFeedbacks;
    public $isFavorite;
    public $search;

    public function formName() {
        return 'UsersForm'; // Имя формы при отправке в представлении, по умолчанию соответствует имени модели. 
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

    /* Элементы для формы, список чекбоксов, выпадающий спикок. */
    // $key - имя атрибут модели в форме 
    public static function getAttributeItems (string $key) : array {

        /* Список чекбоксов категории. Массив 'id_category' => 'name'*/
        $categories = (new \yii\db\Query())->from('categories')->select(['name', 'id_category'])->indexBy('id_category')->orderBy('id_category')->column();
        
        /* Массив. Элементы для формы. */
        $items = [
            /* Список чекбоксов категории */
            'categories' => $categories,
        ];
      
        return $items[$key];
    }

    public function defaultValues () : void {
        
        $defaults = [
            'categories' => [1, 2],
            'isAvailable' => null, 
            'isOnLine' => 1, 
            'isFeedbacks' => 1, 
            'isFavorite' => 1, 
        ];

        $this->attributes = $defaults;
    }
}