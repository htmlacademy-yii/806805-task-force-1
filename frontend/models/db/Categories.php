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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'label'], 'required'],
            [['title', 'label'], 'string', 'max' => 64],
            [['title'], 'unique'],
            [['label'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'title' => 'Title',
            'label' => 'Label',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(UserSpecialization::className(), ['category_id' => 'category_id']);
    }
}
