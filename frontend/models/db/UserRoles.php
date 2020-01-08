<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_roles".
 *
 * @property int $id
 * @property string $symbol
 * @property string $name
 *
 * @property Users[] $users
 */
class UserRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['symbol', 'name'], 'required'],
            [['symbol', 'name'], 'string', 'max' => 32],
            [['symbol'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'symbol' => 'Symbol',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['role_id' => 'id'])->inverseOf('role');
    }
}
