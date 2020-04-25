/*
 * utf8 выдает предупреждение utf8mb3 и utf8mb4
 */
CREATE DATABASE IF NOT EXISTS task_force
    DEFAULT CHARACTER SET 'utf8mb4'
    DEFAULT COLLATE 'utf8mb4_general_ci';
    
USE task_force;


/* 
 * города и координаты.
 * 
 */
CREATE TABLE IF NOT EXISTS locations
(
    `id_location`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `city`        VARCHAR(128) NOT NULL,
    `latitude`    VARCHAR(128) NOT NULL,
    `longitude`   VARCHAR(128) NOT NULL,
    PRIMARY KEY (id_location)
);

/*
 * статусы. 
 *
 * symbol - используется для имени иконки статуса
 */
CREATE TABLE IF NOT EXISTS task_statuses
(
    `id_task_status`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`    VARCHAR(32) NOT NULL UNIQUE,
    `name`      VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id_task_status)
);

/* 
 * действия над заданием. 
 */
CREATE TABLE IF NOT EXISTS task_actions
(
    `id_task_action`        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`    VARCHAR(32) NOT NULL UNIQUE,
    `name`      VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id_task_action)
);

/* 
 * роли пользователей. 
 */
CREATE TABLE IF NOT EXISTS user_roles
(
    `id_user_role`        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`    VARCHAR(32) NOT NULL UNIQUE,
    `name`      VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id_user_role)
);

/* #1
 * категории.
 * 
 * symbol - по нему ищем в заданном каталоге иконку каталога и также используем в формах
 */
 CREATE TABLE IF NOT EXISTS categories
(
    `id_category`          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`      VARCHAR(32) NOT NULL UNIQUE,
    `name`        VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id_category)
);

/* #2
 * зарегистрированные пользователи.
 *
 * `skype``phone``portfolio` `other_contacts``birth_date`- проверить по вводиммым полям
 * `activity_dt` - думал что это делается как то через ссесию и класс
 * `user brief` - краткая информация о пользователе
 * ???`phone` - а почему не строка VARCHAR(10)
 * `portfolio` - описание о выполненных работах
 * !!!TINYINT(1) - значение в скобках выдает предупреждение 1681 Integer display width is deprecated and will be removed in a future release
 * TINYINT(1) - BIT, BOOL Являются синонимами
 * мне показалось `id_role` примет значение 1-2, чем is_executor 0-1
 */
CREATE TABLE IF NOT EXISTS users
(
    `id_user`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`role_id`        TINYINT UNSIGNED NOT NULL DEFAULT 1, 
	`location_id`    INT UNSIGNED NOT NULL,
    `name`           VARCHAR(128) NOT NULL,
    `avatar`         VARCHAR(255),
	`email`          VARCHAR(128) NOT NULL UNIQUE,
    `password`       VARCHAR(255) NOT NULL,
	`skype`          VARCHAR(128),
    `phone`          VARCHAR(11),
	`other_contacts` VARCHAR(255),
    `address`        VARCHAR(255),
	`about`          TEXT,
    `reg_time`       DATETIME NOT NULL,
	`birth_date`     DATE,
	`activity_time`  DATETIME NOT NULL,
    `hide_contacts`  BOOL DEFAULT 0,
    `hide_profile`   BOOL DEFAULT 0,
    PRIMARY KEY (id_user),
    FOREIGN KEY (role_id) REFERENCES user_roles(id_user_role),
    FOREIGN KEY (location_id) REFERENCES locations(id_location)
);

/* #2.1
 * портфолио или фото работ.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS user_portfolio_images
(
    `id_user_portfolio_image`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `image`   VARCHAR(255),
    PRIMARY KEY (id_user_portfolio_image),
    FOREIGN KEY (user_id) REFERENCES users(id_user)
);


/* #2.2
 * специализация в категориях для исполнителя.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS user_specializations (
	`id_user_specialization`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id`     INT UNSIGNED NOT NULL,
	`category_id` TINYINT UNSIGNED,
	PRIMARY KEY (id_user_specialization),
    FOREIGN KEY (user_id) REFERENCES users(id_user),
    FOREIGN KEY (category_id) REFERENCES categories(id_category)
);

/* #2.3
 * уведомления для пользователей в профиле, их виды или названия.
 * 
 */
CREATE TABLE IF NOT EXISTS user_notifications
(
    `id_user_notification` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`symbol`          VARCHAR(32) NOT NULL UNIQUE,
    `name`            VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id_user_notification)
);

/* #2.4
 * настройки уведомлений пользователя.
 *
 * id не используется в других таблицах
 * ??? `status` - можно без этого параметра? просто поставить 0 в id_notification или удалить всю строку. Тоже самое в #6 favorite_users
 * `status`on_off - BOOL DEFAULT 1 - тк при создании активируется
 */
CREATE TABLE IF NOT EXISTS user_notification_settings
(
    `id_user_notification_setting` INT NOT NULL AUTO_INCREMENT,
	`user_id`         INT UNSIGNED NOT NULL,
    `notification_id` INT UNSIGNED NOT NULL,
    `on_off`          BOOL DEFAULT 1,
    PRIMARY KEY (id_user_notification_setting),
    FOREIGN KEY (user_id) REFERENCES users(id_user),
    FOREIGN KEY (notification_id) REFERENCES user_notifications(id_user_notification)
);

