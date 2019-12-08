<?php 

namespace TaskForce\General; 

class AvailableActions extends Task
{ 
    /**
     * Получение всех доступных действий исходя из роли пользователя
     * @param $userId
     * @return array
     */
    public function getAvailableActions($userId): array
    {
        return parent::getAvailableActions($userId);
    }

}