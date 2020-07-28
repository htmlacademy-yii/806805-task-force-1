<?php

namespace frontend\models;

use frontend\models\db\UsersMain;
use function common\functions\basic\transform\prepareLogicSearch;
use yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * @property array $users
 * @property array $userIDs
 * @property array $ratings
 * @property array $deals
 *
 */
class UserView
{
    public $users;
    public $userIDs;
    public $ratings;
    public $deals;

    public function __construct(int $userID)
    {
        $this->userIDs = [$userID];
    }

    /**
     * ID исполнителя
     */
    public function getUserID(): ?int
    {
        return array_shift($this->userIDs);
    }

    /**
     * Исполнитель главный запрос с информацией и связями жадной загрузки.
     */
    public function getContractor(array $addons = []): ?object
    {
        $defaultSettings = ['asQuery']; // значения по умолчанию (всегда включено)
        $contractor = UsersMain::getcontractorsMain('*', $defaultSettings, $this->userIDs);

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

        return $contractor->one();
    }
}
