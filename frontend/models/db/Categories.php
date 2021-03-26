<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $category_id
 * @property string $title
 * @property string $label
 *
 * @property Task[] $tasks
 * @property UserSpecialization[] $userSpecializations
 */
class Categories extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['title', 'label'], 'required'],
            [['title', 'label'], 'string', 'max' => 64],
            [['title'], 'unique'],
            [['label'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'title' => 'Title',
            'label' => 'Label',
        ];
    }

    public function getTasks()
    {
        return $this->hasMany(Task::class, ['category_id' => 'category_id']);
    }

    public function getUserSpecializations()
    {
        return $this->hasMany(UserSpecialization::class, ['category_id' => 'category_id']);
    }
}
