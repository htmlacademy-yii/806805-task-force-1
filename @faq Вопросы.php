*** Журнал ***
1. По заданию папка фикстур расположена common/fixtures. Это удобно, как делают обычно на практике?
2. По умолчанию в шаблоне создаются стили от контроллера по умолчанию, стили повлияли на шрифт и некоторые классы верстки.
3. Как переназначить контроллер по умолчанию и контролировать загрузку нужных стилей. 
4. Как работают альясы @app/views/layouts/main.php
5. $form = ActiveForm::begin() ... echo $form->field($taskForm, 'search') по умолчанию создаются новые стили и новые теги 
6. $_POST в классах не работают, вместо него метод yii->$app->request()
7. ???Как задать значение по умолчанию в модели или контроллере, скорее всего в модели удобнее. Перевая отправка без значений по умолчанию
8. В чем разница между activeCheckbox() и Checkbox(). Первый может использоватся без ->field, тк содержит параметры model, attribute 
9. 

*** Задачи ***
- добавить рыбы
- переписать комменты в стандарт на усмотрение
- Осталось поле поиска по имени и названию
- Осталось сортировка по дате 
- Переделываю на метод rules значения полей по умолчанию https://yiiframework.com.ua/ru/doc/guide/2/tutorial-core-validators/
- Настрой PSR или набей руку. PSR: https://www.php-fig.org/psr/psr-2/ https://svyatoslav.biz/misc/psr_translation/#_PSR-1



*** Сайты ***
Семантическое Версионирование 2.0.0 https://semver.org/lang/ru/
https://proglib.io/


*** Коды ***

/* Фильтр Сейчас свободен. true = сейчас свободен */
        /* Вариант 1 - В таблице task_runnings есть занания которые исполняются или провалены */
        // Запрос id заданий которые выполняются status_id = 3 из tasks
        // Запрос выбираем уникальные id заданий из task_runnings и последние (макс) id_task_running (id строк), при этом исполнители (последними кто работает с проектом) не группируются. + подзапрос runTasks
        // Запрос id исполнителей - из таблицы task_runnings которые имеют максимальный id-строк, те являются последними кто работает с проектом
        // Добавление условия в запрос - исключаем пользователи являются последними кто работает с заданиями и задания исполняются

        if($usersForm->isAvailable) {
            $runTasks = (new Query)->select('id_task')->from('tasks')
                ->where(['status_id' => '3'])
            ;
            // SELECT MAX(id_task_running), `contractor_id` FROM `task_runnings` WHERE task_running_id IN (8) GROUP BY `task_running_id`; 
            $filtersSub = (new Query)->select(['MAX(id_task_running)'])->from('task_runnings')
                ->where(['IN', 'task_running_id', $runTasks])
                ->groupBy('task_running_id')
            ;
            // select contractor_id from task_runnings where id_task_running IN (SELECT MAX(id_task_running) FROM `task_runnings` GROUP BY `task_running_id`) AND task_running_id NOT IN (8); // 8 - пример или подзапрос
            $filters = (new Query)->select(['contractor_id'])->from('task_runnings')
                ->where(['IN', 'id_task_running', $filtersSub]);

            $usersAll->andWhere(['NOT IN', 'id_user', $filters]);
        }

    