<?php 
namespace frontend\controllers;

use yii\web\Controller;

class ImporterController extends Controller {
    
    public function actionIndex() {
        return $this->render('index');
    }
}
