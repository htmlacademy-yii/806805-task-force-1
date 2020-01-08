<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "feedbacks".
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_rated_id
 * @property int $task_id
 * @property string|null $desk
 * @property int $point
 * @property string $add_time
 *
 * @property Users $user
 * @property Users $userRated
 * @property Tasks $task
 */
class Feedbacks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedbacks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_rated_id', 'task_id', 'point', 'add_time'], 'required'],
            [['user_id', 'user_rated_id', 'task_id', 'point'], 'integer'],
            [['desk'], 'string'],
            [['add_time'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['user_rated_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_rated_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'id']],
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
            'user_rated_id' => 'User Rated ID',
            'task_id' => 'Task ID',
            'desk' => 'Desk',
            'point' => 'Point',
            'add_time' => 'Add Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRated()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_rated_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'task_id']);
    }
}
