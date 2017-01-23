--
-- Database: `cod4status`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminactions`
--

CREATE TABLE `adminactions` (
  `id` int(11) NOT NULL,
  `admin_uid` varchar(255) NOT NULL,
  `admin_action` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `admin_uid` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `power` int(11) DEFAULT '20'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`id`, `username`, `admin_uid`, `password`, `power`) VALUES
(2, 'admin', 0, 'efacc4001e857f7eba4ae781c2932dedf843865e', 100);

-- --------------------------------------------------------

--
-- Table structure for table `banned_players`
--

CREATE TABLE `banned_players` (
  `id` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `map` varchar(255) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `banned_by` varchar(255) NOT NULL,
  `admin_uid` varchar(255) NOT NULL,
  `screenshot_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comunity`
--

CREATE TABLE `comunity` (
  `id` int(11) NOT NULL,
  `comunity_name` varchar(255) NOT NULL,
  `server_rules` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comunity`
--

INSERT INTO `comunity` (`id`, `comunity_name`, `server_rules`) VALUES
(1, 'My comunity Name', 'Rule #1: No racism of any kind\r\n\r\nRule #2: Camp is allowed only with sniper, max camp time without sniper 5-10 sec\r\n\r\nRule #3: No arguing with admins (listen and learn or leave)\r\n\r\nRule #4: No abusive language or behavior towards admins or other players\r\n\r\nRule #5: No offensive or potentially offensive names, annoying names\r\n\r\nRule #6: No recruiting for your clan, your server, or anything else\r\n\r\nRule #7: No advertising or spamming of websites or servers\r\n\r\nRule #8: No profanity or offensive language (in any language)');

-- --------------------------------------------------------

--
-- Table structure for table `online_players`
--

CREATE TABLE `online_players` (
  `id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `player_slot` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `player_score` int(11) NOT NULL,
  `player_ping` int(11) NOT NULL,
  `player_country` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `players_stats`
--

CREATE TABLE `players_stats` (
  `players` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `player_ip` varchar(255) NOT NULL,
  `played_time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `server_name` varchar(255) DEFAULT NULL,
  `server_ip` varchar(255) DEFAULT NULL,
  `server_port` int(11) DEFAULT NULL,
  `ftp_username` varchar(255) DEFAULT NULL,
  `ftp_password` varchar(255) DEFAULT NULL,
  `server_maxplayers` int(11) DEFAULT NULL,
  `server_online_players` int(11) DEFAULT NULL,
  `server_game` varchar(255) DEFAULT 'Call of Duty 4 Server',
  `server_current_map` varchar(255) DEFAULT NULL,
  `server_current_map_alias` varchar(255) DEFAULT None,
  `server_location` varchar(10) DEFAULT None,
  `screenshots_dir` varchar(255) DEFAULT NULL,
  `last_refresh` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminactions`
--
ALTER TABLE `adminactions`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `players_stats`
--
ALTER TABLE `players_stats`
  ADD PRIMARY KEY (`players`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminactions`
--
ALTER TABLE `adminactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `banned_players`
--
ALTER TABLE `banned_players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=605;
--
-- AUTO_INCREMENT for table `comunity`
--
ALTER TABLE `comunity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `online_players`
--
ALTER TABLE `online_players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1892685;
--
-- AUTO_INCREMENT for table `players_stats`
--
ALTER TABLE `players_stats`
  MODIFY `players` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
