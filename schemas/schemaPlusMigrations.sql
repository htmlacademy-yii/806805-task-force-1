CREATE TABLE IF NOT EXISTS locations (
    PRIMARY KEY (location_id),               
    location_id INT AUTO_INCREMENT NOT NULL, 
    city        VARCHAR(64)        NOT NULL, 
    latitude    VARCHAR(255)       NOT NULL,
    longitude   VARCHAR(255)       NOT NULL
);
/*
php yii migrate/create create_locations_table --fields='location_id:primaryKey,
    city:string(64):notNull,
    latitude:string(255):notNull,
    longitude:string(255):notNull'
*/

CREATE TABLE IF NOT EXISTS task_statuses (
    PRIMARY KEY (status_id),                      
    status_id  INT AUTO_INCREMENT NOT NULL,        
    title      VARCHAR(64)        NOT NULL UNIQUE, 
    const_name VARCHAR(64)        NOT NULL UNIQUE  
);
/*
php yii migrate/create create_task_statuses_table --fields='status_id:primaryKey,
    title:string(64):notNull:unique,
    const_name:string(64):notNull:unique'
*/

CREATE TABLE IF NOT EXISTS task_actions (
    PRIMARY KEY (action_id),                                
    action_id  INT AUTO_INCREMENT NOT NULL,    
    title      VARCHAR(64)        NOT NULL UNIQUE,                 
    const_name VARCHAR(64)        NOT NULL UNIQUE   
);
/*
php yii migrate/create create_task_actions_table --fields='action_id:primaryKey,
    title:string(64):notNull:unique,
    const_name:string(64):notNull:unique'
*/

CREATE TABLE IF NOT EXISTS user_roles (
    PRIMARY KEY (role_id),                                 
    role_id    INT AUTO_INCREMENT NOT NULL,  
    title      VARCHAR(64)        NOT NULL UNIQUE, 
    const_name VARCHAR(64)        NOT NULL UNIQUE 
);
/*
php yii migrate/create create_user_roles_table --fields='role_id:primaryKey,
    title:string(64):notNull:unique,
    const_name:string(64):notNull:unique'
*/

CREATE TABLE IF NOT EXISTS categories (
    PRIMARY KEY (category_id),   
    category_id INT AUTO_INCREMENT NOT NULL, 
    title       VARCHAR(64)        NOT NULL UNIQUE, 
    label       VARCHAR(64)        NOT NULL UNIQUE   
);
/*
php yii migrate/create create_categories_table --fields='category_id:primaryKey,
    title:string(64):notNull:unique,
    label:string(64):notNull:unique'
*/

