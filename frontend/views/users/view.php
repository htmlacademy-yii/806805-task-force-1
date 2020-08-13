<?php
use yii\helpers\Url;

$this->title = 'Просмотр задания (view.html)';
?>

<!-- контент view.html основная секция -->
<section class="content-view">
    <!-- карточка пользователя -->
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="/<?=$user->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="120" height="120" alt="Аватар пользователя">
                <div class="content-view__headline">
                <h1><?=$user->full_name?></h1>
                    <p>Россия, Санкт-Петербург, <?=strstr(Yii::$app->formatter->asRelativeTime(strtotime($user->birth_date)), ' назад', true)?></p>
                <div class="profile-mini__name five-stars__rate">
                    <!-- Рейтинг -->
                    <?php $avg_point = $user->avg_point ?? 0;?>
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
                <b class="done-task">Выполнил <?= $user->tasks_count ?> заказ</b><b class="done-review">Получил <?= $user->feedbacks_count ?> отзыв</b>
                </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте <?=Yii::$app->formatter->asRelativeTime(strtotime($user->activity_time))?></span>
                    <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $user->desc_text?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                
                <div class="link-specialization">
                    <!-- итерация категории пользователя по связи -->
                    <?php foreach ($user->userSpecializations as $category): ?>
                    <a href="#" class="link-regular"><?=$category->title?></a>
                    <?php endforeach;?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?= $user->phone?></a>
                    <a class="user__card-link--email link-regular" href="#"><?= $user->email?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?= $user->skype?></a>
                </div>
                </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <!-- итерация фотографий портфолио по связи -->
                <?php foreach ($user->userPortfolioImages as $image): ?>
                    <a href="/img/portfolios/<?=$image->image_addr?>"><img src="<?=$image->image_addr?>" width="85" height="86" alt="<?=$image->title?>"></a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    <!-- /карточка пользователя -->
    <!-- Блок отзывы -->
    <?php if ($feedbacks_count = $user->feedbacks_count):?>
    <div class="content-view__feedback">
        <h2>Отзывы <span>(<?= $feedbacks_count ?>)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <!-- отзывы итерация -->
            <?php foreach ($user->feedbacks as $feedback):?>
            <div class="feedback-card__reviews">
                <p class="link-task link">Задание <a href="<?=Url::to(['tasks/view', 'ID' => $feedback->task_id])?>" class="link-regular">«<?= $feedback->task->title?>»</a></p>
                <div class="card__review">
                    <a href="#"><img src="/<?=$feedback->author->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="55" height="54"></a>
                    <div class="feedback-card__reviews-content">
                        <p class="link-name link"><a href="<?=Url::to(['users/view', 'ID' => $feedback->author_id])?>" class="link-regular"><?= $feedback->author->full_name?></a></p>
                        <p class="review-text">
                            <?= $feedback->desc_text?>
                        </p>
                    </div>
                    <div class="card__review-rate">
                        <p class="five-rate big-rate"><?= ceil($user->avg_point) . '<span></span>' ?? '';?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <!-- /отзывы итерация -->
        </div>
    </div>
    <?php endif; ?>
    <!-- Блок отзывы -->
</section>
<!-- /контент view.html основная секция -->
<!-- контент view.html правая секция -->
<section class="connect-desk">
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
</section>
<!-- /контент view.html правая секция -->
