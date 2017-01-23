-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 23, 2016 at 09:23 PM
-- Server version: 5.5.53-0+deb8u1
-- PHP Version: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `codx`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE IF NOT EXISTS `administrators` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `admin_uid` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `power` int(11) DEFAULT '20',
  `steam_profile_url` varchar(255) DEFAULT NULL,
  `extra_1` varchar(255) DEFAULT NULL,
  `extra_2` varchar(255) DEFAULT NULL,
  `extra_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`id`, `username`, `admin_uid`, `password`, `power`, `steam_profile_url`, `extra_1`, `extra_2`, `extra_3`) VALUES
(2, 'S!r.ReaDy', 4899889, 'efacc4001e857f7eba4ae781c2932dedf843865e', 100, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banned_players`
--

CREATE TABLE IF NOT EXISTS `banned_players` (
`id` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `time` varchar(255) DEFAULT NULL,
  `map` varchar(255) DEFAULT NULL,
  `guid` varchar(255) DEFAULT NULL,
  `banned_by` varchar(255) DEFAULT NULL,
  `admin_uid` varchar(255) DEFAULT NULL,
  `screenshot_url` varchar(255) DEFAULT NULL,
  `extra_1` varchar(255) DEFAULT NULL,
  `extra_2` varchar(255) DEFAULT NULL,
  `extra_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comunity`
--

CREATE TABLE IF NOT EXISTS `comunity` (
`id` int(11) NOT NULL,
  `comunity_name` varchar(255) NOT NULL,
  `server_rules` text NOT NULL,
  `comunity_web` varchar(255) DEFAULT NULL,
  `extra_1` varchar(255) DEFAULT NULL,
  `extra_2` varchar(255) DEFAULT NULL,
  `extra_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comunity`
--

INSERT INTO `comunity` (`id`, `comunity_name`, `server_rules`, `comunity_web`, `extra_1`, `extra_2`, `extra_3`) VALUES
(1, 'Capitains.com - GamingCommunity!', 'Rule #1: No Racism OF Any Kind OR Cheating OF Any Sort!\r\n\r\nRule #2: No clan stacking, members must split evenly between the teams\r\n\r\nRule #3: No arguing with admins (listen and learn or leave)\r\n\r\nRule #4: No game exploit abusing (fast fire, elevators,..)\r\n\r\nRule #5: No offensive or potentially offensive names\r\n\r\nRule #6: No recruiting for your clan, your server, or anything else\r\n\r\nRule #7: No advertising or spamming of websites or servers\r\n\r\nRule #8: No profanity or offensive language (in any language)\r\n\r\nRule #9: All players must play for the objective and support their team\r\n\r\nRule #10: Spawn Camp IS Not Allowed IN Defence And Attack , Camp IS Not Allowed For Attack.\r\n\r\nRule #11: Martydom , Last Stand , Jugger , Grenade Launcher IS Not Allowed.\r\n', 'www.capitains.com', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `online_players`
--

CREATE TABLE IF NOT EXISTS `online_players` (
`id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `player_slot` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `player_score` int(11) NOT NULL,
  `player_playingtime` varchar(255) NOT NULL,
  `player_country` varchar(20) DEFAULT NULL,
  `extra_1` varchar(255) DEFAULT NULL,
  `extra_2` varchar(255) DEFAULT NULL,
  `extra_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3525940 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `online_players`
--

INSERT INTO `online_players` (`id`, `server_id`, `player_slot`, `player_name`, `player_score`, `player_playingtime`, `player_country`, `extra_1`, `extra_2`, `extra_3`) VALUES
(3525923, 20, 1, 'FoG Spiner', 93, '48:32', 'nun', NULL, NULL, NULL),
(3525924, 20, 2, 'Orthodox', 33, '24:52', 'nun', NULL, NULL, NULL),
(3525925, 20, 3, 'FoG D1plo', 103, '47:36', 'nun', NULL, NULL, NULL),
(3525926, 20, 4, 'Manutrix11', 38, '24:32', 'nun', NULL, NULL, NULL),
(3525927, 20, 5, 'Grom', 68, '16:24', 'nun', NULL, NULL, NULL),
(3525928, 20, 6, 'Lensko', 48, '15:23', 'nun', NULL, NULL, NULL),
(3525929, 20, 7, 'tilus', 0, '00:44', 'nun', NULL, NULL, NULL),
(3525930, 20, 8, '|EZ|007', 15, '13:01', 'nun', NULL, NULL, NULL),
(3525931, 20, 9, 'STARFIRE', 5, '05:41', 'nun', NULL, NULL, NULL),
(3525932, 21, 1, 'X-IV No Hope :)', 0, '08:23', 'nun', NULL, NULL, NULL),
(3525933, 21, 2, 'new era', 0, '01:26:41', 'nun', NULL, NULL, NULL),
(3525934, 21, 3, 'captv.tk', 0, '23:10:44', 'nun', NULL, NULL, NULL),
(3525935, 21, 4, 'Bahamut', 0, '01:24', 'nun', NULL, NULL, NULL),
(3525936, 21, 5, 'oldhitman', 0, '04:58', 'nun', NULL, NULL, NULL),
(3525937, 21, 6, 'Cap|Mistrija', 0, '15:53', 'nun', NULL, NULL, NULL),
(3525938, 21, 7, 'qLimAxzU | A.E', 0, '17:28', 'nun', NULL, NULL, NULL),
(3525939, 21, 8, '[RK]SDK Vancouv', 0, '20:46', 'nun', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE IF NOT EXISTS `servers` (
`id` int(11) NOT NULL,
  `server_name` varchar(255) DEFAULT NULL,
  `server_ip` varchar(255) NOT NULL,
  `server_port` int(11) NOT NULL,
  `steam_group_url` varchar(255) NOT NULL,
  `ftp_username` varchar(255) NOT NULL,
  `ftp_password` varchar(255) NOT NULL,
  `ftp_ip` varchar(255) DEFAULT NULL,
  `ftp_port` int(11) DEFAULT NULL,
  `adminactions_log` varchar(255) DEFAULT NULL,
  `server_maxplayers` int(11) DEFAULT NULL,
  `server_online_players` int(11) DEFAULT NULL,
  `server_game` varchar(255) DEFAULT 'Call of Duty 4 Server',
  `server_current_map` varchar(255) DEFAULT NULL,
  `server_current_map_alias` varchar(255) NOT NULL,
  `server_location` varchar(10) NOT NULL,
  `screenshots_dir` varchar(255) NOT NULL,
  `server_rcon` varchar(255) DEFAULT NULL,
  `last_refresh` datetime DEFAULT NULL,
  `extra_1` varchar(255) DEFAULT NULL,
  `extra_2` varchar(255) DEFAULT NULL,
  `extra_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `server_name`, `server_ip`, `server_port`, `steam_group_url`, `ftp_username`, `ftp_password`, `ftp_ip`, `ftp_port`, `adminactions_log`, `server_maxplayers`, `server_online_players`, `server_game`, `server_current_map`, `server_current_map_alias`, `server_location`, `screenshots_dir`, `server_rcon`, `last_refresh`, `extra_1`, `extra_2`, `extra_3`) VALUES
(1, 'Capitains ClanWars 1.8x Server || Capitains.com ||', '164.132.233.19', 29111, 'http://steamcommunity.com/groups/CapCommunity', 'l2g_user_9_6', 'tbW8npcx', NULL, NULL, NULL, 21, 0, NULL, 'Crash', 'mp_crash', 'IT', '/home/l2g_user_9_6/screenshots', NULL, '2016-12-23 09:23:03', NULL, NULL, NULL),
(20, '^1Capitains ^2Clan ^0ProModX', '164.132.233.19', 27000, 'http://steamcommunity.com/groups/CapCommunity', '', '', NULL, NULL, NULL, 24, 9, 'Call of Duty 4 - Modern Warfare', 'Strike', 'mp_strike', 'IT', '', NULL, '2016-12-23 09:23:03', NULL, NULL, NULL),
(21, '^1Capitains ^2SnD ^3Hardcore ^5|| ^6Capitains^1.^6com ^5||^7', '164.132.233.19', 28600, 'http://steamcommunity.com/groups/CapCommunity/', '', '', NULL, NULL, NULL, 21, 8, 'Call of Duty 4 - Modern Warfare', 'Crash', 'mp_crash', 'IT', '', NULL, '2016-12-23 09:23:03', NULL, NULL, NULL),
(22, '^1Capitains ^2Clan ^0SniperX', '164.132.233.19', 27001, 'http://steamcommunity.com/groups/CapCommunity/', '', '', NULL, NULL, NULL, 24, 0, 'Call of Duty 4 - Modern Warfare', 'Backlot', 'mp_backlot', 'IT', '', NULL, '2016-12-23 09:23:03', NULL, NULL, NULL),
(23, '^1Capitains ^1ClanWars ^3Server ^5|| ^6Capitains^1.^6com ^5||^7', '164.132.233.19', 29100, 'http://steamcommunity.com/groups/CapCommunity/', '', '', NULL, NULL, NULL, 18, 0, 'Call of Duty 4 - Modern Warfare', 'District', 'mp_citystreets', 'IT', '', NULL, '2016-12-23 09:23:03', NULL, NULL, NULL),
(24, '^1Capitains ^2F^3oo^2t^3B^2a^3ll ^5|| ^6Capitains^1.^6com ^5||^7', '164.132.233.19', 20016, 'http://steamcommunity.com/groups/CapCommunity/', '', '', NULL, NULL, NULL, 47, 0, 'Call of Duty 4 - Modern Warfare', 'mp_soccer_arena', 'mp_soccer_arena', 'IT', '', NULL, '2016-12-23 09:23:03', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `server_maps`
--

CREATE TABLE IF NOT EXISTS `server_maps` (
`id` int(11) NOT NULL,
  `map_name` varchar(255) NOT NULL,
  `friendly_map_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `server_maps`
--

INSERT INTO `server_maps` (`id`, `map_name`, `friendly_map_name`) VALUES
(5, 'mp_backlot', 'Backlot'),
(6, 'mp_bloc', 'Bloc'),
(7, 'mp_bog', 'Bog'),
(8, 'mp_broadcast', 'Broadcast'),
(9, 'mp_carentan', 'Chinatown'),
(10, 'mp_cargoship', 'Wet Work'),
(11, 'mp_citystreets', 'District'),
(12, 'mp_convoy', 'Ambush'),
(13, 'mp_countdown', 'Countdown'),
(14, 'mp_crash', 'Crash'),
(15, 'mp_crash_snow', 'Crash Snow'),
(16, 'mp_creek', 'Creek'),
(17, 'mp_crossfire', 'Crossfire'),
(18, 'mp_farm', 'Downpour'),
(19, 'mp_killhouse', 'Killhouse'),
(20, 'mp_overgrown', 'Overgrown'),
(21, 'mp_pipeline', 'Pipeline'),
(22, 'mp_shipment', 'Shipment'),
(23, 'mp_showdown', 'Showdown'),
(24, 'mp_strike', 'Strike'),
(25, 'mp_vacant', 'Vacant'),
(26, 'mp_nuketown', 'Nuketown');

-- --------------------------------------------------------

--
-- Table structure for table `srv_settings`
--

CREATE TABLE IF NOT EXISTS `srv_settings` (
`id` int(11) NOT NULL,
  `use_cronjob` varchar(2) NOT NULL DEFAULT '0',
  `use_rcon` varchar(2) NOT NULL DEFAULT '0',
  `min_power_for_rcon` varchar(255) NOT NULL DEFAULT '100',
  `use_steam_authentication` varchar(2) NOT NULL DEFAULT '0',
  `use_lightbox` varchar(2) NOT NULL DEFAULT '0',
  `extra_1` varchar(255) DEFAULT NULL,
  `extra_2` varchar(255) DEFAULT NULL,
  `extra_3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `srv_settings`
--

INSERT INTO `srv_settings` (`id`, `use_cronjob`, `use_rcon`, `min_power_for_rcon`, `use_steam_authentication`, `use_lightbox`, `extra_1`, `extra_2`, `extra_3`) VALUES
(1, '0', '1', '100', '0', '1', '12345678', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banned_players`
--
ALTER TABLE `banned_players`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comunity`
--
ALTER TABLE `comunity`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_players`
--
ALTER TABLE `online_players`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `server_maps`
--
ALTER TABLE `server_maps`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `srv_settings`
--
ALTER TABLE `srv_settings`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `banned_players`
--
ALTER TABLE `banned_players`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comunity`
--
ALTER TABLE `comunity`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `online_players`
--
ALTER TABLE `online_players`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3525940;
--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `server_maps`
--
ALTER TABLE `server_maps`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `srv_settings`
--
ALTER TABLE `srv_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
