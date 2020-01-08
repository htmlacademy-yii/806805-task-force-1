<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "tasks_running".
 *
 * @property int $id
 * @property int $task_run_id
 * @property int $contractor_id
 *
 * @property Tasks $taskRun
 * @property Users $contractor
 */
class TasksRunning extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks_running';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_run_id', 'contractor_id'], 'required'],
            [['task_run_id', 'contractor_id'], 'integer'],
            [['task_run_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_run_id' => 'id']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['contractor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_run_id' => 'Task Run ID',
            'contractor_id' => 'Contractor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskRun()
    {
        return $this->hasOne(Tasks::className(), ['id' => 'task_run_id'])->inverseOf('tasksRunnings');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(Users::className(), ['id' => 'contractor_id'])->inverseOf('tasksRunnings');
    }
}
