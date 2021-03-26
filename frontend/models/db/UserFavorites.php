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
    public static function tableName()
    {
        return 'user_favorites';
    }

    public function rules()
    {
        return [
            [['user_id', 'fave_user_id'], 'required'],
            [['user_id', 'fave_user_id', 'is_fave'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['fave_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['fave_user_id' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'favorite_id' => 'Favorite ID',
            'user_id' => 'User ID',
            'fave_user_id' => 'Fave User ID',
            'is_fave' => 'Is Fave',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    public function getFaveUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'fave_user_id']);
    }
}
