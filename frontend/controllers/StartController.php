<?php

namespace frontend\controllers;

use frontend\models\db\Users;
use frontend\models\forms\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class StartController extends Controller
{
    public $layout = 'start';

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->getId()) {
            return $this->redirect('/tasks', 302);
        }

        $formModel = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $formModel->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($formModel);
            }

            if ($formModel->validate()) {
                $userByForm = $formModel->getUser();
                Yii::$app->user->login($userByForm);

                return $this->redirect('/tasks', 302);
            }
        }

        return $this->render('index', ['formModel' => $formModel]);
    }

    public function actionLogin()
    {
        $user = Users::findOne(1);
        Yii::$app->user->login($user);

        return $this->redirect('/tasks', 302);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
