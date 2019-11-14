<?php 

/* СУЩНОСТИ */
// user: id, name, id_role, id_category_I, id_category_II, id_category_III.
// task: id, name, id_customer, id_contractor, id_status, end_life, desc.

/* КЛАСС 
Цель класса/объекта:
- Константы STATUSES, ROLES, BUTTONS
- 1. Метод - показать список кнопок-действий под заданием 
- 2. Метод - определить следующий статус после нажатия кнопки-действия
*/
class Task{ // имя сменено с TaskProcess

    //#1 statuses of task
    // взято сразу снизу из private static $statuses и оно также закоментировано теперь
    const STATUS_NEW = 'Новое';
    const STATUS_CANCELED = 'Отменено';
    const STATUS_RUNNING = 'Выполняется';
    const STATUS_COMPLETED = 'Выполнено';
    const STATUS_FAILED = 'Провалено';

    //#2 roles of user
    // также как и для #1
    const ROLE_CONTRACTOR = 'Исполнитель';
    const ROLE_CUSTOMER = 'Заказчик';

    //#3 action buttons of task
    // также как и для #1 и #2
    const ACTION_ADD_TASK = 'Добавить задание'; // new
    const ACTION_OFFER = 'Откликнуться';
    const ACTION_FAILURE = 'Отказаться';
    const ACTION_CANCEL = 'Отменить';
    const ACTION_SET_CONTRACTOR = 'Выбрать исполнителя';
    const ACTION_COMPLETE = 'Завершить'; // работу, для заказчика
    const ACTION_ACCEPT = 'Принять'; // работу, для исполнителя
    const ACTION_MESS = 'Написать сообщение';

    // перенесено вверх, сразу же в статусы
//    private static $statuses =
//    [
//        self::STATUS_NEW => ['id' => self::STATUS_NEW, 'name' => 'Новое'],
//        self::STATUS_CANCELED => ['id' => self::STATUS_CANCELED, 'name' => 'Отменено'],
//        self::STATUS_RUNNING => ['id' => self::STATUS_RUNNING, 'name' => 'Выполняется'],
//        self::STATUS_COMPLETED => ['id' => self::STATUS_COMPLETED, 'name' => 'Выполнено'],
//        self::STATUS_FAILED => ['id' => self::STATUS_FAILED, 'name' => 'Провалено']
//    ];

//    private static $roles =
//    [
//        self::ROLE_CONTRACTOR => ['id' => self::ROLE_CONTRACTOR, 'name' => 'Исполнитель'],
//        self::ROLE_CUSTOMER => ['id' => self::ROLE_CUSTOMER, 'name' => 'Заказчик']
//    ];

//    public static $actions =
//    [
//        self::ACTION_OFFER =>
//        [
//            'id' => self::ACTION_OFFER,
//            'name' => 'Откликнуться'
//        ],
//        self::ACTION_FAILURE =>
//        [
//            'id' => self::ACTION_FAILURE,
//            'name' => 'Отказаться'
//        ],
//        self::ACTION_CANCEL =>
//        [
//            'id' => self::ACTION_CANCEL,
//            'name' => 'Отменить'
//        ],
//        self::ACTION_COMPLETE =>
//        [
//            'id' => self::ACTION_COMPLETE,
//            'name' => 'Завершить'
//        ],
//        self::ACTION_ACCEPT =>
//        [
//            'id' => self::ACTION_ACCEPT,
//            'name' => 'Принять'
//        ],
//        self::ACTION_MESS =>
//        [
//            'id' => self::ACTION_MESS,
//            'name' => 'Написать сообщение'
//        ]
//    ];

    /* СВОЙСТВА */

    //#8 Свойства стандартные
    // все свойства переведены в camelCase, как я советовал в предыдущем ревью
    public $taskId; // new
    public $taskName; // new
    public $currentStatus; // обязательное свойство
    public $endDate; // обязательное свойство
    public $customerId; // обязательное свойство
    public $contractorId; // обязательное свойство

