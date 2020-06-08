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
            [['categories', 'isAvailable', 'isOnLine', 'isFeedbacks', 'isFavorite'], 'safe'],
        ];
    }

    /* Элементы для полей формы согласно имени атрибута */
    public static function getAttributeItems(string $attributeName): ?array
    {
        /* Категории - список чекбоксов Массив id_category - name */
        $categories = (new \yii\db\Query())
            ->from('categories')
            ->select(['name', 'id_category'])
            ->indexBy('id_category')
            ->orderBy('id_category')
            ->column();

        return $$attributeName ?? null;
    }
}
