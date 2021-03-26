<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $message_id
 * @property int $task_id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $mess_text
 * @property string $add_time
 *
 * @property Tasks $task
 * @property Users $sender
 * @property Users $receiver
 */
class Messages extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'messages';
    }

    public function rules()
    {
        return [
            [['task_id', 'sender_id', 'receiver_id', 'mess_text', 'add_time'], 'required'],
            [['task_id', 'sender_id', 'receiver_id'], 'integer'],
            [['mess_text'], 'string'],
            [['add_time'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'task_id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['sender_id' => 'user_id']],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['receiver_id' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'task_id' => 'Task ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'mess_text' => 'Mess Text',
            'add_time' => 'Add Time',
        ];
    }

    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['task_id' => 'task_id']);
    }

    public function getSender()
    {
        return $this->hasOne(Users::class, ['user_id' => 'sender_id']);
    }

    public function getReceiver()
    {
        return $this->hasOne(Users::class, ['user_id' => 'receiver_id']);
    }
}
