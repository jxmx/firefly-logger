--
-- Table structure for table `qso`
--

DROP TABLE IF EXISTS `qso`;

CREATE TABLE `qso` (
  `qkey` int(10) unsigned NOT NULL,
  `callsign` varchar(10) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `band` varchar(6) DEFAULT NULL,
  `class` varchar(4) DEFAULT NULL,
  `mode` varchar(10) DEFAULT NULL,
  `station` varchar(10) DEFAULT NULL,
  `section` varchar(4) DEFAULT NULL,
  `operator` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`qkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

