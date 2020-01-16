<?php

namespace frontend\models\db;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id_user
 * @property int $role_id
 * @property int $location_id
 * @property string $name
 * @property string|null $avatar
 * @property string $email
 * @property string $password
 * @property string|null $skype
 * @property string|null $phone
 * @property string|null $other_contacts
 * @property string|null $address
 * @property string|null $about
 * @property string $reg_time
 * @property string|null $birth_date
 * @property string $activity_time
 * @property int|null $hide_contacts
 * @property int|null $hide_profile
 *
 * @property Feedbacks[] $feedbacks
 * @property Feedbacks[] $ratedFeedbacks
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Offers[] $offers
 * @property TaskRunnings[] $taskRunnings
 * @property Tasks[] $tasks
 * @property UserFavorites[] $userFavorites
 * @property UserFavorites[] $userFavorites0
 * @property UserNotificationSettings[] $userNotificationSettings
 * @property UserPortfolioImages[] $userPortfolioImages
 * @property UserSpecializations[] $userSpecializations
 * 
 * // Связь много ко многим
 * @property Categories[] $userCategories
 * 
 * @property UserRoles $role
 * @property Locations $location
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'location_id', 'hide_contacts', 'hide_profile'], 'integer'],
            [['location_id', 'name', 'email', 'password', 'reg_time', 'activity_time'], 'required'],
            [['about'], 'string'],
            [['reg_time', 'birth_date', 'activity_time'], 'safe'],
            [['name', 'email', 'skype'], 'string', 'max' => 128],
            [['avatar', 'password', 'other_contacts', 'address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['email'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRoles::className(), 'targetAttribute' => ['role_id' => 'id_user_role']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locations::className(), 'targetAttribute' => ['location_id' => 'id_location']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'role_id' => 'Role ID',
            'location_id' => 'Location ID',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'email' => 'Email',
            'password' => 'Password',
            'skype' => 'Skype',
            'phone' => 'Phone',
            'other_contacts' => 'Other Contacts',
            'address' => 'Address',
            'about' => 'About',
            'reg_time' => 'Reg Time',
            'birth_date' => 'Birth Date',
            'activity_time' => 'Activity Time',
            'hide_contacts' => 'Hide Contacts',
            'hide_profile' => 'Hide Profile',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedbacks::className(), ['user_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatedFeedbacks()
    {
        return $this->hasMany(Feedbacks::className(), ['user_rated_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['sender_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Messages::className(), ['recipient_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::className(), ['contractor_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskRunnings()
    {
        return $this->hasMany(TaskRunnings::className(), ['contractor_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['customer_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavorites()
    {
        return $this->hasMany(UserFavorites::className(), ['user_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavorites0()
    {
        return $this->hasMany(UserFavorites::className(), ['favorite_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotificationSettings()
    {
        return $this->hasMany(UserNotificationSettings::className(), ['user_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPortfolioImages()
    {
        return $this->hasMany(UserPortfolioImages::className(), ['user_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(UserSpecializations::className(), ['user_id' => 'id_user']);
    }


    // Связь много ко многим категории пользователя
    public function getUserCategories()
    {
        return $this->hasMany(Categories::className(), ['id_category' => 'category_id'])
        ->viaTable('user_specializations', ['user_id' => 'id_user']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(UserRoles::className(), ['id_user_role' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Locations::className(), ['id_location' => 'location_id']);
    }
}
