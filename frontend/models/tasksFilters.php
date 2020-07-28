<?php

namespace frontend\models;

use frontend\models\db\Tasks;
use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\base\Model;
use yii\db\Query;

class TasksFilters
{
    public function getNewTasks(Model $tasksForm = null): array
    {
        $tasks = Tasks::find()
            ->where(['tasks.status_id' => 1])
            ->joinWith('category') // Жадная загрузка категорий
            ->orderBy(['add_time' => SORT_DESC]);

        // если форма не отправлена
        if ($tasksForm === null) {
            return $tasks->all();
        }

        /* Фильтры, если форма отправлена */

        // Фильтр Категории
        $tasks->andFilterWhere(['IN', 'tasks.category_id', $tasksForm->categories]);

        // Фильтр без откликов (предложения offers). true = без откликов
        if ($tasksForm->isOffers) {
            $taskWithOffers = (new Query())
                ->select('offers.task_id')
                ->distinct()
                ->from('offers');
            $tasks->andWhere(['NOT IN', 'tasks.task_id', $taskWithOffers]);
        }

        // Фильтр Период. по умолчанию пусто = "За все время"
        if ($tasksForm->dateInterval) {
            $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $tasksForm->dateInterval, 'php:Y-m-d H:i:s');
            $tasks->andWhere(['>', 'tasks.add_time', $datePoint]);
        }

        /* Фильтр поиск по названию задания */
        // Специальные символы логического поиска удаляются.
        // Словам в строке добавляется символ *
        if ($search = $tasksForm->search) {

            $logicSearch = prepareLogicSearch($search);

            $tasks->andWhere("MATCH(tasks.title) AGAINST ('$logicSearch' IN BOOLEAN MODE)");
        }

        return $tasks->all();
    }
}
