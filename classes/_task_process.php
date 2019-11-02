<?php 

/* СУЩНОСТИ */
// user: id, name, id_role, id_category_I, id_category_II, id_category_III.
// task: id, name, id_customer, id_contractor, id_status, endtime, desc.

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

    const STATUSES = 
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

    const ROLES = 
    [
        self::ROLE_CONTRACTOR => ['id' => self::ROLE_CONTRACTOR, 'name' => 'Исполнитель'],
        self::ROLE_CUSTOMER => ['id' => self::ROLE_CUSTOMER, 'name' => 'Заказчик']
    ];

    //#3 action buttons of task 

    const BUTT_MESS = 'MESS';
    const BUTT_OFFER = 'OFFER';
    const BUTT_FAILURE = 'FAILURE';
    const BUTT_CANCEL = 'CANCEL';
    const BUTT_COMPLETE = 'COMPLETE';
    const BUTT_ACCEPT = 'ACCEPT';

    const BUTTONS = 
    [
        self::BUTT_OFFER => 
        [
            'id' => self::BUTT_OFFER, 
            'name' => 'Откликнуться', 
            'id_role' => self::ROLE_CONTRACTOR, 
            'id_status' => self::STATUS_NEW,
            'id_next_status' => null,
        ],
        self::BUTT_FAILURE => 
        [
            'id' => self::BUTT_FAILURE, 
            'name' => 'Отказаться', 
            'id_role' => self::ROLE_CONTRACTOR, 
            'id_status' => self::STATUS_RUNNING,
            'id_next_status' => self::STATUS_FAILED,
        ],
        self::BUTT_CANCEL => 
        [
            'id' => self::BUTT_CANCEL, 
            'name' => 'Отменить', 
            'id_role' => self::ROLE_CUSTOMER, 
            'id_status' => self::STATUS_RUNNING,
            'id_next_status' => self::STATUS_CANCELED
        ],
        self::BUTT_COMPLETE => 
        [
            'id' => self::BUTT_COMPLETE, 
            'name' => 'Завершить', 
            'id_role' => self::ROLE_CUSTOMER, 
            'id_status' => self::STATUS_RUNNING,
            'id_next_status' => self::STATUS_COMPLETED
        ],
        self::BUTT_ACCEPT => 
        [
            'id' => self::BUTT_ACCEPT, 
            'name' => 'Принять', 
            'id_role' => self::ROLE_CUSTOMER, 
            'id_status' => self::STATUS_NEW,
            'id_next_status' => self::STATUS_RUNNING
        ],
        self::BUTT_MESS => 
        [
            'id' => self::BUTT_MESS, 
            'name' => 'Написать сообщение', 
            'id_role' => null, 
            'id_status' => self::STATUS_RUNNING,
            'id_next_status' => null,
        ]
    ];
    
    /* СВОЙСТВА */

    //#4 Свойства стандартные
    public $id_task_status; // значение зависит от $task
    public $dt_end; // значение зависит от $task, формат '2019-11-29 12:00:00'
    public $id_user_role; // значение зависит от $user
    public $my_role; // по умолчанию false, true если пользователь исполнитель или владелец задания  

    //#5 Свойство-цель
    private $task_buttons = [];

    /* МЕТОДЫ МАГИЧЕСКИЕ */

    //#6 Конструктор - Слушать базовые данные страницы.
    public function __construct ($task, $user) {

        $this->id_task_status = $task['id_status'];

        date_default_timezone_set("Europe/Moscow");
        $this->dt_end = strtotime($task['dt_end']);

        $this->id_user_role = $user['id_role'];

        $this->my_role = false;

        if($user['id'] === $task['id_customer']) {
            $this->my_role = true;
        } 
        elseif ($user['id'] === $task['id_contractor']) {
            $this->my_role = true;
        } 

    }

    /* МЕТОДЫ СТАНДАРТНЫЕ */

    //#7 Метод-цель определить следующий статус после нажатия кнопки-действия. Аргумент $id_task_butt - id кнопки окна/формы.
    public function show_next_task_status($id_task_butt = null) {

        if(time() > $this->dt_end && $this->id_task_status === self::STATUS_NEW) {
            return self::STATUS_CANCELED;
        }

        foreach (self::BUTTONS as $id_button => $button) {
            if($id_button === $id_task_butt) {

                return $button['id_next_status'];
            }
        }

        return null;
    }

    //#8 Метод - Список доступных кнопок-действий в зависимости от статуса и роли
    public function list_task_buttons() {

        $task_buttons = [];
        foreach (self::BUTTONS as $id_button => $button) {

            if($button['id_status'] === $this->id_task_status) {
                
                // Кнопка подходит к обеим ролям если id_role === null
                $button['id_role'] = $button['id_role'] ?? $this->id_user_role;

                //если исполнитель или владелец являются действующими для задания
                if($this->my_role) {
                    $task_buttons[$button['id_role']][] = $id_button;
                } 
                //иначе исполнитель, но не действующий 
                elseif($this->id_user_role === self::ROLE_CONTRACTOR && $this->id_task_status !== self::STATUS_RUNNING) {
                    $task_buttons[$button['id_role']][] = $id_button;
                } 
            }
        }

        $this->task_buttons = $task_buttons[$this->id_user_role] ?? [];

        return !empty($task_buttons);
    }

    /* МЕТОДЫ ДОПОЛНИТЕЛЬНЫЕ */

    // Читаем список кнопок-действий

    public function read_task_buttons() {
        return $this->task_buttons;
    }

}