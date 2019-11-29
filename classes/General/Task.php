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
namespace TaskForce\General; 

class Task{ 

    /* КОНСТАНТЫ */

    // #1 statuses of task
    const STATUS_NEW = 'Новое';
    const STATUS_CANCELED = 'Отменено';
    const STATUS_RUNNING = 'Выполняется';
    const STATUS_COMPLETED = 'Выполнено';
    const STATUS_FAILED = 'Провалено';

    // #2 roles of user
    const ROLE_CONTRACTOR = 'Исполнитель';
    const ROLE_CUSTOMER = 'Заказчик';

    // #3 action buttons of task
    const ACTION_ADD_TASK = 'Добавить задание'; // new
    const ACTION_OFFER = 'Откликнуться';
    const ACTION_FAILURE = 'Отказаться';
    const ACTION_CANCEL = 'Отменить';
    const ACTION_SET_CONTRACTOR = 'Выбрать исполнителя';
    const ACTION_COMPLETE = 'Завершить'; // работу, для заказчика
    const ACTION_ACCEPT = 'Принять'; // работу, для исполнителя
    const ACTION_MESS = 'Написать сообщение';

    /* СВОЙСТВА */

    // #4 Свойства стандартные
    public $taskId; // new
    public $taskName; // new
    public $currentStatus; // обязательное свойство
    public $endDate; // обязательное свойство
    public $customerId; // обязательное свойство
    public $contractorId; // обязательное свойство

    /* МЕТОДЫ МАГИЧЕСКИЕ */

    // #5 
    /**
     * Конструктор - Слушать базовые данные страницы.
     * Task constructor.
     * @param $taskId
     * @param $taskName
     * @param $currentStatus
     * @param $endDate
     * @param $customerId
     * @param $contractorId
     */
    public function __construct ($taskId, $taskName, $currentStatus, $endDate, $customerId, $contractorId) {

        $this->taskId = $taskId;
        $this->taskName = $taskName;
        $this->currentStatus = $currentStatus;
        $this->endDate = $endDate;
        $this->customerId = $customerId;
        $this->contractorId = $contractorId;
    }

    /* МЕТОДЫ ЦЕЛЕВЫЕ */

    // #6
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

    // #7
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

    // #8
    /**
     * Получение текущего статуса задачи
     * @return string
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    // #9
    /**
     * Получение следующего статуса
     * @param $action
     * @return string
     */
    public function getNextStatus($action, $userId): string
    {
        if (!in_array($action, $this->getAvailableActions($userId))) {
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
                return $this->currentStatus; // нет перехода - оставить текущий стаутус
        }
    }

    // #10
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

}