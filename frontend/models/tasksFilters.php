<?php

namespace frontend\models;

use frontend\models\db\Tasks;
use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\db\Query;

/**
 * @property object $tasksForm
 * @property array $tasks
 * @property array $taskIDs
 *
 */
class TasksFilters
{
    public $tasksForm;
    public $tasks;
    public $taskIDs;

    public function __construct(object $tasksForm)
    {
        $this->tasksForm = $tasksForm;
    }

    public function getNewTasksMain(array $userIDs = []): object
    {
        $tasks = Tasks::find()
            ->from('tasks t1')
            ->where(['t1.status_id' => 1])
            ->joinWith([
                'status s1',
                'category c1',
                'taskFiles tf1',
                'location l1',
                'offers o1',
            ])
            ->filterWhere(['IN', 't1.task_id', $userIDs])
            ->orderBy(['add_time' => SORT_DESC]);

        return $tasks;
    }

    public function getNewTasks(): array
    {
        return $this->tasks = $this->getNewTasksMain()->all();
    }

    public function getFilterNewTasks(): array
    {
        $tasks = $this->getNewTasksMain();

        // Фильтр поиск по названию задания. Тип Fulltext логический, поиск сбрасывает другие фильтры
        if ($search = $this->tasksForm->search) {
            $logicSearch = prepareLogicSearch($search);
            $tasks->andWhere("MATCH(t1.title) AGAINST ('$logicSearch' IN BOOLEAN MODE)");

            return $this->tasks = $tasks->all();
        }

        // Фильтр Категории
        $tasks->andFilterWhere(['IN', 't1.category_id', $this->tasksForm->categories]);

        // Фильтр без откликов (предложения offers). true = без откликов
        if ($this->tasksForm->isOffers) {
            $taskWithOffers = (new Query())
                ->select('o2.task_id')
                ->distinct()
                ->from('offers o2');
            $tasks->andWhere(['NOT IN', 't1.task_id', $taskWithOffers]);
        }

        // Фильтр Период. по умолчанию пусто = "За все время"
        if ($this->tasksForm->dateInterval) {
            $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $this->tasksForm->dateInterval, 'php:Y-m-d H:i:s');
            $tasks->andWhere(['>', 't1.add_time', $datePoint]);
        }

        return $this->tasks = $tasks->all();
    }

    public function getTaskIDs()
    {
        return $this->taskIDs = array_column($this->tasks, 'task_id');
    }
}
