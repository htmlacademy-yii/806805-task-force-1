<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_favorites".
 *
 * @property int $favorite_id
 * @property int $user_id
 * @property int $fave_user_id
 * @property int|null $is_fave
 *
 * @property Users $user
 * @property Users $faveUser
 */
class UserFavorites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'fave_user_id'], 'required'],
            [['user_id', 'fave_user_id', 'is_fave'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['fave_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['fave_user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'favorite_id' => 'Favorite ID',
            'user_id' => 'User ID',
            'fave_user_id' => 'Fave User ID',
            'is_fave' => 'Is Fave',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaveUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'fave_user_id']);
    }
}
