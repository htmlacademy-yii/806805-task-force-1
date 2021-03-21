<?php

namespace frontend\models;

use frontend\models\db\Tasks;
use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\db\Query;

/**
 * @property object $taskFilters
 * @property object $tasksQuery
 * @property object $filteredTasks
 *
 */
class TasksFiltration
{
    public $taskFilters;
    public $tasksQuery;
    public $filteredTasks;

    public function __construct(object $tasksQuery, object $taskFilters)
    {
        $this->tasksQuery = $tasksQuery;
        $this->taskFilters = $taskFilters;
    }

    public function getFilteredTasks()
    {
        return $this->filteredTasks;
    }

    public function filter(): bool
    {
        $tasks = $this->tasksQuery;

        // Фильтр поиск по названию задания. Тип Fulltext логический, поиск сбрасывает другие фильтры
        if ($search = $this->taskFilters->search) {
            $logicSearch = prepareLogicSearch($search);
            $tasks->andWhere("MATCH(t.title) AGAINST ('$logicSearch' IN BOOLEAN MODE)");

            $this->filteredTasks = $tasks;

            return $tasks->exists();
        }

        // Фильтр Категории
        $tasks->andFilterWhere(['IN', 't.category_id', $this->taskFilters->categories]);

        // Фильтр без откликов (предложения offers). true = без откликов
        if ($this->taskFilters->isOffers) {
            $taskWithOffers = (new Query())
                ->from('offers o')
                ->select('o.task_id')
                ->distinct();

            $tasks->andWhere(['NOT IN', 't.task_id', $taskWithOffers]);
        }

        // Фильтр Период. по умолчанию пусто = "За все время"
        if ($this->taskFilters->dateInterval) {
            $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $this->taskFilters->dateInterval, 'php:Y-m-d H:i:s');
            $tasks->andWhere(['>', 't.add_time', $datePoint]);
        }

        $this->filteredTasks = $tasks;

        return $tasks->exists();
    }
}
