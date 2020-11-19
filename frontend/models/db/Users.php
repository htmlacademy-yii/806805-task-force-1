<?php

namespace frontend\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property int $role_id
 * @property int $location_id
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $messaging_contact
 * @property string|null $full_address
 * @property string|null $avatar_addr
 * @property string|null $desc_text
 * @property string $password_key
 * @property string|null $birth_date
 * @property string $reg_time
 * @property string $activity_time
 * @property int $hide_contacts
 * @property int $hide_profile
 *
 * @property Feedbacks[] $yoursFeedbacks
 * @property Feedbacks[] $feedbacks
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Offers[] $offers
 * @property TaskFailings[] $taskFailings
 * @property TaskRunnings[] $taskRunnings
 * @property Tasks[] $customerTasks
 * @property UserFavorites[] $userFavorites
 * @property UserFavorites[] $userFavorites0
 * @property UserNotificationSettings[] $userNotificationSettings
 * @property UserPortfolioImages[] $userPortfolioImages
 * @property UserRoles $role
 * @property Locations $location
 *
 * Связь много-много
 * @property UserSpecializations[] $userSpecializations
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    // public function validatePassword($password)
    // {
    //     return \Yii::$app->security->validatePassword($password, $this->password);
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'location_id', 'hide_contacts', 'hide_profile'], 'integer'],
            [['location_id', 'full_name', 'email', 'password_key', 'reg_time', 'activity_time'], 'required'],
            [['desc_text'], 'string'],
            [['birth_date', 'reg_time', 'activity_time'], 'safe'],
            [['full_name', 'email', 'skype', 'messaging_contact'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            [['full_address', 'avatar_addr', 'password_key'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [
                ['role_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => UserRoles::class,
                'targetAttribute' => ['role_id' => 'role_id'],
            ],
            [
                ['location_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Locations::class,
                'targetAttribute' => ['location_id' => 'location_id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'location_id' => 'Location ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'messaging_contact' => 'Messaging Contact',
            'full_address' => 'Full Address',
            'avatar_addr' => 'Avatar Addr',
            'desc_text' => 'Desc Text',
            'password_key' => 'Password Key',
            'birth_date' => 'Birth Date',
            'reg_time' => 'Reg Time',
            'activity_time' => 'Activity Time',
            'hide_contacts' => 'Hide Contacts',
            'hide_profile' => 'Hide Profile',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYoursFeedbacks()
    {
        return $this->hasMany(Feedbacks::className(), ['author_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeedbacks()
    {
        return $this->hasMany(Feedbacks::className(), ['recipient_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['sender_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Messages::className(), ['receiver_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::className(), ['contractor_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskFailings()
    {
        return $this->hasMany(TaskFailings::className(), ['contractor_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskRunnings()
    {
        return $this->hasMany(TaskRunnings::className(), ['contractor_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerTasks()
    {
        return $this->hasMany(Tasks::className(), ['customer_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavorites()
    {
        return $this->hasMany(UserFavorites::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavorites0()
    {
        return $this->hasMany(UserFavorites::className(), ['fave_user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotificationSettings()
    {
        return $this->hasMany(UserNotificationSettings::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPortfolioImages()
    {
        return $this->hasMany(UserPortfolioImages::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(UserRoles::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Locations::className(), ['location_id' => 'location_id']);
    }

    // Связи много-много

    /**
     * Специализация (категории пользователя)
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(Categories::className(), ['category_id' => 'category_id'])
            ->viaTable('user_specializations', ['user_id' => 'user_id']);
    }
}
