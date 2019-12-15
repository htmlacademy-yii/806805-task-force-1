USE task_force;

INSERT INTO categories 
	(`symbol`,`name`)
VALUES
    ('translation', 'Перевод текстов'),
    ('clean','Клининг'),
    ('cargo','Грузоперевозки'),
    ('neo','Удаленная помощь'),
    ('flat','Риелтор'),
    ('repair','Ремонт транспорта'),
	('beauty','Косметика'),
	('photo','Фотография')
;

INSERT INTO users
    (`id_role`,`id_location`,`name`,`avatar`,`email`,`password`,`skype`,`phone`,`other_contacts`,`adress`,`portfolio`,`reg_time`,`birth_date`,`activity_time`,`hide_contacts`,`hide_profile`)
VALUES
	('1','1','Robby','avatar_robby.jpg','robby@email.com','asdf1234','robby@skype.ru','1234567890','robby@viber.com','Ideal programmer','www.idealsite.com','2019-11-11','2001-01-01','2019-11-21 12:00:00','0','1'),
	('1','2','John','avatar_john.jpg','john@email.com','asdf1234','john@skype.ru','1234567890','john@viber.com','Ideal driver','DHL, ALI','2019-11-12','2002-01-02','2019-11-22 12:00:00','1','1'),
	('1','3','Adel','avatar_Adel.jpg','Adel@email.com','asdf1234','Adel@skype.ru','1234567890','Adel@viber.com','Ideal cleaner','bank, hostel','2019-11-13','2003-01-03','2019-11-23 12:00:00','0','0'),
	('1','4','Sara','avatar_Sara.jpg','Sara@email.com','asdf1234','Sara@skype.ru','1234567890','Sara@viber.com','Ideal translator','Articles, Books','2019-11-14','2004-01-04','2019-11-24 12:00:00','1','0'),
	('2','5','Boss','avatar_boss.jpg','boss@email.com','asdf1234','boss@skype.ru','1234567890','boss@viber.com','Ideal contractor','Manager in 1C','2019-11-15','2005-01-05','2019-11-25 12:00:00','0','0')
;

INSERT INTO portfolio_images 
    (`id_user`,`image`)
VALUES
    ('1','Robby_project_1.jpg'),
    ('2','john_project_2.jpg'),
    ('3','Adel_project_3.jpg'),
    ('4','Sara_project_4.jpg'),
    ('2','john_project_2-1.jpg'),
    ('1','Robby_project_1-1.jpg')
;

INSERT INTO user_specializations 
    (`id_user`,`id_category`)
VALUES
    ('1','4'),
	('2','3'),
	('3','2'),
	('4','1'),
    ('1','8'),
    ('2','6')
;

INSERT INTO user_notification_settings 
    (`id_user`,`id_notification`,`status`)
VALUES
	('1','1','1'),
	('1','2','1'),
	('1','3','1'),
    
	('2','1','0'),
	('2','2','1'),
	('2','3','1'),
    
	('3','1','1'),
	('3','2','0'),
	('3','3','1'),
    
	('4','1','0'),
	('4','2','0'),
	('4','3','0')
;

INSERT INTO tasks 
    (`id_status`,`id_category`,`id_location`,`id_customer`,`name`,`desc`,`add_time`,`end_date`,`price`,`is_remote`)
VALUES
    ('3','4','1','5','Create site menu','Suspendisse potenti. In eleifend quam a odio','2019-11-21 12:00:00','2019-12-21','100','1'),
    ('1','3','2','5','Move piano','Nam ultrices, libero non mattis pulvinar, nulla pede ullamcorper augue','2019-11-22 12:00:00','2019-12-22','200','0'),
    ('3','2','3','5','Cleaning tapis','Praesent blandit. Nam nulla. Integer pede justo, lacinia eget','2019-11-23 12:00:00','2019-12-23','300','0'),
    ('1','1','4','5','Translete song','Fusce posuere felis sed lacus. Morbi sem mauris, laoreet ut','2019-11-24 12:00:00','2019-12-24','400','1'),
    ('3','4','5','5','Do new startup','Praesent blandit. Nam nulla. Integer pede justo, lacinia eget, tincidunt eget, tempus vel, pede.','2019-11-25 12:00:00','2019-12-25','500','0'),
    ('4','4','1','5','Do mew site','Morbi porttitor lorem id ligula. Suspendisse ornare consequat lectus. In est risus','2019-10-26 12:00:00','2019-11-26','600','0'),
	('5','4','1','5','Do good thins','Nulla ut erat id mauris vulputate elementum. Nullam varius. Nulla facilisi.','2019-10-27 12:00:00','2019-11-27','700','0')
