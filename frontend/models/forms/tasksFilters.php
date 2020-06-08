<?php

namespace frontend\models\forms;

use frontend\models\db\Tasks;
use yii;
use yii\base\Model;
use yii\db\Query;

class TasksFilters
{
    public function getNewTasks(?Model $tasksForm = null): array
    {
        /* Запрос данные заданий новые с учетом жадной загрузки категорий */
        $tasks = Tasks::find()
            ->where(['status_id' => 1])
            ->joinWith('category')
            ->orderBy(['add_time' => SORT_DESC]);

        // если форма не отправлена
        if ($tasksForm === null) {
            return $tasks->all();
        }

        /* Фильтры, если форма отправлена */

        /* Фильтр Категории. Добавление условия в запрос. Атрибут пуст или из формы или по умолчанию */
        $tasks->andFilterWhere(['IN', 'category_id', $tasksForm->categories]);

        /* Фильтр - без откликов (предложения offers). true = без откликов */
        // Запрос id заданий с откликами уникальные, в любом статусе, статус определен $tasks
        if ($tasksForm->isOffers) {
            $taskWithOffers = (new Query())->select('task_id')->distinct()->from('offers');
            $tasks->andWhere(['NOT IN', 'id_task', $taskWithOffers]);
        }

        /* Фильтр Период. по умолчанию пустое значение соответствует "За все время", отображается как 1ая опция (задается activeField promt) */
        // Точка времени - текущее время минус Значение фильтра, формат времени как в БД
        if ($tasksForm->dateInterval) {
            $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $tasksForm->dateInterval, 'php:Y-m-d H:i:s');
            $tasks->andWhere(['>', 'add_time', $datePoint]);
        }

        return $tasks->all();
    }
}