    //#4 Дополнительные (не обязательные поля) параметы для задания
    // const FACT_END_LIFE = 'END_LIFE'; - т.к. это поле для задания, объявлять его нужно как свойство, а не константу

    //#5 Переходы статуса по действию
    static private $status_changers = 
    [
        self::STATUS_NEW => 
        [
            self::ACTION_ACCEPT => self::STATUS_RUNNING,
            self::ACTION_CANCEL => self::STATUS_CANCELED
        ],
        self::STATUS_RUNNING => 
        [
            self::ACTION_FAILURE => self::STATUS_FAILED,
            self::ACTION_COMPLETE => self::STATUS_COMPLETED
        ]
    ];

    //#6 Переходы статуса автоматически по событию
//    static private $status_changers_by_facts =
//    [
//        self::STATUS_NEW =>
//        [
//            self::FACT_END_LIFE => self::STATUS_CANCELED // ? Автоматически отменено. Если время задания закончилось, и задание новое, значит не был принято ни одного предложения
//        ],
//        self::STATUS_RUNNING =>
//        [
//            self::FACT_END_LIFE => self::STATUS_CANCELED // ??  Автоматически отменено. Или автоматически провалено self::STATUS_FAILED те не выполнено в срок
//        ]
//    ];

    //#7 Переходы статуса - права пользователя
//    static private $status_changers_rules =
//    [
//        self::STATUS_NEW =>
//        [
//            self::ROLE_CUSTOMER => [self::ACTION_ACCEPT, self::ACTION_CANCEL],
//            self::ROLE_CONTRACTOR => [self::ACTION_OFFER]
//        ],
//        self::STATUS_RUNNING =>
//        [
//            self::ROLE_CUSTOMER => [self::ACTION_COMPLETE],
//            self::ROLE_CONTRACTOR => [self::ACTION_FAILURE]
//        ]
//    ];

    /* МЕТОДЫ МАГИЧЕСКИЕ */

    //#9 Конструктор - Слушать базовые данные страницы.
    /**
     * Task constructor.
     * @param $taskId
     * @param $taskName
     * @param $currentStatus
     * @param $endDate
     * @param $customerId
     * @param $contractorId
     */
    public function __construct ($taskId, $taskName, $currentStatus, $endDate, $customerId, $contractorId) {

        //$this->id_status = $task['id_status'];
        //date_default_timezone_set("Europe/Moscow");
        //$this->is_end_life = time() > strtotime($task['end_life']) ? self::FACT_END_LIFE : false;
        //$this->id_customer = $task['id_customer'] === $user['id'] ? $task['id_customer'] : null;
        //$this->id_contractor = $task['id_contractor'] === $user['id'] ? $task['id_contractor'] : null;

        // в конструкторе TimeZone не должна быть
        // конструктору логику доверять не будем
        // никаких решение в конструкторе не принимаем

        // конструктор делая малую часть работы, а именно входные параметры сохраняет в свойствах
        $this->taskId = $taskId;
        $this->taskName = $taskName;
        $this->currentStatus = $currentStatus;
        $this->endDate = $endDate;
        $this->customerId = $customerId;
        $this->contractorId = $contractorId;
    }

    /* МЕТОДЫ ЦЕЛЕВЫЕ */

    // # new 1
    /**
     * Получение статусов простым обращением к ним
     * @return array
     */
    public function getStatuses(): array
    {
        return array(
            self::STATUS_NEW,
            self::STATUS_CANCELED,
            self::STATUS_COMPLETED,
            self::STATUS_RUNNING,
            self::STATUS_FAILED
        );
    }

    // # new 2
    /**
     * Получение всех экшенов
     * @return array
     */
    public function getActions(): array
    {
        return array(
            self::ACTION_ACCEPT,
            self::ACTION_ADD_TASK,
            self::ACTION_CANCEL,
            self::ACTION_COMPLETE,
            self::ACTION_FAILURE,
            self::ACTION_OFFER,
            self::ACTION_SET_CONTRACTOR,
            self::ACTION_MESS
        );
    }

