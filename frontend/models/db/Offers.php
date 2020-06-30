<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "offers".
 *
 * @property int $offer_id
 * @property int $task_id
 * @property int $contractor_id
 * @property string $desc_text
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
            [['task_id', 'contractor_id', 'desc_text'], 'required'],
            [['task_id', 'contractor_id'], 'integer'],
            [['desc_text'], 'string'],
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
            'offer_id' => 'Offer ID',
            'task_id' => 'Task ID',
            'contractor_id' => 'Contractor ID',
            'desc_text' => 'Desc Text',
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
