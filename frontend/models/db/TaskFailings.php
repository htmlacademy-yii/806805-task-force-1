<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_failings".
 *
 * @property int $id_task_failing
 * @property int $task_failing_id
 * @property int $contractor_id
 *
 * @property Tasks $taskFailing
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
            [['task_failing_id', 'contractor_id'], 'required'],
            [['task_failing_id', 'contractor_id'], 'integer'],
            [
                ['task_failing_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Tasks::className(), 
                'targetAttribute' => ['task_failing_id' => 'id_task']
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
            'id_task_failing' => 'Id Task Failing',
            'task_failing_id' => 'Task Failing ID',
            'contractor_id' => 'Contractor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFailing()
    {
        return $this->hasOne(Tasks::className(), ['id_task' => 'task_failing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'contractor_id']);
    }
}
