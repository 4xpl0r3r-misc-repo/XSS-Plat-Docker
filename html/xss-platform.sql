-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-05-16 15:31:03
-- 服务器版本： 5.7.29-0ubuntu0.18.04.1-log
-- PHP 版本： 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `sid` int(11) NOT NULL COMMENT '记录ID',
  `qid` int(11) NOT NULL COMMENT '问题ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `qcondition` int(11) NOT NULL COMMENT '0位已做对 1为已做错 2为已Star'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='qcondition 1为曾做错 2为已收藏';

-- --------------------------------------------------------

--
-- 表的结构 `Questions`
--

CREATE TABLE `Questions` (
  `qid` int(11) NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correctRes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakeA` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakeB` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakeC` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resDetail` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `Questions`
--

INSERT INTO `Questions` (`qid`, `question`, `correctRes`, `fakeA`, `fakeB`, `fakeC`, `resDetail`) VALUES
(1, 'XSS的中文全称是什么？', '跨站脚本攻击', '层叠样式表', '网站黑客攻击', 'Web应用程序攻击', 'XSS的中文全称是跨站脚本攻击'),
(2, '以下哪种特征不属于XSS？', '数据库直接被下载', '恶意代码会在用户的浏览器中执行', '攻击者可以在网页中嵌入自定义的客户端脚本', '可以获取网站管理员的登录凭证', '数据库直接被下载一般不是由XSS漏洞直接导致'),
(3, '以下哪种操作不能由XSS直接实现', '关闭网站服务器', '获取用户的 Cookie', '改变网页内容', 'URL跳转', 'JavaScript可以用来获取用户的 Cookie、改变网页内容、URL跳转,因此XXS也只能直接实现这些功能。'),
(4, '反射型跨站脚本又称什么？', '非持久性跨站脚本', '持久性跨站脚本', '简易型跨站脚本', '隐藏型跨站脚本', '反射型跨站脚本又称非持久性跨站脚本。'),
(5, '以下哪种特性是属于反射型跨站脚本的？', '最容易触发', '最难触发', '不需要和后台交互', '隐蔽性极高', '反射型跨站脚本是最容易触发的。'),
(6, '以下哪种特性是不属于存储型跨站脚本的？', '危害较小', '危害性大', '持久型', '隐蔽性极高', '存储型跨站脚本是最危险的。'),
(7, 'DOM型跨站脚本的特性是？', '不需要和服务器交互', '危害性大', '不能改变界面内容', '隐蔽性极高', 'DOM型跨站脚本不需要和服务器交互。'),
(8, '对于检测跨站脚本，哪种态度是正确的？', '不放过每一处输入点，自动化和手工结合', '纯手工操作，精细打磨', '相信智能，使用全自动化工具', '只测试几个重要的输入点即可', '千里之堤毁于蚁穴，不能放过每一个输入点，同时需要手工和工具结合，纯工具缺乏变通，纯手工容易出粗心的错误。'),
(9, '以下哪种方法对于防御XSS攻击无效？', '使用纯前端代码，不使用后端程序', '在输出点设置js过滤器', '在输入点设置后端过滤器', '开启HTTPOnly', '在大多数Web应用中，不使用后端不现实，即使不使用后端，仍然可能出现DOM型XS漏洞。');

-- --------------------------------------------------------

--
-- 表的结构 `userExam`
--

CREATE TABLE `userExam` (
  `uid` int(11) NOT NULL,
  `maxScore` int(11) DEFAULT NULL,
  `lastScore` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `userInfo`
--

CREATE TABLE `userInfo` (
  `uid` int(11) NOT NULL,
  `username` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `realName` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `totalTime` bigint(20) DEFAULT '0',
  `lastUpdate` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户基本信息表';

-- --------------------------------------------------------

--
-- 表的结构 `userProgress`
--

CREATE TABLE `userProgress` (
  `uid` int(11) NOT NULL,
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
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '记录ID';

--
-- 使用表AUTO_INCREMENT `Questions`
--
ALTER TABLE `Questions`
  MODIFY `qid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `userInfo`
--
ALTER TABLE `userInfo`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