CREATE TABLE IF NOT EXISTS users (
    PRIMARY KEY (user_id),                      
    user_id           INT AUTO_INCREMENT NOT NULL, 
	role_id           INT DEFAULT 1      NOT NULL, 
	location_id       INT                NOT NULL,
    full_name         VARCHAR(64)       NOT NULL, 
	email             VARCHAR(64)       NOT NULL UNIQUE,
    phone             VARCHAR(11)       UNIQUE,
    skype             VARCHAR(64)       UNIQUE,
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
/*
php yii migrate/create create_users_table --fields='user_id:primaryKey,
    role_id:integer:notNull:defaultValue(1):foreignKey(user_roles role_id),location_id:integer:notNull:foreignKey(locations location_id),
    full_name:string(64):notNull,
    email:string(64):notNull:unique,
    phone:string(11):unique,
    skype:string(64):unique,
    messaging_contact:string(64),
    full_address:string(255),
    avatar_addr:string(255),
    desc_text:text,
    password_key:string(255):notNull,
    birth_date:date,
    reg_time:datetime:notNull,
    activity_time:datetime:defaultValue(0):notNull,
    hide_contacts:boolean:defaultValue(0):notNull,
    hide_profile:boolean:defaultValue(0):notNull'
    # без FULLTEXT hide_profile
*/

CREATE TABLE IF NOT EXISTS user_portfolio_images (
    PRIMARY KEY (image_id),
    image_id   INT AUTO_INCREMENT NOT NULL, 
    user_id    INT                NOT NULL,
    title      VARCHAR(255)       NOT NULL,
    image_addr VARCHAR(255)       NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
/*
php yii migrate/create create_user_portfolio_images_table --fields='image_id:primaryKey,
    user_id:integer:notNull:foreignKey(users user_id),
    title:string(255):notNull,
    image_addr:string(255):notNull'
*/

CREATE TABLE IF NOT EXISTS user_specializations (
    PRIMARY KEY (specialization_id),            
	specialization_id INT AUTO_INCREMENT NOT NULL, 
	user_id           INT                NOT NULL,
	category_id       INT,
    FOREIGN KEY (user_id)     REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);
/*
php yii migrate/create create_user_specializations_table --fields='specialization_id:primaryKey,
    user_id:integer:notNull:foreignKey(users user_id),
    category_id:integer:foreignKey(categories category_id)'
*/

CREATE TABLE IF NOT EXISTS user_notifications (
    PRIMARY KEY (notification_id),
    notification_id INT AUTO_INCREMENT NOT NULL, 
    title           VARCHAR(64)        NOT NULL UNIQUE,
	label           VARCHAR(64)        NOT NULL UNIQUE
);
/*
php yii migrate/create create_user_notifications_table --fields='notification_id:primaryKey,
    title:string(64):notNull:unique,
    label:string(64):notNull:unique'
*/

CREATE TABLE IF NOT EXISTS user_notification_settings (
    PRIMARY KEY (setting_id),
    setting_id      INT AUTO_INCREMENT NOT NULL, 
	user_id         INT                NOT NULL,
    notification_id INT                NOT NULL,
    is_active       BOOL DEFAULT 1,                 
    FOREIGN KEY (user_id)         REFERENCES users(user_id),
    FOREIGN KEY (notification_id) REFERENCES user_notifications(notification_id)
);
/*
php yii migrate/create create_user_notification_settings_table --fields='setting_id:primaryKey,
    user_id:integer:notNull:foreignKey(users user_id),
    notification_id:integer:notNull:foreignKey(user_notifications notification_id),
    is_active:boolean:defaultValue(1):notNull'
*/

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
/*
php yii migrate/create create_tasks_table --fields='task_id:primaryKey,
    status_id:integer:notNull:foreignKey(task_statuses status_id),
    category_id:integer:notNull:foreignKey(categories category_id),
    location_id:integer:notNull:foreignKey(locations location_id),
    customer_id:integer:notNull:foreignKey(users user_id),
    title:string(128):notNull,
    desc_text:text,
    price:integer:unsigned:notNull,
    full_address:string(255),
    address_desc:string(255),
    latitude:string(255),
    longitude:string(255),
    add_time:datetime:notNull,
    end_date:datetime,
    is_remote:boolean:defaultValue(1):notNull'
    # без FULLTEXT
*/

CREATE TABLE IF NOT EXISTS task_files (
    PRIMARY KEY (file_id),          
    file_id   INT AUTO_INCREMENT NOT NULL,  
    task_id   INT                NOT NULL,
    file_addr VARCHAR(255),
    FOREIGN KEY (task_id) REFERENCES tasks(task_id)
);
/*
php yii migrate/create create_task_files_table --fields='file_id:primaryKey,
    task_id:integer:notNull:foreignKey(tasks task_id),
    file_addr:string(255)'
*/

CREATE TABLE IF NOT EXISTS task_runnings (
    PRIMARY KEY (running_id),           
    running_id    INT AUTO_INCREMENT NOT NULL,  
    task_id       INT                NOT NULL,      
    contractor_id INT                NOT NULL,
    FOREIGN KEY (task_id)       REFERENCES tasks(task_id),
    FOREIGN KEY (contractor_id) REFERENCES users(user_id)
);
/*
php yii migrate/create create_task_runnings_table --fields='running_id:primaryKey,
    task_id:integer:notNull:foreignKey(tasks task_id),
    contractor_id:integer:notNull:foreignKey(users user_id)'
*/

CREATE TABLE IF NOT EXISTS task_failings (
    PRIMARY KEY (failing_id),  
    failing_id    INT AUTO_INCREMENT NOT NULL,
    task_id       INT                NOT NULL,
    contractor_id INT                NOT NULL,
    FOREIGN KEY (task_id)       REFERENCES tasks(task_id),
    FOREIGN KEY (contractor_id) REFERENCES users(user_id)
);
/*
php yii migrate/create create_task_failings_table --fields='failing_id:primaryKey,
    task_id:integer:notNull:foreignKey(tasks task_id),
    contractor_id:integer:notNull:foreignKey(users user_id)'
*/

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
/*
php yii migrate/create create_feedbacks_table --fields='feedback_id:primaryKey,
    author_id:integer:notNull:foreignKey(users user_id),
    recipient_id:integer:notNull:foreignKey(users user_id),
    task_id:integer:notNull:foreignKey(tasks task_id),
    desc_text:text,
    point_num:integer:unsigned:notNull,
    add_time:datetime:notNull'
*/

CREATE TABLE IF NOT EXISTS offers (
    PRIMARY KEY (offer_id),    
    offer_id      INT AUTO_INCREMENT NOT NULL,    
    task_id       INT                NOT NULL,
    contractor_id INT                NOT NULL,
    price         INT                UNSIGNED,
    desc_text     TEXT               NOT NULL,
    add_time      DATETIME           NOT NULL,
    FOREIGN KEY (task_id)       REFERENCES tasks(task_id),
    FOREIGN KEY (contractor_id) REFERENCES users(user_id)
);
/*
php yii migrate/create create_offers_table --fields='offer_id:primaryKey,
    task_id:integer:notNull:foreignKey(tasks task_id),
    contractor_id:integer:notNull:foreignKey(users user_id),
    price:integer:unsigned,
    desc_text:text,
    add_time:datetime:notNull'
*/

CREATE TABLE IF NOT EXISTS user_favorites (
    PRIMARY KEY (favorite_id),     
    favorite_id  INT AUTO_INCREMENT NOT NULL, 
    user_id      INT                NOT NULL,
    fave_user_id INT                NOT NULL,
    is_fave      BOOL DEFAULT 1,                 
    FOREIGN KEY (user_id)      REFERENCES users(user_id),
    FOREIGN KEY (fave_user_id) REFERENCES users(user_id)
);
/*
php yii migrate/create create_user_favorites_table --fields='favorite_id:primaryKey,
    user_id:integer:notNull:foreignKey(users user_id),
    fave_user_id:integer:notNull:foreignKey(users user_id),
    is_fave:boolean:defaultValue(1)'
*/

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
/*
php yii migrate/create create_messages_table --fields='message_id:primaryKey,
    task_id:integer:notNull:foreignKey(tasks task_id),
    sender_id:integer:notNull:foreignKey(users user_id),
    receiver_id:integer:notNull:foreignKey(users user_id),
    mess_text:text:notNull,
    add_time:datetime:notNull'
*/
