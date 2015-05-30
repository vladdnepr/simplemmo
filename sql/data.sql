SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `simplemmo`
--
CREATE DATABASE IF NOT EXISTS `simplemmo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `simplemmo`;

-- --------------------------------------------------------

--
-- Структура таблицы `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `fights` int(11) NOT NULL DEFAULT '0',
  `wins` int(11) NOT NULL DEFAULT '0',
  `coins` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Очистить таблицу перед добавлением данных `characters`
--

TRUNCATE TABLE `characters`;
-- --------------------------------------------------------

--
-- Структура таблицы `characters_skills`
--

CREATE TABLE IF NOT EXISTS `characters_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `character_id_skill_id` (`character_id`,`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Очистить таблицу перед добавлением данных `characters_skills`
--

TRUNCATE TABLE `characters_skills`;
-- --------------------------------------------------------

--
-- Структура таблицы `skills`
--

CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Очистить таблицу перед добавлением данных `skills`
--

TRUNCATE TABLE `skills`;
--
-- Дамп данных таблицы `skills`
--

INSERT INTO `skills` (`id`, `name`) VALUES
(1, 'Удар рукой'),
(2, 'Удар ногой'),
(3, 'Удар головой');
