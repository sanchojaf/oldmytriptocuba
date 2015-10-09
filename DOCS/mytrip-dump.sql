-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2014 at 04:01 PM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mytrip`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_date` datetime NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE IF NOT EXISTS `banner` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `banner_type` enum('Hostal','Distination','Story') COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `banner_id` int(11) NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE IF NOT EXISTS `booking` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `no_of_days` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `conversion_rate` double(8,2) NOT NULL,
  `total_price` double(8,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `adults` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_amount` double(8,2) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cancel_date` date NOT NULL,
  `cancel_reason` text COLLATE utf8_unicode_ci NOT NULL,
  `refund_amount` double(8,2) NOT NULL,
  `refund_status` enum('Pending','Refund') COLLATE utf8_unicode_ci NOT NULL,
  `refund_referenceno` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `refund_date` date NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `hid` (`hid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `view` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `reply` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `reply_message` text COLLATE utf8_unicode_ci NOT NULL,
  `reply_date` datetime NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `destination`
--

CREATE TABLE IF NOT EXISTS `destination` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_date` datetime NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `destinationcontent`
--

CREATE TABLE IF NOT EXISTS `destinationcontent` (
  `dcid` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `location_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`dcid`),
  KEY `did` (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `destination_feature`
--

CREATE TABLE IF NOT EXISTS `destination_feature` (
  `dfid` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dfid`),
  KEY `did` (`did`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `destination_image`
--

CREATE TABLE IF NOT EXISTS `destination_image` (
  `diid` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `image` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`diid`),
  KEY `did` (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `feature`
--

CREATE TABLE IF NOT EXISTS `feature` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `feature` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `featurecontent`
--

CREATE TABLE IF NOT EXISTS `featurecontent` (
  `fcid` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `feature` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`fcid`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table ` hostal`
--

CREATE TABLE IF NOT EXISTS ` hostal` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rooms` int(11) NOT NULL,
  `guests` int(11) NOT NULL,
  `adults` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_date` datetime NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`hid`),
  KEY `did` (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hostalcontent`
--

CREATE TABLE IF NOT EXISTS `hostalcontent` (
  `hcid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `owner_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `small_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `location_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`hcid`),
  KEY `hid` (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hostal_feature`
--

CREATE TABLE IF NOT EXISTS `hostal_feature` (
  `hfid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`hfid`),
  KEY `hid` (`hid`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hostal_image`
--

CREATE TABLE IF NOT EXISTS `hostal_image` (
  `hiid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL,
  `image` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`hiid`),
  KEY `hid` (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lan_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gatway`
--

CREATE TABLE IF NOT EXISTS `payment_gatway` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `gatway` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_info`
--

CREATE TABLE IF NOT EXISTS `payment_info` (
  `piid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`piid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `review_type` enum('Destination','Hostal','Story') COLLATE utf8_unicode_ci NOT NULL,
  `review_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`rid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sociallink`
--

CREATE TABLE IF NOT EXISTS `sociallink` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `site` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `staticpage`
--

CREATE TABLE IF NOT EXISTS `staticpage` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `pagename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `seo` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `content` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `staticpagecontent`
--

CREATE TABLE IF NOT EXISTS `staticpagecontent` (
  `scid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `meta_keyword` text COLLATE utf8_unicode_ci,
  `content` text COLLATE utf8_unicode_ci,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`scid`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `story`
--

CREATE TABLE IF NOT EXISTS `story` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`sid`),
  KEY `hid` (`hid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `story_content`
--

CREATE TABLE IF NOT EXISTS `story_content` (
  `scid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sub_head` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`scid`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lan` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('Active','Inactive','Pending') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE IF NOT EXISTS `visits` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `visit_type` enum('Story','Hostal','Distination') COLLATE utf8_unicode_ci NOT NULL,
  `visit_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`hid`) REFERENCES ` hostal` (`hid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `destinationcontent`
--
ALTER TABLE `destinationcontent`
  ADD CONSTRAINT `destinationcontent_ibfk_1` FOREIGN KEY (`did`) REFERENCES `destination` (`did`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `destination_feature`
--
ALTER TABLE `destination_feature`
  ADD CONSTRAINT `destination_feature_ibfk_2` FOREIGN KEY (`fid`) REFERENCES `feature` (`fid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `destination_feature_ibfk_1` FOREIGN KEY (`did`) REFERENCES `destination` (`did`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `destination_image`
--
ALTER TABLE `destination_image`
  ADD CONSTRAINT `destination_image_ibfk_1` FOREIGN KEY (`did`) REFERENCES `destination` (`did`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `featurecontent`
--
ALTER TABLE `featurecontent`
  ADD CONSTRAINT `featurecontent_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `feature` (`fid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table ` hostal`
--
ALTER TABLE ` hostal`
  ADD CONSTRAINT ` hostal_ibfk_1` FOREIGN KEY (`did`) REFERENCES `destination` (`did`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hostalcontent`
--
ALTER TABLE `hostalcontent`
  ADD CONSTRAINT `hostalcontent_ibfk_1` FOREIGN KEY (`hid`) REFERENCES ` hostal` (`hid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hostal_feature`
--
ALTER TABLE `hostal_feature`
  ADD CONSTRAINT `hostal_feature_ibfk_2` FOREIGN KEY (`fid`) REFERENCES `feature` (`fid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hostal_feature_ibfk_1` FOREIGN KEY (`hid`) REFERENCES ` hostal` (`hid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hostal_image`
--
ALTER TABLE `hostal_image`
  ADD CONSTRAINT `hostal_image_ibfk_1` FOREIGN KEY (`hid`) REFERENCES ` hostal` (`hid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_info`
--
ALTER TABLE `payment_info`
  ADD CONSTRAINT `payment_info_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `payment_gatway` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staticpagecontent`
--
ALTER TABLE `staticpagecontent`
  ADD CONSTRAINT `staticpagecontent_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `staticpage` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `story`
--
ALTER TABLE `story`
  ADD CONSTRAINT `story_ibfk_1` FOREIGN KEY (`hid`) REFERENCES ` hostal` (`hid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `story_content`
--
ALTER TABLE `story_content`
  ADD CONSTRAINT `story_content_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `story` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
