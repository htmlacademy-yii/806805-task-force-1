<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_notifications".
 *
 * @property int $notification_id
 * @property string $title
 * @property string $label
 *
 * @property UserNotificationSettings[] $userNotificationSettings
 */
class UserNotifications extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_notifications';
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
            'notification_id' => 'Notification ID',
            'title' => 'Title',
            'label' => 'Label',
        ];
    }

    public function getUserNotificationSettings()
    {
        return $this->hasMany(UserNotificationSettings::class, ['notification_id' => 'notification_id']);
    }
}
