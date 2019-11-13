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
class TaskProcess {

    //#1 statuses of task

    const STATUS_NEW = 'NEW';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_RUNNING = 'RUNNING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';

    private static $statuses = 
    [
        self::STATUS_NEW => ['id' => self::STATUS_NEW, 'name' => 'Новое'],
        self::STATUS_CANCELED => ['id' => self::STATUS_CANCELED, 'name' => 'Отменено'],
        self::STATUS_RUNNING => ['id' => self::STATUS_RUNNING, 'name' => 'Выполняется'],
        self::STATUS_COMPLETED => ['id' => self::STATUS_COMPLETED, 'name' => 'Выполнено'],
        self::STATUS_FAILED => ['id' => self::STATUS_FAILED, 'name' => 'Провалено']
    ];

    //#2 roles of user
    
    const ROLE_CONTRACTOR = 'CONTRACTOR';
    const ROLE_CUSTOMER = 'CUSTOMER';

    private static $roles = 
    [
        self::ROLE_CONTRACTOR => ['id' => self::ROLE_CONTRACTOR, 'name' => 'Исполнитель'],
        self::ROLE_CUSTOMER => ['id' => self::ROLE_CUSTOMER, 'name' => 'Заказчик']
    ];

    //#3 action buttons of task 

    const ACTION_MESS = 'MESS';
    const ACTION_OFFER = 'OFFER';
    const ACTION_FAILURE = 'FAILURE';
    const ACTION_CANCEL = 'CANCEL';
    const ACTION_COMPLETE = 'COMPLETE';
    const ACTION_ACCEPT = 'ACCEPT';

    public static $actions = 
    [
        self::ACTION_OFFER => 
        [
            'id' => self::ACTION_OFFER, 
            'name' => 'Откликнуться'
        ],
        self::ACTION_FAILURE => 
        [
            'id' => self::ACTION_FAILURE, 
            'name' => 'Отказаться'
        ],
        self::ACTION_CANCEL => 
        [
            'id' => self::ACTION_CANCEL, 
            'name' => 'Отменить'
        ],
        self::ACTION_COMPLETE => 
        [
            'id' => self::ACTION_COMPLETE, 
            'name' => 'Завершить'
        ],
        self::ACTION_ACCEPT => 
        [
            'id' => self::ACTION_ACCEPT, 
            'name' => 'Принять'
        ],
        self::ACTION_MESS => 
        [
            'id' => self::ACTION_MESS, 
            'name' => 'Написать сообщение'
        ]
    ];
    
    //#4 Дополнительные (не обязательные поля) параметы для задания
    const FACT_END_LIFE = 'END_LIFE';

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
    static private $status_changers_by_facts = 
    [
        self::STATUS_NEW => 
        [
            self::FACT_END_LIFE => self::STATUS_CANCELED // ? Автоматически отменено. Если время задания закончилось, и задание новое, значит не был принято ни одного предложения 
        ],
        self::STATUS_RUNNING => 
        [
            self::FACT_END_LIFE => self::STATUS_CANCELED // ??  Автоматически отменено. Или автоматически провалено self::STATUS_FAILED те не выполнено в срок
        ]
    ];

    //#7 Переходы статуса - права пользователя
    static private $status_changers_rules = 
    [
        self::STATUS_NEW => 
        [
            self::ROLE_CUSTOMER => [self::ACTION_ACCEPT, self::ACTION_CANCEL],
            self::ROLE_CONTRACTOR => [self::ACTION_OFFER]
        ],
        self::STATUS_RUNNING => 
        [
            self::ROLE_CUSTOMER => [self::ACTION_COMPLETE],
            self::ROLE_CONTRACTOR => [self::ACTION_FAILURE]
        ]
    ];

    /* СВОЙСТВА */

    //#8 Свойства стандартные
    public $id_status; // значение зависит от $task
    public $is_end_life; // значение зависит от $task
    public $id_customer; // значение зависит от $task и $user
    public $id_contractor; // значение зависит от $task и $user

    /* МЕТОДЫ МАГИЧЕСКИЕ */

    //#9 Конструктор - Слушать базовые данные страницы.
    public function __construct ($task, $user) {

        $this->id_status = $task['id_status'];

        date_default_timezone_set("Europe/Moscow");
        $this->is_end_life = time() > strtotime($task['end_life']) ? self::FACT_END_LIFE : false;

        $this->id_customer = $task['id_customer'] === $user['id'] ? $task['id_customer'] : null;
        $this->id_contractor = $task['id_contractor'] === $user['id'] ? $task['id_contractor'] : null;

    }

    /* МЕТОДЫ ЦЕЛЕВЫЕ */

    //#10 Метод - определить следующий статус по событию - если не false в дальнейшем напишем функцию обновить статус в таблице
    public function show_next_status_by_life() {

        if($this->is_end_life && array_key_exists($this->id_status, self::$status_changers_by_facts)) {

            $next_status = self::$status_changers_by_facts[$this->id_status][$this->is_end_life] ?? null;
            return $next_status; 
        }

        return null;
    }

    //#11 Метод-цель определить следующий статус после нажатия кнопки-действия. 
    public function show_next_status($current_action = null) {

        $right_actions = []; // Разрешенные действия для текущего пользователя

        // Находим массив разрешенных действий в зависимости от статуса и роли
        if($this->id_contractor || $this->id_customer) {

            $index_role = $this->id_contractor ? self::ROLE_CONTRACTOR : self::ROLE_CUSTOMER;

            if(array_key_exists($this->id_status, self::$status_changers_rules)) {
                $right_actions = self::$status_changers_rules[$this->id_status][$index_role] ?? [];
            }
        }

        // Проверяем что текущее действие разрешено для пользователя
        $is_right_actions = is_numeric(array_search($current_action, $right_actions));

        // Проверяем что такое дейсвие меняет статус
        if($is_right_actions && array_key_exists($this->id_status, self::$status_changers)) {

            foreach (self::$status_changers[$this->id_status] as $id_action => $next_status) {
                if($id_action === $current_action) {
                    return $next_status;
                }
            }
        }

        return null;
    }

    //#12 Метод-цель Список кнопок-действий
    public function show_actions() {
        $actions = array_keys(self::$actions);
        return implode(", ", $actions);
    }

    //#13 Метод-цель Список статусов
    public function show_statuses() {
        $statuses = array_keys(self::$statuses);
        return implode(", ", $statuses);
    }

}