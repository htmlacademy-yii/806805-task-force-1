<?php

namespace frontend\controllers;

use yii;
use yii\db\Query;
use yii\web\Controller;
use frontend\models\db\Users;
use frontend\models\db\UserSpecializations;
use frontend\models\db\UserFavorites;
use frontend\models\db\Tasks;
use frontend\models\db\TaskRunnings;
use frontend\models\forms\UsersForm;
use yii\web\NotFoundHttpException;


class UsersController extends Controller
{
    public function actionIndex() 
    {
        /* Модель для формы, страница Users */
        $usersForm = new UsersForm;

        /* Если не загружены данные то форма не отправлена */
        if(!$usersForm->load(Yii::$app->request->post())) {
            $usersForm->defaultValues(); // Загружаем значения по умолчанию при первом запуске, те если форма не отправлена
        }; 

        /* Получение пользователей */
        // По заданию Пользователь является исполнитель, у которого есть специализация user_specializations, те выбираем уникальные user_id
        // Нужно удалить тех пользователей, если пользователь стал Заказчиком, даже если у него есть специализация
        // те проверяем что пользователь не являются заказчиками в текущий момент, те когда Task_status=new и Task_status=running

        // Все действующие Заказчики. Получаем массив со значениями user_id из tasks DISTINCT
        $customers = new Query;
        $customers->select(['customer_id'])->distinct()->from('tasks t')->where(['status_id' => '1'])->orWhere(['status_id' => '3'])
            // ->createCommand()->sql // показать sql-выражение, Не сработает в самом запросе -нужна доп переменная
            // ->createCommand()->queryAll() // аналогично ->all(), Не сработает в самом запросе -нужна доп переменная
        ;

        // Все Исполнители. Получаем массив со значениями user_id из user_specializations DISTINCT, 
        // также  Query позволяет легко делать подзапросы, здесь Удаляем id заказчиков из исполнителей
        $contractorsAll = new \yii\db\Query;
        $contractorsAll->select(['user_id'])->distinct()->from('user_specializations')
            ->where(['NOT IN', 'user_id', $customers])
            // ->createCommand()->sql // показать sql-выражение, Не сработает в самом запросе -нужна доп переменная
            // ->createCommand()->queryAll() // аналогично ->all(), Не сработает в самом запросе -нужна доп переменная
        ;

        // Исполнители, которые имеют специализацию и в данный момент не Заказчики. 
        $usersAll = Users::find()->where(['IN', 'id_user', $contractorsAll])
            ->orderBy(['reg_time' => SORT_DESC])
            ->indexBy('id_user');
        $users = $usersAll->all(); // Запрос если фильтры не используются, если фильтр применяется то перезаписать

        // Используется для фильтров как выборка рейтинга, массив со значениями id исполнителей
        $contractors = array_keys($users);

        // Рейтинг, используем Mysql функции и groupBy
        $rating = new Query();
        $rating = $rating->select(['user_rated_id', 'count(user_rated_id) as num_feedbacks', 'sum(point) as sum_point', 'sum(point)/count(user_rated_id) as avg_point'])
            ->from('feedbacks')
            ->where(['in', 'user_rated_id', $contractors])
            ->groupBy('user_rated_id')
            ->indexBy('user_rated_id')
            ->all()
        ;
    
        $filters; // Если фильтр используется, те не null, то перезаписываем $users
        
        /* Фильтр Категории. массив по умолчанию, id_category из формы или пусто, если снять галочки*/
        $filters = $contractorsAll->andFilterWhere(['IN', 'category_id', $usersForm->categories]); 
        $usersAll->andFilterWhere(['IN', 'id_user', $filters]); 
            // ****
            // print_r($filters);
            // print_r($users->all());

        /* Фильтр Сейчас свободен */
        // Задания выполняются. Находим задания которые выполняются из Tasks status_id = 3
        // Подзапрос. Находим задания которые добавлены в таблицу task_runnings, те исполняются, но могут быть и провалены, поэтому группируем - из таблицы task_runnings выбираем уникальные задания, и последние (макс) id, при этом исполнители не группируются
        // SELECT MAX(id_task_running), `contractor_id` FROM `task_runnings` WHERE task_running_id IN (8) GROUP BY `task_running_id`; // Выборка id правильная, но остальное не правильно, чтобы выдовал предупреждение включаем настройку БД
            // Настройка БД SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
        // Запрос. Получение исполнителей - из таблицы task_runnings выбираем исполнителей которые имеют максимальный id_task_running, те являются последними кто работает с проектом
        // select contractor_id from task_runnings where id_task_running IN (SELECT MAX(id_task_running) FROM `task_runnings` GROUP BY `task_running_id`) AND task_running_id NOT IN (8);
        if($usersForm->isAvailable) {
            // $runTasks = Tasks::find()->select('id_task')->where(['status_id' => '3']);
            $runTasks = (new Query)->select('id_task')->from('tasks')->where(['status_id' => '3']);
            // $filtersSub = TaskRunnings::find()->select(['MAX(id_task_running)'])->where(['IN', 'task_running_id', $runTasks])->groupBy('task_running_id');
            $filtersSub = (new Query)->select(['MAX(id_task_running)'])->from('task_runnings')->where(['IN', 'task_running_id', $runTasks])->groupBy('task_running_id');
            // $filters = TaskRunnings::find()->select(['contractor_id'])->where(['IN', 'id_task_running', $filtersSub]);
            $filters = (new Query)->select(['contractor_id'])->from('task_runnings')->where(['IN', 'id_task_running', $filtersSub]);
            $usersAll->andWhere(['NOT IN', 'id_user', $filters]);
        }

        /* Фильтр сейчас онлайн. */
        // Создается точка времени полчача назад.
        // Запрос - найти пользователей у который users.activity_time > точки времени
        if($usersForm->isOnLine) {
            $filters = $datePoint = Yii::$app->formatter->asDatetime('-30 minutes', 'php:Y-m-d H:i:s'); // формат БД
            $usersAll->andWhere(['>', 'activity_time', $datePoint]);
        }
        
        /* Фильтр. Есть отзывы */
        // Пользователи с рейтингом, у которых есть отзыв, определены в $rating, создаем массив ключей этих пользователей
        if($usersForm->isFeedbacks) {
            $filters = array_keys($rating);
            $usersAll->andWhere(['IN', 'id_user', $filters]);
        }

        /* Фильтр. В избранном */
        // добавляет к условию фильтрации показ пользователей, которые были добавлены в избранное
        if($usersForm->isFavorite) {
            $currentUser = 1; // !!!Пример
            // $filters = UserFavorites::find()->select('favorite_id')->where(['user_id' => $currentUser]);
            $filters = (new Query)->select('favorite_id')->from('user_favorites')->where(['user_id' => $currentUser]);
            $usersAll->andWhere(['IN', 'id_user', $filters]);
        }

        // Если фильтр используется, те не null, то перезаписываем $users
        if($filters) {
            $users = $usersAll->all();
        }

        return $this->render('index', ['users' => $users, 'rating' => $rating, 'usersForm' => $usersForm]);
    }
}