-- --------------------------------------------------------

--
-- Table structure for table `tthwQ`
--

CREATE TABLE IF NOT EXISTS `tthwQ` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `divid` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `src` tinytext NOT NULL,
  `requestor` varchar(3) NOT NULL,
  `shown` tinyint(1) NOT NULL DEFAULT '0',
  `playing` tinyint(1) NOT NULL DEFAULT '0',
  `muted` tinyint(4) NOT NULL,
  `options` tinytext NOT NULL,
  `incept` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Table structure for table `tthwCMD`
--

CREATE TABLE IF NOT EXISTS `tthwCMD` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `divid` int(11) NOT NULL,
  `cmd` varchar(255) NOT NULL,
  `itemid` int(11) NOT NULL,
  `incept` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;