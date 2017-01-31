CREATE TABLE `monolog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL DEFAULT '',
  `message` text,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `ip` text,
  `user_agent` text,
  `channel` varchar(255) NOT NULL DEFAULT '',
  `level` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;