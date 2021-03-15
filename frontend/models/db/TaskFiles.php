<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_files".
 *
 * @property int $file_id
 * @property int $task_id
 * @property string|null $file_addr
 *
 * @property Tasks $task
 */
class TaskFiles extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'task_files';
    }

    public function rules()
    {
        return [
            [['task_id'], 'required'],
            [['task_id'], 'integer'],
            [['file_addr'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'task_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'task_id' => 'Task ID',
            'file_addr' => 'File Addr',
        ];
    }

    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['task_id' => 'task_id']);
    }
}
