<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_roles".
 *
 * @property int $role_id
 * @property string $title
 * @property string $const_name
 *
 * @property Users[] $users
 */
class UserRoles extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_roles';
    }

    public function rules()
    {
        return [
            [['title', 'const_name'], 'required'],
            [['title', 'const_name'], 'string', 'max' => 64],
            [['title'], 'unique'],
            [['const_name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'title' => 'Title',
            'const_name' => 'Const Name',
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(Users::class, ['role_id' => 'role_id']);
    }
}
