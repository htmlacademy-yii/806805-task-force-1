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
 * @property TaskRunnings $taskRunning
 * @property TaskStatuses $status
 * @property Categories $category
 * @property Locations $location
 * @property Users $customer
 * 
 * связи много-много
 * @property Users $taskContractor
 */
class Tasks extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{tasks}}';
    }

    // Стандартные данные таблицы ORM
    
    public function rules()
    {
        return [
            [['status_id', 'category_id', 'location_id', 'customer_id', 'title', 'desc_text', 'add_time'], 'required'],
            [['status_id', 'category_id', 'location_id', 'customer_id', 'price', 'is_remote'], 'integer'],
            [['desc_text'], 'string'],
            [['add_time', 'end_date'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['full_address', 'address_desc', 'latitude', 'longitude'], 'string', 'max' => 255],
            [
                ['status_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => TaskStatuses::class, 
                'targetAttribute' => ['status_id' => 'status_id']
            ],
            [
                ['category_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Categories::class, 
                'targetAttribute' => ['category_id' => 'category_id']
            ],
            [
                ['location_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Locations::class, 
                'targetAttribute' => ['location_id' => 'location_id']
            ],
            [
                ['customer_id'], 
                'exist', 
                'skipOnError' => true, 
                'targetClass' => Users::class, 
                'targetAttribute' => ['customer_id' => 'user_id']
            ],
        ];
    }

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

    // Связи
    // @return \yii\db\ActiveQuery

    public function getFeedbacks()
    {
        return $this->hasMany(Feedbacks::class, ['task_id' => 'task_id']);
    }

    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['task_id' => 'task_id']);
    }

    public function getOffers()
    {
        return $this->hasMany(Offers::class, ['task_id' => 'task_id']);
    }

    public function getTaskFailings()
    {
        return $this->hasMany(TaskFailings::class, ['task_id' => 'task_id']);
    }

    public function getTaskFiles()
    {
        return $this->hasMany(TaskFiles::class, ['task_id' => 'task_id']);
    }

    public function getTaskRunning()
    {
        return $this->hasOne(TaskRunnings::class, ['task_id' => 'task_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(TaskStatuses::class, ['status_id' => 'status_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['category_id' => 'category_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Locations::class, ['location_id' => 'location_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Users::class, ['user_id' => 'customer_id']);
    }


    // Связи много-много

    /**
     * Исполнитель задания, если есть
     * @return \yii\db\ActiveQuery
     */
    public function getTaskContractor()
    {
        return $this->hasOne(Users::class, ['user_id' => 'contractor_id'])
            ->via('taskRunning');
    }

    // Задания с ролями

    public static function findNewTasks(array $IDs = []): \yii\db\ActiveQuery
    {
        $query = self::find()
            ->from('tasks t')
            ->joinWith([
                'status s1',
                'category c1',
                'taskFiles tf1',
                'location l1',
                'offers o1',
            ])
            ->where(['t.status_id' => 1])
            ->andFilterWhere(['IN', 't.task_id', $IDs])
            ->orderBy(['add_time' => SORT_DESC]); // Сортировка по умолчанию - по дате добавления

        return $query;
    }
}