/* #3
 * задания.
 *
 * is_remote - удаленное выполнение 0-1, используется в фильтрах
 * id_author заменен на id_customer ссылается на таблицу users
 * `files` VARCHAR(512) DEFAULT '', удалено тк добавлена отдельная таблица для файлов заданий
 * id_status принимаем как число id из еще одной таблицы а не констант из класса Task
 * ??? `id_contractor` - нужен ли в данной таблице
 * `id_contractor` не нужен в данной таблице, тк задания со статусом Выполняются вынесены в отдельную таблицу, где он и указывается
 */
CREATE TABLE IF NOT EXISTS tasks
(
    `id_task`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`status_id`     INT UNSIGNED NOT NULL,
    `category_id`   TINYINT UNSIGNED NOT NULL,
	`location_id`   INT UNSIGNED NOT NULL,
    `customer_id`   INT UNSIGNED NOT NULL,
    `name`          VARCHAR(128) NOT NULL,
    `description`   TEXT NOT NULL,
    `price`         INT UNSIGNED,
    `address`       VARCHAR(128),
    `latitude`      VARCHAR(128),
    `longitude`     VARCHAR(128),
    `add_time`      DATETIME NOT NULL,
    `end_date`      DATETIME,
	`is_remote`     BOOL DEFAULT 0,
    PRIMARY KEY (id_task),
    FOREIGN KEY (status_id) REFERENCES task_statuses(id_task_status),
    FOREIGN KEY (category_id) REFERENCES categories(id_category),
    FOREIGN KEY (location_id) REFERENCES locations(id_location),
    FOREIGN KEY (customer_id) REFERENCES users(id_user)
);

/* #3.1
 * вложения/файлы задания.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS task_files
(
    `id_task_file`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id` INT UNSIGNED NOT NULL,
    `file`    VARCHAR(255),
    PRIMARY KEY (id_task_file),
    FOREIGN KEY (task_id) REFERENCES tasks(id_task)
);

/* #3.2
 * задания в статусе Выполняется - интересное решение.
 *
 * id не используется в других таблицах
 * действительно это должно быть удобнее, для проверок заданий в статусе Выполняется
 * `id_contractor` INT NOT NULL - перенесено из таблицы task, чтобы искать только по заданиям со статусом Выполняется
 * `id_contractor` ссылается на таблицу а не константы из класса ссылается на таблицу users
 */
CREATE TABLE IF NOT EXISTS task_runnings
(
    `id_task_running`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_running_id`       INT UNSIGNED NOT NULL,
    `contractor_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (id_task_running),
    FOREIGN KEY (task_running_id) REFERENCES tasks(id_task),
    FOREIGN KEY (contractor_id) REFERENCES users(id_user)
);

/* #4
 * отзывы.
 *
 * `id_task` - добавлено
 * `point` - от 1 до 5
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS feedbacks
(
    `id_feedback`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       INT UNSIGNED NOT NULL,
    `user_rated_id` INT UNSIGNED NOT NULL,
    `task_id`       INT UNSIGNED NOT NULL,
    `desk`          TEXT,
    `point`         TINYINT UNSIGNED NOT NULL,
    `add_time`      DATETIME NOT NULL,
    PRIMARY KEY (id_feedback),
    FOREIGN KEY (user_id) REFERENCES users(id_user),
    FOREIGN KEY (user_rated_id) REFERENCES users(id_user),
    FOREIGN KEY (task_id) REFERENCES tasks(id_task)
);

/* #5
 * отклики на задание - предложения от исполнителей.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS offers
(
    `id_offer`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id`       INT UNSIGNED NOT NULL,
    `contractor_id` INT UNSIGNED NOT NULL,
    `desk`          TEXT NOT NULL,
    PRIMARY KEY (id_offer),
    FOREIGN KEY (task_id) REFERENCES tasks(id_task),
    FOREIGN KEY (contractor_id) REFERENCES users(id_user)
);

/* #6
 * избранные пользователи/закладка - добавить пользователя в избранные.
 *
 * id не используется в других таблицах
 * ??? `status` - можно без этого параметра? просто поставить 0 в id_user_favorite или удалить всю строку. Тоже самое в #2.3 user_notification_settings
 * `status`on_off - BOOL DEFAULT 1 - тк при создании активируется
 */
CREATE TABLE IF NOT EXISTS user_favorites
(
    `id_user_favorite`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`          INT UNSIGNED NOT NULL,
    `favorite_id` INT UNSIGNED NOT NULL,
    `on_off`           BOOL DEFAULT 1,
    PRIMARY KEY (id_user_favorite),
    FOREIGN KEY (user_id) REFERENCES users(id_user),
    FOREIGN KEY (favorite_id) REFERENCES users(id_user)
);

/* #7
 * чат/сообщения между заказчиком и исполнителем задания.
 *
 * id_task - везде написал а здесь пропустил.
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS messages
(
    `id_message`           INT NOT NULL AUTO_INCREMENT,
    `task_id`       INT UNSIGNED NOT NULL,
    `sender_id`    INT UNSIGNED NOT NULL,
    `recipient_id` INT UNSIGNED NOT NULL,
    `mess`         TEXT NOT NULL,
    `add_time`     DATETIME NOT NULL,
    PRIMARY KEY (id_message),
    FOREIGN KEY (task_id) REFERENCES tasks(id_task),
    FOREIGN KEY (sender_id) REFERENCES users(id_user),
    FOREIGN KEY (recipient_id) REFERENCES users(id_user)
);


