<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_notifications".
 *
 * @property int $id
 * @property string $symbol
 * @property string $name
 *
 * @property UserNotificationSettings[] $userNotificationSettings
 */
class UserNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_notifications';
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
    public function getUserNotificationSettings()
    {
        return $this->hasMany(UserNotificationSettings::className(), ['notification_id' => 'id'])->inverseOf('notification');
    }
}
