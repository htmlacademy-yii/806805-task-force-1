<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id_task
 * @property int $status_id
 * @property int $category_id
 * @property int $location_id
 * @property int $customer_id
 * @property string $name
 * @property string $description
 * @property int|null $price
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string $add_time
 * @property string|null $end_date
 * @property int|null $is_remote
 *
 * @property Feedbacks[] $feedbacks
 * @property Messages[] $messages
 * @property Offers[] $offers
 * @property TaskFailings[] $taskFailings
 * @property TaskFiles[] $taskFiles
 * @property TaskRunnings[] $taskRunnings
 * @property TaskStatuses $status
 * @property Categories $category
 * @property Locations $location
 * @property Users $customer
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'category_id', 'location_id', 'customer_id', 'name', 'description', 'add_time'], 'required'],
            [['status_id', 'category_id', 'location_id', 'customer_id', 'price', 'is_remote'], 'integer'],
            [['description'], 'string'],
            [['add_time', 'end_date'], 'safe'],
            [['name', 'address', 'latitude', 'longitude'], 'string', 'max' => 128],
            [
                ['status_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => TaskStatuses::className(), 
                'targetAttribute' => ['status_id' => 'id_task_status']
            ],
            [
                ['category_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Categories::className(), 
                'targetAttribute' => ['category_id' => 'id_category']
            ],
            [
                ['location_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Locations::className(), 
                'targetAttribute' => ['location_id' => 'id_location']
            ],
            [
                ['customer_id'], 
                'exist', 'skipOnError' => true, 
                'targetClass' => Users::className(), 
                'targetAttribute' => ['customer_id' => 'id_user']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_task' => 'Id Task',
            'status_id' => 'Status ID',
            'category_id' => 'Category ID',
            'location_id' => 'Location ID',
            'customer_id' => 'Customer ID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'address' => 'Address',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'add_time' => 'Add Time',
            'end_date' => 'End Date',
            'is_remote' => 'Is Remote',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedbacks::className(), ['task_id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['task_id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::className(), ['task_id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFailings()
    {
        return $this->hasMany(TaskFailings::className(), ['task_failing_id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::className(), ['task_id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskRunnings()
    {
        return $this->hasMany(TaskRunnings::className(), ['task_running_id' => 'id_task']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatuses::className(), ['id_task_status' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id_category' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Locations::className(), ['id_location' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::className(), ['id_user' => 'customer_id']);
    }
}
