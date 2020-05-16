-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-05-16 07:50:32
-- 服务器版本： 8.0.20-0ubuntu0.19.10.1
-- PHP 版本： 7.3.11-0ubuntu0.19.10.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `xss-platform`
--

-- --------------------------------------------------------

--
-- 表的结构 `examStatus`
--

CREATE TABLE `examStatus` (
  `sid` int NOT NULL COMMENT '记录ID',
  `qid` int NOT NULL COMMENT '问题ID',
  `uid` int NOT NULL COMMENT '用户ID',
  `qcondition` int NOT NULL COMMENT '0位已做对 1为已做错 2为已Star'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='qcondition 1为曾做错 2为已收藏';

-- --------------------------------------------------------

--
-- 表的结构 `Questions`
--

CREATE TABLE `Questions` (
  `qid` int NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correctRes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakeA` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakeB` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakeC` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resDetail` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `userExam`
--

CREATE TABLE `userExam` (
  `uid` int NOT NULL,
  `maxScore` int DEFAULT NULL,
  `lastScore` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `userInfo`
--

CREATE TABLE `userInfo` (
  `uid` int NOT NULL,
  `username` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `realName` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `totalTime` bigint DEFAULT '0',
  `lastUpdate` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户基本信息表';

-- --------------------------------------------------------

--
-- 表的结构 `userProgress`
--

CREATE TABLE `userProgress` (
  `uid` int NOT NULL,
  `1_1` date DEFAULT NULL,
  `1_2` date DEFAULT NULL,
  `2_1` date DEFAULT NULL,
  `2_2` date DEFAULT NULL,
  `2_3` date DEFAULT NULL,
  `3_1` date DEFAULT NULL,
  `3_2` date DEFAULT NULL,
  `4_1` date DEFAULT NULL,
  `4_2` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `examStatus`
--
ALTER TABLE `examStatus`
  ADD PRIMARY KEY (`sid`);

--
-- 表的索引 `Questions`
--
ALTER TABLE `Questions`
  ADD PRIMARY KEY (`qid`);

--
-- 表的索引 `userExam`
--
ALTER TABLE `userExam`
  ADD PRIMARY KEY (`uid`);

--
-- 表的索引 `userInfo`
--
ALTER TABLE `userInfo`
  ADD PRIMARY KEY (`uid`);

--
-- 表的索引 `userProgress`
--
ALTER TABLE `userProgress`
  ADD PRIMARY KEY (`uid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `examStatus`
--
ALTER TABLE `examStatus`
  MODIFY `sid` int NOT NULL AUTO_INCREMENT COMMENT '记录ID';

--
-- 使用表AUTO_INCREMENT `Questions`
--
ALTER TABLE `Questions`
  MODIFY `qid` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `userInfo`
--
ALTER TABLE `userInfo`
  MODIFY `uid` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
