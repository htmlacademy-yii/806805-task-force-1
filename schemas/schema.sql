CREATE DATABASE IF NOT EXISTS task_force 
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;


USE task_force;


CREATE TABLE IF NOT EXISTS locations (
    PRIMARY KEY (location_id),               
    location_id INT AUTO_INCREMENT NOT NULL, 
    city        VARCHAR(64)        NOT NULL, 
    latitude    VARCHAR(255)       NOT NULL,
    longitude   VARCHAR(255)       NOT NULL
);


CREATE TABLE IF NOT EXISTS task_statuses (
    PRIMARY KEY (status_id),                      
    status_id  INT AUTO_INCREMENT NOT NULL,        
    title      VARCHAR(64)        NOT NULL UNIQUE, 
    const_name VARCHAR(64)        NOT NULL UNIQUE  
);


CREATE TABLE IF NOT EXISTS task_actions (
    PRIMARY KEY (action_id),                                
    action_id  INT AUTO_INCREMENT NOT NULL,    
    title      VARCHAR(64)        NOT NULL UNIQUE,                 
    const_name VARCHAR(64)        NOT NULL UNIQUE   
);


CREATE TABLE IF NOT EXISTS user_roles (
    PRIMARY KEY (role_id),                                 
    role_id    INT AUTO_INCREMENT NOT NULL,  
    title      VARCHAR(64)        NOT NULL UNIQUE, 
    const_name VARCHAR(64)        NOT NULL UNIQUE 
);


CREATE TABLE IF NOT EXISTS categories (
    PRIMARY KEY (category_id),   
    category_id INT AUTO_INCREMENT NOT NULL, 
    title       VARCHAR(64)        NOT NULL UNIQUE, 
    label       VARCHAR(64)        NOT NULL UNIQUE   
);


CREATE TABLE IF NOT EXISTS users (
    PRIMARY KEY (user_id),                      
    user_id           INT AUTO_INCREMENT NOT NULL, 
	role_id           INT DEFAULT 1      NOT NULL, 
	location_id       INT                NOT NULL,
    full_name         VARCHAR(64)       NOT NULL, 
	email             VARCHAR(64)       NOT NULL UNIQUE,
    phone             VARCHAR(11),
    skype             VARCHAR(64),
	messaging_contact VARCHAR(64),           
    full_address      VARCHAR(255),                
    avatar_addr       VARCHAR(255),                
	desc_text         TEXT,                        
    password_key      VARCHAR(255)       NOT NULL,
	birth_date        DATE,
    reg_time          DATETIME           NOT NULL,
	activity_time     DATETIME           NOT NULL,
    hide_contacts     BOOL DEFAULT 0     NOT NULL,
    hide_profile      BOOL DEFAULT 0     NOT NULL,
    FOREIGN KEY (role_id)     REFERENCES user_roles(role_id),       
    FOREIGN KEY (location_id) REFERENCES locations(location_id),  
    FULLTEXT users_full_name_fulltext (full_name)
);


