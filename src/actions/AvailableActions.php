<?php

namespace ownsite\actions;

use ownsite\exs\AvailableNamesException;

class AvailableActions
{
    // statuses of task
    const STATUS_NEW = 'Новое';
    const STATUS_CANCELED = 'Отменено';
    const STATUS_RUNNING = 'Выполняется';
    const STATUS_COMPLETED = 'Выполнено';
    const STATUS_FAILED = 'Провалено';

    // roles of user
    const ROLE_CONTRACTOR = 'Исполнитель';
    const ROLE_CUSTOMER = 'Заказчик';

    // action buttons of task
    const ACTION_ADD_TASK = AddTaskAction::class; // Добавить - STATUS_NEW
    const ACTION_ACCEPT = AcceptAction::class; // принять, начать работу (исполнитель) - STATUS_RUNNING
    const ACTION_CANCEL = CancelAction::class; // Отменить (заказчик) - STATUS_CANCELED
    const ACTION_FAILURE = FailureAction::class; // Отказатся (исполнитель) - STATUS_FAILED
    const ACTION_COMPLETE = CompleteAction::class; // Завершить работу (заказчик) - STATUS_COMPLETED
    const ACTION_OFFER = OfferAction::class; // Откликнутся - STATUS_NEW
    const ACTION_SET_CONTRACTOR = SetContractorAction::class; // Выбрать исполнителя - STATUS_NEW
    const ACTION_SEND_MESS = SendMessAction::class; // Написать сообщение (заказчик или исполнитель)

    // Свойства базовые
    public $currentStatus;
    public $customerId;
    public $contractorId;

    /**
     * Конструктор - Слушать базовые данные страницы.
     */
    public function __construct(?string $currentStatus, ?int $customerId, ?int $contractorId)
    {
        $this->currentStatus = $currentStatus;
        $this->customerId = $customerId;
        $this->contractorId = $contractorId;

        if (!in_array($currentStatus, $this->getStatuses())) {
            throw new AvailableNamesException('статус не существует');
        }
    }

    /**
     * карта статусов
     */
    public function getStatuses(): array
    {
        return [
            'status_new' => self::STATUS_NEW,
            'status_canceled' => self::STATUS_CANCELED,
            'status_comleted' => self::STATUS_COMPLETED,
            'status_running' => self::STATUS_RUNNING,
            'status_failed' => self::STATUS_FAILED,
        ];
    }

    /**
     * карта действий
     */
    public function getActions(): array
    {
        return [
            'action_add_task' => self::ACTION_ADD_TASK,
            'action_offer' => self::ACTION_OFFER,
            'action_failure' => self::ACTION_FAILURE,
            'action_cancel' => self::ACTION_CANCEL,
            'action_set_contractor' => self::ACTION_SET_CONTRACTOR,
            'action_complete' => self::ACTION_COMPLETE,
            'action_accept' => self::ACTION_ACCEPT,
            'action_send_mess' => self::ACTION_SEND_MESS,
        ];
    }

    /**
     * Получение список всех ролей
     */
    public function getRoles(): array
    {
        return [
            'role_contractor' => self::ROLE_CONTRACTOR,
            'role_customer' => self::ROLE_CUSTOMER,
        ];
    }

    /**
     * Получение текущего статуса задачи
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    /**
     * Определяем роль пользователя
     * Проверяем userId на совпадение с id Пользователя/Заказчика
     */
    public function checkRoleInTask(int $userId): ?string
    {

        if ($userId === $this->contractorId) {
            return self::ROLE_CONTRACTOR;
        } elseif ($userId === $this->customerId) {
            return self::ROLE_CUSTOMER;
        }

        return null; // Пользователь гость 
    }

    /**
     * возвращать имя статуса, в который перейдёт задание после выполнения конкретного действия.
     * 
     * const ACTION_ADD_TASK = AddTaskAction::class; // Добавить - STATUS_NEW
     * const ACTION_ACCEPT = AcceptAction::class; // принять, начать работу (исполнитель) - STATUS_RUNNING
     * const ACTION_CANCEL = CancelAction::class; // Отменить (заказчик) - STATUS_CANCELED
     * const ACTION_FAILURE = FailureAction::class; // Отказатся (исполнитель) - STATUS_FAILED
     * const ACTION_COMPLETE = CompleteAction::class; // Завершить работу (заказчик) - STATUS_COMPLETED
     * const ACTION_OFFER = OfferAction::class; // Откликнутся - STATUS_NEW
     * const ACTION_SET_CONTRACTOR = SetContractorAction::class; // Выбрать исполнителя - STATUS_NEW
     * const ACTION_SEND_MESS = SendMessAction::class; // Написать сообщение (заказчик или исполнитель)
     */
    public function getNextStatus(string $action): string
    {
        if (!in_array($action, $this->getActions())) {
            throw new AvailableNamesException('действие не существует');
        }

        switch ($action) {
            case self::ACTION_ADD_TASK:
                return $this->currentStatus = self::STATUS_NEW;
            case self::ACTION_ACCEPT:
                return $this->currentStatus = self::STATUS_RUNNING;
            case self::ACTION_CANCEL:
                return $this->currentStatus = self::STATUS_CANCELED;
            case self::ACTION_FAILURE:
                return $this->currentStatus = self::STATUS_FAILED;
            case self::ACTION_COMPLETE:
                return $this->currentStatus = self::STATUS_COMPLETED;
            case self::ACTION_OFFER:
                break;
            case self::ACTION_SET_CONTRACTOR:
                break;
            case self::ACTION_SEND_MESS:
                break;
        }

        return $this->currentStatus; // статус не меняется
    }

    /**
     * определить список доступных действий для указанного (текущего) статуса
     * какие действия доступны каждой роли
     */
    public function getAvailableActions(string $currentStatus, string $roleInTask): array
    {
        if (!in_array($roleInTask, $this->getRoles())) {
            throw new AvailableNamesException('роль пользователя не существует');
        }

        if (!in_array($this->currentStatus, $this->getStatuses())) {
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
