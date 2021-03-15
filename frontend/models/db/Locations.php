<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "locations".
 *
 * @property int $location_id
 * @property string $city
 * @property string $latitude
 * @property string $longitude
 *
 * @property Task[] $tasks
 * @property User[] $users
 */
class Locations extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'locations';
    }

    public function rules()
    {
        return [
            [['city', 'latitude', 'longitude'], 'required'],
            [['city'], 'string', 'max' => 64],
            [['latitude', 'longitude'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'location_id' => 'Location ID',
            'city' => 'City',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    public function getTasks()
    {
        return $this->hasMany(Task::class, ['location_id' => 'location_id']);
    }

    public function getUsers()
    {
        return $this->hasMany(User::class, ['location_id' => 'location_id']);
    }
}
