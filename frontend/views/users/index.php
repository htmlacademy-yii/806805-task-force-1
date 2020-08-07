<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
// use yii\widgets\ActiveField; // Не используем

$this->title = 'Исполнители (верстка Users.html)';
?>

<!-- Верстка Users.html контент -->

<section class="user__search">

    <!-- контент секция-сортировка: рейтинг, число заказов, популярность -->
    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <?php foreach ($sortings as $sorting): ?>
            <li class="user__search-item <?=Yii::$app->request->get('sorting') === $sorting['label'] ? 'user__search-item--current' : ''?>">
                <a href="<?=Url::to(['', 'sorting' => $sorting['label']])?>" class="link-regular"><?=$sorting['title']?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>

    <!-- begin single user -->
    <?php foreach ($users as $user): ?>
    <div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">
                <a href="<?=Url::to(['users/view', 'ID' => $user->user_id])?>"><img src="/<?=$user->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="65" height="65"></a>
                <span><?=$user->tasks_count?> заданий</span>
                <span><?=$user->feedbacks_count?> отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="<?=Url::to(['users/view', 'ID' => $user->user_id])?>" class="link-regular"><?=$user->full_name?> (ID: <?=$user->user_id?>)</a></p>
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
                <p class="user__search-content">
                    <?=$user->desc_text?>
                </p>
            </div>
            <span class="new-task__time">Был на сайте<br><?=Yii::$app->formatter->asRelativeTime(strtotime($user->activity_time))?></span>
        </div>
        <div class="link-specialization user__search-link--bottom">
            <!-- итерация категории пользователя связи -->
            <?php foreach ($user->userSpecializations as $category): ?>
            <a href="#" class="link-regular"><?=$category->title?></a>
            <?php endforeach;?>

        </div>
    </div>
    <?php endforeach;?>
    <!-- end single user -->

    <!-- Верстка pagination -->

    <?php if (count($users) >= 5): ?>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
    <?php endif;?>

</section>

<section  class="search-task">
    <div class="search-task__wrapper">
        <!-- Форма начало -->
        <!-- <form class="search-task__form" name="users" method="post" action="#"> -->
        <?php 
        $form = ActiveForm::begin([
            'id' => 'users-form',
            'options' => ['class' => 'search-task__form'],
            'fieldConfig' => [
                'template' => "{input}\n{label}", 
                'options' => ['tag' => false],
            ],
        ]); 
        ?>
            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <!-- ПОЛЕ категории. Тип список чекбоксов -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="101" type="checkbox" name="" value="" checked disabled>
                <label for="101">Курьерские услуги </label>
                <input class="visually-hidden checkbox__input" id="102" type="checkbox" name="" value="" checked>
                <label  for="102">Грузоперевозки </label>
                <input class="visually-hidden checkbox__input" id="103" type="checkbox" name="" value="">
                <label  for="103">Переводы </label>
                <input class="visually-hidden checkbox__input" id="104" type="checkbox" name="" value="">
                <label  for="104">Строительство и ремонт </label>
                <input class="visually-hidden checkbox__input" id="105" type="checkbox" name="" value="">
                <label  for="105">Выгул животных </label> -->

                <?php /* ПОЛЕ Категории с помощью ActiveForm */
                echo $form->field($usersForm, 'categories', [
                        'template' => "{input}", 
                    ])
                    ->checkboxList($usersForm->getAttributeItems('categories'), [
                        'tag' => false, // Отклчает создание общего контейнера div
                        'item' => function ($index, $label, $name, $checked, $value) {
                            ++$index;

                            return
                            Html::checkbox($name, $checked, $options = [
                                'id' => $index,
                                'class' => 'visually-hidden checkbox__input',
                                'value' => $value, // !!!отличается от других, если не задавать то создается автоматически у всех всегда =1. !!!В данном случае необходимо использовать $value, для сохранения сортировки, так как значения хранятся в модели.
                                // 'label' => $label, // В виде обертки не подходит
                            ])
                            . // Конкатенация
                            Html::label($label, $for = $index, $options = null);
                        },
                    ]);
                ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <!-- Поле Сейчас свободен. Тип чекбокс, по умолчанию нет -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="106" type="checkbox" name="" value="" disabled>
                <label for="106">Сейчас свободен</label> -->
                <?php
                echo $form->field($usersForm, 'isAvailable')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false 
                    );
                ?>
                <!-- Поле Сейчас онлайн. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="107" type="checkbox" name="" value="" checked>
                <label for="107">Сейчас онлайн</label> -->
                <?php
                echo $form->field($usersForm, 'isOnLine')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>

                <!-- Поле Есть отзывы. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="108" type="checkbox" name="" value="" checked>
                <label for="108">Есть отзывы</label> -->
                <?php
                echo $form->field($usersForm, 'isFeedbacks')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>

                <!-- Поле В избранном. Тип чекбокс -->
                <!-- верстка -->
                <!-- <input class="visually-hidden checkbox__input" id="109" type="checkbox" name="" value="" checked>
                <label for="109">В избранном</label> -->
                <?php
                echo $form->field($usersForm, 'isFavorite')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>
            </fieldset>
            <!-- ПОЛЕ Поиск по имени. тип search-->
            <!-- Верстка -->
            <!-- <label class="search-task__name" for="110">Поиск по имени</label>
            <input class="input-middle input" id="110" type="search" name="q" placeholder=""> -->
            <?php
            echo $form->field($usersForm, 'search', ['template' => "{label}\n{input}"])
                ->label($label = null, ['class' => 'search-task__name'])
                ->input('search', ['class' => 'input-middle input', 'placeholder' => '']); 
            ?>

            <!-- <button class="button" type="submit">Искать</button> -->
            <?=Html::submitButton('Искать', ['class' => 'button'])?>

        <?php ActiveForm::end()?>
        <!-- Форма окончание -->
        <!-- </form> -->
    </div>
</section>
