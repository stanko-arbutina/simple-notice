SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `kolegiji` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `naziv_kolegija` varchar(50) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `web_stranica` varchar(60) NOT NULL,
  `last_check` datetime NOT NULL,
  `check_hash` char(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

INSERT INTO `kolegiji` (`ID`, `naziv_kolegija`, `owner_id`, `web_stranica`, `last_check`, `check_hash`) VALUES
(17, 'Perin kolegij', 56793, 'www.perin-kolegij.com', '0000-00-00 00:00:00', ''),
(55, 'Baze podataka', 56788, 'http://web.math.hr/~karaga/novevbaze.html', '0000-00-00 00:00:00', ''),
(56, 'IzraÄunljivost', 56788, 'http://web.math.hr/~vukovic/dodiplomska_nastava.htm', '0000-00-00 00:00:00', ''),
(57, 'Programiranje C', 56788, '', '0000-00-00 00:00:00', ''),
(58, 'KonaÄne geometrije', 56788, 'web.math.hr/nastava/kg', '0000-00-00 00:00:00', ''),
(59, 'NjemaÄki jezik', 56788, '', '0000-00-00 00:00:00', ''),
(60, 'MatematiÄka logika', 56788, '', '0000-00-00 00:00:00', '');

CREATE TABLE IF NOT EXISTS `menu_items` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `page` varchar(10) NOT NULL,
  `action` varchar(10) NOT NULL,
  `order_num` tinyint(4) NOT NULL,
  `text` varchar(40) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

INSERT INTO `menu_items` (`ID`, `type`, `page`, `action`, `order_num`, `text`) VALUES
(8, 'All', 'Home', 'Index', 1, 'Naslovna'),
(9, 'All', 'Settings', 'Edit', 2, 'Postavke'),
(10, 'Admin', 'Users', 'Index', 3, 'Korisnici'),
(11, 'Admin', 'Studiji', 'Index', 4, 'Studiji'),
(12, 'All', 'Home', 'Logout', 100, 'Odjava'),
(13, 'Profesor', 'Kolegiji', 'Index', 5, 'Kolegiji'),
(14, 'Profesor', 'Obavijesti', 'Index', 7, 'Obavijesti');

CREATE TABLE IF NOT EXISTS `obavijesti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Naslov` varchar(60) NOT NULL,
  `tekst_obavijesti` text NOT NULL,
  `vrsta_obavijesti` enum('Pravila polaganja','Kolokvij','Upis ocjena','Izmjena rasporeda','Ostalo') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `istek` tinyint(1) NOT NULL,
  `Datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

INSERT INTO `obavijesti` (`ID`, `Naslov`, `tekst_obavijesti`, `vrsta_obavijesti`, `istek`, `Datum`, `owner_id`) VALUES
(49, 'Rezultati kolokvija i upis ocjena', '<p>Biti Ä‡e <em>nekog datuma</em>. U prostoriji 505.</p>', 'Upis ocjena', 1, '2011-07-03 01:23:32', 56788),
(50, 'Kupnja rijeÄnika', '<p>Treba kupiti rijeÄnik.</p>', 'Ostalo', 0, '2011-07-03 01:24:27', 56788),
(51, 'Novi termin srijedom', '<p>U 15:00 u 105</p>', 'Pravila polaganja', 0, '2011-07-03 01:24:56', 56788),
(52, 'Neka obavijest za sve kolegije', '<p><strong>VaÅ¾na obavijest!</strong></p>', 'Ostalo', 0, '2011-07-03 01:26:06', 56788);

CREATE TABLE IF NOT EXISTS `ref_kolegiji_studiji` (
  `kolegij_id` int(11) NOT NULL,
  `studij_id` int(11) NOT NULL,
  `godina` int(11) NOT NULL,
  KEY `studij_id` (`studij_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ref_kolegiji_studiji` (`kolegij_id`, `studij_id`, `godina`) VALUES
(56, 14, 1),
(56, 12, 1),
(55, 13, 3),
(55, 11, 3),
(55, 12, 1),
(59, 11, 2),
(58, 14, 2),
(58, 14, 1),
(58, 12, 2),
(58, 12, 1),
(57, 13, 1),
(57, 11, 1),
(59, 13, 2),
(60, 12, 1),
(60, 11, 3);

CREATE TABLE IF NOT EXISTS `ref_obavijesti_kolegiji` (
  `obavijest_id` int(11) NOT NULL,
  `kolegij_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ref_obavijesti_kolegiji` (`obavijest_id`, `kolegij_id`) VALUES
(52, 55),
(51, 56),
(50, 59),
(49, 58),
(49, 55),
(52, 56),
(52, 57),
(52, 58),
(52, 59),
(52, 60);

CREATE TABLE IF NOT EXISTS `ref_studenti_kolegiji` (
  `student_id` int(11) NOT NULL,
  `kolegij_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ref_studenti_kolegiji` (`student_id`, `kolegij_id`) VALUES
(56792, 60),
(56792, 55),
(56792, 56),
(56792, 59);

CREATE TABLE IF NOT EXISTS `studiji` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ime_studija` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `broj_godina` enum('1','2','3','4','5','6','7','8') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

INSERT INTO `studiji` (`ID`, `ime_studija`, `broj_godina`) VALUES
(12, 'Diplomski studij matematika i raÄunarstvo', '2'),
(11, 'Preddiplomski studij matematika', '3'),
(13, 'Preddiplomski studij matematika - profesorski smjer', '3'),
(14, 'Diplomski studij primjenjena matematika', '2');

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `hashed_password` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Email` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Type` enum('Admin','Profesor','Student') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56794 ;

INSERT INTO `users` (`ID`, `Username`, `hashed_password`, `Email`, `Type`) VALUES
(29, 'Administrator', 'e6427442dd58e1ad3a0637da8c777713', '', 'Admin'),
(56792, 'Student', 'e6427442dd58e1ad3a0637da8c777713', 'student@math.hr', 'Student'),
(56788, 'Profesor', 'e6427442dd58e1ad3a0637da8c777713', '', 'Profesor');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
