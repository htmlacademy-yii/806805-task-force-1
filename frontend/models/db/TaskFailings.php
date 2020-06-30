<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_failings".
 *
 * @property int $failing_id
 * @property int $task_id
 * @property int $contractor_id
 *
 * @property Tasks $task
 * @property Users $contractor
 */
class TaskFailings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_failings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'contractor_id'], 'required'],
            [['task_id', 'contractor_id'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'task_id']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['contractor_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'failing_id' => 'Failing ID',
            'task_id' => 'Task ID',
            'contractor_id' => 'Contractor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'contractor_id']);
    }
}
