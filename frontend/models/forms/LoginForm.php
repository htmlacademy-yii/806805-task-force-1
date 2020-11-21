<?php

namespace frontend\models\forms;

use frontend\models\db\Users;
use Yii;
use yii\base\Model;
use yii\widgets\ActiveForm;


class LoginForm extends Model
{
    public $email;
    public $password;

    public $user;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['password', 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }


    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $validatePassword = Yii::$app->security->validatePassword($this->password, $user->password_key);

            if (!$this->user || !$validatePassword) {
                $this->addError($attribute, 'Неправильный email или пароль');

                echo 'wrong';
            }
        }
    }

    protected function getUser() {
        return $this->user ?: Users::findOne(['email' => $this->email]);
    }
}