<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_favorites".
 *
 * @property int $id
 * @property int $user_id
 * @property int $favorite_id
 * @property int|null $on_off
 *
 * @property Users $user
 * @property Users $favorite
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
            [['user_id', 'favorite_id'], 'required'],
            [['user_id', 'favorite_id', 'on_off'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['favorite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['favorite_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'favorite_id' => 'Favorite ID',
            'on_off' => 'On Off',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id'])->inverseOf('userFavorites');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorite()
    {
        return $this->hasOne(Users::className(), ['id' => 'favorite_id'])->inverseOf('userFavorites0');
    }
}
