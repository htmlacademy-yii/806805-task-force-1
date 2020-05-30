<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "user_notification_settings".
 *
 * @property int $id_user_notification_setting
 * @property int $user_id
 * @property int $notification_id
 * @property int|null $on_off
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
            [['user_id', 'notification_id', 'on_off'], 'integer'],
            [
                ['user_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Users::className(), 
                'targetAttribute' => ['user_id' => 'id_user']
            ],
            [
                ['notification_id'], 
                'exist', 'skipOnError' => true, 
                'targetClass' => UserNotifications::className(), 
                'targetAttribute' => ['notification_id' => 'id_user_notification']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user_notification_setting' => 'Id User Notification Setting',
            'user_id' => 'User ID',
            'notification_id' => 'Notification ID',
            'on_off' => 'On Off',
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
    public function getNotification()
    {
        return $this->hasOne(UserNotifications::className(), ['id_user_notification' => 'notification_id']);
    }
}
