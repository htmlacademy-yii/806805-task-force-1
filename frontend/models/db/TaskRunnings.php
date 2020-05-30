<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_runnings".
 *
 * @property int $id_task_running
 * @property int $task_running_id
 * @property int $contractor_id
 *
 * @property Tasks $taskRunning
 * @property Users $contractor
 */
class TaskRunnings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_runnings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_running_id', 'contractor_id'], 'required'],
            [['task_running_id', 'contractor_id'], 'integer'],
            [
                ['task_running_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Tasks::className(), 
                'targetAttribute' => ['task_running_id' => 'id_task']
            ],
            [
                ['contractor_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Users::className(), 
                'targetAttribute' => ['contractor_id' => 'id_user']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_task_running' => 'Id Task Running',
            'task_running_id' => 'Task Running ID',
            'contractor_id' => 'Contractor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskRunning()
    {
        return $this->hasOne(Tasks::className(), ['id_task' => 'task_running_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'contractor_id']);
    }
}
