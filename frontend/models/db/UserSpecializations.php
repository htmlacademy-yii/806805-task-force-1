<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_specializations".
 *
 * @property int $id_user_specialization
 * @property int $user_id
 * @property int|null $category_id
 *
 * @property Users $user
 * @property Categories $category
 */
class UserSpecializations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_specializations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [
                ['user_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Users::className(), 
                'targetAttribute' => ['user_id' => 'id_user']
            ],
            [
                ['category_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Categories::className(), 
                'targetAttribute' => ['category_id' => 'id_category']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user_specialization' => 'Id User Specialization',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id_category' => 'category_id']);
    }
}
