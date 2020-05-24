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

        // if (isset($_POST['FormName'])) {
        //     $model->attributes = $_POST['FormName'];
        //     if ($model->save()) {
        //         // handle success
        //     }
        // }

        /* Модель для формы, страница Tasks */
        $tasksForm = new TasksForm; // В том числе значения по умолчанию
        $tasksForm->defaultValues(Yii::$app->request->post()); // Загружаем значения по умолчанию при первом запуске, те если форма не отправлена

        $tasksForm->load(Yii::$app->request->post()); // Условие отправки формы. Присваивание и сохранение данных формы если форма по имени модели отправлена, тк содержит ключ модели и массив со ствойствами, (для обычного POST, нужно дополнительный параметр null). Yii::$app->request->post() всегда существует, Соответствует $_POST, но переопрелен на специальный буфер
        
        /* Данные из модели Tasks с учетом жадной загрузки категорий + фильтры */
        $tasks = Tasks::find()->where(['status_id' => 1])->joinWith('category')->orderBy(['add_time' => SORT_DESC]);
        
        /* Фильтр Категории. массив пуст или id_task из формы */
        // вариант 1
        $tasks = $tasks->andFilterWhere(['IN', 'category_id', $tasksForm->categories]); // вариант 1
        // вариант 2
        // if($categories = $tasksForm->categories) {
        //     $tasks = $tasks->andWhere(['IN', 'category_id', $categories]); // 
        // }

        /* Фильтр Удаленная работа. false true*/
        // Вариант 1. С помощью фильтра ActiveQuery
        $tasks->andFilterWhere(['is_remote' => $tasksForm->isRemote]); // Иногда значение задано по умолчанию, которые берем из модели формы. uncheck = null удобно, если 0 то в выборку попадут только значения 0, а не все значения 1 и 0. Но значение по умолчанию не переписывается!!! Блть
        
        // Вариант 2. С помощью условия отправки формы, кроме 0, те $tasksPost['is_remote'] === 0 не учитывается, при методом uncheck = null приходится делать проверку на существование
        // $isRemote = Yii::$app->request->post('TasksForm')['isRemote'] ?? null;
        // if ($isRemote) {
        //     $tasks = $tasks->andWhere(['is_remote' => $isRemote]);
        //     print_r('Удаленка');
        // }

        /* Фильтр - без откликов (предложения) offers. false true */
        $isOffers = Yii::$app->request->post('TasksForm')['isOffers'] ?? null;
        if ($isOffers) {
            $offers = Offers::find()->select('task_id')->distinct(); // Задания с откликами, в любом статусе, статус определен $tasks
            $tasks = $tasks->andWhere(['NOT IN', 'id_task', $offers]); // Без откликов, исключаем задания с откликами
        }

        /* Фильтр Период */
        // Условие должно выполнятся при первой загрузке страницы, по умолчанию week
        $datePoint = Yii::$app->formatter->asDatetime('-1 ' . $tasksForm->dateInterval, 'php:Y-m-d H:i:s'); // формат БД
        $tasks = $tasks->andWhere(['>', 'add_time', $datePoint]);

        // echo \Yii::t('app', 'Я сообщение!'); // Перевести сообщение на языке приложения на текущий язык страницы en-US


        $tasks = $tasks->all(); 


        return $this->render('index', ['tasks' => $tasks, 'tasksForm' => $tasksForm]);
    }
}