;

INSERT INTO notifications 
	(`symbol`,`name`)
VALUES
	('notice_mess','Новое сообщение'),
    ('notice_action','Действия по заданию'),
    ('notice_feedback','Новый отзыв')
;

INSERT INTO statuses 
	(`symbol`,`name`)
VALUES
	('STATUS_NEW','Новое'),
    ('STATUS_CANCELED','Отменено'),
	('STATUS_RUNNING','Выполняется'),
    ('STATUS_COMPLETED','Выполнено'),
    ('STATUS_FAILED','Провалено')
;

INSERT INTO roles 
	(`symbol`,`name`)
VALUES
	('ROLE_CONTRACTOR','Исполнитель'),
    ('ROLE_CUSTOMER','Заказчик')
;

INSERT INTO actions 
	(`symbol`,`name`)
VALUES
	('ACTION_ADD_TASK','Добавить задание'),
    ('ACTION_OFFER','Откликнуться'),
	('ACTION_FAILURE','Отказаться'),
    ('ACTION_CANCEL','Отменить'),
    ('ACTION_SET_CONTRACTOR','Выбрать исполнителя'),
    ('ACTION_COMPLETE','Завершить'),
    ('ACTION_ACCEPT','Принять'),
    ('ACTION_MESS','Написать сообщение')
;

INSERT INTO task_files 
    (`id_task`,`file`)
VALUES
    ('1','project_1.doc'),
    ('3','project_3.jpg'),
    ('4','project_4.pdf'),
    ('1','project_1-1.jpg')
;

INSERT INTO running_tasks 
    (`id_task`,`id_contractor`)
VALUES
    ('1','1'),
	('3','3'),
    ('5','1'),
    ('6','1'),
    ('7','1')
;

INSERT INTO feedbacks 
    (`id_user`,`id_user_rated`,`id_task`,`desk`,`point`,`add_time`)
VALUES
	('5','1','6','Doing allright, talk less, work more','5','2019-12-25 12:00:00'),
    ('5','1','7','Rest is as good as work','4','2019-11-27 12:00:00')
;

INSERT INTO offers 
    (`id_task`,`id_contractor`,`desk`)
VALUES
	('2','2','I best Piano mover'),
	('2','3','We will do everything without noise and dust'),
    ('4','4','I am grut'),
    ('6','1','Can do it one month')
;

INSERT INTO favorite_users 
    (`id_user`,`id_user_favorite`,`on_off`)
VALUES
	('5','1','1'),
	('1','5','1'),
	('3','5','0')
;

INSERT INTO messages 
    (`id_task`,`id_sender`,`id_recipient`,`mess`,`add_time`)
VALUES
	('1','1','5','Boss I am start tommorow','2019-11-25 12:00:21'),
	('1','5','1','Do not put off until tomorrow what can be done today','2019-11-25 12:00:25'),
	('3','3','5','The quieter you go, the further you\'ll get','2019-11-30 12:00:00')
;

INSERT INTO locations 
    (`city`,`latitude`,`longitude`)

INSERT INTO locations (city,latitude,longitude)
	('Нижний Новгород','56.3242093','44.0053948'),
	('Орёл','52.9672573','36.0696479'),
	('Сочи','43.5855829','39.7231419'),
    ('Казань','55.7943877','49.1115312'),
    ('Великий Новгород','58.5214003','31.2755051')
;