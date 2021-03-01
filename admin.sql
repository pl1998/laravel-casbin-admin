/*
 Navicat Premium Data Transfer

 Source Server         : laradock_mysql
 Source Server Type    : MySQL
 Source Server Version : 50732
 Source Host           : localhost:3306
 Source Schema         : admin

 Target Server Type    : MySQL
 Target Server Version : 50732
 File Encoding         : 65001

 Date: 01/03/2021 09:46:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限名',
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'link' COMMENT '权限图标',
  `path` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '路径',
  `url` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '前端url',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1正常；2禁用',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'GET' COMMENT '方法名称',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `p_id` int(11) DEFAULT '0' COMMENT '父节点',
  `hidden` tinyint(4) DEFAULT '2' COMMENT '是否隐藏 1:是 2否',
  `is_menu` tinyint(4) DEFAULT '2' COMMENT '是否为菜单 0是 1否',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '前端路由名称',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限表';

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
BEGIN;
INSERT INTO `admin_permissions` VALUES (1, '系统管理', 'fa fa-steam-square', '/admin', '/admin', 1, '2021-02-28 11:40:29', '*', '2021-02-28 13:08:30', 0, 1, 1, '系统管理', NULL);
INSERT INTO `admin_permissions` VALUES (2, '权限管理', 'fa fa-pencil-square', '/permission', '/permission', 1, '2021-02-28 11:42:17', '*', '2021-02-28 12:12:08', 1, 1, 1, '权限管理', NULL);
INSERT INTO `admin_permissions` VALUES (3, '角色管理', 'fa fa-user-secret', '/role', '/role', 1, '2021-02-28 11:43:15', '*', '2021-02-28 12:12:18', 1, 1, 1, '角色管理', NULL);
INSERT INTO `admin_permissions` VALUES (4, '用户管理', 'fa fa-users', '/user', '/user', 1, '2021-02-28 11:43:59', '*', '2021-02-28 12:12:22', 1, 1, 1, '用户管理', NULL);
COMMIT;

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0正常 1 禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
BEGIN;
INSERT INTO `admin_roles` VALUES (1, 'administrator', '超级管理员', 1, '2021-02-28 19:45:14', '2021-02-28 21:01:39', NULL);
INSERT INTO `admin_roles` VALUES (2, '运营', '运营', 1, '2021-02-28 20:04:16', '2021-02-28 21:09:53', NULL);
COMMIT;

-- ----------------------------
-- Table structure for casbin_rules
-- ----------------------------
DROP TABLE IF EXISTS `casbin_rules`;
CREATE TABLE `casbin_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v0` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of casbin_rules
-- ----------------------------
BEGIN;
INSERT INTO `casbin_rules` VALUES (6, 'g', 'roles_1', '1', 'administrator', NULL, NULL, NULL, '2021-02-28 20:05:04', '2021-02-28 20:05:04');
INSERT INTO `casbin_rules` VALUES (8, 'g', 'roles_7', '2', '运营', NULL, NULL, NULL, '2021-02-28 20:21:35', '2021-02-28 20:21:35');
INSERT INTO `casbin_rules` VALUES (12, 'p', 'permission_2', '/role', '*', '3', NULL, NULL, '2021-02-28 21:09:53', '2021-02-28 21:09:53');
INSERT INTO `casbin_rules` VALUES (13, 'p', 'permission_2', '/admin', '*', '1', NULL, NULL, '2021-02-28 21:09:53', '2021-02-28 21:09:53');
INSERT INTO `casbin_rules` VALUES (14, 'p', 'permission_2', '/user', '*', '4', NULL, NULL, '2021-02-28 21:09:53', '2021-02-28 21:09:53');
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (5, '2019_03_01_000000_create_rules_table', 2);
COMMIT;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'admin', 'pltruenine@163.com', NULL, '$2y$10$fC1ieLA0bOu7.urJbyq91.pgXC/lvw3BXCbW8VcRxS5GF4xysy1r6', NULL, '2021-01-20 11:42:00', '2021-02-28 20:05:04', 'http://laraveldcat.top/vendor/dcat-admin/images/default-avatar.jpg');
INSERT INTO `users` VALUES (7, 'test1', '2540463097@qq.com', NULL, '$2y$10$fC1ieLA0bOu7.urJbyq91.pgXC/lvw3BXCbW8VcRxS5GF4xysy1r6', NULL, '2021-02-26 10:23:14', '2021-02-28 20:05:38', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
