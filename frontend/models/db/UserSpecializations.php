<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_specializations".
 *
 * @property int $specialization_id
 * @property int $user_id
 * @property int|null $category_id
 *
 * @property Users $user
 * @property Categories $category
 */
class UserSpecializations extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_specializations';
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'specialization_id' => 'Specialization ID',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['category_id' => 'category_id']);
    }
}
