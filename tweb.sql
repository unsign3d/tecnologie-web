-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Gen 23, 2015 alle 17:23
-- Versione del server: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tweb`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `access_log`
--

CREATE TABLE IF NOT EXISTS `access_log` (
`id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date_access` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `access_log`
--

INSERT INTO `access_log` (`id`, `id_user`, `date_access`) VALUES
(1, 2, '2015-01-11 16:54:17'),
(2, 1, '2015-01-11 16:55:14'),
(3, 1, '2015-01-11 16:55:40'),
(4, 1, '2015-01-11 16:55:49'),
(5, 1, '2015-01-11 21:23:18'),
(6, 1, '2015-01-12 11:04:31'),
(7, 4, '2015-01-12 11:09:21'),
(8, 1, '2015-01-12 19:18:19'),
(9, 1, '2015-01-13 12:28:34'),
(10, 1, '2015-01-13 16:05:36'),
(11, 1, '2015-01-13 16:50:12'),
(12, 1, '2015-01-13 18:20:59'),
(13, 1, '2015-01-13 18:31:07'),
(14, 2, '2015-01-14 23:40:43'),
(15, 1, '2015-01-15 13:44:26'),
(16, 1, '2015-01-16 12:22:22'),
(17, 1, '2015-01-16 15:53:42'),
(18, 1, '2015-01-21 16:48:20'),
(19, 1, '2015-01-21 17:15:19'),
(20, 1, '2015-01-23 10:30:37'),
(21, 1, '2015-01-23 11:12:20');

--
-- Trigger `access_log`
--
DELIMITER //
CREATE TRIGGER `access_log_data` BEFORE INSERT ON `access_log`
 FOR EACH ROW SET NEW.date_access = CURRENT_TIMESTAMP
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
`id` int(11) NOT NULL,
  `user` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(45) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`id`, `user`, `password`) VALUES
(1, 'unsigned', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');

-- --------------------------------------------------------

--
-- Struttura della tabella `amicizia`
--

CREATE TABLE IF NOT EXISTS `amicizia` (
`id` int(11) NOT NULL,
  `amico1` int(11) NOT NULL,
  `amico2` int(11) NOT NULL,
  `accettata` smallint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `amicizia`
--

INSERT INTO `amicizia` (`id`, `amico1`, `amico2`, `accettata`) VALUES
(1, 1, 2, 0),
(3, 3, 1, 0),
(4, 1, 4, 1),
(5, 1, 1, 1),
(6, 2, 2, 1),
(7, 3, 3, 1),
(8, 4, 4, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE IF NOT EXISTS `commenti` (
`id` int(11) NOT NULL,
  `testo` mediumtext NOT NULL,
  `id_notizia` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `commenti`
--

INSERT INTO `commenti` (`id`, `testo`, `id_notizia`, `id_utente`, `data`) VALUES
(1, 'asdfasdf', 1, 1, '2014-12-16 19:22:39'),
(2, 'sdasdfdsfasdfsdaf', 1, 1, '2014-12-16 19:22:39'),
(3, 'asdfasdfsdfsdafs', 1, 1, '2014-12-16 19:22:39'),
(4, 'questa è una prova', 9, 1, '2014-12-18 23:15:26'),
(5, 'commento', 9, 1, '2014-12-18 23:15:57'),
(6, 'questo &egrave; un altro commento ''', 9, 2, '2014-12-18 23:31:58'),
(7, 'She''s up all night till the sun,\r\nI''m up all night to get some.\r\nShe''s up all night for good fun,\r\nI''m up all night to get lucky.', 11, 2, '2014-12-19 12:57:20'),
(8, 'asdf', 11, 2, '2014-12-19 13:03:27'),
(9, 'We''re up all night till the sun,\r\nWe''re up all night to get some.\r\nWe''re up all night for good fun', 11, 2, '2014-12-19 13:04:20'),
(10, 'WEFAWEFEEFWFWF', 16, 1, '2015-01-21 16:52:57'),
(11, 'ASDFASDFASDFAFD', 16, 1, '2015-01-21 16:53:11');

--
-- Trigger `commenti`
--
DELIMITER //
CREATE TRIGGER `commenti_data` BEFORE INSERT ON `commenti`
 FOR EACH ROW SET NEW.data = CURRENT_TIMESTAMP
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `like`
--

CREATE TABLE IF NOT EXISTS `like` (
`id` int(11) NOT NULL,
  `id_utente` int(11) DEFAULT NULL,
  `id_notizia` int(11) DEFAULT NULL,
  `like` smallint(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `like`
--

INSERT INTO `like` (`id`, `id_utente`, `id_notizia`, `like`) VALUES
(4, 2, 10, 0),
(7, 2, 9, 0),
(9, 1, 11, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `notizie`
--

CREATE TABLE IF NOT EXISTS `notizie` (
`id` int(11) NOT NULL,
  `testo` varchar(140) NOT NULL,
  `data` datetime NOT NULL,
  `id_utente` int(11) NOT NULL,
  `like` int(11) DEFAULT '0',
  `dislike` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `notizie`
--

INSERT INTO `notizie` (`id`, `testo`, `data`, `id_utente`, `like`, `dislike`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vitae gravida nisl, ac consequat elit. Fusce sem neque, rutrum in gravida e', '2014-12-16 14:16:34', 1, 0, 0),
(2, 'In eget enim et risus aliquet viverra. Proin et ultrices leo, ut vehicula tellus. Curabitur sit amet magna dignissim arcu ultrices ultricies', '2014-12-16 14:18:50', 2, 0, 0),
(3, 'Donec at tortor auctor, malesuada turpis vel, egestas felis. Morbi a leo vitae orci mattis pharetra eu vel lectus. Fusce ultrices vitae risu', '2014-12-16 14:18:50', 3, 0, 0),
(4, 'In eget enim et risus aliquet viverra. Proin et ultrices leo, ut vehicula tellus. Curabitur sit amet magna dignissim arcu ultrices ultricies', '2014-12-16 14:18:50', 4, 0, 0),
(5, 'Donec at tortor auctor, malesuada turpis vel, egestas felis. Morbi a leo vitae orci mattis pharetra eu vel lectus. Fusce ultrices vitae risu', '2014-12-16 14:18:50', 2, 0, 0),
(6, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vitae gravida nisl, ac consequat elit. Fusce sem neque, rutrum in gravida e', '2014-12-16 14:18:50', 3, 0, 0),
(9, 'altro stato', '2014-12-17 16:11:57', 1, 1, 0),
(10, 'Once I rose above the noise and confusion Just to get a glimpse beyond this illusion I was soaring ever higher, but I flew too high', '2014-12-18 23:34:08', 2, 1, 0),
(11, 'We''ve come too far To give up who we are, So let''s raise the bar And our cups to the stars.', '2014-12-19 12:56:54', 2, 1, 0),
(12, 'Somebody shine a light I''m frozen by the fear in me Somebody make me feel alive', '2014-12-19 13:08:32', 1, 0, 0),
(14, 'questa notizia &egrave; del 2015', '2015-01-11 17:12:24', 1, 0, 0),
(15, 'prova prova', '2015-01-12 11:08:50', 1, 0, 0),
(16, 'asdfasdfasdf', '2015-01-12 18:59:24', 1, 0, 0);

--
-- Trigger `notizie`
--
DELIMITER //
CREATE TRIGGER `notizie_data` BEFORE INSERT ON `notizie`
 FOR EACH ROW SET NEW.data = CURRENT_TIMESTAMP
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
`id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `nome_cognome` varchar(45) NOT NULL,
  `immagine` varchar(150) DEFAULT NULL,
  `testo` mediumtext,
  `eta` int(11) DEFAULT NULL,
  `citta` varchar(50) DEFAULT NULL,
  `cittanatale` varchar(50) DEFAULT NULL,
  `hobbies` varchar(180) DEFAULT NULL,
  `studio` varchar(180) DEFAULT NULL,
  `email` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `password`, `nome_cognome`, `immagine`, `testo`, `eta`, `citta`, `cittanatale`, `hobbies`, `studio`, `email`) VALUES
