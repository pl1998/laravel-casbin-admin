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

 Date: 04/06/2021 09:05:37
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_log
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限表';

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
INSERT INTO `admin_permissions` VALUES (12, '权限列表', 'fa fa-user-secret', '*', '/api/admin/permissions', 1, '2021-03-13 12:51:32', '*', '2021-03-13 20:52:19', 6, 1, 1, '权限列表', '2021-03-13 20:52:19');
INSERT INTO `admin_permissions` VALUES (13, '权限列表', NULL, NULL, 'api/admin/permissions', 1, '2021-03-13 12:52:32', 'GET', '2021-03-13 20:56:41', 6, 1, 0, '权限列表', NULL);
INSERT INTO `admin_permissions` VALUES (14, '角色列表', NULL, NULL, 'api/admin/roles', 1, '2021-03-13 12:53:58', 'GET', '2021-03-13 20:57:25', 6, 1, 0, '角色列表', NULL);
INSERT INTO `admin_permissions` VALUES (15, '测试', 'fa fa-slack', '/test', '/api/test', 1, '2021-05-27 05:50:58', '*', '2021-05-27 14:17:08', 0, 1, 1, '测试', '2021-05-27 14:17:08');
INSERT INTO `admin_permissions` VALUES (16, '子节点', 'fa fa-italic', '/test/z-test', '/*', 1, '2021-05-27 05:51:45', '*', '2021-05-27 13:53:03', 15, 1, 1, '子节点', NULL);
INSERT INTO `admin_permissions` VALUES (17, '测试', 'fa fa-slack', '/test-1', '*', 1, '2021-05-27 05:53:33', '*', '2021-05-27 13:55:37', 0, 1, 1, '测试', '2021-05-27 13:55:37');
INSERT INTO `admin_permissions` VALUES (18, '测试菜单', 'fa fa-arrows-alt', '/test', '/test', 1, '2021-05-27 06:18:06', '*', '2021-06-03 10:21:21', 0, 1, 1, '测试菜单', '2021-06-03 10:21:21');
INSERT INTO `admin_permissions` VALUES (19, '测试子菜单', 'fa fa-video-camera', '/z-test', '/z-test', 1, '2021-05-27 06:19:01', '*', '2021-05-27 14:32:51', 18, 1, 1, '测试子菜单', NULL);
INSERT INTO `admin_permissions` VALUES (20, '测试子菜单2', 'fa fa-arrows-alt', '/z-test1', '/z-test1', 1, '2021-05-27 06:22:10', '*', '2021-05-27 14:24:06', 18, 1, 1, '测试子菜单2', '2021-05-27 14:24:06');
INSERT INTO `admin_permissions` VALUES (21, '系统信息', 'fa fa-sun-o', '/system', '/system', 1, '2021-05-31 08:03:30', '*', '2021-05-31 08:03:30', 1, 1, 1, '系统信息', NULL);
INSERT INTO `admin_permissions` VALUES (22, '系统终端', 'fa fa-terminal', '/terminal', '/terminal', 1, '2021-06-01 02:05:37', '*', '2021-06-01 02:05:37', 1, 1, 1, '系统终端', NULL);
INSERT INTO `admin_permissions` VALUES (23, '系统工具', 'fa fa-steam', '/systemTools', '/systemTools', 1, '2021-06-03 02:12:13', '*', '2021-06-03 10:14:45', 24, 1, 1, '系统工具', NULL);
INSERT INTO `admin_permissions` VALUES (24, '系统工具', 'fa fa-dedent', '/Tools', '/Tools', 1, '2021-06-03 02:14:29', '*', '2021-06-03 10:33:31', 0, 1, 1, '系统工具', '2021-06-03 10:33:31');
INSERT INTO `admin_permissions` VALUES (25, '代码生成', 'fa fa-file-code-o', '/code', '/code', 1, '2021-06-03 02:15:20', '*', '2021-06-03 13:30:43', 1, 1, 1, '代码生成', '2021-06-03 13:30:43');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
BEGIN;
INSERT INTO `admin_roles` VALUES (1, 'administrator', '超级管理员', 1, '2021-02-28 19:45:14', '2021-03-01 16:24:02', NULL);
INSERT INTO `admin_roles` VALUES (2, '运营', '运营', 1, '2021-02-28 20:04:16', '2021-03-03 14:05:05', NULL);
INSERT INTO `admin_roles` VALUES (3, '财务', '财务', 1, '2021-03-04 19:08:25', '2021-03-04 19:08:25', NULL);
INSERT INTO `admin_roles` VALUES (4, 'demo-user', 'demo登录', 1, '2021-03-13 20:54:27', '2021-06-02 15:27:01', NULL);
INSERT INTO `admin_roles` VALUES (5, 'test', '测试', 1, '2021-05-24 20:01:41', '2021-05-24 20:02:27', NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
INSERT INTO `casbin_rules` VALUES (137, 'g', 'roles_9', '4', 'demo-user', NULL, NULL, NULL, '2021-03-13 20:55:03', '2021-03-13 20:55:03');
INSERT INTO `casbin_rules` VALUES (138, 'g', 'roles_1', '1', 'administrator', NULL, NULL, NULL, '2021-03-13 21:12:56', '2021-03-13 21:12:56');
INSERT INTO `casbin_rules` VALUES (144, 'g', 'roles_7', '5', 'test', NULL, NULL, NULL, '2021-05-24 20:01:53', '2021-05-24 20:01:53');
INSERT INTO `casbin_rules` VALUES (145, 'p', 'permission_5', '/user', '*', '4', NULL, NULL, '2021-05-24 20:02:27', '2021-05-24 20:02:27');
INSERT INTO `casbin_rules` VALUES (146, 'p', 'permission_5', '/log', '*', '5', NULL, NULL, '2021-05-24 20:02:27', '2021-05-24 20:02:27');
INSERT INTO `casbin_rules` VALUES (147, 'p', 'permission_5', 'api/admin/log', 'GET', '7', NULL, NULL, '2021-05-24 20:02:27', '2021-05-24 20:02:27');
INSERT INTO `casbin_rules` VALUES (148, 'p', 'permission_5', 'api/admin/users', 'GET', '8', NULL, NULL, '2021-05-24 20:02:27', '2021-05-24 20:02:27');
INSERT INTO `casbin_rules` VALUES (149, 'p', 'permission_4', '/permission', '*', '2', NULL, NULL, '2021-06-02 15:27:01', '2021-06-02 15:27:01');
INSERT INTO `casbin_rules` VALUES (150, 'p', 'permission_4', '/role', '*', '3', NULL, NULL, '2021-06-02 15:27:01', '2021-06-02 15:27:01');
INSERT INTO `casbin_rules` VALUES (151, 'p', 'permission_4', '/user', '*', '4', NULL, NULL, '2021-06-02 15:27:01', '2021-06-02 15:27:01');
INSERT INTO `casbin_rules` VALUES (152, 'p', 'permission_4', '/log', '*', '5', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (153, 'p', 'permission_4', 'api/admin/log', 'GET', '7', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (154, 'p', 'permission_4', 'api/admin/users', 'GET', '8', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (155, 'p', 'permission_4', 'api/admin/log/{id}', 'DELETE', '9', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (156, 'p', 'permission_4', 'api/admin/permissions', 'GET', '13', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (157, 'p', 'permission_4', 'api/admin/roles', 'GET', '14', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (158, 'p', 'permission_4', '/system', '*', '21', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (159, 'p', 'permission_4', '/terminal', '*', '22', NULL, NULL, '2021-06-02 15:27:02', '2021-06-02 15:27:02');
INSERT INTO `casbin_rules` VALUES (160, 'g', 'roles_10', '5', 'test', NULL, NULL, NULL, '2021-06-03 16:45:35', '2021-06-03 16:45:35');
INSERT INTO `casbin_rules` VALUES (170, 'g', 'roles_37', '4', 'demo-user', NULL, NULL, NULL, '2021-06-03 20:07:07', '2021-06-03 20:07:07');
COMMIT;

-- ----------------------------
-- Table structure for dings
-- ----------------------------
DROP TABLE IF EXISTS `dings`;
CREATE TABLE `dings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '昵称',
  `unionid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一id',
  `openid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '当前应用id',
  `ding_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '钉钉id',
  `user_id` int(11) DEFAULT NULL COMMENT '管理员id',
  `ding_user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '钉钉用户ID',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of dings
-- ----------------------------
BEGIN;
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ding_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oauth_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oauth_type` tinyint(1) DEFAULT NULL COMMENT '1.微博 2钉钉',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 'admin', 'pltruenine@163.com', NULL, '$2y$10$f1Kf7DY1A8WJ5BpuRMu6yuOs3kOu1EoIjkhotApZxWHE30nF1nlvi', NULL, '2021-01-20 11:42:00', '2021-05-26 11:54:22', 'http://adminapi.test/storage/xXEmZMhpEARYImy1tfGW43poB4geC5MBQIABTrQH.jpg', NULL, NULL, NULL);
INSERT INTO `users` VALUES (7, 'pan', '2540463097@qq.com', NULL, '$2y$10$/yLMOcd1wedOE..NQ271c.naLU9pltWnnkYy4EJKC6SYmySd8EQTW', NULL, '2021-02-26 10:23:14', '2021-05-27 13:47:56', 'http://financial_api.test/storage/tU0E3915YWsL3R7NSQaLTQ3dbAZtD1vUPbm9KyG2.jpg', NULL, NULL, NULL);
INSERT INTO `users` VALUES (8, 'caiwu', 'caiwu@163.com', NULL, '$2y$10$dhimwV200FCTUYQmjdXkp.h4WNkKskSmF/xGc1BtxznQ0LBBJY3nW', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (9, 'demo-user', 'admin@gmail.com', NULL, '$2y$10$hK42qF7ODa9IG9SyUqY4jOVNOtEkvIl.J3HrU0zJNgweok5Mvbyey', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (10, '34343', 'pltruenine@1633.com', NULL, '$2y$10$0B4MaHcgq0a0CLD85GWljOMlDG3IR8sBlWSw7rFIvkp4is2nSqtXu', NULL, '2021-06-03 16:45:35', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (37, 'Hi叫我李荣浩', '', NULL, '$2y$10$F7pwYD.K9b1r4/CPqS28DeRrPrsdDQlid3qZiog2c0uF9.MA/W6sq', NULL, '2021-06-03 19:50:31', '2021-06-03 19:50:31', 'https://tvax2.sinaimg.cn/crop.0.0.1002.1002.180/006pP2Laly8gqcj17wce9j30ru0ru0up.jpg?KID=imgbed,tva&Expires=1622731831&ssig=Xu7sPFw5ou', NULL, '5878370732', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
