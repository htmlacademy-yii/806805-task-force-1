<?php

namespace frontend\models;

use frontend\models\db\UsersMain;
use yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * @property array $userID
 * @property array $user
 *
 */
class UserView
{
    public $userID;
    public $user;

    public function __construct(int $userID)
    {
        $this->userID = $userID;
    }

    /**
     * Исполнитель главный запрос с информацией и связями жадной загрузки.
     */
    public function getContractor(array $addons = []): ?object
    {
        $defaultSettings = ['asQuery']; // значения по умолчанию (всегда включено)
        $userIDs[] = $this->userID;
        $contractor = UsersMain::getContractorsMain('*', $defaultSettings, $userIDs);

        // Общее дополнение запроса
        $contractor
            ->joinWith([
                'taskRunnings tr1',
                'feedbacks f1',
                'userSpecializations usc1',
                'userPortfolioImages upi1',
            ])
            ->indexBy('user_id'); // Ключ массива (атрибут объекта, не поле)

        // Дополнение запроса или дополнительные данные (addon)
        $defaultAddons = ['addRatings', 'addDeals']; // значения по умолчанию (всегда включено)
        $addons = array_merge($defaultAddons, $addons);

        if ($addons) {
            $contractor = UsersMain::addContractorAddons($contractor, $addons);
        }

        return $this->user = $contractor->one();
    }
}
