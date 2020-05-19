<?php

namespace frontend\controllers;

use frontend\models\db\Tasks;
use frontend\models\forms\TasksForm;
use yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    /* Не изучено, Согласно примера академии */
    // public function beforeAction($action)
    // {
    //     $this->enableCsrfValidation = false;
    //     return true;
    // }

    public function actionIndex() 
    {
        // $this->enableCsrfValidation = false; // Не изучено, Согласно примера академии
        if (isset($_POST['FormName'])) {
            $model->attributes = $_POST['FormName'];
            if ($model->save()) {
                // handle success
            }
        }

        /* Модель для формы, страница Tasks */
        $tasksForm = new TasksForm;
        $tasksPost = Yii::$app->request->post();
        if (Yii::$app->request->getIsPost()) {
            $tasksForm->load($tasksPost);
        }

        /* Данные из модели Tasks с учетом жадной загрузки категорий */
        $tasks = Tasks::find()->where(['status_id' => 1])->joinWith('category')->orderBy(['add_time' => SORT_DESC])
            ->andFilterWhere(['is_remote' => $tasksForm->isRemote])
            ->all(); 


        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
