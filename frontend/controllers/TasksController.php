<?php

namespace frontend\controllers;

use frontend\controllers\AccessController;
use frontend\models\forms\TasksForm;
use frontend\models\TasksFilters;
use frontend\models\TaskView;
use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends AccessController
{
    public function actionIndex(int $category = null)
    {
        $tasksForm = new TasksForm();
        $tasksFilters = new TasksFilters($tasksForm);

        $tasks = [];
        if ($category !== null) {
            $tasksForm->categories = [$category];
            $tasks = $tasksFilters->getFilterNewTasks();
        } elseif ($tasksForm->load(Yii::$app->request->post()) === true) {
            $tasks = $tasksFilters->getFilterNewTasks();
        } else {
            $tasks = $tasksFilters->getNewTasks();
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'tasksForm' => $tasksForm,
        ]);
    }

    public function actionView(int $ID)
    {
        $taskView = new TaskView($ID);
        $task = $taskView->getTask();

        if (!$task) {
            throw new NotFoundHttpException('Такого задания не существует');
        }

        $customer = $taskView->getCustomer();

        // Действующий исполнитель
        $currentContractor = $taskView->getCurrentContractor();

        // Исполнители с их предложениями
        $candidatesAndOffers = $taskView->getCandidatesAndOffers();

        return $this->render('view', [
            'task' => $task,
            'customer' => $customer,
            'currentContractor' => $currentContractor,
            'candidatesAndOffers' => $candidatesAndOffers,
        ]);
    }
}
