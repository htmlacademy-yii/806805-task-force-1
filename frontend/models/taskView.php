<?php

namespace frontend\models;

use frontend\models\db\Tasks;
use frontend\models\db\UsersMain;
use frontend\models\UserView;

/**
 * @property int $taskID
 * @property int $customerID;
 * @property int $currentContractorID;
 * @property array $candidateIDs
 * @property object $task
 * @property object $customer;
 * @property object $currentContractor;
 * @property array $candidates;
 * @property array $offers;
 *
 */
class TaskView
{
    public $taskID;
    public $customerIDs;
    public $currentContractorID;
    public $candidateIDs;
    public $task;
    public $customer;
    public $currentContractor;
    public $candidates;
    public $offers;

    public function __construct(int $taskID)
    {
        $this->taskID = $taskID;
    }

    public function getTasksMain(array $taskIDs = []): object
    {
        $tasks = Tasks::find()
            ->from('tasks t1')
            ->joinWith([
                'status s1',
                'category c1',
                'taskFiles tfi1',
                'location l1',
                'taskRunnings tr1',
                'offers o1',
            ])
        // ->where(['t1.status_id' => 1]) отключаем, тк страница управляет статусом, влияет роль исполнитель или заказчик
            ->filterWhere(['IN', 't1.task_id', $taskIDs])
            ->orderBy(['add_time' => SORT_DESC]);

        return $tasks;
    }

    public function getTask(): object
    {
        return $this->task = $this->getTasksMain([$this->taskID])->one();
    }

    public function getCustomerID(): int
    {
        return $this->customerID = $this->task->customer_id;
    }

    public function getCustomer(): object
    {
        $customerIDs[] = $this->getCustomerID();

        return $this->customer = UsersMain::getCustomersMain('*', ['asQuery'], $customerIDs)->one();
    }

    public function getCurrentContractorID(): int
    {
        return $this->currentContractorID = $this->task->taskRunnings->contractor_id;
    }

    public function getCurrentContractor(): ?object
    {
        $userView = new UserView($this->getCurrentContractorID());

        return $this->currentContractor = $userView->getContractor();
    }

    public function getCandidateIDs(): array
    {
        // проверяем на пустой или вернет всех
        if (!$this->task->offers) {
            return $this->candidateIDs = [];
        }

        return $this->candidateIDs = array_column($this->task->offers, 'contractor_id');
    }

    public function getCandidates(array $addons = []): array
    {
        $defaultSettings = ['asQuery']; // значения по умолчанию (всегда включено)
        $contractor = UsersMain::getcontractorsMain('*', $defaultSettings);

        // Общее дополнение запроса
        $contractor
            ->joinWith([
                'taskRunnings tr1',
                'feedbacks f1',
                'userSpecializations usc1',
                'userPortfolioImages upi1',
                'offers o1',
            ])
            ->andWhere(['o1.task_id' => $this->taskID])
            ->indexBy('user_id'); // Ключ массива (атрибут объекта, не поле)

        // Дополнение запроса или дополнительные данные (addon)
        $defaultAddons = ['addRatings', 'addDeals']; // значения по умолчанию (всегда включено)
        $addons = array_merge($defaultAddons, $addons);

        if ($addons) {
            $contractor = UsersMain::addContractorAddons($contractor, $addons);
        }

        return $this->candidates = $contractor->all();
    }

    public function getOffers(): array
    {
        return $this->offers = $this->task->getOffers()
            ->indexBy('contractor_id')
            ->all();
    }

    public function getCandidatesAndOffers(): array
    {
        $offers = $this->offers ?: $this->getOffers();
        // проверяем на пустой или вернет всех
        if (!$this->task->offers) {
            $this->candidateIDs = [];

            return $this->offers = [];
        }

        $candidates = $this->candidates ?: $this->getCandidates();

        $both = [];
        foreach ($candidates as $ID => $candidate) {
            $offer = $offers[$ID] ?: null;
            $both[$ID] = [$candidate, $offer];
        }

        return $both;
    }
}
