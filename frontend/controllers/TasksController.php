<?php

namespace frontend\controllers;

use frontend\controllers\AccessController;
use frontend\models\forms\TasksForm;
use frontend\models\TasksFiltration;
use frontend\models\db\Tasks;
use yii;
use yii\web\NotFoundHttpException;
use frontend\models\db\Users;

class TasksController extends AccessController
{
    public function actionIndex(int $category = null)
    {
        $taskFiltersForm = new TasksForm();
        $tasksQuery = Tasks::findNewTasks();

        if (($taskFiltersForm->categories[] = $category)
            || $taskFiltersForm->load(Yii::$app->request->post()) === true) {

            $filtration = new TasksFiltration($tasksQuery, $taskFiltersForm);
            $filtration->filter();
            $tasksQuery = $filtration->getFilteredTasks();
        }
        
        $tasks = $tasksQuery->all();

        return $this->render('index', [
            'tasks' => $tasks,
            'tasksForm' => $taskFiltersForm,
        ]);
    }

    public function actionView(int $ID)
    {
        $task = Tasks::findNewTasks([$ID])->one();

        if (!$task) {
            throw new NotFoundHttpException('Такого задания не существует');
        }

        // Исполнители с предложениями к заданию
        $candidates = Users::findCandidates($task->task_id)->all();

        return $this->render('view', [
            'task' => $task,
            'customer' => $task->customer,
            'currentContractor' => $task->taskContractor,
            'candidatesAndOffers' => $candidates,
        ]);
    }
}
