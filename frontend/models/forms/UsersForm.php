<?php

namespace frontend\models\forms;

use yii\base\Model;

class UsersForm extends Model
{
    public $categories;
    public $isAvailable;
    public $isOnLine;
    public $isFeedbacks;
    public $isFavorite;
    public $search;

    public function formName()
    {
        return 'UsersForm';
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
            [
                ['categories', 'isAvailable', 'isOnLine', 'isFeedbacks', 'isFavorite', 'search'], 'safe'
            ],
        ];
    }

    /* Элементы для полей формы согласно имени атрибута */
    public static function getAttributeItems(string $attributeName): ?array
    {
        /* Список чекбоксов категории */
        $categories = \frontend\models\db\Categories::find()
            ->select(['title', 'category_id'])
            ->indexBy('category_id')
            ->orderBy('category_id')
            ->column();

        return $$attributeName ?? null;
    }
}
