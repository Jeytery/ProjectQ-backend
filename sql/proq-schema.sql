-- Pro Q (c) Database Schema
-- Version 1.0.0

-- proq.cc Copyright (c) 2023, MySQL version
-- All rights reserved.
-- Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'TRADITIONAL';
-- SET @CHARSET_COLLATE = 'DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci'; -- DEFAULT CHARSET = utf8; | DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

/* Default schema
DROP SCHEMA IF EXISTS proq;
CREATE SCHEMA proq;
USE proq;
*/

/* Namecheap hosting
    
   admin credentials
   -----------------
   database: xbitoakr_proq
   username: xbitoakr_proq
   password: 3_Ueb]&W@]p-

*/

-- USE <schema_name>;
-- ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE=utf8mb4_general_ci

DROP SCHEMA IF EXISTS xbitoakr_proq;
CREATE SCHEMA xbitoakr_proq CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
USE xbitoakr_proq;

-- [tables]
-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////


-- ------------ [main] ------------

-- user (type: 1 - admin, 2 - customer, 3 - manager/editor)

CREATE TABLE `user` (

  user_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  user_type TINYINT UNSIGNED NOT NULL DEFAULT 0,
  first_name VARCHAR(64) NOT NULL,
  last_name VARCHAR(64) NOT NULL,
  email VARCHAR(64) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  username VARCHAR(64) /* UNIQUE */ NOT NULL,
  password VARCHAR(32) DEFAULT '',
  picture VARCHAR(512) DEFAULT NULL,
  activated BOOLEAN NOT NULL DEFAULT TRUE,
  access_token VARCHAR(64) DEFAULT NULL,
  access_token_created TIMESTAMP DEFAULT NULL, -- for any request, restricted limited time
  update_token VARCHAR(64) NOT NULL, -- for get_token request parameter, restricted once used
  -- update_token_created TIMESTAMP DEFAULT NULL,
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (user_id),
  UNIQUE KEY idx_unique_username (username),
  UNIQUE KEY idx_unique_access_token (access_token),
  FULLTEXT KEY idx_first_name_last_name (first_name, last_name),
  -- KEY idx_first_name (first_name),
  -- KEY idx_last_name (last_name),
  KEY idx_email (email),
  KEY idx_phone (phone),
  KEY idx_activated (activated),
  KEY idx_update_token (update_token)
  
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- store

CREATE TABLE `store` (

  store_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INTEGER UNSIGNED DEFAULT NULL, /* manager's user id */
  name VARCHAR(128) NOT NULL DEFAULT '',
  description VARCHAR(256) NOT NULL DEFAULT '',
  picture VARCHAR(512) DEFAULT NULL,
  activated BOOLEAN NOT NULL DEFAULT TRUE,
  language_code VARCHAR(5) DEFAULT '',
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (store_id),
  KEY idx_user_id (user_id),
  KEY idx_name (name),
  KEY idx_description (description),
  KEY idx_activated (activated),
  KEY idx_language_code (language_code)
  
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- package ( package -> store)

CREATE TABLE `package` (

  package_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(128) NOT NULL DEFAULT '',
  description VARCHAR(256) NOT NULL DEFAULT '',
  code VARCHAR(32) /*UNIQUE*/ NOT NULL DEFAULT '',
  language_code VARCHAR(5) DEFAULT '',
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (package_id),
  -- UNIQUE KEY idx_unique_code (code),
  KEY idx_code (code),
  KEY idx_name (name),
  KEY idx_description (description),
  KEY idx_language_code (language_code)

) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- package_task ( package_task -> package )

CREATE TABLE `task` (

  task_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  task_type TINYINT UNSIGNED NOT NULL DEFAULT 1,
  name VARCHAR(128) NOT NULL DEFAULT '',
  description VARCHAR(256) NOT NULL DEFAULT '',  
  language_code VARCHAR(5) DEFAULT '',
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (task_id),
  KEY idx_type (task_type),
  KEY idx_name (name),
  KEY idx_description (description),
  KEY idx_language_code (language_code)

) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;


CREATE TABLE `component` (

  component_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  pure_number INTEGER NOT NULL,
  handler_input TEXT,
  name VARCHAR(128) NOT NULL DEFAULT '',
  description VARCHAR(256) NOT NULL DEFAULT '',  
  language_code VARCHAR(5) DEFAULT '',
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  PRIMARY KEY (component_id),
  KEY idx_name (name),
  KEY idx_description (description),
  KEY idx_language_code (language_code)

) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ------------ [dictionaries] ------------

-- language

CREATE TABLE `language` (

  language_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(128) NOT NULL DEFAULT '',
  code VARCHAR(5) NOT NULL DEFAULT '',
  last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (language_id),
  UNIQUE KEY idx_unique_name (name),
  UNIQUE KEY idx_unique_code (code)
  
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- product_category

-- ------------ [union helpers] ------------

-- package_content_item

CREATE TABLE `package_task` ( -- WARNING ! 

  package_id INTEGER UNSIGNED NOT NULL,
  task_id INTEGER UNSIGNED NOT NULL,
  
  PRIMARY KEY (package_id, task_id),
  KEY idx_package_id (package_id)
  
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- product_package

CREATE TABLE `task_component` (

  task_id INTEGER UNSIGNED NOT NULL,
  component_id INTEGER UNSIGNED NOT NULL,
  
  PRIMARY KEY (task_id, component_id),
  KEY idx_task_id (task_id)
  
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- store_product

CREATE TABLE `store_package` (

  store_id INTEGER UNSIGNED NOT NULL,
  package_id INTEGER UNSIGNED NOT NULL,
  
  PRIMARY KEY (store_id, package_id),
  KEY idx_store_id (store_id)
  
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;


-- ------------ [settings / options] ------------

-- settings (system options)

CREATE TABLE `settings` ( -- WARNING ! 

    name VARCHAR(128) /*UNIQUE*/ NOT NULL DEFAULT '',
    value VARCHAR(512) NOT NULL DEFAULT '',
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY idx_unique_name (name)

) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- options (table objects options, object_name: user, store, product)

CREATE TABLE `options` ( -- WARNING ! 

    object_id INTEGER UNSIGNED NOT NULL,
    object_name VARCHAR(32) NOT NULL DEFAULT '',
    name VARCHAR(128) NOT NULL DEFAULT '',
    value VARCHAR(512) NOT NULL DEFAULT '',
    option_type VARCHAR(16) NOT NULL DEFAULT 'string', -- option_type: string, number, boolean, array
    last_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (object_id, object_name, name),
    KEY idx_name (name)

) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////

SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
