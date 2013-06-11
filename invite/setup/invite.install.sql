CREATE TABLE IF NOT EXISTS `cot_invite_sent` (
  `ins_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ins_byuser` int(11) NOT NULL,
  `ins_date` int(11) NOT NULL,
  UNIQUE KEY `ins_email` (`ins_email`),
  KEY `ins_byuser` (`ins_byuser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `cot_invite` (
  `inv_byuser` int(11) NOT NULL,
  `inv_userid` int(11) NOT NULL,
  `inv_date` int(11) NOT NULL,
  `inv_status` tinyint(1) NOT NULL,
  UNIQUE KEY `inv_userid` (`inv_userid`),
  KEY `inv_byuser` (`inv_byuser`),
  KEY `inv_date` (`inv_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;