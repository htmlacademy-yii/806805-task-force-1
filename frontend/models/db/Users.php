<?php

namespace frontend\models\db;

use frontend\models\db\Tasks;
use frontend\models\db\UserSpecializations as Specialization;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
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
 * @property Feedbacks[] $feedbacksToUsers
 * @property Feedbacks[] $feedbacks
 * @property Messages[] $messagesOutgoing
 * @property Messages[] $messages
 * @property Offers[] $offers
 * @property TaskFailings[] $taskFailings
 * @property TaskRunnings[] $taskRunnings
 * @property Tasks[] $customerTasks
 * @property UserFavorites[] $userFavorites
 * @property UserFavorites[] $usersLikedYou
 * @property UserNotificationSettings[] $userNotificationSettings
 * @property UserPortfolioImages[] $userPortfolioImages
 * @property UserRoles $role
 * @property Locations $location
 *
 * много-много
 * @property UserSpecializations[] $userSpecializations
 *
 * Дополнительные вычисляемые атрибуты
 * @property int $feedbackCounter
 * @property int $sumRating
 * @property int $avgRating
 * @property int $skillCounter
 * @property int $taskCounter
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{users}}';
    }

    // Дополнительные вычисляемые атрибуты

    public $feedbackCounter;
    public $sumRating;
    public $avgRating;
    public $skillCounter;
    public $taskCounter;

    // Авторизация

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

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_key);
    }

    // Стандартные данные таблицы ORM

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
                'targetAttribute' => ['location_id' => 'location_id'],
            ],
        ];
    }

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

    // Связи

    public function getFeedbacksToUsers()
    {
        return $this->hasMany(Feedbacks::class, ['author_id' => 'user_id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(Feedbacks::class, ['recipient_id' => 'user_id']);
    }

    public function getMessagesOutgoing()
    {
        return $this->hasMany(Messages::class, ['sender_id' => 'user_id']);
    }

    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['receiver_id' => 'user_id']);
    }

    public function getOffers()
    {
        return $this->hasMany(Offers::class, ['contractor_id' => 'user_id'])
            ->indexBy('task_id');
    }

    public function getTaskFailings()
    {
        return $this->hasMany(TaskFailings::class, ['contractor_id' => 'user_id']);
    }

    public function getTaskRunnings()
    {
        return $this->hasMany(TaskRunnings::class, ['contractor_id' => 'user_id']);
    }

    public function getCustomerTasks()
    {
        return $this->hasMany(Tasks::class, ['customer_id' => 'user_id']);
    }

    public function getUserFavorites()
    {
        return $this->hasMany(UserFavorites::class, ['user_id' => 'user_id']);
    }

    public function getUsersLikedYou()
    {
        return $this->hasMany(UserFavorites::class, ['fave_user_id' => 'user_id']);
    }

    public function getUserNotificationSettings()
    {
        return $this->hasMany(UserNotificationSettings::class, ['user_id' => 'user_id']);
    }

    public function getUserPortfolioImages()
    {
        return $this->hasMany(UserPortfolioImages::class, ['user_id' => 'user_id']);
    }

    public function getRole()
    {
        return $this->hasOne(UserRoles::class, ['role_id' => 'role_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Locations::class, ['location_id' => 'location_id']);
    }

    // Связи много-много

    /**
     * Специализация (категории пользователя)
     * @return \yii\db\ActiveQuery
     */
    public function getUserSpecializations()
    {
        return $this->hasMany(Categories::class, ['category_id' => 'category_id'])
            ->viaTable('user_specializations', ['user_id' => 'user_id']);
    }

    // Пользователи с ролями

    public static function findCustomers(array $IDs = []): \yii\db\ActiveQuery
    {
        $contractorIDs = array_values(self::findContractors()->column());

        $query = self::find()
            ->from('users u')
            ->where(['NOT IN', 'u.user_id', $contractorIDs])
            ->andFilterWhere(['IN', 'u.user_id', $IDs]);

        return $query;
    }

    public static function findCustomersActive(array $IDs = []): \yii\db\ActiveQuery
    {
        $query = self::find()
            ->from('users u')
            ->joinWith('customerTasks ct', true, 'LEFT JOIN')
            ->filterWhere(['ct.status_id' => 1])
            ->orfilterWhere(['ct.status_id' => 3]);

        return $query;
    }

    public static function findContractors(array $IDs = []): \yii\db\ActiveQuery
    {
        $specializationQuantity = 1;

        $query = self::find()
            ->from('users u')
            ->where(['>=', self::subSkillCounter(), $specializationQuantity])
            ->andFilterWhere(['IN', 'u.user_id', $IDs])
            ->orderBy(['reg_time' => SORT_DESC]); // Сортировка по умолчанию - по дате регистрации

        return $query;
    }

    public static function findContractorsAll(): array
    {
        $specializationQuantity = 1;

        $contractors = (new Query())
            ->from('users u')
            ->select(['u.user_id', 'us.category_id'])
            ->join('INNER JOIN', 'user_specializations us', 'u.user_id = us.user_id')
            ->where(['>=', self::subSkillCounter(), $specializationQuantity]);

        return $contractors->all();
    }

    /**
     * Исполнители сдалавшие предложение к заданию
     * Предложение по свойству-связи, жадная загрузка offers
     * @return \yii\db\ActiveQuery
     */
    public static function findCandidates(int $taskID): \yii\db\ActiveQuery
    {
        $query = self::find()
            ->from('users u')
            ->joinWith(['offers o'])
            ->where(['o.task_id' => $taskID]);

        return $query;
    }

    // Дополнительные вычисляемые атрибуты

    public static function subTaskCounter()
    {
        return (new Query())
            ->select(['count(contractor_id)'])
            ->from((new Query())
                    ->from(['task_runnings tr'])
                    ->where('tr.contractor_id = u.user_id')
                    ->union((new Query())
                            ->from(['task_failings tf'])
                            ->where('tf.contractor_id = u.user_id')
                        , true)
                    ->groupBy('contractor_id'));
    }

    public static function subSkillCounter()
    {
        return (new Query())
            ->from('user_specializations us')
            ->select(['count(us.user_id)'])
            ->where('us.user_id = u.user_id')
            ->groupBy('us.user_id');
    }

    public static function subFeedbackCounter()
    {
        return (new Query())
            ->from('feedbacks fb')
            ->select(['count(fb.recipient_id)'])
            ->where('fb.recipient_id = u.user_id')
            ->groupBy('fb.recipient_id');
    }

    public static function subSumRating()
    {
        return (new Query())
            ->from('feedbacks fb')
            ->select(['sum(fb.point_num)'])
            ->where('fb.recipient_id = u.user_id')
            ->groupBy('fb.recipient_id');
    }

    public static function subAvgRating()
    {
        return (new Query())
            ->from('feedbacks fb')
            ->select(['sum(fb.point_num)/count(fb.recipient_id)'])
            ->where('fb.recipient_id = u.user_id')
            ->groupBy('fb.recipient_id');
    }
}
