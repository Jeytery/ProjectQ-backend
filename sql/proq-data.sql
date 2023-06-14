
/* 
  USER table:
  type TINYINT UNSIGNED NOT NULL DEFAULT 1,
  first_name VARCHAR(64) NOT NULL,
  last_name VARCHAR(64) NOT NULL,
  email VARCHAR(64) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  username VARCHAR(64) NOT NULL,
  password VARCHAR(32) DEFAULT ''
*/

INSERT INTO user ( user_type, first_name, last_name, email, phone, username, password )
VALUES ( 1, 'admin', '', 'admin@proq.cc', '', 'admin', 'proq.admin' );

INSERT INTO user ( user_type, first_name, last_name, email, phone, username, password )
VALUES ( 2, 'customer', '', 'customer@proq.cc', '', 'customer', 'proq.customer' );

INSERT INTO user ( user_type, first_name, last_name, email, phone, username, password )
VALUES ( 3, 'manager', '', 'manager@proq.cc', '', 'manager', 'proq.manager' );

/*
  USER OPTIONS table:
  object_id INTEGER UNSIGNED NOT NULL,
  object_name VARCHAR(32) NOT NULL DEFAULT '',
  name VARCHAR(128) NOT NULL DEFAULT '',
  value VARCHAR(512) NOT NULL DEFAULT ''

*/


INSERT INTO options ( object_id, object_name, name, value, option_type )
VALUES (1, 'user', 'emailVerified', 'true', 'boolean'), (1, 'user', 'phoneVerified', 'false', 'boolean'), (1, 'user', 'banned', 'false', 'boolean');

INSERT INTO options ( object_id, object_name, name, value, option_type )
VALUES (3, 'user', 'emailVerified', 'true', 'boolean'), (3, 'user', 'phoneVerified', 'false', 'boolean'), (3, 'user', 'banned', 'false', 'boolean');

INSERT INTO options ( object_id, object_name, name, value, option_type )
VALUES (1, 'user', 'admin', 'true', 'boolean');

INSERT INTO language (name, code) VALUES ('English', 'eng');

INSERT INTO store ( store_id, user_id, name, description, activated, language_code )
VALUES (1, 3, 'Store-1', 'Store of packages', true, 'eng');

INSERT INTO package ( name, description, code, language_code)
VALUES ('Package-1', 'Package of data', 'P-1', 'eng'), ('Package-2', 'Package of data', 'P-2', 'eng');

INSERT INTO task (task_type, name, description, language_code)
VALUES (1, 'Task-1', 'Task 1 of Package 1', 'eng'),
(1, 'Task-2', 'Task 2 of Package 1', 'eng'),
(1, 'Task-3', 'Task 3 of Package 1', 'eng'),
(1, 'Task-1', 'Task 1 of Package 2', 'eng'),
(1, 'Task-2', 'Task 2 of Package 2', 'eng');

INSERT INTO component (pure_number, handler_input, name, description, language_code)
VALUES (1, 'AAA-BBB-CCC', 'Component-1', 'Component of Task 1 of Package 1', 'eng'),
(2, 'CCC-DDD-BBB', 'Component-2', 'Component of Task 2 of Package 1', 'eng'),
(3, 'FFF-XXX-EEE', 'Component-3', 'Component of Task 1 of Package 1', 'eng'),
(4, 'WWW-SSS-HHH', 'Component-4', 'Component of Task 2 of Package 1', 'eng'),
(5, 'QQQ-VVV-UUU', 'Component-5', 'Component of Task 1 of Package 2', 'eng'),
(6, 'KKK-LLL-III', 'Component-6', 'Component of Task 1 of Package 2', 'eng');

INSERT INTO store_package (store_id, package_id) VALUES (1, 1), (1, 2);
INSERT INTO package_task (package_id, task_id) VALUES (1, 1), (1, 2), (1, 3), (2, 4), (2, 5);
INSERT INTO task_component (task_id, component_id) VALUES (1, 1), (2, 2), (1, 3), (2, 4), (4, 5), (5, 6);
 

