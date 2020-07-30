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
            [['title', 'label'], 'required'],
            [['title', 'label'], 'string', 'max' => 64],
            [['title'], 'unique'],
            [['label'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'notification_id' => 'Notification ID',
            'title' => 'Title',
            'label' => 'Label',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotificationSettings()
    {
        return $this->hasMany(UserNotificationSettings::className(), ['notification_id' => 'notification_id']);
    }
}
