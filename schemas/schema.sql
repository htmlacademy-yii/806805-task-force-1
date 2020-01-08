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
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `city`        VARCHAR(128) NOT NULL,
    `latitude`    VARCHAR(128) NOT NULL,
    `longitude`   VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

/*
 * статусы. 
 *
 * symbol - используется для имени иконки статуса
 */
CREATE TABLE IF NOT EXISTS task_statuses
(
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`    VARCHAR(32) NOT NULL UNIQUE,
    `name`      VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

/* 
 * действия над заданием. 
 */
CREATE TABLE IF NOT EXISTS task_actions
(
    `id`        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`    VARCHAR(32) NOT NULL UNIQUE,
    `name`      VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

/* 
 * роли пользователей. 
 */
CREATE TABLE IF NOT EXISTS user_roles
(
    `id`        TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`    VARCHAR(32) NOT NULL UNIQUE,
    `name`      VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

/* #1
 * категории.
 * 
 * symbol - по нему ищем в заданном каталоге иконку каталога и также используем в формах
 */
 CREATE TABLE IF NOT EXISTS categories
(
    `id`          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `symbol`      VARCHAR(32) NOT NULL UNIQUE,
    `name`        VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id)
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
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
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
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES user_roles(id),
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

/* #2.1
 * портфолио или фото работ.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS user_portfolio_images
(
    `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `image`   VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


/* #2.2
 * специализация в категориях для исполнителя.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS user_specializations (
	`id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id`     INT UNSIGNED NOT NULL,
	`category_id` TINYINT UNSIGNED,
	PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

/* #2.3
 * уведомления для пользователей в профиле, их виды или названия.
 * 
 */
CREATE TABLE IF NOT EXISTS user_notifications
(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`symbol`          VARCHAR(32) NOT NULL UNIQUE,
    `name`            VARCHAR(32) NOT NULL UNIQUE,
    PRIMARY KEY (id)
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
    `id`              INT NOT NULL AUTO_INCREMENT,
	`user_id`         INT UNSIGNED NOT NULL,
    `notification_id` INT UNSIGNED NOT NULL,
    `on_off`          BOOL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (notification_id) REFERENCES user_notifications(id)
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
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
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
    PRIMARY KEY (id),
    FOREIGN KEY (status_id) REFERENCES task_statuses(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (location_id) REFERENCES locations(id),
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

/* #3.1
 * вложения/файлы задания.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS task_files
(
    `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id` INT UNSIGNED NOT NULL,
    `file`    VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

/* #3.2
 * задания в статусе Выполняется - интересное решение.
 *
 * id не используется в других таблицах
 * действительно это должно быть удобнее, для проверок заданий в статусе Выполняется
 * `id_contractor` INT NOT NULL - перенесено из таблицы task, чтобы искать только по заданиям со статусом Выполняется
 * `id_contractor` ссылается на таблицу а не константы из класса ссылается на таблицу users
 */
CREATE TABLE IF NOT EXISTS tasks_running
(
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_run_id`       INT UNSIGNED NOT NULL,
    `contractor_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (task_run_id) REFERENCES tasks(id),
    FOREIGN KEY (contractor_id) REFERENCES users(id)
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
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       INT UNSIGNED NOT NULL,
    `user_rated_id` INT UNSIGNED NOT NULL,
    `task_id`       INT UNSIGNED NOT NULL,
    `desk`          TEXT,
    `point`         TINYINT UNSIGNED NOT NULL,
    `add_time`      DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (user_rated_id) REFERENCES users(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

/* #5
 * отклики на задание - предложения от исполнителей.
 *
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS offers
(
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id`       INT UNSIGNED NOT NULL,
    `contractor_id` INT UNSIGNED NOT NULL,
    `desk`          TEXT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (contractor_id) REFERENCES users(id)
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
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`          INT UNSIGNED NOT NULL,
    `favorite_id` INT UNSIGNED NOT NULL,
    `on_off`           BOOL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (favorite_id) REFERENCES users(id)
);

/* #7
 * чат/сообщения между заказчиком и исполнителем задания.
 *
 * id_task - везде написал а здесь пропустил.
 * id не используется в других таблицах
 */
CREATE TABLE IF NOT EXISTS messages
(
    `id`           INT NOT NULL AUTO_INCREMENT,
    `task_id`       INT UNSIGNED NOT NULL,
    `sender_id`    INT UNSIGNED NOT NULL,
    `recipient_id` INT UNSIGNED NOT NULL,
    `mess`         TEXT NOT NULL,
    `add_time`     DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (recipient_id) REFERENCES users(id)
);