    // # new 3

    /**
     * Получение текущего статуса задачи
     * @return string
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    /**
     * Получение следующего статуса
     * @param $action
     * @return string
     */
    public function getNextStatus($action): string
    {
        if (!in_array($action, $this->getActions())) {
            return 'Ошибка';
        }
        switch ($action) {
            case self::ACTION_ADD_TASK:
                return $this->currentStatus = self::STATUS_NEW;
                break;
            case self::ACTION_SET_CONTRACTOR:
                return $this->currentStatus = self::STATUS_RUNNING;
                break;
            case self::ACTION_CANCEL:
                return $this->currentStatus = self::STATUS_CANCELED;
                break;
            case self::ACTION_FAILURE:
                return $this->currentStatus = self::STATUS_FAILED;
                break;
            case self::ACTION_COMPLETE: // приянть работу, для заказчика
            case self::ACTION_ACCEPT: // принять работу для выполнения, для исполнителя
                return $this->currentStatus = self::STATUS_COMPLETED;
                break;
            default:
                return $this->currentStatus = self::STATUS_NEW;
        }
    }

    /**
     * Получение всех доступных действий исходя из роли пользователя
     * @param $userId
     * @return array
     */
    public function getAvailableActions($userId): array
    {
        $currentStatus = $this->getCurrentStatus();
        if ($userId === $this->customerId) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return [self::ACTION_CANCEL, self::ACTION_SET_CONTRACTOR];
                case self::STATUS_RUNNING:
                    return [self::ACTION_COMPLETE, self::ACTION_MESS];
            }
        } elseif ($userId === $this->contractorId) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return [self::ACTION_OFFER, self::ACTION_MESS];
                case self::STATUS_RUNNING:
                    return [self::ACTION_FAILURE, self::ACTION_ACCEPT];
            }
        }
        return [];
    }


    // следующие 2 метода оказались слишком громоздкими
    //#10 Метод - определить следующий статус по событию - если не false в дальнейшем напишем функцию обновить статус в таблице
//    public function show_next_status_by_life() {
//
//        if($this->is_end_life && array_key_exists($this->id_status, self::$status_changers_by_facts)) {
//
//            $next_status = self::$status_changers_by_facts[$this->id_status][$this->is_end_life] ?? null;
//            return $next_status;
//        }
//
//        return null;
//    }

    //#11 Метод-цель определить следующий статус после нажатия кнопки-действия. 
//    public function show_next_status($current_action = null) {
//
//        $right_actions = []; // Разрешенные действия для текущего пользователя
//
//        // Находим массив разрешенных действий в зависимости от статуса и роли
//        if($this->id_contractor || $this->id_customer) {
//
//            $index_role = $this->id_contractor ? self::ROLE_CONTRACTOR : self::ROLE_CUSTOMER;
//
//            if(array_key_exists($this->id_status, self::$status_changers_rules)) {
//                $right_actions = self::$status_changers_rules[$this->id_status][$index_role] ?? [];
//            }
//        }
//
//        // Проверяем что текущее действие разрешено для пользователя
//        $is_right_actions = is_numeric(array_search($current_action, $right_actions));
//
//        // Проверяем что такое дейсвие меняет статус
//        if($is_right_actions && array_key_exists($this->id_status, self::$status_changers)) {
//
//            foreach (self::$status_changers[$this->id_status] as $id_action => $next_status) {
//                if($id_action === $current_action) {
//                    return $next_status;
//                }
//            }
//        }
//
//        return null;
//    }


    // #12 и #13 являеются лишними в классе, ибо показ будет осуществляться в представлении,
    // а в классе требуется лишь получение, например getStatuses(), getActions
    //#12 Метод-цель Список кнопок-действий
//    public function show_actions() {
//        $actions = array_keys(self::$actions);
//        return implode(", ", $actions);
//    }
    //#13 Метод-цель Список статусов
//    public function show_statuses() {
//        $statuses = array_keys(self::$statuses);
//        return implode(", ", $statuses);
//    }

}