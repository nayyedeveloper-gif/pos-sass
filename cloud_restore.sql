-- MySQL dump 10.13  Distrib 9.5.0, for macos15.4 (arm64)
--
-- Host: localhost    Database: teahouse_pos
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '6945d99a-b6cf-11f0-a974-65c3a4ecb1b4:1-32371';

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('pos_machine_id','s:32:\"5d992dd1b9bfc0b59d2b0d5f6a9109f9\";',2079932537);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_transactions`
--

DROP TABLE IF EXISTS `card_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_id` bigint unsigned NOT NULL,
  `transaction_type` enum('load','payment','refund','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'payment',
  `amount` decimal(10,2) NOT NULL,
  `balance_before` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_transactions_created_by_foreign` (`created_by`),
  KEY `card_transactions_card_id_index` (`card_id`),
  KEY `card_transactions_transaction_type_index` (`transaction_type`),
  KEY `card_transactions_order_id_index` (`order_id`),
  KEY `card_transactions_created_at_index` (`created_at`),
  CONSTRAINT `card_transactions_card_id_foreign` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `card_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `card_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_transactions`
--

LOCK TABLES `card_transactions` WRITE;
/*!40000 ALTER TABLE `card_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `card_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `card_type` enum('virtual','physical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'virtual',
  `issued_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cards_card_number_unique` (`card_number`),
  KEY `cards_card_number_index` (`card_number`),
  KEY `cards_customer_id_index` (`customer_id`),
  KEY `cards_status_index` (`status`),
  CONSTRAINT `cards_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `printer_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Nan Pyar / Paratha','နံပြား/ပလာတာ/ အီကြာ',NULL,'nan_pyar',1,1,'2025-11-30 18:23:42','2025-11-30 18:23:42',NULL),(2,'Foods','အစားအစာများ',NULL,'kitchen',1,2,'2025-11-30 18:23:42','2025-11-30 18:23:42',NULL),(3,'Drinks','သောက်စရာများ',NULL,'bar',1,3,'2025-11-30 18:23:42','2025-11-30 18:23:42',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_loyalty_transactions`
--

DROP TABLE IF EXISTS `customer_loyalty_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_loyalty_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `type` enum('earn','redeem','expire','adjust') COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `balance_before` int NOT NULL,
  `balance_after` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_loyalty_transactions_customer_id_foreign` (`customer_id`),
  KEY `customer_loyalty_transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `customer_loyalty_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_loyalty_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_loyalty_transactions`
--

LOCK TABLES `customer_loyalty_transactions` WRITE;
/*!40000 ALTER TABLE `customer_loyalty_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_loyalty_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `loyalty_points` int NOT NULL DEFAULT '0',
  `total_spent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `visit_count` int NOT NULL DEFAULT '0',
  `last_visit_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_customer_code_unique` (`customer_code`),
  UNIQUE KEY `customers_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_sales_summaries`
--

DROP TABLE IF EXISTS `daily_sales_summaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_sales_summaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `total_orders` int NOT NULL DEFAULT '0',
  `gross_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discounts` decimal(10,2) NOT NULL DEFAULT '0.00',
  `taxes` decimal(10,2) NOT NULL DEFAULT '0.00',
  `service_charges` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dine_in_orders` int NOT NULL DEFAULT '0',
  `takeaway_orders` int NOT NULL DEFAULT '0',
  `cash_payments` decimal(10,2) NOT NULL DEFAULT '0.00',
  `card_payments` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mobile_payments` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_sales_summaries_date_unique` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_sales_summaries`
--

