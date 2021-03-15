<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_statuses".
 *
 * @property int $status_id
 * @property string $title
 * @property string $const_name
 *
 * @property Tasks[] $tasks
 */
class TaskStatuses extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'task_statuses';
    }

    public function rules()
    {
        return [
            [['title', 'const_name'], 'required'],
            [['title', 'const_name'], 'string', 'max' => 64],
            [['title'], 'unique'],
            [['const_name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'title' => 'Title',
            'const_name' => 'Const Name',
        ];
    }

    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['status_id' => 'status_id']);
    }
}
