/*
 Navicat MySQL Data Transfer

 Source Server         : qing_es
 Source Server Type    : MySQL
 Source Server Version : 50734
 Source Host           : 101.34.219.247:3306
 Source Schema         : es_admin

 Target Server Type    : MySQL
 Target Server Version : 50734
 File Encoding         : 65001

 Date: 14/08/2021 14:15:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_account
-- ----------------------------
DROP TABLE IF EXISTS `admin_account`;
CREATE TABLE `admin_account`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pwd` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nickname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remark` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_account
-- ----------------------------
INSERT INTO `admin_account` VALUES (1, 'admin', '$2y$10$NWivW5LdQQLQYUUaatuv7eKHNd0034X/s5GBYxXonn9O10t89gxfm', '超管', '862774625@qq.com', '超级管理员', 1, 1628869735, 1628869735);
INSERT INTO `admin_account` VALUES (2, 'dalang', '$2y$10$I00B5.WHt.QsYvRWWcsCfu76BZu4Qu/5RhalMnBsb/yQWMgPXzFNu', '大浪', '862774625@qq.com', NULL, 1, 1628914833, 1628915707);

-- ----------------------------
-- Table structure for admin_account_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_account_role`;
CREATE TABLE `admin_account_role`  (
  `account_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  UNIQUE INDEX `account_role_union`(`account_id`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_account_role
-- ----------------------------
INSERT INTO `admin_account_role` VALUES (2, 2);

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_pid` int(10) NOT NULL DEFAULT 0,
  `menu_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1=>目录，2=》菜单，3=》按钮',
  `icon` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `route_path` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `component` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `permission` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '目录/控制器/action',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_ext` tinyint(1) NOT NULL DEFAULT 0,
  `show` tinyint(1) NOT NULL DEFAULT 1,
  `sort` int(10) NOT NULL DEFAULT 0,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, 0, '系统管理', 1, NULL, 'system', 'Layout', NULL, 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (2, 1, '账号管理', 2, NULL, 'account', 'system/account/index', 'admin/account', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (3, 1, '角色管理', 2, NULL, 'role', 'system/role/index', 'admin/role', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (4, 1, '菜单管理', 2, NULL, 'menu', 'system/menu/index', 'admin/menu', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (5, 2, '新增按钮', 3, NULL, 'account/create', 'system/account/create', 'admin/account/create', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (6, 2, '编辑账号', 3, NULL, 'account/update', 'system/account/update', 'admin/account/update', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (7, 2, '删除账号', 3, NULL, 'account/delete', 'system/account/delete', 'admin/account/delete', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (8, 3, '新增按钮', 3, NULL, 'role/create', 'system/role/create', 'admin/role/create', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (9, 3, '编辑角色', 3, NULL, 'role/update', 'system/role/update', 'admin/role/update', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (10, 3, '删除角色', 3, NULL, 'role/delete', 'system/role/delete', 'admin/role/delete', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (11, 4, '新增按钮', 3, NULL, 'menu/create', 'system/menu/create', 'admin/menu/create', 1, 0, 1, 0, 1628835530, 1628908602);
INSERT INTO `admin_menu` VALUES (12, 4, '编辑菜单', 3, NULL, 'menu/update', 'system/menu/update', 'admin/menu/update', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (13, 4, '删除菜单', 3, NULL, 'menu/delete', 'system/menu/delete', 'admin/menu/delete', 1, 0, 1, 0, 1628835530, 1628835530);
INSERT INTO `admin_menu` VALUES (15, 2, '新增账户', 3, NULL, NULL, NULL, 'admin/account/save', 1, 0, 1, 0, 1628921421, 1628921421);
INSERT INTO `admin_menu` VALUES (16, 2, '编辑按钮', 3, NULL, NULL, NULL, 'admin/account/edit', 1, 0, 1, 0, 1628921455, 1628921455);
INSERT INTO `admin_menu` VALUES (17, 3, '新增角色', 3, NULL, NULL, NULL, 'admin/role/save', 1, 0, 1, 0, 1628921496, 1628921496);
INSERT INTO `admin_menu` VALUES (18, 3, '编辑按钮', 3, NULL, NULL, NULL, 'admin/role/edit', 1, 0, 1, 0, 1628921511, 1628921511);
INSERT INTO `admin_menu` VALUES (19, 4, '新增菜单', 3, NULL, NULL, NULL, 'admin/menu/save', 1, 0, 1, 0, 1628921598, 1628921598);
INSERT INTO `admin_menu` VALUES (20, 4, '菜单状态', 3, NULL, NULL, NULL, 'admin/menu/status', 1, 0, 1, 0, 1628921649, 1628921649);
INSERT INTO `admin_menu` VALUES (21, 3, '角色状态', 3, NULL, NULL, NULL, 'admin/role/status', 1, 0, 1, 0, 1628921661, 1628921661);
INSERT INTO `admin_menu` VALUES (22, 2, '角色状态', 3, NULL, NULL, NULL, 'admin/account/status', 1, 0, 1, 0, 1628921667, 1628921667);

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `remark` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `create_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (2, '普通管理1号', NULL, 1, 1628913043, 1628913222);

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu`  (
  `role_id` int(10) NOT NULL,
  `menu_id` int(10) NOT NULL,
  UNIQUE INDEX `role_menu_union`(`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
INSERT INTO `admin_role_menu` VALUES (2, 2);
INSERT INTO `admin_role_menu` VALUES (2, 3);
INSERT INTO `admin_role_menu` VALUES (2, 4);

SET FOREIGN_KEY_CHECKS = 1;
