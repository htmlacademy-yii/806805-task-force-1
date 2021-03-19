<?php

namespace frontend\controllers;

use frontend\controllers\AccessController;
use frontend\models\db\Categories;
use frontend\models\forms\TasksForm;
use frontend\models\TasksFiltration;
use frontend\models\db\Tasks;
use yii;
use yii\web\NotFoundHttpException;
use frontend\models\db\Users;

class TasksController extends AccessController
{
    public function actionIndex(int $categoryID = null)
    {
        $taskFiltersForm = new TasksForm();
        $tasksQuery = Tasks::findNewTasks();

        if (($taskFiltersForm->categories[] = $categoryID)
            || $taskFiltersForm->load(Yii::$app->request->post()) === true) {

            if (in_array($categoryID, Categories::find()->column())) {
                throw new NotFoundHttpException('Такой категории не существует');
            }
        
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
        $candidates = Users::findCandidates($task->task_id)
            ->addSelect(['*', 'avgRating' => Users::subAvgRating()])
            ->all();
        // пример для получения предложения
        $candidates[0]->offers[$task->task_id]->toArray();

        return $this->render('view', [
            'task' => $task,
            'customer' => $task->customer,
            'currentContractor' => $task->taskContractor,
            'candidatesAndOffers' => $candidates,
        ]);
    }
}
