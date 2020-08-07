<?php

namespace frontend\controllers;

use frontend\models\forms\SignupForm;
use phpDocumentor\Reflection\Location;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SignupController extends Controller
{
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
   
                return $this->redirect('/', 302);
            }
        }

        return $this->render('index', ['formModel' => $formModel]);
    }
}
