<?php

namespace frontend\controllers;

use frontend\models\db\Tasks;
use frontend\models\db\Offers;
use frontend\models\forms\TasksForm;
use yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    /* Не изучено, Согласно примера академии */
    // public function beforeAction($action)
    // {
    //     $this->enableCsrfValidation = false;
    //     return true;
    // }

    public function actionIndex() 
    {
        // $this->enableCsrfValidation = false; // Не изучено, Согласно примера академии

        /* Модель для формы фильтров, страница tasks */
        $tasksForm = new TasksForm; 
        
        /* Проверка. Если форма отправлена с именем как в модели загрузить значения формы в модель*/
        $tasksForm->load(Yii::$app->request->post());

        /* Запрос данные заданий новые с учетом жадной загрузки категорий + условия с проверкой фильтров  */
        $tasks = Tasks::find()
            ->where(['status_id' => 1])
            ->joinWith('category')
            ->orderBy(['add_time' => SORT_DESC])
        ;
        
        /* Фильтр Категории. Добавление условия в запрос. Атрибут пуст или из формы или по умолчанию */
        $tasks->andFilterWhere(['IN', 'category_id', $tasksForm->categories]); 

        /* Фильтр - без откликов (предложения offers). true = без откликов */
        if ($tasksForm->isOffers) 
        {
            // Запрос id заданий с откликами уникальные, в любом статусе, статус определен $tasks
            $taskWithOffers = (new Query)->select('task_id')->from('offers')
                ->distinct()
            ;
            // Добавление условия в запрос. Без откликов, исключаем задания с откликами подзапросом
            $tasks->andWhere(['NOT IN', 'id_task', $taskWithOffers]); 
        }

        /* Фильтр Период. Выполнятся всегда, при первой загрузке страницы по умолчанию week */
        // Точка времени - текущее время минус Значение фильтра, формат времени как в БД
        $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $tasksForm->dateInterval, 'php:Y-m-d H:i:s');
        // Добавление условия в запрос. 
        $tasks->andWhere(['>', 'add_time', $datePoint]);

        // Запись данных всех заданий в массив
        $tasks = (array) $tasks->all(); 

        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
