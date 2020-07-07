<?php

namespace frontend\controllers;

use frontend\models\db\Tasks;
use frontend\models\forms\TasksFilters;
use frontend\models\forms\TasksForm;
use frontend\models\forms\UsersFilters;
use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasksForm = new TasksForm();
        $tasksFilters = new TasksFilters();

        $tasks = [];
        if ($tasksForm->load(Yii::$app->request->post()) === false) {
            $tasks = $tasksFilters->getNewTasks();
        } else {
            $tasks = $tasksFilters->getNewTasks($tasksForm);
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'tasksForm' => $tasksForm,
        ]);
    }

    public function actionView(int $id)
    {
        $taskQuery = Tasks::find()
            ->joinWith([
                'status',
                'category',
                'taskFiles',
                'location',
                'offers.contractor oc',
            ])
            ->where(['tasks.task_id' => $id])
            ->limit(1);
        $task = $taskQuery->one();

        if (!$task) {
            throw new NotFoundHttpException('Такого задания не существует');
        }
            define('USER_ID', 3); // условно текущий пользователь, в условии показа блоков мини-панели и чата

            $customer = $task->customer->attributes;
            $customerRating = UsersFilters::getRatingGeneric(
                [$task->customer_id], 'one');
            $customerRating === null ?: $customer = array_merge($customer, $customerRating);
            $customer['tasks'] = $task->customer->customerTasks;
            // var_dump($customer);

            $contractor = null;
            if ($taskQuery->andWhere(['tasks.status_id' => 3])->exists()) {
                $contractor = $task->taskRunnings->contractor->attributes;
                $contractorRating = UsersFilters::getRatingGeneric(
                    [$contractor['user_id']], 'one');
                $contractorRating === null ?: $contractor = array_merge($contractor, $contractorRating);
                $contractor['tasks'] = UsersFilters::getContractorTasks(
                    [$contractor['user_id']]
                );
            }
            // var_dump($contractor);

            $offerContractors = null;
            if ($task->offers) {
                $contractorRaitings = UsersFilters::getRatingGeneric(
                    array_column($task->offers, 'contractor_id'));
                    
                foreach ($task->offers as $offer) {
                    $offerContractor = $offer->contractor->attributes;
                    $contractorRaiting = $contractorRaitings[$offerContractor['user_id']] ?? [];
                    $offerContractors[$offer['offer_id']] = array_merge(
                        $offerContractor, $contractorRaiting);
                }
            }

        return $this->render('view', [
            'task' => $task,
            'customer' => $customer,
            'contractor' => $contractor,
            'offerContractors' => $offerContractors,
        ]);
    }
}
