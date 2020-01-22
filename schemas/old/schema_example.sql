CREATE DATABASE IF NOT EXISTS task_force
    DEFAULT CHARACTER SET 'utf8'
    DEFAULT COLLATE 'utf8_general_ci';

USE task_force;

/* 
 * категории.
 * 
 * все префиксы category_ можно убрать 
 */
CREATE TABLE IF NOT EXISTS categories
(
    category_id    TINYINT NOT NULL AUTO_INCREMENT,
    category_name  VARCHAR(128),
    PRIMARY KEY (category_id)
);

/* 
 * города.
 * 
 * все префиксы location_ можно убрать 
 */
CREATE TABLE IF NOT EXISTS locations
(
    location_id         INT NOT NULL AUTO_INCREMENT,
    location_city       VARCHAR(100) NOT NULL,
    location_latitude   INT NOT NULL,
    location_longitude  INT NOT NULL,
    PRIMARY KEY (location_id)
);


/* 
 * 
 * все префиксы user_ можно убрать 
 */
CREATE TABLE IF NOT EXISTS users
(
    user_id                INT NOT NULL AUTO_INCREMENT,
    user_name              VARCHAR(128) NOT NULL,
    user_reg_date          DATETIME     NOT NULL,
    user_birthday          DATE,
    user_email             VARCHAR(128) NOT NULL UNIQUE,
    user_password          VARCHAR(255) NOT NULL,
    user_avatar            VARCHAR(512),
    user_last_activity     DATETIME NOT NULL,
    user_portfolio         VARCHAR(512),
    user_info              VARCHAR(512),
    user_phone             INT,
    user_skype             VARCHAR(128),
    user_other_contacts    VARCHAR(128),
    user_location          INT NOT NULL,
    hide_contacts          TINYINT(1) NOT NULL DEFAULT 0,
    hide_profile           TINYINT(1) NOT NULL DEFAULT 0,
    is_executor            BOOLEAN NOT NULL DEFAULT 0,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_location) REFERENCES locations(location_id)
);




/* все префиксы user_ можно убрать */
CREATE TABLE IF NOT EXISTS statuses
(
    status_id      INT NOT NULL AUTO_INCREMENT,
    status_name    VARCHAR(20) NOT NULL,
    PRIMARY KEY (status_id)
);

/* все префиксы user_ можно убрать */
CREATE TABLE IF NOT EXISTS task
(
    task_id                INT NOT NULL AUTO_INCREMENT,
    task_name              VARCHAR(128) NOT NULL,
    task_description       VARCHAR(512) NOT NULL,
    task_category          TINYINT NOT NULL,
    task_author            INT NOT NULL,
    task_files             VARCHAR(512) DEFAULT '',
    task_location          INT,
    task_creation_date     DATETIME NOT NULL,
    task_end_date          DATETIME,
    task_status            INT DEFAULT 1,
    task_budget            INT UNSIGNED,
    PRIMARY KEY (task_id),
    FOREIGN KEY (task_category) REFERENCES categories(category_id),
    FOREIGN KEY (task_author) REFERENCES users(user_id),
    FOREIGN KEY (task_location) REFERENCES locations(location_id),
    FOREIGN KEY (task_status) REFERENCES statuses(status_id)

);

/* все префиксы user_ можно убрать */
CREATE TABLE IF NOT EXISTS task_in_works
(
  id                   INT NOT NULL AUTO_INCREMENT,
  work_task_id         INT NOT NULL,
  executor_id          INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (work_task_id) REFERENCES task(task_id),
  FOREIGN KEY (executor_id) REFERENCES users(user_id)
);


/* все префиксы user_ можно убрать */
CREATE TABLE IF NOT EXISTS feedback
(
    feedback_id           INT NOT NULL AUTO_INCREMENT,
    feedback_executor_id  INT,
    feedback_customer_id  INT,
    feedback_comment      TEXT,
    feedback_rate         TINYINT,
    feedback_date         DATETIME NOT NULL,
    PRIMARY KEY (feedback_id),
    FOREIGN KEY (feedback_executor_id) REFERENCES users(user_id),
    FOREIGN KEY (feedback_customer_id) REFERENCES users(user_id)
);

/* все префиксы user_ можно убрать */
CREATE TABLE IF NOT EXISTS bookmarked
(
    bookmarked_id          INT NOT NULL AUTO_INCREMENT,
    user_id                INT,
    bookmarked_user_id     INT,
    bookmarked_status      BOOLEAN DEFAULT 0,
    PRIMARY KEY (bookmarked_id),
    FOREIGN KEY (bookmarked_user_id) REFERENCES users(user_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

/* все префиксы messages_ можно убрать */
CREATE TABLE IF NOT EXISTS messages
(
    messages_id            INT NOT NULL AUTO_INCREMENT,
    messages_time          DATETIME,
    messages_sender        INT NOT NULL,
    messages_recipient     INT NOT NULL,
    messages_text          TEXT NOT NULL,
    PRIMARY KEY (messages_id),
    FOREIGN KEY (messages_sender) REFERENCES users(user_id),
    FOREIGN KEY (messages_recipient) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS response
(
    response_id            INT NOT NULL AUTO_INCREMENT,
    response_time          DATETIME NOT NULL,
    response_executor_id   INT NOT NULL,
    response_task_id       INT NOT NULL,
    response_comment       TEXT NOT NULL,
    PRIMARY KEY (response_id),
    FOREIGN KEY (response_task_id) REFERENCES task(task_id),
    FOREIGN KEY (response_executor_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS notifications
(
    id                     INT NOT NULL AUTO_INCREMENT,
    notify_name            VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS user_alerts
(
    id                     INT NOT NULL AUTO_INCREMENT,
    alert_id               INT NOT NULL,
    user_id                INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (alert_id) REFERENCES notifications(id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS user_portfolio
(
    id                INT NOT NULL AUTO_INCREMENT,
    photo_path        VARCHAR(512) NOT NULL,
    user_id           INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE user_specialization (
	id              INT(11) NOT NULL AUTO_INCREMENT,
	user_id         INT(11) NOT NULL,
	category_id     TINYINT NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	FOREIGN KEY (category_id) REFERENCES categories (category_id),
	FOREIGN KEY (user_id) REFERENCES users (user_id)
);



CREATE FULLTEXT INDEX tasks_search ON task (task_name, task_description);


