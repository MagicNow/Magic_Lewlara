CREATE TABLE `midia_pastas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `midias` ADD `pasta_id` INT  NULL  DEFAULT NULL  AFTER `alinhamento`;
ALTER TABLE `midias` CHANGE `pasta_id` `pasta_id` INT(11)  UNSIGNED  NULL  DEFAULT NULL;
ALTER TABLE `midias` ADD CONSTRAINT `midias_pasta_id` FOREIGN KEY (`pasta_id`) REFERENCES `midia_pastas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
