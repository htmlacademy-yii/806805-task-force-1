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
class _task_process {

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

    const ACT_MESS = 'MESS';
    const ACT_OFFER = 'OFFER';
    const ACT_FAILURE = 'FAILURE';
    const ACT_CANCEL = 'CANCEL';
    const ACT_COMPLETE = 'COMPLETE';
    const ACT_ACCEPT = 'ACCEPT';

    private static $acts = 
    [
        self::ACT_OFFER => 
        [
            'id' => self::ACT_OFFER, 
            'name' => 'Откликнуться'
        ],
        self::ACT_FAILURE => 
        [
            'id' => self::ACT_FAILURE, 
            'name' => 'Отказаться'
        ],
        self::ACT_CANCEL => 
        [
            'id' => self::ACT_CANCEL, 
            'name' => 'Отменить'
        ],
        self::ACT_COMPLETE => 
        [
            'id' => self::ACT_COMPLETE, 
            'name' => 'Завершить'
        ],
        self::ACT_ACCEPT => 
        [
            'id' => self::ACT_ACCEPT, 
            'name' => 'Принять'
        ],
        self::ACT_MESS => 
        [
            'id' => self::ACT_MESS, 
            'name' => 'Написать сообщение'
        ]
    ];
    
    //#5 Задание просрочено
    const END_LIFE = 'END_LIFE';

    //#6 Переходы статуса
    static private $status_changers = 
    [
        self::STATUS_NEW => 
        [
            self::ACT_ACCEPT => self::STATUS_RUNNING,
            self::ACT_CANCEL => self::STATUS_CANCELED,
            self::END_LIFE => self::STATUS_CANCELED
        ],
        self::STATUS_RUNNING => 
        [
            self::ACT_FAILURE => self::STATUS_FAILED,
            self::ACT_COMPLETE => self::STATUS_COMPLETED
        ]
    ];

    /* СВОЙСТВА */

    //#7 Свойства стандартные
    public $id_status; // значение зависит от $task
    public $is_end_life; // значение зависит от $task
    public $id_customer; // значение зависит от $task
    public $id_contractor; // значение зависит от $task 

    //#8 Свойство-цель
    private $task_buttons = [];

    /* МЕТОДЫ МАГИЧЕСКИЕ */

    //#9 Конструктор - Слушать базовые данные страницы.
    public function __construct ($task, $user) {

        $this->id_status = $task['id_status'];

        date_default_timezone_set("Europe/Moscow");
        $this->is_end_life = time() > strtotime($task['end_life']) ? self::END_LIFE : false;

        $this->id_customer = $user['id_role'] === self::ROLE_CUSTOMER ? $user['id'] : false;
        $this->id_contractor = $user['id_role'] === self::ROLE_CUSTOMER ? $user['id'] : false;

    }

    /* МЕТОДЫ ЦЕЛЕВЫЕ */

    //#10 Проверка просроченности перед показом задания
    public function check_is_end_life() {

        $next_status = '';
        if($this->is_end_life && $this->id_status === self::STATUS_NEW) {
            $next_status = self::$status_changers[self::STATUS_NEW][self::END_LIFE];
            // Перезаписываем в таблицу $task новый статус $next_status используя общую функцию
            return true; 
        }

        return false;
    }

    //#11 Метод-цель определить следующий статус после нажатия кнопки-действия. 
    public function show_next_status($current_act = null) {


        foreach (self::$status_changers[$this->id_status] as $id_act => $next_status) {
            if($id_act === $current_act) {

                return $next_status;
            }
        }

        return null;
    }

    //#12 Метод-цель Список кнопок-действий
    public function show_acts() {
        $acts = array_keys(self::$acts);
        return implode(", ", $acts);
    }

    //#13 Метод-цель Список статусов
    public function show_statuses() {
        $statuses = array_keys(self::$statuses);
        return implode(", ", $statuses);
    }

}