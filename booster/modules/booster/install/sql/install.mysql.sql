--
-- Structure de la table `boo_items`
--

CREATE TABLE %%PREFIX%%boo_items (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `item_composer_id` varchar(255) default NULL,
  `slogan` varchar(255) default NULL,
  `slogan_fr` varchar(255) default NULL,
  `short_desc` text default NULL,
  `short_desc_fr` text default NULL,
  `type_id` int(12) NOT NULL,
  `url_website` varchar(255) default NULL,
  `url_repo` varchar(255) default NULL,
  `url_download` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  `author` varchar(80) NOT NULL,
  `item_by` int(12) NOT NULL,
  `status` int(1) NOT NULL default 0,
  `dev_status` int(1) NOT NULL default 0,
  `recommendation` BOOLEAN NOT NULL,
  `created` datetime NOT NULL,
  `edited` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `date_version` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_by` (`item_by`),
  KEY `type_id` (`type_id`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `edited` (`edited`),
  KEY `modified` (`modified`),
  KEY `date_version` (`date_version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  %%PREFIX%%boo_items ADD UNIQUE (`name`);


--
-- Structure de la table boo_type
--

CREATE TABLE %%PREFIX%%boo_type (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `type_name` VARCHAR( 80 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

INSERT INTO %%PREFIX%%boo_type (`id`, `type_name`) VALUES
(1, 'Application'),
(2, 'Module'),
(3, 'Plugin'),
(4, 'LangPack'),
(5, 'Library');

--
-- Structure de la table `boo_versions`
--

CREATE TABLE IF NOT EXISTS %%PREFIX%%boo_versions (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(12) NOT NULL,
  `id_jelix_version` int(12) NOT NULL,
  `status` int(1) NOT NULL,
  `version_name` varchar(80) NOT NULL,
  `version_date` datetime default NULL,
  `last_changes` varchar(255) default NULL,
  `stability` enum('pre-alpha','alpha','stable','mature') NOT NULL DEFAULT 'stable',
  `filename` varchar(80) default NULL,
  `download_url` varchar(255) default NULL,
  `created` datetime NOT NULL,
  `edited` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `edited` (`edited`),
  KEY `modified` (`modified`),
  KEY `id_jelix_version` (`id_jelix_version`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Structure de la table `boo_jelix_versions`
--

CREATE TABLE IF NOT EXISTS %%PREFIX%%boo_jelix_versions (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `boo_jelix_versions`
--

INSERT INTO %%PREFIX%%boo_jelix_versions (`id`, `version`) VALUES
(1, 'Jelix 1.1'),
(2, 'Jelix 1.2'),
(3, 'Jelix 1.3'),
(4, 'Jelix dev'),
(5, 'Jelix 1.4'),
(6, 'Jelix 1.5'),
(7, 'Jelix 1.6'),
(8, 'Jelix 1.7'),
(9, 'Jelix 1.8');

--
-- Structure de la table `boo_items_modifs`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%boo_items_modifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `field` varchar(40) NOT NULL,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Structure de la table `boo_versions_modifs`
--

CREATE TABLE IF NOT EXISTS `%%PREFIX%%boo_versions_modifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version_id` int(11) NOT NULL,
  `field` varchar(40) NOT NULL,
  `old_value` varchar(255) NOT NULL,
  `new_value` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;