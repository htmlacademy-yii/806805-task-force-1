<?php

namespace frontend\controllers;

use frontend\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SignupController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $formModel = new SignupForm();

        if (Yii::$app->request->getIsPost()) {
            $formModel->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($formModel);
            }

            if ($formModel->validate()) {
                $formModel->signup();

                return $this->redirect('/?singup=success', 302);
            }
        }

        return $this->render('index', ['formModel' => $formModel]);
    }
}
