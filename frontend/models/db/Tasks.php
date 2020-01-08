<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
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
 * @property TaskFiles[] $taskFiles
 * @property TaskStatuses $status
 * @property Categories $category
 * @property Locations $location
 * @property Users $customer
 * @property TasksRunning[] $tasksRunnings
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
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatuses::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['location_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
        return $this->hasMany(Feedbacks::className(), ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::className(), ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::className(), ['task_id' => 'id'])->inverseOf('task');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatuses::className(), ['id' => 'status_id'])->inverseOf('tasks');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id'])->inverseOf('tasks');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Locations::className(), ['id' => 'location_id'])->inverseOf('tasks');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::className(), ['id' => 'customer_id'])->inverseOf('tasks');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasksRunnings()
    {
        return $this->hasMany(TasksRunning::className(), ['task_run_id' => 'id'])->inverseOf('taskRun');
    }
}