CREATE TABLE IF NOT EXISTS user_portfolio_images (
    PRIMARY KEY (image_id),
    image_id   INT AUTO_INCREMENT NOT NULL, 
    user_id    INT                NOT NULL,
    title      VARCHAR(255)       NOT NULL,
    image_addr VARCHAR(255)       NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


CREATE TABLE IF NOT EXISTS user_specializations (
    PRIMARY KEY (specialization_id),            
	specialization_id INT AUTO_INCREMENT NOT NULL, 
	user_id           INT                NOT NULL,
	category_id       INT,
    FOREIGN KEY (user_id)     REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);


CREATE TABLE IF NOT EXISTS user_notifications (
    PRIMARY KEY (notification_id),
    notification_id INT AUTO_INCREMENT NOT NULL, 
    title           VARCHAR(64)        NOT NULL UNIQUE,
	label           VARCHAR(64)        NOT NULL UNIQUE
);


CREATE TABLE IF NOT EXISTS user_notification_settings (
    PRIMARY KEY (setting_id),
    setting_id      INT AUTO_INCREMENT NOT NULL, 
	user_id         INT                NOT NULL,
    notification_id INT                NOT NULL,
    is_active       BOOL DEFAULT 1,                 
    FOREIGN KEY (user_id)         REFERENCES users(user_id),
    FOREIGN KEY (notification_id) REFERENCES user_notifications(notification_id)
);


CREATE TABLE IF NOT EXISTS tasks (
    PRIMARY KEY (task_id),                      
    task_id      INT AUTO_INCREMENT NOT NULL,    
	status_id    INT                NOT NULL,
    category_id  INT                NOT NULL,
	location_id  INT                NOT NULL,
    customer_id  INT                NOT NULL,
    title        VARCHAR(128)       NOT NULL,
    desc_text    TEXT               NOT NULL,          
    price        INT                UNSIGNED,
    full_address VARCHAR(255),
    address_desc VARCHAR(255),      
    latitude     VARCHAR(255),
    longitude    VARCHAR(255),
    add_time     DATETIME           NOT NULL,
    end_date     DATETIME,
	is_remote    BOOL DEFAULT 1,
    FOREIGN KEY (status_id)   REFERENCES task_statuses(status_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id),
    FOREIGN KEY (customer_id) REFERENCES users(user_id),
    FULLTEXT tasks_full_name_fulltext (title)
);


CREATE TABLE IF NOT EXISTS task_files (
    PRIMARY KEY (file_id),          
    file_id   INT AUTO_INCREMENT NOT NULL,  
    task_id   INT                NOT NULL,
    file_addr VARCHAR(255),
    FOREIGN KEY (task_id) REFERENCES tasks(task_id)
);


CREATE TABLE IF NOT EXISTS task_runnings (
    PRIMARY KEY (running_id),           
    running_id    INT AUTO_INCREMENT NOT NULL,  
    task_id       INT                NOT NULL,      
    contractor_id INT                NOT NULL,
    FOREIGN KEY (task_id)       REFERENCES tasks(task_id),
    FOREIGN KEY (contractor_id) REFERENCES users(user_id)
);


CREATE TABLE IF NOT EXISTS task_failings (
    PRIMARY KEY (failing_id),  
    failing_id    INT AUTO_INCREMENT NOT NULL,
    task_id       INT                NOT NULL,
    contractor_id INT                NOT NULL,
    FOREIGN KEY (task_id)       REFERENCES tasks(task_id),
    FOREIGN KEY (contractor_id) REFERENCES users(user_id)
);


CREATE TABLE IF NOT EXISTS feedbacks (
    PRIMARY KEY (feedback_id),  
    feedback_id  INT AUTO_INCREMENT NOT NULL,  
    author_id    INT                NOT NULL,     
    recipient_id INT                NOT NULL,    
    task_id      INT                NOT NULL,
    desc_text    TEXT,        
    point_num    INT                NOT NULL,  
    add_time     DATETIME           NOT NULL,
    FOREIGN KEY (author_id)    REFERENCES users(user_id),
    FOREIGN KEY (recipient_id) REFERENCES users(user_id),
    FOREIGN KEY (task_id)      REFERENCES tasks(task_id)
);


CREATE TABLE IF NOT EXISTS offers (
    PRIMARY KEY (offer_id),    
    offer_id      INT AUTO_INCREMENT NOT NULL,    
    task_id       INT                NOT NULL,
    contractor_id INT                NOT NULL,
    price         INT                UNSIGNED,
    desc_text     TEXT               NOT NULL,        
    FOREIGN KEY (task_id)       REFERENCES tasks(task_id),
    FOREIGN KEY (contractor_id) REFERENCES users(user_id)
);


CREATE TABLE IF NOT EXISTS user_favorites (
    PRIMARY KEY (favorite_id),     
    favorite_id  INT AUTO_INCREMENT NOT NULL, 
    user_id      INT                NOT NULL,
    fave_user_id INT                NOT NULL,
    is_fave      BOOL DEFAULT 1,                 
    FOREIGN KEY (user_id)      REFERENCES users(user_id),
    FOREIGN KEY (fave_user_id) REFERENCES users(user_id)
);


CREATE TABLE IF NOT EXISTS messages (
    PRIMARY KEY (message_id),       
    message_id  INT AUTO_INCREMENT NOT NULL,
    task_id     INT                NOT NULL,
    sender_id   INT                NOT NULL,
    receiver_id INT                NOT NULL,         
    mess_text   TEXT               NOT NULL,
    add_time    DATETIME           NOT NULL,
    FOREIGN KEY (task_id)     REFERENCES tasks(task_id),
    FOREIGN KEY (sender_id)   REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id)
);

