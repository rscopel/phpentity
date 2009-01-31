
--
-- Table structure for table `_template`
--

DROP TABLE IF EXISTS `_template`;
CREATE TABLE IF NOT EXISTS `_template` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date_created` datetime NOT NULL,
  `date_modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `viewable` tinyint(3) unsigned NOT NULL default '1',
  `deleted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