(1, 'unsigned', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Luca Bruzzone', '1781349811.jpg', '<h2 id="educazione">Ho studiato</h2>\n  <p>Istututo tecnico gestionale Buniva, diploma</p>\n  <p>Università di Torino, corrente</p>\n  <h2 id="residenza">Vivo a</h2>\n  <p>Torino</p>\n  <h2 id="provenienza">Nato a</h2>\n  <p>Genova</p>\n  <h2>Hobbies</h2>\n  <p id="hobbies">Leggere, guardare serie tv, sport</p>\n', 22, 'Torino', 'Genova', 'Tanti hobbies oppure no', 'Pinerolo (TO) IIS Michele buniva', 'asdfasdf'),
(2, 'tiziaseduta', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Tizia Seduta', 'tizia1.jpg', '<h2 id="educazione">Ho studiato</h2>\n        <p>Liceo scientifico, TO</p>\n        <p>Università di Torino, corrente</p>\n        <h2 id="residenza">Vivo a</h2>\n        <p>Torino</p>\n        <h2 id="provenienza">Nata a</h2>\n        <p>Venezia</p>\n        <h2>Hobbies</h2>\n        <p id="hobbies">Film, passeggiare, musica</p>\n', 21, 'Torino', 'Venezia', 'Stare seduta', 'Da nessuna parte', 'tiziaseduta@hotmail.it'),
(3, 'tizioaria', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Tizio In Aria', 'tizio1.jpg', '<h2 id="educazione">Ho studiato</h2>\n        <p>Istituto tecnico</p>\n        <p>Università di Torino, corrente</p>\n        <h2 id="residenza">Vivo a</h2>\n        <p>Torino</p>\n        <h2 id="provenienza">Nato a</h2>\n        <p>Milano</p>\n        <h2>Hobbies</h2>\n        <p id="hobbies">Sport, fotografia</p>\n', 24, 'Roma', NULL, NULL, NULL, ''),
(4, 'ziaocchio', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Zia Occhio', 'tizia2.jpg', '<h2 id="educazione">Ho studiato</h2>\n        <p>Liceo artistico</p>\n        <p>Università di Torino, corrente</p>\n        <h2 id="residenza">Vivo a</h2>\n        <p>Torino</p>\n        <h2 id="provenienza">Nato a</h2>\n        <p>Vicenza</p>\n        <h2>Hobbies</h2>\n        <p id="hobbies">Pittura, arte, foto</p>\n', 18, 'Venezia', NULL, NULL, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_log`
--
ALTER TABLE `access_log`
 ADD PRIMARY KEY (`id`), ADD KEY `user_idx` (`id_user`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_UNIQUE` (`user`);

--
-- Indexes for table `amicizia`
--
ALTER TABLE `amicizia`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `unique_index_amicizia` (`amico1`,`amico2`), ADD KEY `amico1_idx` (`amico1`), ADD KEY `amico2_idx` (`amico2`);

--
-- Indexes for table `commenti`
--
ALTER TABLE `commenti`
 ADD PRIMARY KEY (`id`), ADD KEY `utente_idx` (`id_utente`), ADD KEY `notizia_idx` (`id_notizia`), ADD KEY `utente_id_notizia` (`id_utente`), ADD KEY `notizia_id_notizia` (`id_notizia`);

--
-- Indexes for table `like`
--
ALTER TABLE `like`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uniq_like` (`id_utente`,`id_notizia`), ADD KEY `utente_idx` (`id_utente`), ADD KEY `notizie_idx` (`id_notizia`);

--
-- Indexes for table `notizie`
--
ALTER TABLE `notizie`
 ADD PRIMARY KEY (`id`), ADD KEY `utente_idx` (`id_utente`), ADD KEY `data` (`data`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `amicizia`
--
ALTER TABLE `amicizia`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `commenti`
--
ALTER TABLE `commenti`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `notizie`
--
ALTER TABLE `notizie`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `utenti`
--
ALTER TABLE `utenti`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `access_log`
--
ALTER TABLE `access_log`
ADD CONSTRAINT `user` FOREIGN KEY (`id_user`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `amicizia`
--
ALTER TABLE `amicizia`
ADD CONSTRAINT `amico1` FOREIGN KEY (`amico1`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `amico2` FOREIGN KEY (`amico2`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
ADD CONSTRAINT `notizia_commento` FOREIGN KEY (`id_notizia`) REFERENCES `notizie` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `utente_commento` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `like`
--
ALTER TABLE `like`
ADD CONSTRAINT `notizia1` FOREIGN KEY (`id_notizia`) REFERENCES `notizie` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `user1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `notizie`
--
ALTER TABLE `notizie`
ADD CONSTRAINT `utente` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
