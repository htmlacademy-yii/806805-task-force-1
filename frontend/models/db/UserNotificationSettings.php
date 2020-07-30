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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_notification_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_id'], 'required'],
            [['user_id', 'notification_id', 'is_active'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserNotifications::className(), 'targetAttribute' => ['notification_id' => 'notification_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'user_id' => 'User ID',
            'notification_id' => 'Notification ID',
            'is_active' => 'Is Active',
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
    public function getNotification()
    {
        return $this->hasOne(UserNotifications::className(), ['notification_id' => 'notification_id']);
    }
}
