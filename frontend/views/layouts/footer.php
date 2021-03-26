<?php

use yii\helpers\Url;

?>

<footer class="page-footer">
    <div class="main-container page-footer__container">
        <div class="page-footer__info">
            <p class="page-footer__info-copyright">
                © 2019, ООО «ТаскФорс»
                Все права защищены
            </p>
            <p class="page-footer__info-use">
                «TaskForce» — это сервис для поиска исполнителей на разовые задачи.
                mail@taskforce.com
            </p>
        </div>
        <div class="page-footer__links">
            <ul class="links__list">
                <li class="links__item">
                    <a href="<?=Url::to(['/tasks'])?>">Задания</a>
                </li>
                <li class="links__item">
                    <?php $ID = Yii::$app->user->getId();?>
                    <a href="#">Мой профиль</a>
                </li>
                <li class="links__item">
                    <a href="<?=Url::to(['/users'])?>">Исполнители</a>
                </li>
                <li class="links__item">
                    <a href="<?=Url::to(['/signup'])?>">Регистрация</a>
                </li>
                <li class="links__item">
                    <a href="#">Создать задание</a>
                </li>
                <li class="links__item">
                    <a href="#">Справка</a>
                </li>
            </ul>
        </div>
        <div class="page-footer__copyright">
            <a href="https://htmlacademy.ru">
                <img class="copyright-logo"
                        src="/img/academy-logo.png"
                        width="185" height="63"
                        alt="Логотип HTML Academy">
            </a>
        </div>
    </div>
</footer>