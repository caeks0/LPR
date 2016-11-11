/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 100113
Source Host           : localhost:3306
Source Database       : lpr

Target Server Type    : MYSQL
Target Server Version : 100113
File Encoding         : 65001

Date: 2016-11-11 20:25:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for obj_alert_types
-- ----------------------------
DROP TABLE IF EXISTS `obj_alert_types`;
CREATE TABLE `obj_alert_types` (
  `flag` int(2) DEFAULT NULL,
  `alert_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for obj_permission_types
-- ----------------------------
DROP TABLE IF EXISTS `obj_permission_types`;
CREATE TABLE `obj_permission_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_flag` varchar(255) DEFAULT NULL,
  `permission_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for tbl_plates
-- ----------------------------
DROP TABLE IF EXISTS `tbl_plates`;
CREATE TABLE `tbl_plates` (
  `plate_id` int(12) NOT NULL AUTO_INCREMENT,
  `site_id` int(12) DEFAULT NULL,
  `uuid` varchar(24) DEFAULT NULL,
  `unix_timestamp` varchar(255) DEFAULT NULL,
  `camera_id` varchar(255) DEFAULT NULL,
  `plate_number` varchar(255) DEFAULT NULL,
  `confidence` varchar(255) DEFAULT NULL,
  `matches_pattern` int(12) DEFAULT NULL,
  `flag_type` varchar(255) DEFAULT NULL,
  `alert_flag` int(255) DEFAULT NULL,
  PRIMARY KEY (`plate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5514 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for tbl_users
-- ----------------------------
DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `permissions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for tbl_vehicles
-- ----------------------------
DROP TABLE IF EXISTS `tbl_vehicles`;
CREATE TABLE `tbl_vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(255) DEFAULT NULL,
  `matches_pattern` varchar(255) DEFAULT NULL,
  `owner_firstname` varchar(255) DEFAULT NULL,
  `owner_lastname` varchar(255) DEFAULT NULL,
  `vehicle_make` varchar(255) DEFAULT NULL,
  `vehicle_model` varchar(255) DEFAULT NULL,
  `vehicle_color` varchar(255) DEFAULT NULL,
  `vehicle_notes` varchar(255) DEFAULT NULL,
  `alert_flag` int(11) DEFAULT NULL,
  `date_added` varchar(255) DEFAULT NULL,
  `site_id` int(11) DEFAULT NULL,
  `camera_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2773 DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
