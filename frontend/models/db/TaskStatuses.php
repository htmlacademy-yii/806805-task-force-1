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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'const_name'], 'required'],
            [['title', 'const_name'], 'string', 'max' => 64],
            [['title'], 'unique'],
            [['const_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'status_id' => 'Status ID',
            'title' => 'Title',
            'const_name' => 'Const Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['status_id' => 'status_id']);
    }
}
