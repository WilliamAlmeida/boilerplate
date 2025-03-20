CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `idmenu` int DEFAULT NULL,
  `posicao` int unsigned NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `local` tinyint(1) DEFAULT '0',
  `insert_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `insert_user` int DEFAULT NULL,
  `last_update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_update_user` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;

CREATE TABLE `menu_2` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL DEFAULT '',
  `idmenu` int DEFAULT NULL,
  `posicao` int NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `local` tinyint DEFAULT '0',
  `idcompany` int DEFAULT NULL,
  `insert_time` datetime DEFAULT NULL,
  `insert_user` int DEFAULT NULL,
  `last_update_time` datetime DEFAULT NULL,
  `last_update_user` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idmenu` int DEFAULT NULL,
  `permission` tinyint DEFAULT NULL,
  `last_update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_update_user` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkmenu` (`idmenu`),
  KEY `fkuser` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=4229 DEFAULT CHARSET=utf8mb3;