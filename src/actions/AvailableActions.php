<?php

namespace ownsite\actions;

use ownsite\exs\AvailableNamesException;

class AvailableActions
{
    // statuses of task
    const STATUS_NEW = 'Новое';
        // после ACTION_ADD_TASK
    const STATUS_CANCELED = 'Отменено';
        // после ACTION_CANCEL && !==STATUS_RUNNING
    const STATUS_RUNNING = 'В работе';
        // после ACTION_ACCEPT
    const STATUS_COMPLETED = 'Завершено'; 
        // После ACTION_COMPLETE && STATUS_RUNNING
    const STATUS_FAILED = 'Провалено';
        // после (ACTION_FAILURE && STATUS_RUNNING) || (ACTION_COMPLETE && галочка *ВОЗНИКЛИ ПРОБЛЕМЫ)
        // ???Просроченные — все задания со статусом «На исполнении», у которых истёк срок выполнения;

    // roles of user
    const ROLE_CONTRACTOR = 'Исполнитель';
    const ROLE_CUSTOMER = 'Заказчик';

    // actions, buttons of task
    const ACTION_ADD_TASK = AddTaskAction::class;
        // ДОБАВИТЬ ЗАДАНИЕ (заказчик) >> STATUS_NEW 
        // сохранить новое задание в таблице заданий, а прикреплённые файлы перенести в публичную директорию и сохранить ссылки на них
    const ACTION_OFFER = OfferAction::class;
        // ОТКЛИКНУТЬСЯ (исполнитель) <> статус не меняется && ACTION_NOTICE
        // Проверить, что роль пользователя «Исполнитель» и он еще не откликался на это задание.
        // Добавить отклик в таблицу откликов с привязкой к заданию.
    const ACTION_FAILURE = FailureAction::class;
        // ОТКАЗАТЬСЯ (исполнитель) >> STATUS_FAILED && ACTION_NOTICE
        // счетчик заданий провалено +1
    const ACTION_CANCEL = CancelAction::class;
        // ОТМЕНИТЬ (заказчик) >> STATUS_CANCELED
        // Отмена заданий со статусом «На исполнении» невозможна.
    const ACTION_ACCEPT = AcceptAction::class;
        // ПРИНЯТЬ, ПОДТВЕРДИТЬ (заказчик) >> STATUS_RUNNING && ACTION_NOTICE
        // Назначить автора отклика исполнителем этого задания.
    const ACTION_DENIED = deniedAction::class;
        // ОТКАЗАТЬ (заказчик) <> статус не меняется
        // помечает отклик как отклонённый и больше не показывает кнопки доступных действий для этого отклика.
    const ACTION_COMPLETE = CompleteAction::class; 
        // ЗАВЕРШИТЬ ЗАДАНИЕ (заказчик) >> STATUS_COMPLETED (если STATUS_RUNNING и галочка *ДА)  || >> STATUS_FAILED (если галочка *ВОЗНИКЛИ ПРОБЛЕМЫ)
        // + ОТКЛИК (заказчик) && ACTION_NOTICE
        // в отклике будет переключатель выполненности задания («Да» или «Возникли проблемы»), текст комментария (при наличии) и значение оценки (если выбрано)
    const ACTION_SEND_MESS = SendMessAction::class;
        // ОТПРАВИТЬ СООБЩЕНИЕ в чате (заказчик или исполнитель) <> статус не меняется 
        // форма из блока «Переписка» на странице задания
    const ACTION_NOTICE = NoticeAction::class;
        // ОТПРАВКА УВЕДОМЛЕНИЯ <> статус не меняется
        // побочное, не учитываетя для ролей и статусов, вызывается совместно с другими действиями 
        // от действий ACTION_OFFER, ACTION_FAILURE, ACTION_COMPLETE, ACTION_ACCEPT, ACTION_SEND_MESS
        // Сформировать сообщение эл.почты, где тема - название события, а текст - все необходимые детали по вашему усмотрению (ссылка на задание ...).
        // у получателя в настройках включено получение уведомлений
        // Добавить новое событие в «Ленту событий» получателя.
    
    /**
     * @property string $currentStatus
     * название рус.
     * @property int $customerId
     * @property int $contractorId
     */
    public $currentStatus;
    public $customerId;
    public $contractorId;

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
            'status_running' => self::STATUS_RUNNING,
            'status_comleted' => self::STATUS_COMPLETED,
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
            'action_accept' => self::ACTION_ACCEPT,
            'action_denied' => self::ACTION_DENIED,
            'action_complete' => self::ACTION_COMPLETE,
            'action_send_mess' => self::ACTION_SEND_MESS,
            'action_notice' => self::ACTION_NOTICE,
        ];
    }

    /**
     * карта ролей
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
     * Проверяем id пользователя на совпадение с id Исполнителя/Заказчика
     */
    public function getRoleOfUser(int $userID): ?string
    {
        switch ($userID) {
            case $this->contractorId:
                return self::ROLE_CONTRACTOR;
                break;
            case $this->customerId:
                return self::ROLE_CUSTOMER;
        }

        return null; // Пользователь без роли 
    }

    /**
     * возвращать имя статуса(сов), в который перейдёт задание после выполнения конкретного действия.
     */
    public function getNextStatus(string $action): mixed
    {
        if (!in_array($action, $this->getActions())) {
            throw new AvailableNamesException('действие не существует');
        }

        switch ($action) {
            case self::ACTION_ADD_TASK:
                return $this->currentStatus = self::STATUS_NEW;
                break;
            case self::ACTION_CANCEL:
                return $this->currentStatus = self::STATUS_CANCELED;
                break;
            case self::ACTION_ACCEPT:
                return $this->currentStatus = self::STATUS_RUNNING;
                break;
            case self::ACTION_COMPLETE:
                return $this->currentStatus = [self::STATUS_FAILED, self::STATUS_COMPLETED];
                break;
            case self::ACTION_FAILURE:
                return $this->currentStatus = self::STATUS_FAILED;
        }

        return $this->currentStatus; // в остальных случаях статус не меняется
    }

    /**
     * определить список доступных действий для указанного (текущего) статуса
     * какие действия доступны каждой роли
     */
    public function getAvailableActions(?string $currentStatus, string $roleOfUser): array
    {
        if (!in_array($roleOfUser, $this->getRoles())) {
            throw new AvailableNamesException('роль пользователя не существует');
        }

        if (!in_array($this->currentStatus, $this->getStatuses())) {
            throw new AvailableNamesException('статус задания не существует');
        }


        if ($roleOfUser === self::ROLE_CUSTOMER) {
            switch ($currentStatus) {
                case null:
                    return [self::ACTION_ADD_TASK];
                    break;
                case self::STATUS_NEW:
                    return [self::ACTION_CANCEL, self::ACTION_ACCEPT, self::ACTION_DENIED];
                    break;
                case self::STATUS_RUNNING:
                    return [self::ACTION_COMPLETE, self::ACTION_SEND_MESS];
            }
        } elseif ($roleOfUser === self::ROLE_CONTRACTOR) {
            switch ($currentStatus) {
                case self::STATUS_NEW:
                    return [self::ACTION_OFFER];
                    break;
                case self::STATUS_RUNNING:
                    return [self::ACTION_FAILURE, self::ACTION_SEND_MESS];
            }
        }

        return [];
    }
}
