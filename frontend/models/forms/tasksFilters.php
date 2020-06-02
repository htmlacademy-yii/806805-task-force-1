<?php

namespace frontend\models\forms;

use frontend\models\db\Tasks;
use frontend\models\db\Offers;
use yii;
use yii\base\Model;
use yii\db\Query;

class TasksFilters 
{
    public function getNewTasksFilters($tasksForm) 
    {
        /* Запрос данные заданий новые с учетом жадной загрузки категорий */
        $tasks = Tasks::find()
            ->where(['status_id' => 1])
            ->joinWith('category')
            ->orderBy(['add_time' => SORT_DESC])
        ;

        /* Фильтр Категории. Добавление условия в запрос. Атрибут пуст или из формы или по умолчанию */
        $tasks->andFilterWhere(['IN', 'category_id', $tasksForm->categories]); 

        /* Фильтр - без откликов (предложения offers). true = без откликов */
        // Запрос id заданий с откликами уникальные, в любом статусе, статус определен $tasks
        if ($tasksForm->isOffers) {
            $taskWithOffers = (new Query)->select('task_id')->distinct()->from('offers');
            $tasks->andWhere(['NOT IN', 'id_task', $taskWithOffers]); 
        }

        /* Фильтр Период. Выполнятся всегда, при первой загрузке страницы по умолчанию week */
        // Точка времени - текущее время минус Значение фильтра, формат времени как в БД
        $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $tasksForm->dateInterval, 'php:Y-m-d H:i:s');
        $tasks->andWhere(['>', 'add_time', $datePoint]);

        return $tasks->all(); 
    }
}