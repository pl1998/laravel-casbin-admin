/*
 Navicat Premium Data Transfer

 Source Server         : 120.79.241.204
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : 127.0.0.1:3306
 Source Schema         : admin

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 24/05/2021 18:43:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `method` varchar(10) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `u_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;


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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限表';

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
BEGIN;
INSERT INTO `admin_permissions` VALUES (1, '系统管理', 'fa fa-steam-square', '/admin', '/admin', 1, '2021-02-28 11:40:29', '*', '2021-02-28 13:08:30', 0, 1, 1, '系统管理', NULL);
INSERT INTO `admin_permissions` VALUES (2, '权限管理', 'fa fa-pencil-square', '/permission', '/permission', 1, '2021-02-28 11:42:17', '*', '2021-02-28 12:12:08', 1, 1, 1, '权限管理', NULL);
INSERT INTO `admin_permissions` VALUES (3, '角色管理', 'fa fa-user-secret', '/role', '/role', 1, '2021-02-28 11:43:15', '*', '2021-02-28 12:12:18', 1, 1, 1, '角色管理', NULL);
INSERT INTO `admin_permissions` VALUES (4, '用户管理', 'fa fa-users', '/user', '/user', 1, '2021-02-28 11:43:59', '*', '2021-02-28 12:12:22', 1, 1, 1, '用户管理', NULL);
INSERT INTO `admin_permissions` VALUES (5, '系统日志', 'fa fa-location-arrow', '/log', '/log', 1, '2021-03-01 07:02:04', '*', '2021-03-01 07:02:04', 1, 1, 1, '系统日志', NULL);
INSERT INTO `admin_permissions` VALUES (6, '系统', NULL, NULL, 'api/admin', 1, '2021-03-03 02:08:23', '*', '2021-03-03 11:56:06', 0, 1, 0, '系统', NULL);
INSERT INTO `admin_permissions` VALUES (7, '日志列表', NULL, NULL, 'api/admin/log', 1, '2021-03-03 02:19:14', 'GET', '2021-03-03 14:06:12', 6, 1, 0, '日志列表', NULL);
INSERT INTO `admin_permissions` VALUES (8, '用户列表', NULL, NULL, 'api/admin/users', 1, '2021-03-03 05:33:21', 'GET', '2021-03-03 14:06:24', 6, 1, 0, '用户列表', NULL);
INSERT INTO `admin_permissions` VALUES (9, '日志删除', NULL, NULL, 'api/admin/log/{id}', 1, '2021-03-03 05:44:09', 'DELETE', '2021-03-03 13:46:51', 6, 1, 0, '日志删除', NULL);
INSERT INTO `admin_permissions` VALUES (10, '添加用户', NULL, NULL, 'api/admin/users', 1, '2021-03-03 06:03:04', 'POST', '2021-03-03 14:06:52', 6, 1, 0, '添加用户', NULL);
INSERT INTO `admin_permissions` VALUES (11, '更新用户', NULL, NULL, 'api/admin/users/{id}', 1, '2021-03-03 06:05:41', 'PUT', '2021-03-03 06:05:41', 6, 1, 0, '更新用户', NULL);
INSERT INTO `admin_permissions` VALUES (12, '权限列表', NULL, NULL, 'api/admin/permissions', 1, '2021-03-14 10:37:34', 'GET', '2021-03-14 10:37:34', 6, 1, 0, '权限列表', NULL);
INSERT INTO `admin_permissions` VALUES (13, '角色列表', NULL, NULL, 'api/admin/roles', 1, '2021-03-14 10:37:56', 'GET', '2021-03-14 10:37:56', 6, 1, 0, '角色列表', NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
BEGIN;
INSERT INTO `admin_roles` VALUES (1, 'administrator', '超级管理员', 1, '2021-02-28 19:45:14', '2021-03-01 16:24:02', NULL);
INSERT INTO `admin_roles` VALUES (2, '运营', '运营', 1, '2021-02-28 20:04:16', '2021-03-03 14:05:05', NULL);
INSERT INTO `admin_roles` VALUES (3, '财务', '财务', 1, '2021-03-04 19:08:25', '2021-03-04 19:08:25', NULL);
INSERT INTO `admin_roles` VALUES (4, 'demo', 'demo', 1, '2021-03-14 10:38:22', '2021-03-14 10:38:22', NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of casbin_rules
-- ----------------------------
BEGIN;
INSERT INTO `casbin_rules` VALUES (48, 'p', 'permission_1', '/permission', '*', '2', NULL, NULL, '2021-03-01 19:06:41', '2021-03-01 19:06:41');
INSERT INTO `casbin_rules` VALUES (49, 'p', 'permission_1', '/admin', '*', '1', NULL, NULL, '2021-03-01 19:06:41', '2021-03-01 19:06:41');
INSERT INTO `casbin_rules` VALUES (50, 'p', 'permission_1', '/user', '*', '4', NULL, NULL, '2021-03-01 19:06:41', '2021-03-01 19:06:41');
INSERT INTO `casbin_rules` VALUES (51, 'p', 'permission_1', '/log', '*', '5', NULL, NULL, '2021-03-01 19:06:41', '2021-03-01 19:06:41');
INSERT INTO `casbin_rules` VALUES (111, 'p', 'permission_2', '/user', '*', '4', NULL, NULL, '2021-03-03 14:05:05', '2021-03-03 14:05:05');
INSERT INTO `casbin_rules` VALUES (112, 'p', 'permission_2', '/log', '*', '5', NULL, NULL, '2021-03-03 14:05:05', '2021-03-03 14:05:05');
INSERT INTO `casbin_rules` VALUES (113, 'p', 'permission_2', 'api/admin/log', 'GET', '7', NULL, NULL, '2021-03-03 14:05:05', '2021-03-03 14:05:05');
INSERT INTO `casbin_rules` VALUES (114, 'p', 'permission_2', 'api/admin/users', 'GET', '8', NULL, NULL, '2021-03-03 14:05:05', '2021-03-03 14:05:05');
INSERT INTO `casbin_rules` VALUES (115, 'p', 'permission_2', 'api/admin/log/{id}', 'DELETE', '9', NULL, NULL, '2021-03-03 14:05:05', '2021-03-03 14:05:05');
INSERT INTO `casbin_rules` VALUES (117, 'p', 'permission_3', '/log', '*', '5', NULL, NULL, '2021-03-04 19:08:25', '2021-03-04 19:08:25');
INSERT INTO `casbin_rules` VALUES (118, 'p', 'permission_3', 'api/admin/log', 'GET', '7', NULL, NULL, '2021-03-04 19:08:25', '2021-03-04 19:08:25');
INSERT INTO `casbin_rules` VALUES (119, 'g', 'roles_8', '3', '财务', NULL, NULL, NULL, '2021-03-04 19:08:35', '2021-03-04 19:08:35');
INSERT INTO `casbin_rules` VALUES (126, 'g', 'roles_7', '2', '运营', NULL, NULL, NULL, '2021-03-05 15:41:28', '2021-03-05 15:41:28');
INSERT INTO `casbin_rules` VALUES (127, 'g', 'roles_7', '3', '财务', NULL, NULL, NULL, '2021-03-05 15:41:28', '2021-03-05 15:41:28');
INSERT INTO `casbin_rules` VALUES (128, 'g', 'roles_1', '1', 'administrator', NULL, NULL, NULL, '2021-03-14 10:35:50', '2021-03-14 10:35:50');
INSERT INTO `casbin_rules` VALUES (129, 'p', 'permission_4', '/permission', '*', '2', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (130, 'p', 'permission_4', '/role', '*', '3', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (131, 'p', 'permission_4', '/user', '*', '4', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (132, 'p', 'permission_4', '/log', '*', '5', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (133, 'p', 'permission_4', 'api/admin/log', 'GET', '7', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (134, 'p', 'permission_4', 'api/admin/users', 'GET', '8', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (135, 'p', 'permission_4', 'api/admin/log/{id}', 'DELETE', '9', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (136, 'p', 'permission_4', 'api/admin/permissions', 'GET', '12', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (137, 'p', 'permission_4', 'api/admin/roles', 'GET', '13', NULL, NULL, '2021-03-14 10:38:22', '2021-03-14 10:38:22');
INSERT INTO `casbin_rules` VALUES (138, 'g', 'roles_9', '4', 'demo', NULL, NULL, NULL, '2021-03-14 10:38:56', '2021-03-14 10:38:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'admin', 'pltruenine@163.com', NULL, '$2y$10$H8d70FYi1fw0pEe8Pjh3x.Sumu9GEMb1CDxLpDXfzITAb9IaB7xT6', NULL, '2021-01-20 11:42:00', '2021-03-15 14:18:26', 'http://adminapi.test/storage/syT44DJOvmw7vO8320J6WRltRlS7MJi4dGrGO6wt.jpg');
INSERT INTO `users` VALUES (7, 'test1', '2540463097@qq.com', NULL, '$2y$10$fC1ieLA0bOu7.urJbyq91.pgXC/lvw3BXCbW8VcRxS5GF4xysy1r6', NULL, '2021-02-26 10:23:14', '2021-02-28 20:05:38', NULL);
INSERT INTO `users` VALUES (8, 'caiwu', 'caiwu@163.com', NULL, '$2y$10$dhimwV200FCTUYQmjdXkp.h4WNkKskSmF/xGc1BtxznQ0LBBJY3nW', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (9, 'demouser', 'admin@gmail.com', NULL, '$2y$10$RTZPlJjY0z75EN6pJjZj6eRZuzVAJnU6Max5hpT/0ZRgPOF92/BTy', NULL, NULL, '2021-03-19 16:54:24', '/storage/default-avatar.jpg');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
