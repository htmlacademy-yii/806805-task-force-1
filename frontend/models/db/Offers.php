<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "offers".
 *
 * @property int $offer_id
 * @property int $task_id
 * @property int $contractor_id
 * @property string $price
 * @property string $desc_text
 * @property string $add_time
 * 
 * @property Tasks $task
 * @property Users $contractor
 */
class Offers extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'offers';
    }

    public function rules()
    {
        return [
            [['task_id', 'contractor_id', 'desc_text'], 'required'],
            [['task_id', 'contractor_id', 'price'], 'integer'],
            [['desc_text'], 'string'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'task_id']],
            [['contractor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['contractor_id' => 'user_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'offer_id' => 'Offer ID',
            'task_id' => 'Task ID',
            'contractor_id' => 'Contractor ID',
            'price' => 'Price',
            'desc_text' => 'Desc Text',
        ];
    }

    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['task_id' => 'task_id']);
    }

    public function getContractor()
    {
        return $this->hasOne(Users::class, ['user_id' => 'contractor_id']);
    }
}
