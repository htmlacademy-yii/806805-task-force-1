<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Просмотр задания (view.html)';
?>

<!-- контент view.html основная секция -->

<section class="content-view">
    <div class="content-view__card">

        <!-- задание -->
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?=$task->title?></h1>
                    <span><?='Статус: ' . $task->status->title?>. Размещено в категории
                        <a href="<?=Url::to(['tasks/index', 'category' => $task->category_id])?>" class="link-regular"><?=$task->category->title?></a>
                        25 минут назад</span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price"><?=$task->price?><b> ₽</b></b>
                <div class="new-task__icon new-task__icon--<?=$task->category->label?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                <?=$task->desc_text?>
                </p>
            </div>
            <div class="content-view__attach">
                <!-- файлы итерация-->
                <h3 class="content-view__h3">Вложения</h3>
                <?php foreach ($task->taskFiles as $file): ?>
                <a href="/img/tasks/<?=$file->file_addr?>"><?=$file->file_addr?></a>
                <?php endforeach;?>
                <!-- /файлы итерация-->
            </div>
            <!-- адрес -->
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                            alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town"><?=$task->location->city?></span><br>
                        <span><?=$task->full_address?></span>
                        <p><?=$task->address_desc?></p>
                    </div>
                </div>
            </div>
            <!-- /адрес -->
        </div>
        <!-- /задание -->

        <div class="content-view__action-buttons">
                <button class=" button button__big-color response-button open-modal"
                        type="button" data-for="response-form">Откликнуться</button>
                <button class="button button__big-color refusal-button open-modal"
                        type="button" data-for="refuse-form">Отказаться</button>
            <button class="button button__big-color request-button open-modal"
                    type="button" data-for="complete-form">Завершить</button>
        </div>

    </div>
    <?php if (count($candidatesAndOffers)): ?>
    <div class="content-view__feedback">
        <h2>Отклики <span><?=count($candidatesAndOffers)?></span></h2>
        <div class="content-view__feedback-wrapper">

            <!-- Отклики (предложения) итерация -->
            <?php foreach ($candidatesAndOffers as $candidateAndOffer): ?>
            <?php list($candidate, $offer) = $candidateAndOffer; ?>
            <div class="content-view__feedback-card">
                <div class="feedback-card__top">
                    <a href="<?=Url::to(['users/view', 'ID' => $candidate->user_id])?>">
                        <img src="/<?=$candidate->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="55" height="55">
                    </a>
                    <div class="feedback-card__top--name">
                        <p><a href="<?=Url::to(['users/view', 'ID' => $candidate->user_id])?>" class="link-regular"><?=$candidate->full_name?></a></p>
                        <!-- Рейтинг -->
                        <?php $avg_point = $candidate->avg_point ?? 0;?>
                        
                        <!-- итерация желтой звездочки -->
                        <?php for ($i = 1; $i <= $avg_point; $i++): ?>
                            <span></span>
                        <?php endfor;?>

                        <!-- итерация белой звездочки -->
                        <?php for ($i = $avg_point; $i < 5; $i++): ?>
                            <span class="star-disabled"></span>
                        <?php endfor;?>

                        <!-- Средний балл рейтинг -->
                        <b><?=Yii::$app->formatter->asDecimal($avg_point, 2)?></b>
                        <!-- /Рейтинг -->
                    </div>
                    <span class="new-task__time"><?=Yii::$app->formatter->asRelativeTime(strtotime($offer->add_time))?></span>
                </div>
                <div class="feedback-card__content">
                    <p>
                        <?=$offer->desc_text?>
                    </p>
                    <span><?=$offer->price?> ₽</span>
                </div>
                <div class="feedback-card__actions">
                    <a class="button__small-color request-button button"
                            type="button">Подтвердить</a>
                    <a class="button__small-color refusal-button button" type="button">Отказать</a>
                </div>
            </div>
            <?php endforeach;?>
            <!-- /Отклики (предложения) итерация -->
        </div>
    </div>
    <?php endif; ?>
</section>
<!-- /контент view.html основная секция -->

<!-- контент view.html правая секция -->
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <?php if (USER_ID !== $task->customer_id OR $currentContractor === null): // переписки нет?>
        <!-- мини профиль заказчика -->
        <div class="profile-mini__wrapper">
            <h3>Заказчик (<?='id'. $customer->user_id ?>)</h3>
            <div class="profile-mini__top">
                <img src="/<?=$customer->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?=$customer->full_name?></p>
                </div>
            </div>
            <p class="info-customer"><span><?=count($customer->customerTasks)?> заданий</span><span class="last-">
                <?=strstr(
                    Yii::$app->formatter->asRelativeTime(strtotime($customer->reg_time))
                    , ' назад', true
                )?> на сайте</span></p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
        <!-- /мини профиль заказчика -->
        <?php else: ?>
        <!-- мини профиль исполнителя -->
        <div class="profile-mini__wrapper">
            <h3>Исполнитель (<?="id-" . $currentContractor->user_id?>)</h3>
            <div class="profile-mini__top">
                <img src="/<?=$currentContractor->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="62" height="62" alt="Аватар заказчика">
                <div class="profile-mini__name five-stars__rate">
                    <p><?=$currentContractor->full_name?></p>
                    <!-- Рейтинг -->
                    <?php $avg_point = $currentContractor->avg_point ?? 0;?>
                    
                    <!-- итерация желтой звездочки -->
                    <?php for ($i = 1; $i <= $avg_point; $i++): ?>
                        <span></span>
                    <?php endfor;?>

                    <!-- итерация белой звездочки -->
                    <?php for ($i = $avg_point; $i < 5; $i++): ?>
                        <span class="star-disabled"></span>
                    <?php endfor;?>

                    <!-- Средний балл рейтинг -->
                    <b><?=Yii::$app->formatter->asDecimal($avg_point, 2)?></b>
                    <!-- /Рейтинг -->
                </div>
            </div>
            <p class="info-customer"><span><?=$currentContractor->tasks_count?> заданий</span><span class="last-">
                <?=strstr(
                    Yii::$app->formatter->asRelativeTime(strtotime($customer->reg_time))
                    , ' назад', true
                )?> на сайте</span></p>
            <a href="#" class="link-regular">Смотреть профиль</a>
        </div>
        <!-- /мини профиль исполнителя -->
        <?php endif; ?>
    </div>

    <!-- Чат -->
    <?php if ($currentContractor !== null && (USER_ID === $currentContractor['user_id'] || USER_ID === $customer->user_id)): ?>
    <div class="connect-desk__chat">
        <h3>Переписка</h3>
        <div class="chat__overflow">
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:56</p>
                <p class="chat__message-text">Привет. Во сколько сможешь
                    приступить к работе?</p>
            </div>
            <div class="chat__message chat__message--in">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">На задание
                выделены всего сутки, так что через час</p>
            </div>
            <div class="chat__message chat__message--out">
                <p class="chat__message-time">10.05.2019, 14:57</p>
                <p class="chat__message-text">Хорошо. Думаю, мы справимся</p>
            </div>
        </div>
        <p class="chat__your-message">Ваше сообщение</p>
        <form class="chat__form">
            <textarea class="input textarea textarea-chat" rows="2" name="message-text" placeholder="Текст сообщения"></textarea>
            <button class="button chat__button" type="submit">Отправить</button>
        </form>
    </div>
    <?php endif; ?>
    <!-- /чат -->
</section>

<!-- /контент view.html правая секция -->
