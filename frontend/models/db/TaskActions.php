<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "task_actions".
 *
 * @property int $id
 * @property string $symbol
 * @property string $name
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
            [['symbol', 'name'], 'required'],
            [['symbol', 'name'], 'string', 'max' => 32],
            [['symbol'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'symbol' => 'Symbol',
            'name' => 'Name',
        ];
    }
}
