CREATE TABLE IF NOT EXISTS `administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` tinytext NOT NULL,
  `level` tinytext NOT NULL,
  `enable` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abr` varchar(2) NOT NULL,
  `name` varchar(20) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abr` (`abr`),
  KEY `abr_` (`abr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `https` tinyint(4) NOT NULL DEFAULT '0',
  `file_name` varchar(50) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT 'default',
  `visible` tinyint(2) NOT NULL DEFAULT '1',
  `admin` tinyint(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `name1` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pages_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL,
  `lang` varchar(2) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `keywords` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id_lang` (`page_id`,`lang`),
  KEY `page_id` (`page_id`),
  KEY `FK_pages_info_languages` (`lang`),
  CONSTRAINT `FK_pages_info_languages` FOREIGN KEY (`lang`) REFERENCES `languages` (`abr`),
  CONSTRAINT `FK_pages_info_pages` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;