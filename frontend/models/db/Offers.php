<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "offers".
 *
 * @property int $id_offer
 * @property int $task_id
 * @property int $contractor_id
 * @property string $desk
 *
 * @property Tasks $task
 * @property Users $contractor
 */
class Offers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'contractor_id', 'desk'], 'required'],
            [['task_id', 'contractor_id'], 'integer'],
            [['desk'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'id_task']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['contractor_id' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_offer' => 'Id Offer',
            'task_id' => 'Task ID',
            'contractor_id' => 'Contractor ID',
            'desk' => 'Desk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['id_task' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContractor()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'contractor_id']);
    }
}
