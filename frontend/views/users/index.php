<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
                <a href="<?=Url::to(['view', 'ID' => $user->user_id])?>"><img src="/<?=$user->avatar_addr ?: \Yii::$app->params['defaultAvatarAddr']?>" width="65" height="65"></a>
                <span><?=$user->tasks_count?> заданий</span>
                <span><?=$user->feedbacks_count?> отзывов</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name"><a href="<?=Url::to(['view', 'ID' => $user->user_id])?>" class="link-regular"><?=$user->full_name?> (ID: <?=$user->user_id?>)</a></p>
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
                <?php 
                echo $form->field($usersForm, 'categories', ['template' => "{input}"])
                    ->checkboxList(
                        $usersForm->getAttributeItems('categories'), 
                        [
                            'tag' => false, 
                            'item' => function ($index, $label, $name, $checked, $value) {
                                ++$index;

                                $field = Html::checkbox($name, $checked, $options = [
                                    'id' => $index,
                                    'class' => 'visually-hidden checkbox__input',
                                    'value' => $value, 
                                ]);
                                $field .= Html::label($label, $for = $index, $options = null);

                                return $field;
                            },
                        ]
                    );
                ?>
            </fieldset>
            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <!-- Поле Сейчас свободен. Тип чекбокс, по умолчанию нет -->
                <?php
                echo $form->field($usersForm, 'isAvailable')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false 
                    );
                ?>
                <!-- Поле Сейчас онлайн. Тип чекбокс -->
                <?php
                echo $form->field($usersForm, 'isOnLine')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>

                <!-- Поле Есть отзывы. Тип чекбокс -->
                <?php
                echo $form->field($usersForm, 'isFeedbacks')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>

                <!-- Поле В избранном. Тип чекбокс -->
                <?php
                echo $form->field($usersForm, 'isFavorite')
                    ->checkbox(
                        ['class' => 'visually-hidden checkbox__input', 'uncheck' => null],
                        $enclosedByLabel = false
                    );
                ?>
            </fieldset>
            <!-- ПОЛЕ Поиск по имени. тип search-->
            <?php
            echo $form->field($usersForm, 'search', ['template' => "{label}\n{input}"])
                ->label($label = null, ['class' => 'search-task__name'])
                ->input('search', ['class' => 'input-middle input', 'placeholder' => '']); 
            ?>
            <?=Html::submitButton('Искать', ['class' => 'button'])?>
        <?php ActiveForm::end()?>
        <!-- Форма окончание -->
    </div>
</section>
