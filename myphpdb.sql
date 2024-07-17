/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : myphpdb

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 16/06/2024 15:23:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `AdminID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`AdminID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, 'lwhtest', 'admin');

-- ----------------------------
-- Table structure for customers
-- ----------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers`  (
  `CustomerID` int(11) NOT NULL AUTO_INCREMENT,
  `Phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `FullName` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`CustomerID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of customers
-- ----------------------------
INSERT INTO `customers` VALUES (1, '13800138000', 'zhangwei@example.com', '张伟');
INSERT INTO `customers` VALUES (2, '13912345678', 'wangfang@example.com', '王芳');
INSERT INTO `customers` VALUES (3, '13700001234', 'li_na@example.com', '李 娜');
INSERT INTO `customers` VALUES (4, '13612345678', 'liuyang@example.com', '刘 洋');
INSERT INTO `customers` VALUES (5, '13500001234', 'chenjing@example.com', '陈 静');
INSERT INTO `customers` VALUES (6, '13412345678', 'yanghui@example.com', '杨 辉');
INSERT INTO `customers` VALUES (7, '13300001234', 'zhaolei@example.com', '赵 雷');
INSERT INTO `customers` VALUES (8, '13212345678', 'huangyan@example.com', '黄 艳');
INSERT INTO `customers` VALUES (9, '13112345678', 'zhouming@example.com', '周 明');
INSERT INTO `customers` VALUES (10, '13000001234', 'wujun@example.com', '吴 军');
INSERT INTO `customers` VALUES (11, '12912345678', 'zhengli@example.com', '郑 丽');
INSERT INTO `customers` VALUES (12, '12812345678', 'suntao@example.com', '孙 涛');
INSERT INTO `customers` VALUES (13, '12700001234', 'xuqiang@example.com', '徐 强');
INSERT INTO `customers` VALUES (14, '12612345678', 'zhuyan@example.com', '朱 燕');
INSERT INTO `customers` VALUES (17, '11334455', 'ergou@test.com', '二狗');
INSERT INTO `customers` VALUES (16, '113344', 'rick@outlook.com', '瑞克');

-- ----------------------------
-- Table structure for dishes
-- ----------------------------
DROP TABLE IF EXISTS `dishes`;
CREATE TABLE `dishes`  (
  `DishID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `Price` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`DishID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dishes
-- ----------------------------
INSERT INTO `dishes` VALUES (2, '宫保鸡丁', '鲜香微辣的经典川菜，鸡肉丁与花生米的完美结合111', 32.00);
INSERT INTO `dishes` VALUES (3, '鱼香茄子', '酸甜适口，软糯的茄子搭配特制鱼香汁', 24.00);
INSERT INTO `dishes` VALUES (4, '清蒸鲈鱼', '保留了鱼的原汁原味，肉质细嫩，清淡可口', 68.00);
INSERT INTO `dishes` VALUES (5, '红烧肉', '肥而不腻，色泽红亮，入口即化', 38.00);
INSERT INTO `dishes` VALUES (6, '西湖牛肉羹', '口感滑嫩，鲜美可口，一道经典的江南汤品', 28.00);
INSERT INTO `dishes` VALUES (7, '麻婆豆腐', '麻辣鲜香，豆腐嫩滑，是四川菜系中的经典之作', 22.00);
INSERT INTO `dishes` VALUES (8, '干煸四季豆', '四季豆干炒至表皮微皱，香辣可口', 18.00);
INSERT INTO `dishes` VALUES (9, '水煮鱼', '鱼片滑嫩，辣椒和花椒带来热烈的麻辣感', 58.00);
INSERT INTO `dishes` VALUES (10, '手撕包菜', '清脆爽口，简单调味突显蔬菜本味', 16.00);
INSERT INTO `dishes` VALUES (11, '东坡肉', '肥瘦相间，慢火炖制，肉质酥烂', 42.00);
INSERT INTO `dishes` VALUES (12, '糖醋排骨', '酸甜适中，外焦里嫩，色泽诱人', 36.00);
INSERT INTO `dishes` VALUES (13, '蒜蓉西兰花', '西兰花清脆，蒜香浓郁，营养丰富', 20.00);
INSERT INTO `dishes` VALUES (14, '虾仁炒饭', '米饭松散，虾仁鲜美，简单而美味的主食', 26.00);
INSERT INTO `dishes` VALUES (15, '西红柿炒蛋', '家常菜，酸甜可口，色泽鲜艳', 14.00);

-- ----------------------------
-- Table structure for orderdetails
-- ----------------------------
DROP TABLE IF EXISTS `orderdetails`;
CREATE TABLE `orderdetails`  (
  `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NULL DEFAULT NULL,
  `DishID` int(11) NULL DEFAULT NULL,
  `Quantity` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`OrderDetailID`) USING BTREE,
  INDEX `OrderID`(`OrderID`) USING BTREE,
  INDEX `DishID`(`DishID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 24 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of orderdetails
-- ----------------------------
INSERT INTO `orderdetails` VALUES (22, 2, 2, 1);
INSERT INTO `orderdetails` VALUES (21, 1, 12, 1);
INSERT INTO `orderdetails` VALUES (20, 1, 10, 1);
INSERT INTO `orderdetails` VALUES (19, 1, 6, 1);
INSERT INTO `orderdetails` VALUES (18, 1, 4, 1);
INSERT INTO `orderdetails` VALUES (23, 2, 3, 1);

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `ReservationID` int(11) NULL DEFAULT NULL,
  `OrderTime` datetime NULL DEFAULT NULL,
  `TotalPrice` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`OrderID`) USING BTREE,
  INDEX `ReservationID`(`ReservationID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 2, '2024-06-15 22:01:00', 148.00);
