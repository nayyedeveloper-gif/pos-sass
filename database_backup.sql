-- MySQL dump 10.13  Distrib 9.5.0, for macos15.4 (arm64)
--
-- Host: 127.0.0.1    Database: teahouse_pos
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
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,2,2,0,2500.00,5000.00,0,'','pending',0,NULL,'2025-11-18 15:14:08','2025-11-30 14:18:03'),(2,1,3,2,0,3000.00,6000.00,0,'','pending',0,NULL,'2025-11-18 15:14:08','2025-11-30 14:18:03'),(3,1,1,2,0,2000.00,4000.00,0,'','pending',0,NULL,'2025-11-18 15:14:08','2025-11-30 14:18:03'),(4,2,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-18 15:18:58','2025-11-18 15:18:58'),(5,2,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-18 15:18:58','2025-11-18 15:18:58'),(6,2,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-18 15:18:58','2025-11-18 15:18:58'),(7,3,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 14:37:23','2025-11-30 14:37:23'),(8,3,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 14:37:23','2025-11-30 14:37:23'),(9,3,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 14:37:23','2025-11-30 14:37:23'),(10,4,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 14:42:00','2025-11-30 14:42:00'),(11,4,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 14:42:00','2025-11-30 14:42:00'),(12,4,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 14:42:00','2025-11-30 14:42:00'),(13,5,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 14:47:13','2025-11-30 14:47:13'),(14,5,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 14:47:13','2025-11-30 14:47:13'),(15,5,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 14:47:13','2025-11-30 14:47:13'),(16,6,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 14:50:04','2025-11-30 14:50:04'),(17,6,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 14:50:04','2025-11-30 14:50:04'),(18,6,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 14:50:04','2025-11-30 14:50:04'),(19,7,1,1,0,2000.00,2000.00,0,NULL,'pending',0,NULL,'2025-11-30 14:52:23','2025-11-30 14:52:23'),(20,7,24,1,0,2500.00,2500.00,0,NULL,'pending',0,NULL,'2025-11-30 14:52:23','2025-11-30 14:52:23'),(21,8,27,1,0,4000.00,4000.00,0,'','pending',0,NULL,'2025-11-30 14:58:49','2025-11-30 14:58:49'),(22,8,24,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 14:58:49','2025-11-30 14:58:49'),(23,8,26,1,0,1500.00,1500.00,0,'','pending',0,NULL,'2025-11-30 14:58:49','2025-11-30 14:58:49'),(24,8,25,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 14:58:49','2025-11-30 14:58:49'),(25,9,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:00:57','2025-11-30 15:00:57','2025-11-30 15:00:57'),(26,9,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:00:57','2025-11-30 15:00:57','2025-11-30 15:00:57'),(27,9,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:00:57','2025-11-30 15:00:57','2025-11-30 15:00:57'),(28,9,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:00:57','2025-11-30 15:00:57','2025-11-30 15:00:57'),(29,10,28,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 15:04:28','2025-11-30 15:04:28','2025-11-30 15:04:28'),(30,10,30,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:04:28','2025-11-30 15:04:28','2025-11-30 15:04:28'),(31,10,29,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:04:28','2025-11-30 15:04:28','2025-11-30 15:04:28'),(32,11,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:08:26','2025-11-30 15:08:26','2025-11-30 15:08:26'),(33,11,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:08:26','2025-11-30 15:08:26','2025-11-30 15:08:26'),(34,11,30,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:08:26','2025-11-30 15:08:26','2025-11-30 15:08:26'),(35,11,28,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 15:08:26','2025-11-30 15:08:26','2025-11-30 15:08:26'),(36,11,29,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:08:26','2025-11-30 15:08:26','2025-11-30 15:08:26'),(37,12,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:14:55','2025-11-30 15:14:55','2025-11-30 15:14:55'),(38,12,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:14:55','2025-11-30 15:14:55','2025-11-30 15:14:55'),(39,12,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:14:55','2025-11-30 15:14:55','2025-11-30 15:14:55'),(40,12,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:14:55','2025-11-30 15:14:55','2025-11-30 15:14:55'),(41,13,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:18:01','2025-11-30 15:18:01','2025-11-30 15:18:01'),(42,13,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:18:01','2025-11-30 15:18:01','2025-11-30 15:18:01'),(43,13,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:18:01','2025-11-30 15:18:01','2025-11-30 15:18:01'),(44,13,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:18:01','2025-11-30 15:18:01','2025-11-30 15:18:01'),(45,14,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:19:44','2025-11-30 15:19:44','2025-11-30 15:19:44'),(46,14,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:19:44','2025-11-30 15:19:44','2025-11-30 15:19:44'),(47,14,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:19:44','2025-11-30 15:19:44','2025-11-30 15:19:44'),(48,14,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:19:44','2025-11-30 15:19:44','2025-11-30 15:19:44'),(49,15,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:23:26','2025-11-30 15:23:26','2025-11-30 15:23:26'),(50,15,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:23:26','2025-11-30 15:23:26','2025-11-30 15:23:26'),(51,15,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:23:26','2025-11-30 15:23:26','2025-11-30 15:23:26'),(52,16,28,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 15:30:59','2025-11-30 15:30:59','2025-11-30 15:30:59'),(53,16,30,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:30:59','2025-11-30 15:30:59','2025-11-30 15:30:59'),(54,16,29,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:30:59','2025-11-30 15:30:59','2025-11-30 15:30:59'),(55,17,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:37:35','2025-11-30 15:37:35','2025-11-30 15:37:35'),(56,17,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:37:35','2025-11-30 15:37:35','2025-11-30 15:37:35'),(57,17,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:37:35','2025-11-30 15:37:35','2025-11-30 15:37:35'),(58,18,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:38:58','2025-11-30 15:38:58','2025-11-30 15:38:58'),(59,18,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:38:58','2025-11-30 15:38:58','2025-11-30 15:38:58'),(60,18,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:38:58','2025-11-30 15:38:58','2025-11-30 15:38:58'),(61,18,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:38:58','2025-11-30 15:38:58','2025-11-30 15:38:58'),(62,18,28,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 15:38:58','2025-11-30 15:38:58','2025-11-30 15:38:58'),(63,18,30,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:38:58','2025-11-30 15:38:58','2025-11-30 15:38:58'),(64,19,28,1,0,2000.00,2000.00,0,'','pending',0,'2025-11-30 15:41:49','2025-11-30 15:41:49','2025-11-30 15:42:40'),(65,19,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:42:40','2025-11-30 15:42:40','2025-11-30 15:42:40'),(66,19,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 15:42:40','2025-11-30 15:42:40','2025-11-30 15:42:40'),(67,20,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:52:47','2025-11-30 15:52:47','2025-11-30 15:52:47'),(68,20,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:52:47','2025-11-30 15:52:47','2025-11-30 15:52:47'),(69,20,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:52:47','2025-11-30 15:52:47','2025-11-30 15:52:47'),(70,21,30,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:54:27','2025-11-30 15:54:27','2025-11-30 15:54:27'),(71,21,29,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:54:27','2025-11-30 15:54:27','2025-11-30 15:54:27'),(72,22,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 15:55:31','2025-11-30 15:55:31','2025-11-30 15:55:31'),(73,22,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:55:31','2025-11-30 15:55:31','2025-11-30 15:55:31'),(74,23,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 15:55:58','2025-11-30 15:55:58','2025-11-30 15:55:58'),(75,23,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 15:55:58','2025-11-30 15:55:58','2025-11-30 15:55:58'),(76,24,28,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 17:03:29','2025-11-30 17:03:29'),(77,24,30,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 17:03:29','2025-11-30 17:03:29'),(78,24,29,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 17:03:29','2025-11-30 17:03:29'),(79,25,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 17:04:25','2025-11-30 17:04:25','2025-11-30 17:04:25'),(80,25,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 17:04:25','2025-11-30 17:04:25','2025-11-30 17:04:25'),(81,26,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 17:06:10','2025-11-30 17:06:10'),(82,26,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 17:06:10','2025-11-30 17:06:10'),(83,26,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 17:06:10','2025-11-30 17:06:10'),(84,26,26,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 17:06:10','2025-11-30 17:06:10','2025-11-30 17:06:10'),(85,26,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 17:06:10','2025-11-30 17:06:10','2025-11-30 17:06:10'),(86,26,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 17:06:10','2025-11-30 17:06:10','2025-11-30 17:06:10'),(87,27,11,1,0,1500.00,1500.00,0,'','pending',0,NULL,'2025-11-30 17:06:45','2025-11-30 17:06:45'),(88,27,13,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 17:06:45','2025-11-30 17:06:45'),(89,27,12,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 17:06:45','2025-11-30 17:06:45'),(90,28,27,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 17:07:08','2025-11-30 17:07:08','2025-11-30 17:07:08'),(91,28,24,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 17:07:08','2025-11-30 17:07:08','2025-11-30 17:07:08'),(92,29,38,1,0,1000.00,1000.00,0,'','pending',0,NULL,'2025-11-30 17:41:14','2025-11-30 17:41:14'),(93,30,38,1,0,1000.00,1000.00,0,'','pending',0,NULL,'2025-11-30 17:43:03','2025-11-30 17:43:03'),(94,31,38,1,0,1000.00,1000.00,0,'','pending',0,NULL,'2025-11-30 17:44:38','2025-11-30 17:44:38'),(95,32,38,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 17:49:51','2025-11-30 17:49:51','2025-11-30 17:49:51'),(96,33,13,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 17:50:19','2025-11-30 17:50:19','2025-11-30 17:50:19'),(97,34,38,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 17:51:00','2025-11-30 17:51:00','2025-11-30 17:51:00'),(98,35,13,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 17:51:31','2025-11-30 17:51:31','2025-11-30 17:51:31'),(99,36,25,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 17:54:22','2025-11-30 17:54:22','2025-11-30 17:54:22'),(100,37,12,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 18:16:53','2025-11-30 18:16:53'),(101,37,10,1,0,1500.00,1500.00,0,'','pending',0,NULL,'2025-11-30 18:16:53','2025-11-30 18:16:53'),(102,38,13,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 18:17:22','2025-11-30 18:17:22','2025-11-30 18:17:22'),(103,38,10,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 18:17:22','2025-11-30 18:17:22','2025-11-30 18:17:22'),(104,38,12,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 18:17:22','2025-11-30 18:17:22','2025-11-30 18:17:22'),(105,39,18,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-30 18:18:15','2025-11-30 18:18:15'),(106,39,20,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-30 18:18:15','2025-11-30 18:18:15'),(107,39,17,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-30 18:18:15','2025-11-30 18:18:15'),(108,40,40,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 18:32:02','2025-11-30 18:32:02','2025-11-30 18:32:02'),(109,40,39,1,0,1800.00,1800.00,0,'','pending',1,'2025-11-30 18:32:02','2025-11-30 18:32:02','2025-11-30 18:32:02'),(110,41,40,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 18:46:16','2025-11-30 18:46:16','2025-11-30 18:46:16'),(111,42,53,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:03:10','2025-11-30 19:03:10','2025-11-30 19:03:10'),(112,42,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:03:10','2025-11-30 19:03:10','2025-11-30 19:03:10'),(113,42,60,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 19:03:10','2025-11-30 19:03:10','2025-11-30 19:03:10'),(114,42,67,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 19:03:10','2025-11-30 19:03:10','2025-11-30 19:03:10'),(115,43,53,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:07:45','2025-11-30 19:07:45','2025-11-30 19:07:45'),(116,43,54,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 19:07:45','2025-11-30 19:07:45','2025-11-30 19:07:45'),(117,43,60,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 19:07:45','2025-11-30 19:07:45','2025-11-30 19:07:45'),(118,43,67,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 19:07:45','2025-11-30 19:07:45','2025-11-30 19:07:45'),(119,44,53,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:10:00','2025-11-30 19:10:00','2025-11-30 19:10:00'),(120,44,54,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 19:10:00','2025-11-30 19:10:00','2025-11-30 19:10:00'),(121,44,50,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 19:10:00','2025-11-30 19:10:00','2025-11-30 19:10:00'),(122,45,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:12:27','2025-11-30 19:12:26','2025-11-30 19:12:27'),(123,45,53,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:12:27','2025-11-30 19:12:26','2025-11-30 19:12:27'),(124,45,50,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 19:12:27','2025-11-30 19:12:26','2025-11-30 19:12:27'),(125,46,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:18:47','2025-11-30 19:18:47','2025-11-30 19:18:47'),(126,46,59,1,0,500.00,500.00,0,'','pending',1,'2025-11-30 19:18:47','2025-11-30 19:18:47','2025-11-30 19:18:47'),(127,46,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:18:47','2025-11-30 19:18:47','2025-11-30 19:18:47'),(128,47,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:23:31','2025-11-30 19:23:31','2025-11-30 19:23:31'),(129,47,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:23:31','2025-11-30 19:23:31','2025-11-30 19:23:31'),(130,48,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:28:07','2025-11-30 19:28:07','2025-11-30 19:28:07'),(131,48,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:28:07','2025-11-30 19:28:07','2025-11-30 19:28:07'),(132,49,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:36:51','2025-11-30 19:36:50','2025-11-30 19:36:51'),(133,49,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:36:51','2025-11-30 19:36:50','2025-11-30 19:36:51'),(136,51,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:41:53','2025-11-30 19:41:53','2025-11-30 19:41:53'),(137,51,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:41:53','2025-11-30 19:41:53','2025-11-30 19:41:53'),(138,52,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:44:57','2025-11-30 19:44:57','2025-11-30 19:44:57'),(139,52,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:44:57','2025-11-30 19:44:57','2025-11-30 19:44:57'),(140,53,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:48:13','2025-11-30 19:48:12','2025-11-30 19:48:13'),(141,53,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:48:13','2025-11-30 19:48:12','2025-11-30 19:48:13'),(142,54,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:50:06','2025-11-30 19:50:05','2025-11-30 19:50:06'),(143,54,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 19:50:06','2025-11-30 19:50:05','2025-11-30 19:50:06'),(144,55,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 19:54:56','2025-11-30 19:54:56','2025-11-30 19:54:56'),(145,56,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 20:02:07','2025-11-30 20:02:07','2025-11-30 20:02:07'),(146,56,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 20:02:07','2025-11-30 20:02:07','2025-11-30 20:02:07'),(147,57,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 20:04:51','2025-11-30 20:04:51','2025-11-30 20:04:51'),(148,57,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 20:04:51','2025-11-30 20:04:51','2025-11-30 20:04:51'),(149,57,59,1,0,500.00,500.00,0,'','pending',1,'2025-11-30 20:04:51','2025-11-30 20:04:51','2025-11-30 20:04:51'),(150,57,50,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 20:04:51','2025-11-30 20:04:51','2025-11-30 20:04:51'),(151,58,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 20:09:41','2025-11-30 20:09:40','2025-11-30 20:09:41'),(152,58,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 20:09:41','2025-11-30 20:09:40','2025-11-30 20:09:41'),(153,59,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 20:16:53','2025-11-30 20:16:51','2025-11-30 20:16:53'),(154,59,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 20:16:53','2025-11-30 20:16:51','2025-11-30 20:16:53'),(155,59,50,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 20:16:53','2025-11-30 20:16:51','2025-11-30 20:16:53'),(156,60,52,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-30 20:22:26','2025-11-30 20:22:23','2025-11-30 20:22:26'),(157,60,49,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-30 20:22:26','2025-11-30 20:22:23','2025-11-30 20:22:26'),(158,60,68,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 20:22:27','2025-11-30 20:22:23','2025-11-30 20:22:27'),(159,60,63,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-30 20:22:27','2025-11-30 20:22:23','2025-11-30 20:22:27'),(160,60,39,1,0,1800.00,1800.00,0,'','pending',1,'2025-11-30 20:22:27','2025-11-30 20:22:23','2025-11-30 20:22:27'),(161,61,38,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:25:01','2025-11-30 20:25:01','2025-11-30 20:25:01'),(162,61,46,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:25:01','2025-11-30 20:25:01','2025-11-30 20:25:01'),(163,61,44,1,0,500.00,500.00,0,'','pending',1,'2025-11-30 20:25:01','2025-11-30 20:25:01','2025-11-30 20:25:01'),(164,62,38,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:31:07','2025-11-30 20:31:06','2025-11-30 20:31:07'),(165,62,46,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:31:07','2025-11-30 20:31:06','2025-11-30 20:31:07'),(166,62,47,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 20:31:07','2025-11-30 20:31:06','2025-11-30 20:31:07'),(167,63,38,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:34:47','2025-11-30 20:34:45','2025-11-30 20:34:47'),(168,63,46,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:34:47','2025-11-30 20:34:45','2025-11-30 20:34:47'),(169,64,38,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:38:54','2025-11-30 20:38:54','2025-11-30 20:38:54'),(170,64,46,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-30 20:38:54','2025-11-30 20:38:54','2025-11-30 20:38:54'),(171,64,48,1,0,1200.00,1200.00,0,'','pending',1,'2025-11-30 20:38:54','2025-11-30 20:38:54','2025-11-30 20:38:54'),(172,65,55,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-30 20:42:03','2025-11-30 20:42:02','2025-11-30 20:42:03'),(173,65,40,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-30 20:42:03','2025-11-30 20:42:02','2025-11-30 20:42:03'),(174,65,51,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-30 20:42:03','2025-11-30 20:42:02','2025-11-30 20:42:03');
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
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'202511180001',2,3,NULL,NULL,'dine_in','pending',15000.00,0.00,0.00,0.00,0.00,0.00,0.00,15000.00,'cash',0.00,0.00,'',NULL,'2025-11-18 15:14:08','2025-11-30 14:18:03',NULL),(2,'202511180002',1,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-18 15:18:58','2025-11-30 15:14:16',NULL),(3,'202511300001',3,3,NULL,NULL,'dine_in','pending',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 14:37:23','2025-11-30 14:37:23',NULL),(4,'202511300002',4,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 14:42:00','2025-11-30 15:14:28','2025-11-30 15:14:28'),(5,'202511300003',5,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 14:47:13','2025-11-30 15:14:31',NULL),(6,'202511300004',6,3,NULL,NULL,'dine_in','pending',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 14:50:04','2025-11-30 14:50:04',NULL),(7,'TEST-1764514343',1,1,NULL,NULL,'dine_in','pending',4500.00,0.00,0.00,0.00,0.00,0.00,0.00,4500.00,'cash',0.00,0.00,NULL,NULL,'2025-11-30 14:52:23','2025-11-30 14:52:23',NULL),(8,'202511300005',7,3,NULL,NULL,'dine_in','cancelled',11000.00,0.00,0.00,0.00,0.00,0.00,0.00,11000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 14:58:49','2025-11-30 15:14:24',NULL),(9,'202511300006',8,3,NULL,NULL,'dine_in','cancelled',11000.00,0.00,0.00,0.00,0.00,0.00,0.00,11000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:00:57','2025-11-30 15:14:20',NULL),(10,'202511300007',9,3,NULL,NULL,'dine_in','cancelled',7000.00,0.00,0.00,0.00,0.00,0.00,0.00,7000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:04:28','2025-11-30 15:14:10',NULL),(11,'202511300008',10,3,NULL,NULL,'dine_in','cancelled',13500.00,0.00,0.00,0.00,0.00,0.00,0.00,13500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:08:26','2025-11-30 15:14:05',NULL),(12,'202511300009',4,3,NULL,NULL,'dine_in','pending',11000.00,0.00,0.00,0.00,0.00,0.00,0.00,11000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:14:55','2025-11-30 15:14:55',NULL),(13,'202511300010',5,3,NULL,NULL,'dine_in','completed',11000.00,0.00,0.00,0.00,0.00,0.00,0.00,11000.00,'cash',15000.00,0.00,'','2025-11-30 15:35:21','2025-11-30 15:18:01','2025-11-30 15:35:21',NULL),(14,'202511300011',7,3,NULL,NULL,'dine_in','pending',11000.00,0.00,0.00,0.00,0.00,0.00,0.00,11000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:19:44','2025-11-30 15:19:44',NULL),(15,'202511300012',10,3,NULL,NULL,'dine_in','completed',8000.00,0.00,0.00,0.00,0.00,0.00,0.00,8000.00,'cash',10000.00,0.00,'','2025-11-30 15:45:46','2025-11-30 15:23:26','2025-11-30 15:45:46',NULL),(16,'202511300013',8,3,NULL,NULL,'dine_in','cancelled',7000.00,0.00,0.00,0.00,0.00,0.00,0.00,7000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:30:59','2025-11-30 15:40:04',NULL),(17,'202511300014',5,3,NULL,NULL,'dine_in','cancelled',8000.00,0.00,0.00,0.00,0.00,0.00,0.00,8000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:37:35','2025-11-30 15:40:15',NULL),(18,'202511300015',9,3,NULL,NULL,'dine_in','cancelled',15500.00,0.00,0.00,0.00,0.00,0.00,0.00,15500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:38:58','2025-11-30 15:40:20',NULL),(19,'202511300016',5,3,NULL,NULL,'dine_in','completed',6500.00,0.00,0.00,0.00,0.00,0.00,0.00,6500.00,'cash',7000.00,0.00,'','2025-11-30 15:44:40','2025-11-30 15:41:49','2025-11-30 15:44:40',NULL),(20,'202511300017',5,3,NULL,NULL,'dine_in','pending',9500.00,0.00,0.00,0.00,0.00,0.00,0.00,9500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:52:47','2025-11-30 15:52:47',NULL),(21,'202511300018',8,3,NULL,NULL,'dine_in','cancelled',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:54:27','2025-11-30 17:05:33',NULL),(22,'202511300019',10,3,NULL,NULL,'dine_in','cancelled',6500.00,0.00,0.00,0.00,0.00,0.00,0.00,6500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:55:31','2025-11-30 17:03:14',NULL),(23,'202511300020',9,3,NULL,NULL,'dine_in','cancelled',5500.00,0.00,0.00,0.00,0.00,0.00,0.00,5500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 15:55:58','2025-11-30 17:03:10',NULL),(24,'202511300021',9,3,NULL,NULL,'dine_in','cancelled',7000.00,0.00,0.00,0.00,0.00,0.00,0.00,7000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:03:29','2025-11-30 17:49:40','2025-11-30 17:49:40'),(25,'202511300022',10,3,NULL,NULL,'dine_in','cancelled',6500.00,0.00,0.00,0.00,0.00,0.00,0.00,6500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:04:25','2025-11-30 17:05:24',NULL),(26,'202511300023',8,3,NULL,NULL,'dine_in','cancelled',14500.00,0.00,0.00,0.00,0.00,0.00,0.00,14500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:06:10','2025-11-30 17:49:35',NULL),(27,'202511300024',9,3,NULL,NULL,'dine_in','cancelled',5500.00,0.00,0.00,0.00,0.00,0.00,0.00,5500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:06:45','2025-11-30 17:49:30',NULL),(28,'202511300025',10,3,NULL,NULL,'dine_in','cancelled',6500.00,0.00,0.00,0.00,0.00,0.00,0.00,6500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:07:08','2025-11-30 17:40:44',NULL),(29,'202512010001',10,3,NULL,NULL,'dine_in','cancelled',1000.00,0.00,0.00,0.00,0.00,0.00,0.00,1000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:41:14','2025-11-30 17:41:55',NULL),(30,'202512010002',10,3,NULL,NULL,'dine_in','cancelled',1000.00,0.00,0.00,0.00,0.00,0.00,0.00,1000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:43:03','2025-11-30 17:44:03',NULL),(31,'202512010003',10,3,NULL,NULL,'dine_in','cancelled',1000.00,0.00,0.00,0.00,0.00,0.00,0.00,1000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:44:38','2025-11-30 17:49:25',NULL),(32,'202512010004',8,3,NULL,NULL,'dine_in','completed',1000.00,0.00,5.00,0.00,0.00,0.00,0.00,1000.00,'cash',1000.00,0.00,'','2025-11-30 18:09:42','2025-11-30 17:49:51','2025-11-30 18:09:42',NULL),(33,'202512010005',9,3,NULL,NULL,'dine_in','cancelled',2000.00,0.00,0.00,0.00,0.00,0.00,0.00,2000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:50:19','2025-11-30 17:53:53',NULL),(34,'202512010006',10,3,NULL,NULL,'dine_in','cancelled',1000.00,0.00,0.00,0.00,0.00,0.00,0.00,1000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:51:00','2025-11-30 17:51:13',NULL),(35,'202512010007',10,3,NULL,NULL,'dine_in','cancelled',2000.00,0.00,0.00,0.00,0.00,0.00,0.00,2000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 17:51:31','2025-11-30 17:54:01',NULL),(36,'202512010008',9,3,NULL,NULL,'dine_in','completed',3000.00,0.00,5.00,0.00,0.00,0.00,0.00,3000.00,'cash',5000.00,0.00,'','2025-11-30 18:04:11','2025-11-30 17:54:22','2025-11-30 18:04:11',NULL),(37,'202512010009',8,3,NULL,NULL,'dine_in','cancelled',3500.00,0.00,0.00,0.00,0.00,0.00,0.00,3500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 18:16:53','2025-11-30 18:45:51',NULL),(38,'202512010010',9,3,NULL,NULL,'dine_in','cancelled',5500.00,0.00,0.00,0.00,0.00,0.00,0.00,5500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 18:17:22','2025-11-30 18:24:45',NULL),(39,'202512010011',10,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 18:18:15','2025-11-30 18:24:41',NULL),(40,'202512010012',9,3,NULL,NULL,'dine_in','completed',4800.00,0.00,5.00,0.00,0.00,0.00,0.00,4800.00,'cash',5000.00,0.00,'','2025-11-30 18:40:47','2025-11-30 18:32:02','2025-11-30 18:40:47',NULL),(41,'202512010013',8,3,NULL,NULL,'dine_in','cancelled',3000.00,0.00,0.00,0.00,0.00,0.00,0.00,3000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 18:46:16','2025-11-30 19:09:41',NULL),(42,'202512010014',9,3,NULL,NULL,'dine_in','cancelled',11500.00,0.00,0.00,0.00,0.00,0.00,0.00,11500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:03:10','2025-11-30 19:09:38',NULL),(43,'202512010015',10,3,NULL,NULL,'dine_in','cancelled',10500.00,0.00,0.00,0.00,0.00,0.00,0.00,10500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:07:45','2025-11-30 19:09:35',NULL),(44,'202512010016',8,3,NULL,NULL,'dine_in','cancelled',9500.00,0.00,0.00,0.00,0.00,0.00,0.00,9500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:10:00','2025-11-30 19:34:33',NULL),(45,'202512010017',9,3,NULL,NULL,'dine_in','cancelled',10500.00,0.00,0.00,0.00,0.00,0.00,0.00,10500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:12:26','2025-11-30 19:23:15',NULL),(46,'202512010018',10,3,NULL,NULL,'dine_in','cancelled',8000.00,0.00,0.00,0.00,0.00,0.00,0.00,8000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:18:47','2025-11-30 19:23:11',NULL),(47,'202512010019',9,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:23:31','2025-11-30 19:34:28',NULL),(48,'202512010020',10,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:28:07','2025-11-30 19:34:25',NULL),(49,'202512010021',8,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:36:50','2025-11-30 19:47:10',NULL),(51,'202512010022',9,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:41:53','2025-11-30 19:47:07',NULL),(52,'202512010023',10,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:44:57','2025-11-30 19:47:04',NULL),(53,'202512010024',8,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:48:12','2025-11-30 20:08:11',NULL),(54,'202512010025',9,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:50:05','2025-11-30 20:01:50',NULL),(55,'202512010026',10,3,NULL,NULL,'dine_in','cancelled',4000.00,0.00,0.00,0.00,0.00,0.00,0.00,4000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 19:54:56','2025-11-30 20:01:47',NULL),(56,'202512010027',9,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:02:07','2025-11-30 20:08:06',NULL),(57,'202512010028',10,3,NULL,NULL,'dine_in','cancelled',10500.00,0.00,0.00,0.00,0.00,0.00,0.00,10500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:04:51','2025-11-30 20:08:02',NULL),(58,'202512010029',8,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:09:40','2025-11-30 20:21:42',NULL),(59,'202512010030',9,3,NULL,NULL,'dine_in','cancelled',10000.00,0.00,0.00,0.00,0.00,0.00,0.00,10000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:16:51','2025-11-30 20:21:38',NULL),(60,'202512010031',8,3,NULL,NULL,'dine_in','cancelled',11800.00,0.00,0.00,0.00,0.00,0.00,0.00,11800.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:22:23','2025-11-30 20:34:07',NULL),(61,'202512010032',9,3,NULL,NULL,'dine_in','cancelled',2500.00,0.00,0.00,0.00,0.00,0.00,0.00,2500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:25:01','2025-11-30 20:34:02',NULL),(62,'202512010033',10,3,NULL,NULL,'dine_in','cancelled',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:31:06','2025-11-30 20:33:59',NULL),(63,'202512010034',8,3,NULL,NULL,'dine_in','pending',2000.00,0.00,0.00,0.00,0.00,0.00,0.00,2000.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:34:45','2025-11-30 20:34:45',NULL),(64,'202512010035',9,3,NULL,NULL,'dine_in','pending',3200.00,0.00,0.00,0.00,0.00,0.00,0.00,3200.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:38:54','2025-11-30 20:38:54',NULL),(65,'202512010036',10,3,NULL,NULL,'dine_in','pending',9500.00,0.00,0.00,0.00,0.00,0.00,0.00,9500.00,'cash',0.00,0.00,'',NULL,'2025-11-30 20:42:02','2025-11-30 20:42:02',NULL);
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
င
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
INSERT INTO `sessions` VALUES ('13kMFNhVLrTqQ2wjUXqLb0x45H4KEj2nlsw3m19F',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkhaYm1LQlFDbkVSaWthdXhoc0lTZlhSVEZXQ0ZSOFR5NTFFM0hTVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764533315),('4kpxZT8UagVPGVt2erEQUgaldL12lwY4EyZD0SM5',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUZXNXVIRzFob0RJeHRLcmtTYURGelZhbDdyY2NPMVlzZWJCMHdiZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764533166),('6AFP2PPKkikr3LWOEunvCrU4tqh34aE7GIA5EyGW',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3ZsYnB0OFJQczA4c1V5MmhWNDJPcWwxSDFvTEZaeEtMOTZ1SDRCSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764531983),('bovOCXgNgHni1TeUT46JAhdaQK7h8r3e6oetIhhx',3,'192.168.0.100','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ2NUSDRhVHd0bmp2VDJtbmpPSlZFUkhUSFVydXpDUzRFMkZmOFZXYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vMTkyLjE2OC4wLjEwMzo4MDAwL3dhaXRlci90YWJsZXMiO3M6NToicm91dGUiO3M6MTk6IndhaXRlci50YWJsZXMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=',1764529728),('cSJUNrvPRITrMstdWmkwOTsRjcvPjSqLlvVBV6dU',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTZpUmRZaUltd3M3T2hYMkdGTmNsbDVobFZlS2J3b0dPa09CRmdoUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764532060),('DkOC8D4upt4250Zqgsga5HBoeWw5W1KFsxXGwGM5',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibmRSWEZpWWlJeDFiQlMxMHR3aVJHUnRyQnJXREJpMmZVSDVPM1FVYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzAuMC4wLjA6ODAwMC93YWl0ZXIvdGFibGVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8wLjAuMC4wOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319',1764556358),('Fv1sdz2Kfu5tXHBA5OCwTjGF9p0XcCdywa5hhCr2',3,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNWs1c09QT0lNM0hHWHVLZ3hlbkR3VGY0UVdvaWx2SEk0VDFsVTlwUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8wLjAuMC4wOjgwMDAvd2FpdGVyL3RhYmxlcyI7czo1OiJyb3V0ZSI7czoxOToid2FpdGVyLnRhYmxlcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1764535415),('Gk0HMnwv4jjxXsMPcmscsGNY8okjJe7EsZcvqqVO',NULL,'192.168.0.102','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0xDdkhQTlNhenNIZ2s0U3J2TWVvSnFOanFMMlBqa242Q25GY1VzdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xOTIuMTY4LjAuMTAzOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319',1764535912),('iiwxGpTSbjcsrEtuh7eqzLpaDXTuNaSlMAm3Fm8J',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXF1OU9uSDNvQmdsZjNGeURZUElxb3ZkSjh6UzQ5Sk9mYndDSUNtWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1764529861),('lVLOFSxegUS3zuGhT1NrQNqr7wlFQRo53ECzZyDd',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVd5aUxtVnBRY2xDUDVONEdjNlZHdVkyamI3UUsxVGtRQjU0UkVBRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MTp7aTowO3M6NzoibWVzc2FnZSI7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9jYXNoaWVyL29yZGVycyI7czo1OiJyb3V0ZSI7czoyMDoiY2FzaGllci5vcmRlcnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1764528351),('RuA10geFQBJy5AtHC5zSkdua9npDEIlR3SSconxR',3,'192.168.0.101','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSG8yQVRyQnc4OTl4bTVBN1J2QlBSQkJya0JtdnlRZ3R2WUlhekNkaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xOTIuMTY4LjAuMTAzOjgwMDAvd2FpdGVyL3RhYmxlcyI7czo1OiJyb3V0ZSI7czoxOToid2FpdGVyLnRhYmxlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==',1764528442);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'business_name','Thar Cho Cafe','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(2,'business_name_mm','သာချိုကော်ဖီဆိုင်','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(3,'business_address','Yangon, Myanmar','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(4,'business_address_mm','ရန်ကုန်မြို့၊ မြန်မာနိုင်ငံ','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(5,'business_phone','+95 9 123 456 789','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(6,'business_email','info@tharchocafe.com','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(7,'tax_enabled','0','boolean','2025-11-18 15:11:24','2025-11-18 15:11:24'),(8,'tax_percentage','0','decimal','2025-11-18 15:11:24','2025-11-18 15:11:24'),(9,'service_charge_enabled','0','boolean','2025-11-18 15:11:24','2025-11-18 15:11:24'),(10,'service_charge_amount','0','decimal','2025-11-18 15:11:24','2025-11-18 15:11:24'),(11,'currency','MMK','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(12,'currency_symbol','Ks','string','2025-11-18 15:11:24','2025-11-18 15:11:24'),(13,'auto_print_kitchen','1','boolean','2025-11-18 15:11:24','2025-11-18 15:11:24'),(14,'auto_print_Bar','1','boolean','2025-11-18 15:11:24','2025-11-18 15:11:24'),(15,'app_logo','logos/logo.png','string','2025-11-30 17:58:51','2025-11-30 17:58:51');
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
INSERT INTO `tables` VALUES (1,'Table 1','စားပွဲ ၁',2,'available',1,1,'2025-11-18 15:11:24','2025-11-30 15:14:16',NULL),(2,'Table 2','စားပွဲ ၂',2,'available',1,2,'2025-11-18 15:11:24','2025-11-30 14:49:43',NULL),(3,'Table 3','စားပွဲ ၃',4,'occupied',1,3,'2025-11-18 15:11:24','2025-11-30 14:37:23',NULL),(4,'Table 4','စားပွဲ ၄',4,'occupied',1,4,'2025-11-18 15:11:24','2025-11-30 15:14:55',NULL),(5,'Table 5','စားပွဲ ၅',4,'occupied',1,5,'2025-11-18 15:11:24','2025-11-30 15:52:47',NULL),(6,'Table 6','စားပွဲ ၆',4,'occupied',1,6,'2025-11-18 15:11:24','2025-11-30 14:50:04',NULL),(7,'Table 7','စားပွဲ ၇',6,'occupied',1,7,'2025-11-18 15:11:24','2025-11-30 15:19:44',NULL),(8,'Table 8','စားပွဲ ၈',6,'occupied',1,8,'2025-11-18 15:11:24','2025-11-30 20:34:45',NULL),(9,'Table 9','စားပွဲ ၉',8,'occupied',1,9,'2025-11-18 15:11:24','2025-11-30 20:38:54',NULL),(10,'Table 10','စားပွဲ ၁၀',8,'occupied',1,10,'2025-11-18 15:11:24','2025-11-30 20:42:02',NULL);
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
INSERT INTO `users` VALUES (1,'Admin','admin@tharchocafe.com',NULL,'$2y$12$lzOVyxDX1skSd65BxmvgHOsjDZWHDWsfsgeMnWcMx793tdStIeVzy','+95 9 111 111 111',1,NULL,'2025-11-18 15:11:23','2025-11-18 15:11:23',NULL),(2,'Cashier','cashier@tharchocafe.com',NULL,'$2y$12$Lzf.eF4ApJLWwmycS6CEg.LWZtqc.6Y4TWbsJqLFP0O9Ka4JJTowa','+95 9 222 222 222',1,NULL,'2025-11-18 15:11:23','2025-11-18 15:11:23',NULL),(3,'Htet Htet','waiter@tharchocafe.com',NULL,'$2y$12$ib2B9GREzC4smi7BjpDnCOrXCjsWfYIPbhe7hAy8z53JxvtkucimG','+95 9 333 333 333',1,NULL,'2025-11-18 15:11:23','2025-11-30 19:06:43',NULL),(4,'Waiter 2','waiter2@tharchocafe.com',NULL,'$2y$12$2N4lg9gBkfzaQwb0W9FN2u1SBX2yZ/Y7oIQ/KVIVM.RDk4g0Rt9e6','+95 9 444 444 444',1,NULL,'2025-11-18 15:11:24','2025-11-18 15:11:24',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-01  9:58:34
