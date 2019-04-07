SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- CREATE DATABASE
--
DROP DATABASE IF EXISTS `reversi_db`;
CREATE DATABASE IF NOT EXISTS `reversi_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `reversi_db`;

-- --------------------------------------------------------

--
-- CREATE TABLE
--

CREATE TABLE `ai` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Description` VARCHAR(100) DEFAULT NULL,
  `Remark` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ai` (`Description`, `Remark`) VALUES
('EasyAI', NULL),
('MediumAI', NULL),
('HardAI', '2'),
('ExpertAI', NULL),
('RandomAI', NULL);

-- --------------------------------------------------------

CREATE TABLE `board` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Content` VARCHAR(8000) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `game` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `UserID` INT(11) NOT NULL,
  `StartDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ModeID` INT(11) NOT NULL,
  `AIID` INT(11) DEFAULT NULL,
  `Winner` INT(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `gameboard` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `GameID` INT(11) NOT NULL,
  `BoardID` INT(11) NOT NULL,
  `Turn` INT(11) NOT NULL,
  `NextX` INT(11) DEFAULT NULL,
  `NextY` INT(11) DEFAULT NULL,
  `Mover` INT(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `mode` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Description` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `mode` (`Description`) VALUES
('Player vs Player'),
('Player vs AI');

-- --------------------------------------------------------

CREATE TABLE `record` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `BoardID` INT(11) NOT NULL,
  `NextX` INT(11) DEFAULT NULL,
  `NextY` INT(11) DEFAULT NULL,
  `Mover` INT(11) DEFAULT NULL,
  `WinCount` INT(11) DEFAULT 0,
  `TotalCount` INT(11) DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- ALTER FOREIGN KEY
--

ALTER TABLE `game`
  ADD KEY `FK_game_ModeID` (`ModeID`),
  ADD KEY `FK_game_AIID` (`AIID`);

-- --------------------------------------------------------

ALTER TABLE `gameboard`
  ADD KEY `FK_gameboard_GameID` (`GameID`),
  ADD KEY `FK_gameboard_BoardID` (`BoardID`);

-- --------------------------------------------------------

ALTER TABLE `record`
  ADD KEY `FK_record_BoardID` (`BoardID`);

-- --------------------------------------------------------

--
-- ALTER CONSTRAINT
--

ALTER TABLE `game`
  ADD CONSTRAINT `FK_game_ModeID` FOREIGN KEY (`ModeID`) REFERENCES `mode` (`ID`),
  ADD CONSTRAINT `FK_game_AIID` FOREIGN KEY (`AIID`) REFERENCES `ai` (`ID`);

-- --------------------------------------------------------

ALTER TABLE `gameboard`
  ADD CONSTRAINT `FK_gameboard_GameID` FOREIGN KEY (`GameID`) REFERENCES `game` (`ID`),
  ADD CONSTRAINT `FK_gameboard_BoardID` FOREIGN KEY (`BoardID`) REFERENCES `board` (`ID`);

-- --------------------------------------------------------

ALTER TABLE `record`
  ADD CONSTRAINT `FK_record_BoardID` FOREIGN KEY (`BoardID`) REFERENCES `board` (`ID`);

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
