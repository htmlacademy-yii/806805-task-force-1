<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Просмотр задания (view.html)';
?>

<!-- контент view.html основная секция -->

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <img src="./img/man-hat.png" width="120" height="120" alt="Аватар пользователя">
                <div class="content-view__headline">
                <h1>Мамедов Кумар</h1>
                    <p>Россия, Санкт-Петербург, 30 лет</p>
                <div class="profile-mini__name five-stars__rate">
                    <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                    <b>4.25</b>
                </div>
                <b class="done-task">Выполнил 5 заказов</b><b class="done-review">Получил 6 отзывов</b>
                </div>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span>Был на сайте 25 минут назад</span>
                    <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p>Внезапно, ключевые особенности структуры проекта неоднозначны и будут подвергнуты целой серии
            независимых исследований. Следует отметить, что высококачественный прототип будущего проекта, в
            своём классическом представлении, допускает внедрение своевременного выполнения сверхзадачи.
            Кстати, некоторые особенности внутренней политики будут функционально разнесены на
            независимые элементы.</p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <a href="#" class="link-regular">Ремонт</a>
                    <a href="#" class="link-regular">Курьер</a>
                    <a href="#" class="link-regular">Оператор ПК</a>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#">8 (555) 172 83 69</a>
                    <a class="user__card-link--email link-regular" href="#">Kumarm@mail.ru</a>
                    <a class="user__card-link--skype link-regular" href="#">Kumarm</a>
                </div>
                </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <a href="#"><img src="./img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="./img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="./img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
                </div>
        </div>
    </div>
    <div class="content-view__feedback">
        <h2>Отзывы<span>(2)</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">
            <div class="feedback-card__reviews">
                <p class="link-task link">Задание <a href="#" class="link-regular">«Выгулять моего боевого петуха»</a></p>
                <div class="card__review">
                    <a href="#"><img src="./img/man-glasses.jpg" width="55" height="54"></a>
                    <div class="feedback-card__reviews-content">
                        <p class="link-name link"><a href="#" class="link-regular">Астахов Павел</a></p>
                        <p class="review-text">
                            Кумар сделал всё в лучшем виде.  Буду обращаться к нему в будущем, если
                            возникнет такая необходимость!
                        </p>
                    </div>
                    <div class="card__review-rate">
                        <p class="five-rate big-rate">5<span></span></p>
                    </div>
                </div>
            </div>
                <div class="feedback-card__reviews">
                    <p class="link-task link">Задание <a href="#" class="link-regular">«Повесить полочку»</a></p>
                    <div class="card__review">
                        <a href="#"><img src="./img/woman-glasses.jpg" width="55" height="54"></a>
                        <div class="feedback-card__reviews-content">
                            <p class="link-name link"><a href="#" class="link-regular">Морозова Евгения</a></p>
                            <p class="review-text">
                                Кумар приехал позже, чем общал и не привез с собой всех
                                инстументов. В итоге пришлось еще ходить в строительный магазин.
                            </p>
                        </div>
                        <div class="card__review-rate">
                            <p class="three-rate big-rate">3<span></span></p>
                        </div>
                    </div>
                </div>
        </div>
    </div>
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
