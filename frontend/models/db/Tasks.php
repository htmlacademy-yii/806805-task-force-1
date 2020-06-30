<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $task_id
 * @property int $status_id
 * @property int $category_id
 * @property int $location_id
 * @property int $customer_id
 * @property string $title
 * @property string $desc_text
 * @property int|null $price
 * @property string|null $full_address
 * @property string|null $address_desc
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
            [['status_id', 'category_id', 'location_id', 'customer_id', 'title', 'desc_text', 'add_time'], 'required'],
            [['status_id', 'category_id', 'location_id', 'customer_id', 'price', 'is_remote'], 'integer'],
            [['desc_text'], 'string'],
            [['add_time', 'end_date'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['full_address', 'address_desc', 'latitude', 'longitude'], 'string', 'max' => 255],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatuses::className(), 'targetAttribute' => ['status_id' => 'status_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['location_id' => 'location_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['customer_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'status_id' => 'Status ID',
            'category_id' => 'Category ID',
            'location_id' => 'Location ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'desc_text' => 'Desc Text',
            'price' => 'Price',
            'full_address' => 'Full Address',
            'address_desc' => 'Address Desc',
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
        return $this->hasMany(Feedbacks::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFailings()
    {
        return $this->hasMany(TaskFailings::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskRunnings()
    {
        return $this->hasMany(TaskRunnings::className(), ['task_id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatuses::className(), ['status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Locations::className(), ['location_id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'customer_id']);
    }
}
