-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `covid_records`;

DROP TABLE IF EXISTS `records`;
CREATE TABLE `records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `contact` varchar(11) NOT NULL,
  `bodyTemp` decimal(5,2) NOT NULL,
  `covidDiagnosed` enum('Yes','No') NOT NULL,
  `covidEncounter` enum('Yes','No') NOT NULL,
  `vaccinated` enum('Yes','No') NOT NULL,
  `nationality` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE `records`;
INSERT INTO `records` (`id`, `firstname`, `lastname`, `dob`, `contact`, `bodyTemp`, `covidDiagnosed`, `covidEncounter`, `vaccinated`, `nationality`) VALUES
(1,	'Savannah',	'Arnold',	'2003-06-26',	'09111111111',	48.00,	'No',	'Yes',	'No',	'In qui sint inventor'),
(2,	'Serina',	'Moreno',	'1977-05-03',	'09888888888',	46.00,	'No',	'Yes',	'Yes',	'Filipino'),
(3,	'Edward',	'Burgess',	'1987-06-27',	'09888888888',	73.00,	'Yes',	'No',	'Yes',	'American'),
(4,	'Len',	'Knapp',	'2020-01-06',	'09444444444',	75.00,	'Yes',	'No',	'No',	'Dolore eius proident'),
(5,	'Elvis',	'Flynn',	'2011-07-22',	'09777777777',	1.00,	'No',	'No',	'Yes',	'Non fugiat laudantiu'),
(6,	'Bree',	'Calhoun',	'2019-01-12',	'09898989898',	8.00,	'No',	'Yes',	'Yes',	'Voluptatum fugit ev'),
(7,	'Darrel',	'Tate',	'1970-12-10',	'09147436456',	40.12,	'Yes',	'Yes',	'No',	' Chinese'),
(8,	'TaShya',	'Dudley',	'1989-09-10',	'09336814875',	35.00,	'Yes',	'No',	'No',	'American');

-- 2023-03-27 00:42:56
