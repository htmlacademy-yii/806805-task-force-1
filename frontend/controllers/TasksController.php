<?php

namespace frontend\controllers;

use frontend\models\db\Tasks;
use frontend\models\forms\TasksFilters;
use frontend\models\forms\TasksForm;
use frontend\models\forms\UsersFilters;
use yii;
use yii\web\Controller;

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

    public function actionView(int $id = null)
    {
        $taskQuery = Tasks::find()
            ->joinWith([
                'status',
                'category',
                'taskFiles',
                'location',
                'offers.contractor oc',
            ])
            ->where($id)
            ->limit(1);
        $task = $taskQuery->one();

        define('USER_ID', 11); // условно текущий пользователь, влияет на показ мини-пользователь и чат

        $customer = $task->customer->attributes;
        $customerRating = UsersFilters::getRatingGeneric(
            $task['customer_id']) ?: [];
        $customer = array_merge($customer, $customerRating);
        $customer['tasks'] = $task->customer->customerTasks;

        $contractor = null;
        if ($taskQuery->andWhere(['tasks.status_id' => 3])->exists()) {
            $contractor = $task->taskRunnings->contractor->attributes;
            $contractorRating = UsersFilters::getRatingGeneric(
                $contractor['user_id']) ?: [];
            $contractor = array_merge($contractor, $contractorRating);
            $contractor['tasks'] = UsersFilters::getContractorTasks(
                $contractor['user_id']
            );
        }

        $offerContractors = null;
        if ($task->offers !== null) {
            $contractorRaitings = UsersFilters::getRatingGeneric(
                array_column($task->offers, 'contractor_id'));

            foreach ($task->offers as $offer) {
                $offerContractor = $offer->contractor->attributes;
                $contractorRaiting = $contractorRaitings[$offerContractor['user_id']];
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
