<?php
namespace frontend\models\forms;

use frontend\models\db\Users;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $full_name;
    public $location_id;
    public $password;

    public $errorCssClass = 'input-danger';

    public function formName()
    {
        return 'signupForm';
    }

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required', 'message' => 'Обязательно. '],
            ['email', 'email'],
            ['email', 'string', 'max' => 64],
            ['email', 'unique', 'targetClass' => '\frontend\models\db\Users', 'message' => 'Адрес занят. '],

            ['full_name', 'trim'],
            ['full_name', 'required', 'message' => 'Обязательно. '],
            ['full_name', 'string', 'min' => 4, 'max' => 64],

            ['location_id', 'required', 'message' => 'Обязательно. '],
            ['location_id', 'integer'],
            ['location_id', 'exist', 'targetClass' => '\frontend\models\db\Locations', 'targetAttribute' => 'location_id'],

            ['password', 'required', 'message' => 'Обязательно. '],
            ['password', 'string', 'min' => 4, 'tooShort' => 'Неверно. '],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'full_name' => 'Ваше имя',
            'location_id' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }

    public function attributeHints()
    {
        return [
            'email' => 'Введите валидный адрес электронной почты',
            'full_name' => 'Введите ваше имя и фамилию',
            'location_id' => 'Укажите город, чтобы находить подходящие задачи',
            'password' => 'Длина пароля от 8 символов',
        ];
    }

    public static function getAttributeItems(string $attributeName): ?array
    {
        /* Список чекбоксов категории */
        $items['location_id'] = \frontend\models\db\locations::find()
            ->select(['city', 'location_id'])
            ->indexBy('location_id')
            ->orderBy('location_id')
            ->column();

        return $items[$attributeName] ?? null;
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Users();
        $user->full_name = $this->full_name;
        $user->email = $this->email;
        $user->location_id = $this->location_id;
        $user->reg_time = date('Y-m-d h:i:s', time());
        $user->activity_time = date('Y-m-d h:i:s', time());

        $user->password_key = $this->password;

        return $user->save();
    }
}
