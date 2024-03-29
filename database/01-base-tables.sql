/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 */

CREATE DATABASE IF NOT EXISTS billboard
  CHARSET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `user`
(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `login` VARCHAR(50) NOT NULL DEFAULT '',
  `password` VARCHAR(255) NOT NULL DEFAULT '' COLLATE utf8_bin,

-- timestamps
  `time_created` DATETIME NOT NULL,
  `time_updated` DATETIME NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8;


-- The table to store comments
CREATE TABLE IF NOT EXISTS `bulletin`
(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `content` VARCHAR(1000) NOT NULL DEFAULT '',
  `author_id` INT UNSIGNED NULL DEFAULT NULL, -- the reference to a user

  -- timestamps
  `time_created` DATETIME NOT NULL,
  `time_updated` DATETIME NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8;


-- The table to store bulletin's comments
CREATE TABLE IF NOT EXISTS `comment`
(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `content` VARCHAR(1000) NOT NULL DEFAULT '',
  `author_id` INT UNSIGNED NULL DEFAULT NULL, -- the reference to a user

  -- Comments are stored with Adjacent List algorithm
  `parent_id` INT UNSIGNED NULL DEFAULT NULL, -- the reference to a parent comment
  `level` INT UNSIGNED NOT NULL DEFAULT 0, -- current comment level

  -- timestamps
  `time_created` DATETIME NOT NULL,
  `time_updated` DATETIME NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8;


-- The table to store categories bulletins belong to
CREATE TABLE IF NOT EXISTS `category`
(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `slug` VARCHAR(255) NOT NULL DEFAULT '',

-- Categories are stored with Nested Set algorithm
  `child_left` INT UNSIGNED NOT NULL DEFAULT 0,
  `child_right` INT UNSIGNED NOT NULL DEFAULT 0,

-- timestamps
  `time_created` DATETIME NOT NULL,
  `time_updated` DATETIME NOT NULL
) ENGINE=InnoDB CHARACTER SET utf8;


-- The table to store categories bulletins belong to
CREATE TABLE IF NOT EXISTS `bulletin_category`
(
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bulletin_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,

-- timestamps
  `time_created` DATETIME NOT NULL,

  UNIQUE KEY (`bulletin_id`, `category_id`)
) ENGINE=InnoDB CHARACTER SET utf8;