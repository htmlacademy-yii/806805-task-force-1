<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_notification_settings".
 *
 * @property int $setting_id
 * @property int $user_id
 * @property int $notification_id
 * @property int|null $is_active
 *
 * @property Users $user
 * @property UserNotifications $notification
 */
class UserNotificationSettings extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_notification_settings';
    }

    public function rules()
    {
        return [
            [['user_id', 'notification_id'], 'required'],
            [['user_id', 'notification_id', 'is_active'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNotifications::class, 'targetAttribute' => ['notification_id' => 'notification_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'user_id' => 'User ID',
            'notification_id' => 'Notification ID',
            'is_active' => 'Is Active',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['user_id' => 'user_id']);
    }

    public function getNotification()
    {
        return $this->hasOne(UserNotifications::class, ['notification_id' => 'notification_id']);
    }
}
