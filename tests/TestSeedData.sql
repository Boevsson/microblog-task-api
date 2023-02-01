/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3308
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : localhost:3308
 Source Schema         : microblog

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 01/02/2023 05:12:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for posts
-- ----------------------------
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts`  (
                          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                          `title` char(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                          `content` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                          `image_file_name` char(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
                          `created_at` datetime(0) NULL DEFAULT NULL,
                          `updated_at` datetime(0) NULL DEFAULT NULL,
                          PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of posts
-- ----------------------------
INSERT INTO `posts` VALUES (1, 'test', 'content', '1cae0d4fd155afca.jpg', '2023-02-01 03:09:36', '2023-02-01 03:09:36');
INSERT INTO `posts` VALUES (2, 'test', 'content', 'd6817272a8a32091.jpg', '2023-02-01 03:09:38', '2023-02-01 03:09:38');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
                          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                          `email` char(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                          `password` char(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                          `created_at` datetime(0) NULL DEFAULT NULL,
                          `updated_at` datetime(0) NULL DEFAULT NULL,
                          `token` char(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
                          PRIMARY KEY (`id`) USING BTREE,
                          UNIQUE INDEX `users_email_index`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'test@email.com', '$2y$10$SD5g.3Y3M49WsE1x6mgZQOirGs2B97RHG0JbqY5dtzVdwAseNqTLe', '2023-02-01 00:50:14', '2023-02-01 01:16:25', '822598513530fe4733a3db5dc82068e5c99a0da1c765d1bd21a7589b4c92e7d3');

SET FOREIGN_KEY_CHECKS = 1;