INSERT INTO `orders` VALUES (2, 3, '2024-06-15 14:13:00', 56.00);

-- ----------------------------
-- Table structure for reservations
-- ----------------------------
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations`  (
  `ReservationID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NULL DEFAULT NULL,
  `TableID` int(11) NULL DEFAULT NULL,
  `ReservationTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`ReservationID`) USING BTREE,
  INDEX `CustomerID`(`CustomerID`) USING BTREE,
  INDEX `TableID`(`TableID`) USING BTREE,
  INDEX `idx_reservations_reservationtime`(`ReservationTime`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of reservations
-- ----------------------------
INSERT INTO `reservations` VALUES (2, 1, 9, '2024-06-15 22:01:00');
INSERT INTO `reservations` VALUES (3, 17, 2, '2024-06-16 14:11:00');
INSERT INTO `reservations` VALUES (4, 16, 9, '2024-06-16 14:31:00');

-- ----------------------------
-- Table structure for tables
-- ----------------------------
DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables`  (
  `TableID` int(11) NOT NULL AUTO_INCREMENT,
  `Capacity` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`TableID`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of tables
-- ----------------------------
INSERT INTO `tables` VALUES (1, 8);
INSERT INTO `tables` VALUES (2, 10);
INSERT INTO `tables` VALUES (3, 8);
INSERT INTO `tables` VALUES (4, 8);
INSERT INTO `tables` VALUES (5, 10);
INSERT INTO `tables` VALUES (6, 12);
INSERT INTO `tables` VALUES (7, 4);
INSERT INTO `tables` VALUES (8, 4);
INSERT INTO `tables` VALUES (9, 4);
INSERT INTO `tables` VALUES (10, 4);
INSERT INTO `tables` VALUES (11, 12);
INSERT INTO `tables` VALUES (12, 16);
INSERT INTO `tables` VALUES (13, 16);
INSERT INTO `tables` VALUES (14, 20);
INSERT INTO `tables` VALUES (15, 12);

SET FOREIGN_KEY_CHECKS = 1;
