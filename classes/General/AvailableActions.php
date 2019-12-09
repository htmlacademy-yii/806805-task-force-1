<?php 

namespace TaskForce\General; 

use TaskForce\Exs\AvailableNamesException;

class AvailableActions
{
    /* КОНСТАНТЫ */

    // statuses of task
    const STATUS_NEW = 'Новое';
    const STATUS_CANCELED = 'Отменено';
    const STATUS_RUNNING = 'Выполняется';
    const STATUS_COMPLETED = 'Выполнено';
    const STATUS_FAILED = 'Провалено';

    // roles of user
    const ROLE_CONTRACTOR = 'Исполнитель';
    const ROLE_CUSTOMER = 'Заказчик';

    // action buttons of task,
    // как работает ::class - https://www.php.net/manual/ru/function.get-class.php
    const ACTION_ADD_TASK = AddTaskAction::class; // Новое: или new
    const ACTION_OFFER = OfferAction::class; // Откликнутся: или respond 
    const ACTION_FAILURE = FailureAction::class; // Отказатся: или refuse
    const ACTION_CANCEL = CancelAction::class; // Отменить
    const ACTION_SET_CONTRACTOR = SetContractorAction::class; // Выбрать исполнителя: или executor - добавляет id в БД
    const ACTION_COMPLETE = CompleteAction::class; // Завершить работу, для заказчика: или Finish
    const ACTION_ACCEPT = AcceptAction::class; // Принять для исполнителя, согласится, те начать работать
    const ACTION_SEND_MESS = SendMessAction::class; // Написать сообщение

    /* СВОЙСТВА */

    // Свойства стандартные
    public $taskId; // new
    public $taskName; // new
    public $currentStatus; // обязательное свойство
    public $endDate; // обязательное свойство
    public $customerId; // обязательное свойство
    public $contractorId; // обязательное свойство

    /* МЕТОДЫ МАГИЧЕСКИЕ */

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
    public function __construct (int $taskId, string $taskName, string $currentStatus, string $endDate, int $customerId, ?int $contractorId) {

        $this->taskId = $taskId;
        $this->taskName = $taskName;
        $this->currentStatus = $currentStatus;
        $this->endDate = $endDate;
        $this->customerId = $customerId;
        $this->contractorId = $contractorId;

        if(!in_array($currentStatus, $this->getStatuses())) {
            throw new AvailableNamesException('статус. см Конструктор');
        }

    }

    /* МЕТОДЫ ЦЕЛЕВЫЕ */

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

    /**
     * Получение всех экшенов
     * @return array
     */
    public function getActions(): array
    {
        return array(
            self::ACTION_ADD_TASK,
            self::ACTION_OFFER,
            self::ACTION_FAILURE,
            self::ACTION_CANCEL,
            self::ACTION_SET_CONTRACTOR,
            self::ACTION_COMPLETE,
            self::ACTION_ACCEPT,
            self::ACTION_SEND_MESS
        );
    }

    /**
     * Получение текущего статуса задачи
     * @return string
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

/**
 * Получение список всех ролей
 * @return string
 * 
 */
    public function getRoles(): array 
    {
        return array(
            self::ROLE_CONTRACTOR,
            self::ROLE_CUSTOMER
        );
    }

/**
 * Проверяем userId на совпадение с id Пользователя/Заказчика из БД
 * 
 * @return ?string
 * 
 */
public function checkRoleInTask(?int $userId): ?string {
    
    if ($userId === $this->contractorId) {
        return self::ROLE_CONTRACTOR;
    } 
    elseif ($userId === $this->customerId) {
        return self::ROLE_CUSTOMER;
    } 

    return null; // Если Исполнитель не выбран
}

    /**
     * Получение следующего статуса
     * @param $action
     * @return string
     */
    public function getNextStatus(string $action): string
    {
        if (!in_array($action, $this->getActions())) {
            throw new AvailableNamesException('действие. см Показать следующий статус');
        }
        switch ($action) {
            case self::ACTION_ADD_TASK:
                return $this->currentStatus = self::STATUS_NEW;
                break;
            case self::ACTION_SET_CONTRACTOR: // добавляет исполнителя в БД
            case self::ACTION_ACCEPT: // принять для исполнителя - начать работать, согласится 
                return $this->currentStatus = self::STATUS_RUNNING;
                break;
            case self::ACTION_CANCEL:
                return $this->currentStatus = self::STATUS_CANCELED;
                break;
            case self::ACTION_FAILURE:
                return $this->currentStatus = self::STATUS_FAILED;
                break;
            case self::ACTION_COMPLETE: // приянть работу, для заказчика
                return $this->currentStatus = self::STATUS_COMPLETED;
                break;
            default:
                return $this->currentStatus; // нет перехода - оставить текущий стаутус
        }
    }

    /**
     * Получение всех доступных действий исходя из роли пользователя
     * @param $userId
     * @return array
     */
    public function getAvailableActions(string $currentStatus, string $roleInTask): array
    {
        if(!in_array($roleInTask, $this->getRoles())) {
            throw new AvailableNamesException('роль пользователя. см Доступные действия');
        }

        if(!in_array($this->currentStatus, $this->getStatuses())) {
            throw new AvailableNamesException('статус. см Доступные действия');
        }

        if ($roleInTask === self::ROLE_CUSTOMER) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return [self::ACTION_CANCEL, self::ACTION_SET_CONTRACTOR];
                case self::STATUS_RUNNING:
                    return [self::ACTION_COMPLETE, self::ACTION_SEND_MESS];
            }
        } elseif ($roleInTask === self::ROLE_CONTRACTOR) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return [self::ACTION_OFFER];
                case self::STATUS_RUNNING:
                    return [self::ACTION_ACCEPT, self::ACTION_FAILURE, self::ACTION_SEND_MESS];
            }
        } 
        /* #пример_1
        elseif ($roleInTask === NULL) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return [self::ACTION_OFFER];
            }
        }
        */
        // ???ACTION_ACCEPT должно быть при STATUS_RUNNING, в предыдушем методе getNextStatus указывается при STATUS_RUNNING, но после ее нужно не показывать?
        // ???ACTION_OFFER - откликнутся не должно быть у задания у которого пользователь уже является Исполнителем, $roleInTask === NULL (как в #пример_1)
        // ???ACTION_ADD_TASK - его не нужно указывать в данном методе, тк он не связан с просматриеваемым Заданием, а связан с $_POST и ролью внешней
        return [];
    }

}
