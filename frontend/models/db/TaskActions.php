<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_actions".
 *
 * @property int $action_id
 * @property string $title
 * @property string $const_name
 */
class TaskActions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_actions';
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
            'action_id' => 'Action ID',
            'title' => 'Title',
            'const_name' => 'Const Name',
        ];
    }
}