LOCK TABLES `daily_sales_summaries` WRITE;
/*!40000 ALTER TABLE `daily_sales_summaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `daily_sales_summaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  KEY `expenses_expense_date_category_index` (`expense_date`,`category`),
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_stock_usage`
--

DROP TABLE IF EXISTS `item_stock_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_stock_usage` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint unsigned NOT NULL,
  `stock_item_id` bigint unsigned NOT NULL,
  `quantity_used` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_stock_usage_item_id_foreign` (`item_id`),
  KEY `item_stock_usage_stock_item_id_foreign` (`stock_item_id`),
  CONSTRAINT `item_stock_usage_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_stock_usage_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_stock_usage`
--

LOCK TABLES `item_stock_usage` WRITE;
/*!40000 ALTER TABLE `item_stock_usage` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_stock_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_mm_zawgyi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_category_id_foreign` (`category_id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,1,'Espresso','အက်စ်ပရက်ဆို',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:12:37','2025-11-30 17:12:37'),(2,1,'Americano','အမေရိကန်နို',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:10:54','2025-11-30 17:10:54'),(3,1,'Cappuccino','ကက်ပူချီနို',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:11:25','2025-11-30 17:11:25'),(4,1,'Latte','လာတေး',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:12:42','2025-11-30 17:12:42'),(5,1,'Mocha','မိုကာ',NULL,NULL,3500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:12:46','2025-11-30 17:12:46'),(6,2,'Iced Americano','အမေရိကန်နိုအေး',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:12:53','2025-11-30 17:12:53'),(7,2,'Iced Latte','လာတေးအေး',NULL,NULL,3500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:13:02','2025-11-30 17:13:02'),(8,2,'Iced Mocha','မိုကာအေး',NULL,NULL,4000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:13:05','2025-11-30 17:13:05'),(9,2,'Iced Caramel Macchiato','ကာရာမယ်မက်ကီအာတိုအေး',NULL,NULL,4500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:12:58','2025-11-30 17:12:58'),(10,3,'Green Tea','လက်ဖက်စိမ်း',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:38','2025-11-30 18:28:38'),(11,3,'Black Tea','လက်ဖက်နက်',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:11:05','2025-11-30 17:11:05'),(12,3,'Lemon Tea','သံပုရာလက်ဖက်',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:32','2025-11-30 18:28:32'),(13,3,'Ginger Tea','ဂျင်းလက်ဖက်',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:41','2025-11-30 18:28:41'),(14,4,'Myanmar Milk Tea','မြန်မာနို့လက်ဖက်',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:13:13','2025-11-30 17:13:13'),(15,4,'Thai Milk Tea','ထိုင်းနို့လက်ဖက်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:16:55','2025-11-30 17:16:55'),(16,4,'Bubble Milk Tea','ပုလဲနို့လက်ဖက်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:11:13','2025-11-30 17:11:13'),(17,5,'Orange Juice','လိမ္မော်ရည်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:13','2025-11-30 18:28:13'),(18,5,'Lime Juice','သံပုရာရည်',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:25','2025-11-30 18:28:25'),(19,5,'Watermelon Juice','ဖရဲသီးဖျော်ရည်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:00','2025-11-30 18:28:00'),(20,5,'Mango Juice','သရက်သီးဖျော်ရည်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:29','2025-11-30 18:28:29'),(21,6,'Strawberry Smoothie','စတော်ဘယ်ရီချောမွေ့',NULL,NULL,3500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:27:56','2025-11-30 18:27:56'),(22,6,'Mango Smoothie','သရက်သီးချောမွေ့',NULL,NULL,3500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:22','2025-11-30 18:28:22'),(23,6,'Avocado Smoothie','ထောပတ်သီးချောမွေ့',NULL,NULL,4000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:11:00','2025-11-30 17:11:00'),(24,7,'French Fries','အာလူးကြော်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:49','2025-11-30 18:28:49'),(25,7,'Spring Rolls','ကော်ပြန့်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:27:53','2025-11-30 18:27:53'),(26,7,'Samosa','ဆမူဆာ',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:10','2025-11-30 18:28:10'),(27,7,'Chicken Wings','ကြက်သားတောင်ပံ',NULL,NULL,4000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:51','2025-11-30 18:28:51'),(28,8,'Mohinga','မုန့်ဟင်းခါး',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:19','2025-11-30 18:28:19'),(29,8,'Shan Noodles','ရှမ်းခေါက်ဆွဲ',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:06','2025-11-30 18:28:06'),(30,8,'Nan Gyi Thoke','နန်းကြီးသုပ်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:16','2025-11-30 18:28:16'),(31,9,'Fried Noodles','ခေါက်ဆွဲကြော်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:46','2025-11-30 18:28:46'),(32,9,'Soup Noodles','ခေါက်ဆွဲချို',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:03','2025-11-30 18:28:03'),(33,10,'Fried Rice','ထမင်းကြော်',NULL,NULL,3500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:43','2025-11-30 18:28:43'),(34,10,'Chicken Rice','ကြက်သားထမင်း',NULL,NULL,4000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:55','2025-11-30 18:28:55'),(35,11,'Cake Slice','ကိတ်မုန့်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:11:20','2025-11-30 17:11:20'),(36,11,'Ice Cream','ရေခဲမုန့်',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 18:28:35','2025-11-30 18:28:35'),(37,11,'Brownie','ဘရောင်နီ',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-18 15:11:24','2025-11-30 17:11:09','2025-11-30 17:11:09'),(38,1,'Bean Nan Pyar','ပဲနံပြား',NULL,'ပဲနံပြား',1000.00,NULL,1,1,0,'2025-11-30 17:38:28','2025-11-30 18:29:08',NULL),(39,3,'Cho Seint','ချိုစိမ့်',NULL,'ချိုစိမ့်',1800.00,NULL,1,1,0,'2025-11-30 18:29:42','2025-11-30 18:29:42',NULL),(40,2,'Shan Nodel','ရှမ်းခေါက်ဆွဲ',NULL,'ရှမ်းခေါက်ဆွဲ',3000.00,NULL,1,1,0,'2025-11-30 18:31:18','2025-11-30 18:31:18',NULL),(41,1,'Nan Pyar','နံပြား',NULL,NULL,500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(42,1,'Palata','ပလာတာ',NULL,NULL,1000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(43,1,'Egg Palata','ကြက်ဥပလာတာ',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(44,1,'E Kyar Kway','အီကြာကွေး',NULL,NULL,500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(45,1,'Pe Byouk','ပဲပြုတ်',NULL,NULL,500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(46,1,'Butter Naan','ထောပတ်နံပြား',NULL,NULL,1000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(47,1,'Mutton Curry','ဆိတ်သားဟင်း',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(48,1,'Pe Palata','ပဲပလာတာ',NULL,NULL,1200.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(49,2,'Mohinga','မုန့်ဟင်းခါး',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(50,2,'Shan Noodles','ရှမ်းခေါက်ဆွဲ',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(51,2,'Nan Gyi Thoke','နန်းကြီးသုပ်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(52,2,'Fried Rice','ထမင်းကြော်',NULL,NULL,3500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(53,2,'Chicken Rice','ကြက်ဆီထမင်း',NULL,NULL,4000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(54,2,'Fried Noodles','ခေါက်ဆွဲကြော်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(55,2,'Chicken Wings','ကြက်သားတောင်ပံ',NULL,NULL,4000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(56,2,'French Fries','အာလူးကြော်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(57,2,'Samosa','ဆမူဆာ',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(58,2,'Spring Rolls','ကော်ပြန့်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(59,2,'Pork Stick','ဝက်သားတုတ်ထိုး',NULL,NULL,500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(60,3,'Myanmar Tea','လက်ဖက်ရည်',NULL,NULL,1000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(61,3,'Coffee','ကော်ဖီ',NULL,NULL,1000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(62,3,'Black Coffee','ကော်ဖီမဲ',NULL,NULL,1000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(63,3,'Iced Coffee','ကော်ဖီအေး',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(64,3,'Lemon Tea','သံပုရာလက်ဖက်ရည်',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(65,3,'Lime Juice','သံပုရာရည်',NULL,NULL,1500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(66,3,'Orange Juice','လိမ္မော်ရည်',NULL,NULL,2000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(67,3,'Strawberry Smoothie','စတော်ဘယ်ရီဖျော်ရည်',NULL,NULL,2500.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(68,3,'Avocado Smoothie','ထောပတ်သီးဖျော်ရည်',NULL,NULL,3000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL),(69,3,'Cola','ကိုလာ',NULL,NULL,1000.00,NULL,1,1,0,'2025-11-30 19:01:24','2025-11-30 19:01:24',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_01_01_000001_create_users_table',1),(2,'2024_01_01_000002_create_cache_table',1),(3,'2024_01_01_000003_create_jobs_table',1),(4,'2024_01_01_000004_create_categories_table',1),(5,'2024_01_01_000005_create_items_table',1),(6,'2024_01_01_000006_create_tables_table',1),(7,'2024_01_01_000007_create_orders_table',1),(8,'2024_01_01_000008_create_order_items_table',1),(9,'2024_01_01_000009_create_printers_table',1),(10,'2024_01_01_000010_create_settings_table',1),(11,'2024_11_03_000001_simplify_order_statuses',1),(12,'2024_11_03_000002_add_foc_quantity_to_order_items',1),(13,'2024_11_03_000003_add_payment_details_to_orders',1),(14,'2025_11_03_024417_create_permission_tables',1),(15,'2025_11_03_084620_create_expenses_table',1),(16,'2025_11_03_161107_create_signage_media_table',1),(17,'2025_11_03_164145_create_signage_stats_table',1),(18,'2025_11_03_202559_create_inventory_tables',1),(19,'2025_11_03_202749_create_customers_table',1),(20,'2025_11_03_203430_create_report_caches_table',1),(21,'2025_11_04_013519_create_cards_table',1),(22,'2025_11_04_013530_create_card_transactions_table',1),(23,'2025_11_05_153301_add_zawgyi_name_to_items_table',1),(24,'2025_11_07_140248_create_products_table',1),(25,'2025_11_10_172104_add_service_charge_percentage_to_orders_table',1),(26,'2025_11_11_133004_create_personal_access_tokens_table',1),(27,'2025_11_13_000001_add_connection_type_to_printers_table',1),(28,'2025_11_30_230000_update_kitchen_printer_to_nan_pyar',2),(29,'2025_11_30_231000_configure_three_printers',3),(30,'2025_11_30_232000_update_categories_printer_type',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(3,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `foc_quantity` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `is_foc` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','preparing','ready','served') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_printed` tinyint(1) NOT NULL DEFAULT '0',
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_item_id_foreign` (`item_id`),
  KEY `order_items_order_id_status_index` (`order_id`,`status`),
  CONSTRAINT `order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_id` bigint unsigned DEFAULT NULL,
  `waiter_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `cashier_id` bigint unsigned DEFAULT NULL,
  `order_type` enum('dine_in','takeaway') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine_in',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `service_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `service_charge_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','card','mobile') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `amount_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `change_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_table_id_foreign` (`table_id`),
  KEY `orders_waiter_id_foreign` (`waiter_id`),
  KEY `orders_cashier_id_foreign` (`cashier_id`),
  KEY `orders_status_created_at_index` (`status`,`created_at`),
  KEY `orders_order_number_index` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  CONSTRAINT `orders_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_waiter_id_foreign` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view orders','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(2,'create orders','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(3,'update orders','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(4,'delete orders','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(5,'complete orders','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(6,'view items','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(7,'create items','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(8,'update items','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(9,'delete items','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(10,'view tables','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(11,'create tables','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(12,'update tables','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(13,'delete tables','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(14,'view users','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(15,'create users','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(16,'update users','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(17,'delete users','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(18,'view reports','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(19,'view dashboard','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(20,'view settings','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(21,'update settings','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(22,'view printers','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(23,'update printers','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(24,'print receipts','web','2025-11-18 15:11:23','2025-11-18 15:11:23');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `printers`
--

DROP TABLE IF EXISTS `printers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `printers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'receipt',
  `connection_type` enum('network','usb') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'network',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` int NOT NULL DEFAULT '9100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `paper_width` int NOT NULL DEFAULT '80',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `printers`
--

LOCK TABLES `printers` WRITE;
/*!40000 ALTER TABLE `printers` DISABLE KEYS */;
INSERT INTO `printers` VALUES (1,'Cashier Receipt Printer','receipt','network','192.168.0.99',9100,1,80,'2025-11-18 15:11:24','2025-11-30 17:30:17'),(2,'Kitchen Printer (Food)','kitchen','network','192.168.0.88',9100,1,80,'2025-11-18 15:11:24','2025-11-30 17:28:43'),(3,'Bar Printer (Beverages)','Bar','network','192.168.0.66',9100,1,80,'2025-11-18 15:11:24','2025-11-30 17:42:46'),(4,'Nan Pyar Printer','nan_pyar','network','192.168.0.77',9100,1,80,'2025-11-30 17:29:37','2025-11-30 17:42:38');
/*!40000 ALTER TABLE `printers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('diamond','gold','platinum') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `type_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `stock_item_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_stock_item_id_foreign` (`stock_item_id`),
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_order_items_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `status` enum('pending','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_caches`
--

DROP TABLE IF EXISTS `report_caches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_caches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` date NOT NULL,
  `data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_caches_report_type_report_date_unique` (`report_type`,`report_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_caches`
--

LOCK TABLES `report_caches` WRITE;
/*!40000 ALTER TABLE `report_caches` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_caches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(1,2),(2,2),(3,2),(5,2),(6,2),(10,2),(24,2),(1,3),(2,3),(3,3),(4,3),(6,3),(10,3),(1,4),(1,5);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(2,'cashier','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(3,'waiter','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(4,'kitchen','web','2025-11-18 15:11:23','2025-11-18 15:11:23'),(5,'Bar','web','2025-11-18 15:11:23','2025-11-18 15:11:23');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3bHxLcUdMgFoGb238cnfLZ7ds5gmZwPdItWKaacT',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVTlMOEs1WFcwYjVIbEw4UDlLSVZ0Nk42aXV4cUxKNEd0T1lIYkF6MSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572387),('8ZaaUyMcgxkCj3uzQChXZxsu5aXtYr0d9iN5ed5a',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidE9mWlRsU1RXa0FWb2RZZzNiRThVcHNjTE8yS0J2Sk9OUzYzdGFnUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764573917),('cpPX6KSxPhRcQZFfzCmmCtZuoSMpRvfsfCvizchG',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibTdBQjNBYzFQTnNHaGE1aUhQNTZpYTJHTFhsOWFxcEtkaXlreHZ0ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572391),('CYbC5aSySqdTxnSbuT203yYayZmmo8hN04sID1xz',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRzdWS09HNWZxaXFQdjFQVWtXYnNwN3BNcUl3RnNsODFoZjBoNUJtNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764571865),('FSVowRxUO4mjnFWpIvci22JiGWNOvoFB1BnDJGN9',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOE1XS1haNE9xZGFYZFA4UlNCY3oxdndobjI2UTNTWVVnVWthZkhQaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764573917),('gDgne3RAEhaIvDVFZY9VD0PMjoiRZ73KGU1ux6V4',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidG1JQ3ZCeXVndHhoYjcxVE54aEdlaWVvWGZlWGpWMnVPYzB1ZHl2cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572383),('GKiu2NZ0vrUZeUVofzh5AtKeT3nkvBunhYV3YmUU',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3lRZG9xV2dQZ1N3NXFicXZDRlgyVFZCZnVUcm1TVDBwelRteE5TcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764573927),('h1xajkLSlz4f4IWVSz6ZUdZhe3Ceoxu3TGwkAJ5s',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVlqcDlLU0pOVFJXbDNGaXI1SlFCUklDcndXUUlnWE9rUkpsWGdXVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764578586),('hCwLMv57adaSLfgNJqRgubQtjjXrH6zYK2ynlNsC',3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlpiSkQ3Y0xmdVhWWlRjdUt6YkFBWnU0VFhUYW0yOGcwYmR2dnBQNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC93YWl0ZXIvb3JkZXJzIjtzOjU6InJvdXRlIjtzOjE5OiJ3YWl0ZXIub3JkZXJzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9',1764580084),('i1ypFf7RnoJIWzEiSVZW6YyGK2VXPHJ7aHT1wpu9',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVlQ3bzNFc3F3Wm5VR3lIZTVmTWdpRkRTVkp0RTlXN05zR1k1YXJwNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572390),('LJOJbprP2A89wa6pmEtSVE0s6NN0ECNOMFMZGaZf',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0xiYjFTdjM3UnJCUUMwS1AwV0dOUXd2MW1aQ1E0cVgxT2ZVNGVnRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572363),('OUIHm3rDBuQVLuSPeU4BDPm4gHNf2gmpCI2jJlfI',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVdmNkdNd0tMcW5vY0xXanltTmx3SjFVclo3bjczN1BNTFdQMTFLbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572389),('q9NuHT0YkDTzZZ1veIubmrDyVIrSi5Dohyoev28d',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoia2lMTG92dzFDVzlsMHR2TXhFNFlkS1BNTHFINWJuQ1A0cUlVVVVaUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXNoaWVyL29yZGVycyI7czo1OiJyb3V0ZSI7czoyMDoiY2FzaGllci5vcmRlcnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1764579000),('T4nWKWzEHu4DmcIheTl48kdGYjaLfs2jslIPtCi8',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjFiS2x3bWtHVGhQbW83NkZJRzBqRGhON0ZnVjB5ZkFubVpNYWp0ciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764579000),('vrpvTMFGJE16FMPuFHPh2gwd8sjTnxgVjS0g19tR',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVW9VSU5hMjVTd2lBZENGZk1TRENsNGU4MU1OdThYYnpUclE4R1pvNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764572388),('Yf83Qf7tz6nWYFc1RlfDOADu5l07MCffMUqPkhbZ',NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiemdQeE9oUlJQQ3N0UjM1VlJqNjhsbWx2RnRlNzFwQVVlTWFXTktCbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764571970),('YtYL5RSFbBC7ZrtEQIY5jZZIfWA8mFKSBCRccBze',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTZpb1RyVHM5ME1mUlVYU3FmVm1JQ21ZOWtNam1NMmZnSmJZZ01QdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tYW5pZmVzdC5qc29uIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1764571893);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'business_name','သာချို ကဖီးနှင့်စားဖွယ်စုံ','string','2025-11-18 15:11:24','2025-12-01 04:54:16'),(2,'business_name_mm','သာချို ကဖီးနှင့်စားဖွယ်စုံ','string','2025-11-18 15:11:24','2025-12-01 04:38:39'),(3,'business_address','Yangon, Myanmar','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(4,'business_address_mm','ရန်ကုန်မြို့၊ မြန်မာနိုင်ငံ','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(5,'business_phone','+95 9 123 456 789','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(6,'business_email','info@tharchocafe.com','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(7,'tax_enabled','0','boolean','2025-11-18 15:11:24','2025-11-18 15:11:24'),(8,'tax_percentage','0','decimal','2025-11-18 15:11:24','2025-11-18 15:11:24'),(9,'service_charge_enabled','0','boolean','2025-11-18 15:11:24','2025-11-18 15:11:24'),(10,'service_charge_amount','0','decimal','2025-11-18 15:11:24','2025-11-18 15:11:24'),(11,'currency','MMK','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(12,'currency_symbol','Ks','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(13,'auto_print_kitchen','1','boolean','2025-11-18 15:11:24','2025-12-01 04:54:16'),(14,'auto_print_Bar','1','boolean','2025-11-18 15:11:24','2025-12-01 04:54:16'),(15,'app_logo','logos/04wf1PUYKJCnEisLmejUqT3bUuVzYiaB6Rbl3qev.png','string','2025-11-30 17:58:51','2025-12-01 04:38:39'),(16,'license_key','POS-5d992dd1-LIFETIME-F11299A3','string','2025-12-01 04:08:17','2025-12-01 04:08:17'),(17,'app_name','သာချို ကဖီးနှင့်စားဖွယ်စုံ','string','2025-12-01 04:17:14','2025-12-01 04:45:24'),(18,'default_tax_percentage','5','float','2025-12-01 04:17:14','2025-12-01 04:17:14'),(19,'default_service_charge_percentage','10','float','2025-12-01 04:17:14','2025-12-01 04:17:14'),(20,'receipt_header','','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(21,'receipt_footer','Thank you for your visit!','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(22,'show_logo_on_receipt','0','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(23,'date_format','Y-m-d','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(24,'time_format','H:i','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(25,'timezone','Asia/Yangon','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(26,'signage_enabled','1','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(27,'promotional_message','Welcome to our restaurant!','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(28,'signage_rotation_speed','10','integer','2025-12-01 04:17:14','2025-12-01 04:17:14'),(29,'signage_show_prices','1','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(30,'signage_show_descriptions','1','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(31,'signage_show_availability','1','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(32,'signage_theme','dark','string','2025-12-01 04:17:14','2025-12-01 04:17:14'),(33,'signage_auto_refresh','5','integer','2025-12-01 04:17:14','2025-12-01 04:17:14'),(34,'signage_show_media','1','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(35,'auto_print_receipt','0','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(36,'card_system_enabled','0','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(37,'card_bonus_enabled','0','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(38,'card_bonus_percentage','0','float','2025-12-01 04:17:14','2025-12-01 04:17:14'),(39,'card_expiry_enabled','0','boolean','2025-12-01 04:17:14','2025-12-01 04:54:16'),(40,'card_expiry_months','12','integer','2025-12-01 04:17:14','2025-12-01 04:17:14');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signage_media`
--

DROP TABLE IF EXISTS `signage_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signage_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('video','image') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL DEFAULT '10',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signage_media`
--

LOCK TABLES `signage_media` WRITE;
/*!40000 ALTER TABLE `signage_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `signage_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signage_stats`
--

DROP TABLE IF EXISTS `signage_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signage_stats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `total_views` int NOT NULL DEFAULT '0',
  `category_rotations` int NOT NULL DEFAULT '0',
  `media_displays` int NOT NULL DEFAULT '0',
  `total_uptime_minutes` int NOT NULL DEFAULT '0',
  `popular_categories` json DEFAULT NULL,
  `media_views` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `signage_stats_date_unique` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signage_stats`
--

LOCK TABLES `signage_stats` WRITE;
/*!40000 ALTER TABLE `signage_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `signage_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_item_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('add','remove','damage','expired','correction') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `previous_stock` decimal(10,2) NOT NULL,
  `new_stock` decimal(10,2) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustments_stock_item_id_foreign` (`stock_item_id`),
  KEY `stock_adjustments_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_adjustments_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_adjustments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_adjustments`
--

LOCK TABLES `stock_adjustments` WRITE;
/*!40000 ALTER TABLE `stock_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_items`
--

DROP TABLE IF EXISTS `stock_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_items_sku_unique` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_items`
--

LOCK TABLES `stock_items` WRITE;
/*!40000 ALTER TABLE `stock_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '4',
  `status` enum('available','occupied','reserved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,'Table 1','စားပွဲ ၁',2,'available',1,1,'2025-11-18 15:11:24','2025-11-30 15:14:16',NULL),(2,'Table 2','စားပွဲ ၂',2,'available',1,2,'2025-11-18 15:11:24','2025-11-30 14:49:43',NULL),(3,'Table 3','စားပွဲ ၃',4,'available',1,3,'2025-11-18 15:11:24','2025-11-30 14:37:23',NULL),(4,'Table 4','စားပွဲ ၄',4,'available',1,4,'2025-11-18 15:11:24','2025-11-30 15:14:55',NULL),(5,'Table 5','စားပွဲ ၅',4,'available',1,5,'2025-11-18 15:11:24','2025-11-30 15:52:47',NULL),(6,'Table 6','စားပွဲ ၆',4,'available',1,6,'2025-11-18 15:11:24','2025-11-30 14:50:04',NULL),(7,'Table 7','စားပွဲ ၇',6,'available',1,7,'2025-11-18 15:11:24','2025-11-30 15:19:44',NULL),(8,'Table 8','စားပွဲ ၈',6,'available',1,8,'2025-11-18 15:11:24','2025-12-01 06:52:30',NULL),(9,'Table 9','စားပွဲ ၉',8,'available',1,9,'2025-11-18 15:11:24','2025-12-01 05:23:01',NULL),(10,'Table 10','စားပွဲ ၁၀',8,'available',1,10,'2025-11-18 15:11:24','2025-12-01 05:22:58',NULL);
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@tharchocafe.com',NULL,'$2y$12$lzOVyxDX1skSd65BxmvgHOsjDZWHDWsfsgeMnWcMx793tdStIeVzy','+95 9 111 111 111',1,NULL,'2025-11-18 15:11:23','2025-11-18 15:11:23',NULL),(2,'Cashier','cashier@tharchocafe.com',NULL,'$2y$12$Lzf.eF4ApJLWwmycS6CEg.LWZtqc.6Y4TWbsJqLFP0O9Ka4JJTowa','+95 9 222 222 222',1,NULL,'2025-11-18 15:11:23','2025-11-18 15:11:23',NULL),(3,'Htet Htet','waiter@tharchocafe.com',NULL,'$2y$12$ib2B9GREzC4smi7BjpDnCOrXCjsWfYIPbhe7hAy8z53JxvtkucimG','+95 9 333 333 333',1,NULL,'2025-11-18 15:11:23','2025-11-30 19:06:43',NULL),(4,'Ma Wint','mawint@tharchocafe.com',NULL,'$2y$12$2N4lg9gBkfzaQwb0W9FN2u1SBX2yZ/Y7oIQ/KVIVM.RDk4g0Rt9e6','+95 9 444 444 444',1,NULL,'2025-11-18 15:11:24','2025-12-01 06:16:45',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-01 16:05:53
