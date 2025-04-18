-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: kiot
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assignment`
--

LOCK TABLES `auth_assignment` WRITE;
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` VALUES ('admin','1',1744630492),('cashier','12',1744634482),('cashier','13',1744634482),('cashier','21',1744634482),('cashier','4',1744634482),('cashier','5',1744634482),('cashier','6',1744634482),('manager','17',1744634482),('manager','2',1744634482),('manager','3',1744634482),('staff','10',1744634482),('staff','11',1744634482),('staff','14',1744634482),('staff','15',1744634482),('staff','16',1744634482),('staff','18',1744634482),('staff','19',1744634482),('staff','20',1744634482),('staff','7',1744634482),('staff','8',1744634482),('staff','9',1744634482);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `fk_auth_item_rule` (`rule_name`),
  CONSTRAINT `fk_auth_item_rule` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item`
--

LOCK TABLES `auth_item` WRITE;
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('accessPos',2,'Truy cập màn hình bán hàng',NULL,NULL,1744630491,1744630491),('admin',1,'Quản trị viên',NULL,NULL,1744630491,1744630491),('cashier',1,'Thu ngân',NULL,NULL,1744630491,1744630491),('createCategory',2,'Thêm danh mục sản phẩm',NULL,NULL,1744630491,1744630491),('createCustomer',2,'Thêm khách hàng',NULL,NULL,1744630491,1744630491),('createOrder',2,'Tạo đơn hàng',NULL,NULL,1744630491,1744630491),('createProduct',2,'Thêm sản phẩm',NULL,NULL,1744630491,1744630491),('createSupplier',2,'Thêm nhà cung cấp',NULL,NULL,1744630491,1744630491),('createUnit',2,'Thêm đơn vị tính',NULL,NULL,1744630491,1744630491),('createUser',2,'Thêm người dùng',NULL,NULL,1744630491,1744630491),('createWarranty',2,'Thêm bảo hành',NULL,NULL,1744630491,1744630491),('deleteCategory',2,'Xóa danh mục sản phẩm',NULL,NULL,1744630491,1744630491),('deleteCustomer',2,'Xóa khách hàng',NULL,NULL,1744630491,1744630491),('deleteOrder',2,'Xóa đơn hàng',NULL,NULL,1744630491,1744630491),('deleteProduct',2,'Xóa sản phẩm',NULL,NULL,1744630491,1744630491),('deleteSupplier',2,'Xóa nhà cung cấp',NULL,NULL,1744630491,1744630491),('deleteUnit',2,'Xóa đơn vị tính',NULL,NULL,1744630491,1744630491),('deleteUser',2,'Xóa người dùng',NULL,NULL,1744630491,1744630491),('deleteWarranty',2,'Xóa bảo hành',NULL,NULL,1744630491,1744630491),('manager',1,'Quản lý',NULL,NULL,1744630491,1744630491),('manageRbac',2,'Quản lý phân quyền',NULL,NULL,1744630491,1744630491),('staff',1,'Nhân viên',NULL,NULL,1744630491,1744630491),('updateCategory',2,'Cập nhật danh mục sản phẩm',NULL,NULL,1744630491,1744630491),('updateCustomer',2,'Cập nhật khách hàng',NULL,NULL,1744630491,1744630491),('updateOrder',2,'Cập nhật đơn hàng',NULL,NULL,1744630491,1744630491),('updateOwnOrder',2,'Cập nhật đơn hàng của chính mình','isAuthor',NULL,1744630491,1744630491),('updateProduct',2,'Cập nhật sản phẩm',NULL,NULL,1744630491,1744630491),('updateSupplier',2,'Cập nhật nhà cung cấp',NULL,NULL,1744630491,1744630491),('updateUnit',2,'Cập nhật đơn vị tính',NULL,NULL,1744630491,1744630491),('updateUser',2,'Cập nhật người dùng',NULL,NULL,1744630491,1744630491),('updateWarranty',2,'Cập nhật bảo hành',NULL,NULL,1744630491,1744630491),('viewCategory',2,'Xem danh mục sản phẩm',NULL,NULL,1744630491,1744630491),('viewCustomer',2,'Xem khách hàng',NULL,NULL,1744630491,1744630491),('viewOrder',2,'Xem đơn hàng',NULL,NULL,1744630491,1744630491),('viewProduct',2,'Xem sản phẩm',NULL,NULL,1744630491,1744630491),('viewReport',2,'Xem báo cáo',NULL,NULL,1744630491,1744630491),('viewSupplier',2,'Xem nhà cung cấp',NULL,NULL,1744630491,1744630491),('viewUnit',2,'Xem đơn vị tính',NULL,NULL,1744630491,1744630491),('viewUser',2,'Xem người dùng',NULL,NULL,1744630491,1744630491),('viewWarranty',2,'Xem bảo hành',NULL,NULL,1744630491,1744630491);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_item_child`
--

LOCK TABLES `auth_item_child` WRITE;
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('cashier','accessPos'),('staff','cashier'),('manager','createCategory'),('cashier','createCustomer'),('cashier','createOrder'),('manager','createProduct'),('manager','createSupplier'),('manager','createUnit'),('admin','createUser'),('staff','createWarranty'),('admin','deleteCategory'),('manager','deleteCustomer'),('manager','deleteOrder'),('admin','deleteProduct'),('manager','deleteSupplier'),('admin','deleteUnit'),('admin','deleteUser'),('manager','deleteWarranty'),('admin','manager'),('admin','manageRbac'),('manager','staff'),('manager','updateCategory'),('cashier','updateCustomer'),('staff','updateOrder'),('updateOwnOrder','updateOrder'),('cashier','updateOwnOrder'),('manager','updateProduct'),('manager','updateSupplier'),('manager','updateUnit'),('admin','updateUser'),('staff','updateWarranty'),('cashier','viewCategory'),('cashier','viewCustomer'),('cashier','viewOrder'),('cashier','viewProduct'),('manager','viewReport'),('staff','viewSupplier'),('cashier','viewUnit'),('manager','viewUser'),('cashier','viewWarranty');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_rule`
--

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
INSERT INTO `auth_rule` VALUES ('isAuthor',_binary 'O:22:\"common\\rbac\\AuthorRule\":3:{s:4:\"name\";s:8:\"isAuthor\";s:9:\"createdAt\";i:1744630491;s:9:\"updatedAt\";i:1744630491;}',1744630491,1744630491);
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `branch_created` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `delivery_area` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ward` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tax_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_card` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'male',
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_group` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_points` int DEFAULT '0',
  `total_points` int DEFAULT '0',
  `creator` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` int NOT NULL,
  `last_transaction_date` int DEFAULT NULL,
  `current_debt` decimal(15,2) DEFAULT '0.00',
  `total_sales` decimal(15,2) DEFAULT '0.00',
  `total_sales_net` decimal(15,2) DEFAULT '0.00',
  `status` tinyint DEFAULT '1',
  `note` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_customer_phone` (`phone`),
  KEY `idx_customer_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'retail','Chi nhánh 1','KH0001','Nguyễn Văn An','0912345678','123 Nguyễn Huệ, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789012','1990-05-15','male','nguyenan@email.com','facebook.com/nguyenan','Thường',50,150,'admin',1744634592,1744548192,0.00,5000000.00,5000000.00,1,'Khách hàng thân thiết'),(2,'retail','Chi nhánh 1','KH0002','Trần Thị Bình','0923456789','456 Lê Lợi, Quận 1','Quận 1','Phường Bến Thành',NULL,NULL,'023456789013','1985-08-20','female','tranbinh@email.com','facebook.com/tranbinh','Thường',120,320,'admin',1744634592,1744461792,0.00,8500000.00,8500000.00,1,'Hay mua sắm online'),(3,'business','Chi nhánh 2','KH0003','Lê Văn Cường','0934567890','789 CMT8, Quận 3','Quận 3','Phường 7','Công ty TNHH Lê Cường','0123456789','023456789014','1980-03-10','male','lecuong@email.com',NULL,'Doanh nghiệp',250,800,'admin',1744634592,1744375392,2000000.00,32000000.00,30000000.00,1,'Khách hàng doanh nghiệp'),(4,'retail','Chi nhánh 1','KH0004','Phạm Thị Dung','0945678901','101 Nguyễn Đình Chiểu, Quận 3','Quận 3','Phường 9',NULL,NULL,'023456789015','1995-12-25','female','phamdung@email.com','facebook.com/phamdung','VIP',350,950,'admin',1744634592,1744288992,0.00,15000000.00,15000000.00,1,'Khách hàng VIP'),(5,'retail','Chi nhánh 2','KH0005','Hoàng Văn Em','0956789012','202 Võ Văn Tần, Quận 3','Quận 3','Phường 5',NULL,NULL,'023456789016','1992-06-18','male','hoangem@email.com','facebook.com/hoangem','Thường',80,230,'admin',1744634592,1744202592,0.00,7500000.00,7500000.00,1,NULL),(6,'business','Chi nhánh 1','KH0006','Công ty TNHH ABC','0967890123','303 Điện Biên Phủ, Quận Bình Thạnh','Quận Bình Thạnh','Phường 15','Công ty TNHH ABC','1234567890',NULL,NULL,'other','info@abc.com',NULL,'Doanh nghiệp',500,1500,'admin',1744634592,1744116192,5000000.00,45000000.00,40000000.00,1,'Doanh nghiệp vừa'),(7,'retail','Chi nhánh 2','KH0007','Ngô Thị Phương','0978901234','404 Nguyễn Thị Minh Khai, Quận 3','Quận 3','Phường 2',NULL,NULL,'023456789017','1988-09-30','female','ngophuong@email.com','facebook.com/ngophuong','Thường',65,195,'admin',1744634592,1744029792,0.00,6500000.00,6500000.00,1,NULL),(8,'retail','Chi nhánh 1','KH0008','Vũ Văn Giang','0989012345','505 Trần Hưng Đạo, Quận 5','Quận 5','Phường 6',NULL,NULL,'023456789018','1991-02-14','male','vugiang@email.com','facebook.com/vugiang','Thường',85,245,'admin',1744634592,1743943392,0.00,8200000.00,8200000.00,1,NULL),(9,'business','Chi nhánh 2','KH0009','Công ty CP XYZ','0990123456','606 Nguyễn Đình Chiểu, Quận 3','Quận 3','Phường 9','Công ty CP XYZ','2345678901',NULL,NULL,'other','info@xyz.com',NULL,'Doanh nghiệp',650,2100,'admin',1744634592,1743856992,3000000.00,38000000.00,35000000.00,1,'Doanh nghiệp lớn'),(10,'retail','Chi nhánh 1','KH0010','Đinh Thị Hoa','0901234567','707 Lý Tự Trọng, Quận 1','Quận 1','Phường Bến Thành',NULL,NULL,'023456789019','1987-07-22','female','dinhhoa@email.com','facebook.com/dinhhoa','VIP',420,1200,'admin',1744634592,1743770592,0.00,18500000.00,18500000.00,1,'Khách hàng VIP'),(11,'retail','Chi nhánh 1','KH0011','Đặng Văn Inh','0912345098','808 Bạch Đằng, Quận Bình Thạnh','Quận Bình Thạnh','Phường 15',NULL,NULL,'023456789020','1993-11-05','male','danginh@email.com','facebook.com/danginh','Thường',75,225,'admin',1744634592,1743684192,0.00,7200000.00,7200000.00,1,NULL),(12,'retail','Chi nhánh 2','KH0012','Bùi Thị Kiều','0923450987','909 Trần Quốc Thảo, Quận 3','Quận 3','Phường 7',NULL,NULL,'023456789021','1989-04-12','female','buikieu@email.com','facebook.com/buikieu','Thường',60,180,'admin',1744634592,1743597792,0.00,6000000.00,6000000.00,1,NULL),(13,'business','Chi nhánh 1','KH0013','Công ty TNHH DEF','0934509876','111 Lê Lai, Quận 1','Quận 1','Phường Bến Thành','Công ty TNHH DEF','3456789012',NULL,NULL,'other','info@def.com',NULL,'Doanh nghiệp',350,1050,'admin',1744634592,1743511392,1500000.00,28000000.00,26500000.00,1,'Doanh nghiệp nhỏ'),(14,'retail','Chi nhánh 2','KH0014','Lý Văn Lam','0945098765','222 Nguyễn Công Trứ, Quận 1','Quận 1','Phường Nguyễn Thái Bình',NULL,NULL,'023456789022','1994-01-28','male','lylam@email.com','facebook.com/lylam','Thường',90,270,'admin',1744634592,1743424992,0.00,9000000.00,9000000.00,1,NULL),(15,'retail','Chi nhánh 1','KH0015','Phan Thị Mai','0956987654','333 Cao Thắng, Quận 3','Quận 3','Phường 12',NULL,NULL,'023456789023','1990-10-15','female','phanmai@email.com','facebook.com/phanmai','Thường',70,210,'admin',1744634592,1743338592,0.00,7000000.00,7000000.00,1,NULL),(16,'retail','Chi nhánh 2','KH0016','Tô Văn Nga','0967876543','444 Nguyễn Trãi, Quận 5','Quận 5','Phường 7',NULL,NULL,'023456789024','1986-03-20','male','tonga@email.com','facebook.com/tonga','Thường',55,165,'admin',1744634592,1743252192,0.00,5500000.00,5500000.00,1,NULL),(17,'business','Chi nhánh 1','KH0017','Công ty CP GHI','0978765432','555 Đường 3/2, Quận 10','Quận 10','Phường 14','Công ty CP GHI','4567890123',NULL,NULL,'other','info@ghi.com',NULL,'Doanh nghiệp',450,1350,'admin',1744634592,1743165792,2500000.00,32500000.00,30000000.00,1,'Doanh nghiệp vừa'),(18,'retail','Chi nhánh 2','KH0018','Hồ Thị Oanh','0989654321','666 Sư Vạn Hạnh, Quận 10','Quận 10','Phường 12',NULL,NULL,'023456789025','1992-12-08','female','hooanh@email.com','facebook.com/hooanh','VIP',380,1050,'admin',1744634592,1743079392,0.00,16800000.00,16800000.00,1,'Khách hàng VIP'),(19,'retail','Chi nhánh 1','KH0019','Đoàn Văn Phúc','0990543210','777 Cống Quỳnh, Quận 1','Quận 1','Phường Nguyễn Cư Trinh',NULL,NULL,'023456789026','1995-07-17','male','doanphuc@email.com','facebook.com/doanphuc','Thường',65,195,'admin',1744634592,1742992992,0.00,6500000.00,6500000.00,1,NULL),(20,'business','Chi nhánh 2','KH0020','Công ty TNHH JKL','0901543210','888 Trần Quang Khải, Quận 1','Quận 1','Phường Tân Định','Công ty TNHH JKL','5678901234',NULL,NULL,'other','info@jkl.com',NULL,'Doanh nghiệp',550,1650,'admin',1744634592,1742906592,4000000.00,42000000.00,38000000.00,1,'Doanh nghiệp lớn'),(21,'retail','Chi nhánh 1','KH0021','Mai Văn Quang','0912543210','999 Trần Nhân Tông, Quận 5','Quận 5','Phường 9',NULL,NULL,'023456789027','1988-05-25','male','maiquang@email.com','facebook.com/maiquang','Thường',85,255,'admin',1744634592,1742820192,0.00,8500000.00,8500000.00,1,NULL),(22,'retail','Chi nhánh 2','KH0022','Chu Thị Rạng','0923654321','101 Trần Hưng Đạo, Quận 1','Quận 1','Phường Cầu Ông Lãnh',NULL,NULL,'023456789028','1991-09-14','female','churang@email.com','facebook.com/churang','Thường',70,210,'admin',1744634592,1742733792,0.00,7000000.00,7000000.00,1,NULL),(23,'retail','Chi nhánh 1','KH0023','Đinh Văn Sơn','0934765432','202 Hai Bà Trưng, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789029','1993-02-18','male','dinhson@email.com','facebook.com/dinhson','Thường',60,180,'admin',1744634592,1742647392,0.00,6000000.00,6000000.00,1,NULL),(24,'business','Chi nhánh 2','KH0024','Công ty CP MNO','0945876543','303 Nguyễn Thị Minh Khai, Quận 1','Quận 1','Phường Bến Thành','Công ty CP MNO','6789012345',NULL,NULL,'other','info@mno.com',NULL,'Doanh nghiệp',400,1200,'admin',1744634592,1742560992,2000000.00,30000000.00,28000000.00,1,'Doanh nghiệp vừa'),(25,'retail','Chi nhánh 1','KH0025','Trần Văn Tân','0956987654','404 Lý Chính Thắng, Quận 3','Quận 3','Phường 8',NULL,NULL,'023456789030','1987-11-30','male','trantan@email.com','facebook.com/trantan','VIP',390,1100,'admin',1744634592,1742474592,0.00,17000000.00,17000000.00,1,'Khách hàng VIP'),(26,'retail','Chi nhánh 2','KH0026','Lê Thị Uyên','0967098765','505 Nguyễn Bỉnh Khiêm, Quận 1','Quận 1','Phường Đa Kao',NULL,NULL,'023456789031','1994-06-15','female','leuyen@email.com','facebook.com/leuyen','Thường',75,225,'admin',1744634592,1742388192,0.00,7500000.00,7500000.00,1,NULL),(27,'retail','Chi nhánh 1','KH0027','Nguyễn Văn Vang','0978209876','606 Bà Huyện Thanh Quan, Quận 3','Quận 3','Phường 6',NULL,NULL,'023456789032','1989-08-08','male','nguyenvang@email.com','facebook.com/nguyenvang','Thường',80,240,'admin',1744634592,1742301792,0.00,8000000.00,8000000.00,1,NULL),(28,'business','Chi nhánh 2','KH0028','Công ty TNHH PQR','0989310987','707 Nguyễn Thái Học, Quận 1','Quận 1','Phường Cầu Ông Lãnh','Công ty TNHH PQR','7890123456',NULL,NULL,'other','info@pqr.com',NULL,'Doanh nghiệp',480,1440,'admin',1744634592,1742215392,3000000.00,36000000.00,33000000.00,1,'Doanh nghiệp vừa'),(29,'retail','Chi nhánh 1','KH0029','Hoàng Thị Xuyến','0990421098','808 Phạm Ngũ Lão, Quận 1','Quận 1','Phường Phạm Ngũ Lão',NULL,NULL,'023456789033','1996-03-22','female','hoangxuyen@email.com','facebook.com/hoangxuyen','Thường',55,165,'admin',1744634592,1742128992,0.00,5500000.00,5500000.00,1,NULL),(30,'retail','Chi nhánh 2','KH0030','Đỗ Văn Yên','0901532109','909 Lê Thánh Tôn, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789034','1992-01-10','male','doyen@email.com','facebook.com/doyen','Thường',65,195,'admin',1744634592,1742042592,0.00,6500000.00,6500000.00,1,NULL),(31,'retail','Chi nhánh 1','KH0031','Nguyễn Thị Ánh','0912643210','111 Phạm Hồng Thái, Quận 1','Quận 1','Phường Bến Thành',NULL,NULL,'023456789035','1990-12-05','female','nguyenanh@email.com','facebook.com/nguyenanh','Thường',70,210,'admin',1744634592,1741956192,0.00,7000000.00,7000000.00,1,NULL),(32,'business','Chi nhánh 2','KH0032','Công ty CP STU','0923754321','222 Nguyễn Cư Trinh, Quận 1','Quận 1','Phường Nguyễn Cư Trinh','Công ty CP STU','8901234567',NULL,NULL,'other','info@stu.com',NULL,'Doanh nghiệp',520,1560,'admin',1744634592,1741869792,2500000.00,35000000.00,32500000.00,1,'Doanh nghiệp lớn'),(33,'retail','Chi nhánh 1','KH0033','Trần Văn Bách','0934865432','333 Hàm Nghi, Quận 1','Quận 1','Phường Nguyễn Thái Bình',NULL,NULL,'023456789036','1985-04-15','male','tranbach@email.com','facebook.com/tranbach','VIP',360,1000,'admin',1744634592,1741783392,0.00,16000000.00,16000000.00,1,'Khách hàng VIP'),(34,'retail','Chi nhánh 2','KH0034','Lê Thị Cúc','0945976543','444 Phó Đức Chính, Quận 1','Quận 1','Phường Nguyễn Thái Bình',NULL,NULL,'023456789037','1993-07-28','female','lecuc@email.com','facebook.com/lecuc','Thường',60,180,'admin',1744634592,1741696992,0.00,6000000.00,6000000.00,1,NULL),(35,'retail','Chi nhánh 1','KH0035','Phạm Văn Dũng','0956087654','555 Mạc Thị Bưởi, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789038','1988-09-16','male','phamdung@email.com','facebook.com/phamdung','Thường',85,255,'admin',1744634592,1741610592,0.00,8500000.00,8500000.00,1,NULL),(36,'business','Chi nhánh 2','KH0036','Công ty TNHH VWX','0967198765','666 Tôn Thất Đạm, Quận 1','Quận 1','Phường Nguyễn Thái Bình','Công ty TNHH VWX','9012345678',NULL,NULL,'other','info@vwx.com',NULL,'Doanh nghiệp',600,1800,'admin',1744634592,1741524192,4500000.00,42000000.00,37500000.00,1,'Doanh nghiệp lớn'),(37,'retail','Chi nhánh 1','KH0037','Hoàng Thị Gấm','0978309876','777 Thái Văn Lung, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789039','1995-02-13','female','hoanggam@email.com','facebook.com/hoanggam','Thường',75,225,'admin',1744634592,1741437792,0.00,7500000.00,7500000.00,1,NULL),(38,'retail','Chi nhánh 2','KH0038','Vũ Văn Hải','0989410987','888 Tôn Thất Tùng, Quận 1','Quận 1','Phường Bến Thành',NULL,NULL,'023456789040','1991-05-20','male','vuhai@email.com','facebook.com/vuhai','Thường',60,180,'admin',1744634592,1741351392,0.00,6000000.00,6000000.00,1,NULL),(39,'business','Chi nhánh 1','KH0039','Công ty CP YZA','0990521098','999 Tôn Đức Thắng, Quận 1','Quận 1','Phường Bến Nghé','Công ty CP YZA','0123456780',NULL,NULL,'other','info@yza.com',NULL,'Doanh nghiệp',550,1650,'admin',1744634592,1741264992,3500000.00,38000000.00,34500000.00,1,'Doanh nghiệp lớn'),(40,'retail','Chi nhánh 2','KH0040','Trần Văn Kiên','0901632109','111 Bùi Thị Xuân, Quận 1','Quận 1','Phường Phạm Ngũ Lão',NULL,NULL,'023456789041','1987-04-23','male','trankien@email.com','facebook.com/trankien','Thường',70,210,'admin',1744634592,1741178592,0.00,7000000.00,7000000.00,1,NULL),(41,'retail','Chi nhánh 1','KH0041','Nguyễn Thị Liên','0912743210','222 Cao Bá Quát, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789042','1994-08-16','female','nguyenlien@email.com','facebook.com/nguyenlien','Thường',55,165,'admin',1744634592,1741092192,0.00,5500000.00,5500000.00,1,NULL),(42,'retail','Chi nhánh 2','KH0042','Đặng Văn Minh','0923854321','333 Nguyễn Siêu, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789043','1990-10-05','male','dangminh@email.com','facebook.com/dangminh','Thường',80,240,'admin',1744634592,1741005792,0.00,8000000.00,8000000.00,1,NULL),(43,'business','Chi nhánh 1','KH0043','Công ty TNHH ZAB','0934965432','444 Tôn Thất Thiệp, Quận 1','Quận 1','Phường Bến Nghé','Công ty TNHH ZAB','1234567809',NULL,NULL,'other','info@zab.com',NULL,'Doanh nghiệp',480,1440,'admin',1744634592,1740919392,2500000.00,32000000.00,29500000.00,1,'Doanh nghiệp vừa'),(44,'retail','Chi nhánh 2','KH0044','Trần Thị Nga','0946076543','555 Trương Định, Quận 1','Quận 1','Phường Bến Thành',NULL,NULL,'023456789044','1993-03-18','female','trannga@email.com','facebook.com/trannga','Thường',65,195,'admin',1744634592,1740832992,0.00,6500000.00,6500000.00,1,NULL),(45,'retail','Chi nhánh 1','KH0045','Phan Văn Oanh','0957187654','666 Lê Thị Hồng Gấm, Quận 1','Quận 1','Phường Nguyễn Thái Bình',NULL,NULL,'023456789045','1988-07-22','male','phanoanh@email.com','facebook.com/phanoanh','Thường',50,150,'admin',1744634592,1740746592,0.00,5000000.00,5000000.00,1,NULL),(46,'retail','Chi nhánh 2','KH0046','Hoàng Thị Phúc','0968298765','777 Calmette, Quận 1','Quận 1','Phường Nguyễn Thái Bình',NULL,NULL,'023456789046','1995-09-30','female','hoangphuc@email.com','facebook.com/hoangphuc','Thường',60,180,'admin',1744634592,1740660192,0.00,6000000.00,6000000.00,1,NULL),(47,'business','Chi nhánh 1','KH0047','Công ty CP BCD','0979309876','888 Ký Con, Quận 1','Quận 1','Phường Nguyễn Thái Bình','Công ty CP BCD','2345678019',NULL,NULL,'other','info@bcd.com',NULL,'Doanh nghiệp',520,1560,'admin',1744634592,1740573792,3000000.00,35000000.00,32000000.00,1,'Doanh nghiệp vừa'),(48,'retail','Chi nhánh 2','KH0048','Lý Thị Quỳnh','0980410987','999 Huỳnh Thúc Kháng, Quận 1','Quận 1','Phường Bến Nghé',NULL,NULL,'023456789047','1991-11-14','female','lyquynh@email.com','facebook.com/lyquynh','VIP',340,950,'admin',1744634592,1740487392,0.00,15000000.00,15000000.00,1,'Khách hàng VIP'),(49,'retail','Chi nhánh 1','KH0049','Nguyễn Văn Rạng','0991521098','101 Võ Thị Sáu, Quận 3','Quận 3','Phường 6',NULL,NULL,'023456789048','1986-05-10','male','nguyenrang@email.com','facebook.com/nguyenrang','Thường',75,225,'admin',1744634592,1740400992,0.00,7500000.00,7500000.00,1,NULL),(50,'retail','Chi nhánh 2','KH0050','Trần Thị Sương','0902632109','202 Nam Kỳ Khởi Nghĩa, Quận 3','Quận 3','Phường 8',NULL,NULL,'023456789049','1993-08-28','female','transuong@email.com','facebook.com/transuong','Thường',60,180,'admin',1744634592,1740314592,0.00,6000000.00,6000000.00,1,NULL),(51,NULL,NULL,'KH250414001','lebatoan','0938467397',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'male',NULL,NULL,NULL,0,0,NULL,1744641502,NULL,0.00,0.00,0.00,1,NULL),(52,NULL,NULL,'KH250414002','Lê Linh','0979530887','38A ngõ 109 Trường Chinh, P.Phương Liệt, Q.Thanh Xuân, TP.Hà Nội',NULL,NULL,NULL,NULL,NULL,NULL,'male','lelinh1002@gmail.com',NULL,NULL,0,0,NULL,1744641572,NULL,0.00,0.00,0.00,1,NULL),(53,NULL,NULL,'KH250417001','Lê Bá Toán','0888333358','38A Ngo 109 Truong Chinh',NULL,NULL,NULL,NULL,NULL,NULL,'male','toanlb@live.com',NULL,NULL,0,0,NULL,1744883495,NULL,0.00,0.00,0.00,1,NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8mb4_general_ci NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m130524_201442_init',1744630491),('m190124_110200_add_verification_token_column_to_user_table',1744630491),('m230425_101130_create_transaction_history_table',1744820984),('m240414_100000_update_user_table',1744630491),('m240414_120000_create_user_login_history_table',1744630491),('m250413_023113_init_rbac',1744630491),('m250414_000000_create_pos_tables',1744630491),('m250414_000001_create_admin_user',1744630492);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `branch` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_order_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pickup_address` text COLLATE utf8mb4_general_ci,
  `reconciliation_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT '0.00',
  `salesperson` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sales_channel` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creator` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_partner` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receiver_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receiver_address` text COLLATE utf8mb4_general_ci,
  `receiver_area` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receiver_ward` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `service` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `weight_grams` int DEFAULT NULL,
  `length_cm` decimal(10,2) DEFAULT NULL,
  `width_cm` decimal(10,2) DEFAULT NULL,
  `height_cm` decimal(10,2) DEFAULT NULL,
  `delivery_status_note` text COLLATE utf8mb4_general_ci,
  `delivery_note` text COLLATE utf8mb4_general_ci,
  `order_note` text COLLATE utf8mb4_general_ci,
  `cod_remaining` decimal(15,2) DEFAULT '0.00',
  `delivery_time` int DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-order_details-order_id` (`order_id`),
  CONSTRAINT `fk-order_details-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Nguyễn Văn An','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(2,2,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Nguyễn Văn An','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(3,3,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Nguyễn Văn An','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(4,4,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Trần Thị Bình','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(5,5,'Chi nhánh 1','DH000001','Chi nhánh 1, 123 Lê Lợi, Quận 1','RC000001',50000.00,'Trần Thị Bình','Online','admin','Giao Hàng Nhanh','Công ty TNHH ABC','0967890123','303 Điện Biên Phủ, Quận Bình Thạnh','Quận Bình Thạnh','Phường 15','Giao hàng tiêu chuẩn',2000,30.00,20.00,10.00,'Đã giao hàng','Giao trong giờ hành chính','Đơn hàng còn nợ 29.990.000đ',29990000.00,1743685640,'Hoàn thành','Đã giao'),(6,6,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Trần Thị Bình','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(7,7,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Hoàng Văn Em','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(8,8,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Hoàng Văn Em','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(9,9,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Hoàng Văn Em','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(10,10,'Chi nhánh 2','DH000002','Chi nhánh 2, 456 Hai Bà Trưng, Quận 3','RC000002',100000.00,'Nguyễn Văn An','Online','admin','Giao Hàng Tiết Kiệm','Hồ Thị Oanh','0989654321','666 Sư Vạn Hạnh, Quận 10','Quận 10','Phường 12','Giao hàng nhanh',5000,50.00,40.00,20.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743705640,'Hoàn thành','Đã giao'),(11,11,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Nguyễn Văn An','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(12,12,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Nguyễn Văn An','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(13,13,'Chi nhánh 1','DH000003','Chi nhánh 1, 123 Lê Lợi, Quận 1','RC000003',200000.00,'Trần Thị Bình','Online','admin','Giao Hàng Nhanh','Trần Văn Tân','0956987654','404 Lý Chính Thắng, Quận 3','Quận 3','Phường 8','Giao hàng hỏa tốc',3000,40.00,30.00,15.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743725640,'Hoàn thành','Đã giao'),(14,14,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Trần Thị Bình','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(15,15,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Trần Thị Bình','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(16,16,'Chi nhánh 2','DH000004','Chi nhánh 2, 456 Hai Bà Trưng, Quận 3','RC000004',50000.00,'Hoàng Văn Em','Online','admin','Giao Hàng Tiết Kiệm','Trần Văn Bách','0934865432','333 Hàm Nghi, Quận 1','Quận 1','Phường Nguyễn Thái Bình','Giao hàng tiêu chuẩn',1500,25.00,20.00,10.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743745640,'Hoàn thành','Đã giao'),(17,17,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Hoàng Văn Em','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(18,18,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Hoàng Văn Em','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(19,19,'Chi nhánh 1','DH000005','Chi nhánh 1, 123 Lê Lợi, Quận 1','RC000005',250000.00,'Bùi Thị Kiều','Online','admin','Giao Hàng Nhanh','Trần Văn Kiên','0901632109','111 Bùi Thị Xuân, Quận 1','Quận 1','Phường Phạm Ngũ Lão','Giao hàng hỏa tốc',10000,80.00,60.00,30.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743765640,'Hoàn thành','Đã giao'),(20,20,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Bùi Thị Kiều','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(21,21,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Bùi Thị Kiều','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(22,22,'Chi nhánh 2','DH000006','Chi nhánh 2, 456 Hai Bà Trưng, Quận 3','RC000006',150000.00,'Lý Văn Lam','Online','admin','Giao Hàng Tiết Kiệm','Lý Thị Quỳnh','0980410987','999 Huỳnh Thúc Kháng, Quận 1','Quận 1','Phường Bến Nghé','Giao hàng nhanh',1500,35.00,25.00,5.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743785640,'Hoàn thành','Đã giao'),(23,23,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Lý Văn Lam','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(24,24,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Lý Văn Lam','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(25,25,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Phan Thị Mai','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(26,26,'Chi nhánh 1','DH000007','Chi nhánh 1, 123 Lê Lợi, Quận 1','RC000007',100000.00,'Phan Thị Mai','Online','admin','Giao Hàng Nhanh','Ngô Thị Phương','0978901234','404 Nguyễn Thị Minh Khai, Quận 3','Quận 3','Phường 2','Giao hàng tiêu chuẩn',2000,35.00,25.00,5.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743815640,'Hoàn thành','Đã giao'),(27,27,'Chi nhánh 1',NULL,NULL,NULL,0.00,'Phan Thị Mai','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(28,28,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Tô Văn Nga','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(29,29,'Chi nhánh 2',NULL,NULL,NULL,0.00,'Tô Văn Nga','Trực tiếp','admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.00,NULL,'Hoàn thành',NULL),(30,30,'Chi nhánh 2','DH000008','Chi nhánh 2, 456 Hai Bà Trưng, Quận 3','RC000008',150000.00,'Tô Văn Nga','Online','admin','Giao Hàng Tiết Kiệm','Công ty CP GHI','0978765432','555 Đường 3/2, Quận 10','Quận 10','Phường 14','Giao hàng nhanh',1800,30.00,20.00,10.00,'Đã giao hàng','Giao trong giờ hành chính',NULL,0.00,1743835640,'Hoàn thành','Đã giao');
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT '0.00',
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `final_price` decimal(15,2) NOT NULL,
  `warranty_note` text COLLATE utf8mb4_general_ci,
  `maintenance_note` text COLLATE utf8mb4_general_ci,
  `product_note` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `fk-order_items-order_id` (`order_id`),
  KEY `fk-order_items-product_id` (`product_id`),
  CONSTRAINT `fk-order_items-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-order_items-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (46,151,52,'SP002',NULL,'iPhone 15 Pro 128GB',NULL,NULL,2.00,28990000.00,0.00,0.00,57980000.00,NULL,NULL,NULL),(47,152,51,'SP001',NULL,'iPhone 15 Pro Max 256GB',NULL,NULL,1.00,34990000.00,0.00,0.00,34990000.00,NULL,NULL,NULL),(48,153,78,'SP028',NULL,'iPad Pro 12.9 inch M2 (2022) 256GB',NULL,NULL,1.00,35990000.00,0.00,0.00,35990000.00,NULL,NULL,NULL),(49,154,52,'SP002',NULL,'iPhone 15 Pro 128GB',NULL,NULL,1.00,28990000.00,0.00,0.00,28990000.00,NULL,NULL,NULL),(50,155,78,'SP028',NULL,'iPad Pro 12.9 inch M2 (2022) 256GB',NULL,NULL,1.00,35990000.00,0.00,0.00,35990000.00,NULL,NULL,NULL),(51,156,80,'SP030',NULL,'iPad 10.2 inch (2021) 64GB',NULL,NULL,1.00,10490000.00,0.00,0.00,10490000.00,NULL,NULL,NULL),(52,156,79,'SP029',NULL,'iPad Air 10.9 inch M1 (2022) 64GB',NULL,NULL,1.00,17990000.00,0.00,0.00,17990000.00,NULL,NULL,NULL),(53,157,79,'SP029',NULL,'iPad Air 10.9 inch M1 (2022) 64GB',NULL,NULL,1.00,17990000.00,0.00,0.00,17990000.00,NULL,NULL,NULL);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_payments`
--

DROP TABLE IF EXISTS `order_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `cash_amount` decimal(15,2) DEFAULT '0.00',
  `card_amount` decimal(15,2) DEFAULT '0.00',
  `ewallet_amount` decimal(15,2) DEFAULT '0.00',
  `bank_transfer_amount` decimal(15,2) DEFAULT '0.00',
  `points_used` int DEFAULT '0',
  `voucher_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `voucher_amount` decimal(15,2) DEFAULT '0.00',
  `additional_fee` decimal(15,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk-order_payments-order_id` (`order_id`),
  CONSTRAINT `fk-order_payments-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_payments`
--

LOCK TABLES `order_payments` WRITE;
/*!40000 ALTER TABLE `order_payments` DISABLE KEYS */;
INSERT INTO `order_payments` VALUES (1,1,28990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(2,2,19990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(3,3,0.00,51980000.00,0.00,0.00,0,NULL,0.00,0.00),(4,4,0.00,17490000.00,0.00,0.00,0,NULL,0.00,0.00),(5,5,0.00,0.00,0.00,50000000.00,0,NULL,0.00,0.00),(6,6,0.00,27990000.00,0.00,0.00,0,NULL,0.00,0.00),(7,7,15990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(8,8,0.00,24990000.00,0.00,0.00,0,NULL,0.00,0.00),(9,9,9490000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(10,10,0.00,45980000.00,0.00,0.00,0,NULL,0.00,0.00),(11,11,24990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(12,12,0.00,24990000.00,0.00,0.00,0,NULL,0.00,0.00),(13,13,0.00,0.00,0.00,57990000.00,0,NULL,0.00,0.00),(14,14,18990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(15,15,0.00,25990000.00,0.00,0.00,0,NULL,0.00,0.00),(16,16,0.00,0.00,16990000.00,0.00,0,NULL,0.00,0.00),(17,17,0.00,32990000.00,0.00,0.00,0,NULL,0.00,0.00),(18,18,21990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(19,19,0.00,0.00,0.00,10990000.00,0,NULL,0.00,0.00),(20,20,0.00,32990000.00,0.00,0.00,0,NULL,0.00,0.00),(21,21,7780000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(22,22,0.00,0.00,35990000.00,0.00,0,NULL,0.00,0.00),(23,23,0.00,0.00,0.00,32990000.00,0,NULL,0.00,0.00),(24,24,26990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(25,25,0.00,29990000.00,0.00,0.00,0,NULL,0.00,0.00),(26,26,0.00,0.00,0.00,30990000.00,0,NULL,0.00,0.00),(27,27,19990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(28,28,0.00,0.00,23990000.00,0.00,0,NULL,0.00,0.00),(29,29,0.00,22990000.00,0.00,0.00,0,NULL,0.00,0.00),(30,30,0.00,0.00,0.00,30990000.00,0,NULL,0.00,0.00),(32,151,57980000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(33,152,34990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(34,153,35990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(35,154,28990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(36,155,35990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(37,156,28480000.00,0.00,0.00,0.00,0,NULL,0.00,0.00),(38,157,17990000.00,0.00,0.00,0.00,0,NULL,0.00,0.00);
/*!40000 ALTER TABLE `order_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `return_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `pos_session_id` int DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `final_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT '0.00',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_order_code` (`code`),
  KEY `idx_order_customer` (`customer_id`),
  KEY `idx-orders-pos_session_id` (`pos_session_id`),
  CONSTRAINT `fk-orders-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk-orders-pos_session_id` FOREIGN KEY (`pos_session_id`) REFERENCES `pos_session` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'HD20250414001',NULL,1,4,29990000.00,1000000.00,28990000.00,28990000.00,1743645340),(2,'HD20250414002',NULL,2,4,19990000.00,0.00,19990000.00,19990000.00,1743650340),(3,'HD20250414003',NULL,3,4,53980000.00,2000000.00,51980000.00,51980000.00,1743653340),(4,'HD20250414004',NULL,5,5,17990000.00,500000.00,17490000.00,17490000.00,1743660340),(5,'HD20250414005',NULL,6,5,82990000.00,3000000.00,79990000.00,50000000.00,1743665340),(6,'HD20250414006',NULL,8,5,28990000.00,1000000.00,27990000.00,27990000.00,1743670340),(7,'HD20250414007',NULL,10,6,15990000.00,0.00,15990000.00,15990000.00,1743680340),(8,'HD20250414008',NULL,12,6,24990000.00,0.00,24990000.00,24990000.00,1743685340),(9,'HD20250414009',NULL,15,6,9990000.00,500000.00,9490000.00,9490000.00,1743690340),(10,'HD20250414010',NULL,18,7,47980000.00,2000000.00,45980000.00,45980000.00,1743700340),(11,'HD20250414011',NULL,20,7,24990000.00,0.00,24990000.00,24990000.00,1743705340),(12,'HD20250414012',NULL,22,7,25990000.00,1000000.00,24990000.00,24990000.00,1743710340),(13,'HD20250414013',NULL,25,8,60990000.00,3000000.00,57990000.00,57990000.00,1743720340),(14,'HD20250414014',NULL,27,8,18990000.00,0.00,18990000.00,18990000.00,1743725340),(15,'HD20250414015',NULL,30,8,25990000.00,0.00,25990000.00,25990000.00,1743730340),(16,'HD20250414016',NULL,33,9,16990000.00,0.00,16990000.00,16990000.00,1743740340),(17,'HD20250414017',NULL,35,9,33990000.00,1000000.00,32990000.00,32990000.00,1743745340),(18,'HD20250414018',NULL,38,9,21990000.00,0.00,21990000.00,21990000.00,1743750340),(19,'HD20250414019',NULL,40,10,10990000.00,0.00,10990000.00,10990000.00,1743760340),(20,'HD20250414020',NULL,42,10,34990000.00,2000000.00,32990000.00,32990000.00,1743765340),(21,'HD20250414021',NULL,45,10,7780000.00,0.00,7780000.00,7780000.00,1743770340),(22,'HD20250414022',NULL,48,11,35990000.00,0.00,35990000.00,35990000.00,1743780340),(23,'HD20250414023',NULL,50,11,32990000.00,0.00,32990000.00,32990000.00,1743785340),(24,'HD20250414024',NULL,1,11,27990000.00,1000000.00,26990000.00,26990000.00,1743790340),(25,'HD20250414025',NULL,4,12,29990000.00,0.00,29990000.00,29990000.00,1743800340),(26,'HD20250414026',NULL,7,12,32990000.00,2000000.00,30990000.00,30990000.00,1743805340),(27,'HD20250414027',NULL,9,12,19990000.00,0.00,19990000.00,19990000.00,1743810340),(28,'HD20250414028',NULL,11,13,23990000.00,0.00,23990000.00,23990000.00,1743820340),(29,'HD20250414029',NULL,14,13,22990000.00,0.00,22990000.00,22990000.00,1743825340),(30,'HD20250414030',NULL,17,13,33990000.00,3000000.00,30990000.00,30990000.00,1743830340),(151,'HD17448328709325',NULL,NULL,37,57980000.00,0.00,57980000.00,0.00,1744832870),(152,'HD17448329735034',NULL,NULL,37,34990000.00,0.00,34990000.00,0.00,1744832973),(153,'HD17448331099513',NULL,NULL,37,35990000.00,0.00,35990000.00,0.00,1744833109),(154,'HD17448332304575',NULL,NULL,37,28990000.00,0.00,28990000.00,0.00,1744833230),(155,'HD17449836978840',NULL,52,39,35990000.00,0.00,35990000.00,0.00,1744983697),(156,'HD17449837445600',NULL,32,39,28480000.00,0.00,28480000.00,0.00,1744983744),(157,'HD17449837722176',NULL,32,39,17990000.00,0.00,17990000.00,0.00,1744983772);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_offline_transactions`
--

DROP TABLE IF EXISTS `pos_offline_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_offline_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `offline_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `transaction_data` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '1',
  `error_message` text COLLATE utf8mb4_general_ci,
  `created_at` int NOT NULL,
  `processed_at` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-pos_offline_transactions-offline_id` (`offline_id`),
  KEY `idx-pos_offline_transactions-user_id` (`user_id`),
  KEY `idx-pos_offline_transactions-status` (`status`),
  CONSTRAINT `fk-pos_offline_transactions-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_offline_transactions`
--

LOCK TABLES `pos_offline_transactions` WRITE;
/*!40000 ALTER TABLE `pos_offline_transactions` DISABLE KEYS */;
INSERT INTO `pos_offline_transactions` VALUES (1,'OFF-20250410-001',4,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250410001\",\"customer_id\":5,\"products\":[{\"product_id\":1,\"quantity\":1,\"price\":34990000,\"discount\":2000000}],\"payment\":{\"cash\":32990000}}}',2,NULL,1743635735,1743640735),(2,'OFF-20250410-002',4,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250410002\",\"customer_id\":8,\"products\":[{\"product_id\":12,\"quantity\":1,\"price\":15990000,\"discount\":0}],\"payment\":{\"card\":15990000}}}',2,NULL,1743645735,1743650735),(3,'OFF-20250410-003',5,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250410003\",\"customer_id\":10,\"products\":[{\"product_id\":22,\"quantity\":1,\"price\":17990000,\"discount\":500000}],\"payment\":{\"cash\":17490000}}}',2,NULL,1743655735,1743660735),(4,'OFF-20250410-004',6,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250410004\",\"customer_id\":15,\"products\":[{\"product_id\":33,\"quantity\":1,\"price\":6990000,\"discount\":0},{\"product_id\":37,\"quantity\":1,\"price\":790000,\"discount\":0}],\"payment\":{\"ewallet\":7780000}}}',2,NULL,1743665735,1743670735),(5,'OFF-20250410-005',6,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250410005\",\"customer_id\":20,\"products\":[{\"product_id\":38,\"quantity\":1,\"price\":3490000,\"discount\":0}],\"payment\":{\"cash\":3490000}}}',2,NULL,1743675735,1743680735),(6,'OFF-20250411-001',4,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250411001\",\"customer_id\":25,\"products\":[{\"product_id\":27,\"quantity\":1,\"price\":32990000,\"discount\":0}],\"payment\":{\"bank_transfer\":32990000}}}',2,NULL,1743735735,1743740735),(7,'OFF-20250411-002',5,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250411002\",\"customer_id\":30,\"products\":[{\"product_id\":8,\"quantity\":1,\"price\":22990000,\"discount\":1000000}],\"payment\":{\"card\":21990000}}}',2,NULL,1743745735,1743750735),(8,'OFF-20250411-003',12,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250411003\",\"customer_id\":35,\"products\":[{\"product_id\":14,\"quantity\":1,\"price\":16990000,\"discount\":0}],\"payment\":{\"cash\":16990000}}}',2,NULL,1743755735,1743760735),(9,'OFF-20250411-004',12,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250411004\",\"customer_id\":40,\"products\":[{\"product_id\":46,\"quantity\":1,\"price\":10990000,\"discount\":0}],\"payment\":{\"ewallet\":10990000}}}',2,NULL,1743765735,1743770735),(10,'OFF-20250411-005',13,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250411005\",\"customer_id\":45,\"products\":[{\"product_id\":33,\"quantity\":1,\"price\":6990000,\"discount\":0}],\"payment\":{\"card\":6990000}}}',2,NULL,1743775735,1743780735),(11,'OFF-20250412-001',4,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250412001\",\"customer_id\":22,\"products\":[{\"product_id\":23,\"quantity\":1,\"price\":21990000,\"discount\":0}],\"payment\":{\"cash\":21990000}}}',2,NULL,1743835735,1743840735),(12,'OFF-20250412-002',5,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250412002\",\"customer_id\":18,\"products\":[{\"product_id\":39,\"quantity\":1,\"price\":3290000,\"discount\":0}],\"payment\":{\"cash\":3290000}}}',2,NULL,1743845735,1743850735),(13,'OFF-20250412-003',6,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250412003\",\"customer_id\":12,\"products\":[{\"product_id\":48,\"quantity\":1,\"price\":12990000,\"discount\":1000000}],\"payment\":{\"bank_transfer\":11990000}}}',1,'Kết nối máy chủ bị gián đoạn',1743855735,NULL),(14,'OFF-20250412-004',13,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250412004\",\"customer_id\":7,\"products\":[{\"product_id\":21,\"quantity\":1,\"price\":18990000,\"discount\":0}],\"payment\":{\"ewallet\":18990000}}}',1,'Không thể xác nhận thanh toán',1743865735,NULL),(15,'OFF-20250412-005',12,'{\"type\":\"order\",\"data\":{\"code\":\"HD20250412005\",\"customer_id\":33,\"products\":[{\"product_id\":5,\"quantity\":1,\"price\":19990000,\"discount\":1000000}],\"payment\":{\"card\":18990000}}}',1,'Lỗi đồng bộ dữ liệu',1743875735,NULL);
/*!40000 ALTER TABLE `pos_offline_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_session`
--

DROP TABLE IF EXISTS `pos_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `start_time` int NOT NULL,
  `end_time` int DEFAULT NULL,
  `start_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `end_amount` decimal(12,2) DEFAULT NULL,
  `expected_amount` decimal(12,2) DEFAULT NULL,
  `difference` decimal(12,2) DEFAULT NULL,
  `cash_sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `card_sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `bank_transfer_sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `other_sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_sales` decimal(12,2) NOT NULL DEFAULT '0.00',
  `current_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `note` text COLLATE utf8mb4_general_ci,
  `close_note` text COLLATE utf8mb4_general_ci,
  `status` smallint NOT NULL DEFAULT '1',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-pos_session-user_id` (`user_id`),
  KEY `idx-pos_session-status` (`status`),
  CONSTRAINT `fk-pos_session-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_session`
--

LOCK TABLES `pos_session` WRITE;
/*!40000 ALTER TABLE `pos_session` DISABLE KEYS */;
INSERT INTO `pos_session` VALUES (1,1,1744630541,1744630555,1000000.00,40000000.00,1000000.00,39000000.00,0.00,0.00,0.00,0.00,0.00,1000000.00,'sdfds','',2,1744630541),(2,1,1744631763,1744632194,1000000.00,300000.00,1000000.00,-700000.00,0.00,0.00,0.00,0.00,0.00,1000000.00,'sdfsd','',2,1744631763),(3,1,1744633592,1744809027,1000000.00,4000000.00,1000000.00,3000000.00,0.00,0.00,0.00,0.00,0.00,1000000.00,'sdfsd','',2,1744633592),(4,4,1743635316,1743655316,2000000.00,5850000.00,5800000.00,50000.00,3800000.00,1000000.00,500000.00,0.00,5300000.00,2000000.00,'Ca sáng','Ca bàn giao với số dư chênh lệch nhỏ',2,1743635316),(5,5,1743655316,1743675316,5850000.00,9250000.00,9200000.00,50000.00,2500000.00,1800000.00,1200000.00,0.00,5500000.00,5850000.00,'Ca chiều','Kết thúc ca làm việc bình thường',2,1743655316),(6,6,1743675316,1743695316,9250000.00,11450000.00,11500000.00,-50000.00,1800000.00,900000.00,500000.00,0.00,3200000.00,9250000.00,'Ca tối','Thiếu 50k khi kiểm đếm',2,1743675316),(7,4,1743695316,1743715316,11450000.00,14800000.00,14850000.00,-50000.00,3000000.00,1300000.00,800000.00,0.00,5100000.00,11450000.00,'Ca sáng','Thiếu tiền khi kiểm đếm cuối ca',2,1743695316),(8,5,1743715316,1743735316,14800000.00,19100000.00,19100000.00,0.00,3200000.00,1500000.00,1100000.00,0.00,5800000.00,14800000.00,'Ca chiều','Kết thúc ca làm việc bình thường',2,1743715316),(9,6,1743735316,1743755316,19100000.00,21300000.00,21300000.00,0.00,1500000.00,700000.00,800000.00,0.00,3000000.00,19100000.00,'Ca tối','Kết thúc ca làm việc bình thường',2,1743735316),(10,4,1743755316,1743775316,21300000.00,24800000.00,24800000.00,0.00,2500000.00,1600000.00,900000.00,0.00,5000000.00,21300000.00,'Ca sáng','Kết thúc ca làm việc bình thường',2,1743755316),(11,5,1743775316,1743795316,24800000.00,29850000.00,29800000.00,50000.00,3200000.00,2300000.00,1500000.00,0.00,7000000.00,24800000.00,'Ca chiều','Thừa tiền khi kiểm đếm',2,1743775316),(12,12,1743795316,1743815316,29850000.00,31950000.00,31900000.00,50000.00,1500000.00,800000.00,300000.00,0.00,2600000.00,29850000.00,'Ca tối','Kết thúc ca làm việc bình thường',2,1743795316),(13,13,1743815316,1743835316,31950000.00,35650000.00,35650000.00,0.00,2700000.00,1800000.00,1200000.00,0.00,5700000.00,31950000.00,'Ca sáng','Kết thúc ca làm việc bình thường',2,1743815316),(14,4,1743835316,1743855316,35650000.00,39550000.00,39600000.00,-50000.00,2900000.00,1500000.00,1100000.00,0.00,5500000.00,35650000.00,'Ca chiều','Thiếu tiền khi kiểm đếm cuối ca',2,1743835316),(15,5,1743855316,1743875316,39550000.00,42150000.00,42150000.00,0.00,1800000.00,900000.00,700000.00,0.00,3400000.00,39550000.00,'Ca tối','Kết thúc ca làm việc bình thường',2,1743855316),(16,12,1743875316,1743895316,42150000.00,46250000.00,46200000.00,50000.00,3200000.00,1800000.00,1000000.00,0.00,6000000.00,42150000.00,'Ca sáng','Thừa tiền khi kiểm đếm',2,1743875316),(17,13,1743895316,1743915316,46250000.00,50750000.00,50750000.00,0.00,3100000.00,1900000.00,1300000.00,0.00,6300000.00,46250000.00,'Ca chiều','Kết thúc ca làm việc bình thường',2,1743895316),(18,4,1743915316,NULL,50750000.00,NULL,NULL,NULL,0.00,0.00,0.00,0.00,0.00,50750000.00,'Ca đang diễn ra',NULL,1,1743915316),(19,4,1743635319,1743655319,2000000.00,5850000.00,5800000.00,50000.00,3800000.00,1000000.00,500000.00,0.00,5300000.00,2000000.00,'Ca sáng','Ca bàn giao với số dư chênh lệch nhỏ',2,1743635319),(20,5,1743655319,1743675319,5850000.00,9250000.00,9200000.00,50000.00,2500000.00,1800000.00,1200000.00,0.00,5500000.00,5850000.00,'Ca chiều','Kết thúc ca làm việc bình thường',2,1743655319),(21,6,1743675319,1743695319,9250000.00,11450000.00,11500000.00,-50000.00,1800000.00,900000.00,500000.00,0.00,3200000.00,9250000.00,'Ca tối','Thiếu 50k khi kiểm đếm',2,1743675319),(22,4,1743695319,1743715319,11450000.00,14800000.00,14850000.00,-50000.00,3000000.00,1300000.00,800000.00,0.00,5100000.00,11450000.00,'Ca sáng','Thiếu tiền khi kiểm đếm cuối ca',2,1743695319),(23,5,1743715319,1743735319,14800000.00,19100000.00,19100000.00,0.00,3200000.00,1500000.00,1100000.00,0.00,5800000.00,14800000.00,'Ca chiều','Kết thúc ca làm việc bình thường',2,1743715319),(24,6,1743735319,1743755319,19100000.00,21300000.00,21300000.00,0.00,1500000.00,700000.00,800000.00,0.00,3000000.00,19100000.00,'Ca tối','Kết thúc ca làm việc bình thường',2,1743735319),(25,4,1743755319,1743775319,21300000.00,24800000.00,24800000.00,0.00,2500000.00,1600000.00,900000.00,0.00,5000000.00,21300000.00,'Ca sáng','Kết thúc ca làm việc bình thường',2,1743755319),(26,5,1743775319,1743795319,24800000.00,29850000.00,29800000.00,50000.00,3200000.00,2300000.00,1500000.00,0.00,7000000.00,24800000.00,'Ca chiều','Thừa tiền khi kiểm đếm',2,1743775319),(27,12,1743795319,1743815319,29850000.00,31950000.00,31900000.00,50000.00,1500000.00,800000.00,300000.00,0.00,2600000.00,29850000.00,'Ca tối','Kết thúc ca làm việc bình thường',2,1743795319),(28,13,1743815319,1743835319,31950000.00,35650000.00,35650000.00,0.00,2700000.00,1800000.00,1200000.00,0.00,5700000.00,31950000.00,'Ca sáng','Kết thúc ca làm việc bình thường',2,1743815319),(29,4,1743835319,1743855319,35650000.00,39550000.00,39600000.00,-50000.00,2900000.00,1500000.00,1100000.00,0.00,5500000.00,35650000.00,'Ca chiều','Thiếu tiền khi kiểm đếm cuối ca',2,1743835319),(30,5,1743855319,1743875319,39550000.00,42150000.00,42150000.00,0.00,1800000.00,900000.00,700000.00,0.00,3400000.00,39550000.00,'Ca tối','Kết thúc ca làm việc bình thường',2,1743855319),(33,4,1743915319,NULL,50750000.00,NULL,NULL,NULL,0.00,0.00,0.00,0.00,0.00,50750000.00,'Ca đang diễn ra',NULL,1,1743915319),(34,1,1744811202,1744815263,100000.00,200000.00,100000.00,100000.00,0.00,0.00,0.00,0.00,0.00,100000.00,'bắt đầu ca giao dịch tối','',2,1744811202),(35,1,1744815505,1744816967,100000.00,300000.00,100000.00,200000.00,0.00,0.00,0.00,0.00,0.00,100000.00,'243','',2,1744815505),(36,1,1744826492,1744829163,100000.00,200000.00,100000.00,100000.00,0.00,0.00,0.00,0.00,0.00,100000.00,'dsfsd','',2,1744826492),(37,1,1744829184,1744833270,34200000.00,3000000.00,34200000.00,-31200000.00,0.00,0.00,0.00,0.00,0.00,34200000.00,'','',2,1744829184),(38,1,1744855393,1744855441,10000.00,1000000.00,10000.00,990000.00,0.00,0.00,0.00,0.00,0.00,10000.00,'','',2,1744855393),(39,1,1744879493,NULL,1000000.00,NULL,NULL,NULL,0.00,0.00,0.00,0.00,0.00,1000000.00,'',NULL,1,1744879493);
/*!40000 ALTER TABLE `pos_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pos_user_preferences`
--

DROP TABLE IF EXISTS `pos_user_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pos_user_preferences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `preference_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `preference_value` text COLLATE utf8mb4_general_ci,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx-pos_user_preferences-user_id-preference_key` (`user_id`,`preference_key`),
  CONSTRAINT `fk-pos_user_preferences-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pos_user_preferences`
--

LOCK TABLES `pos_user_preferences` WRITE;
/*!40000 ALTER TABLE `pos_user_preferences` DISABLE KEYS */;
INSERT INTO `pos_user_preferences` VALUES (1,1,'dark_mode','true',1744635723,1744635723),(2,1,'language','vi',1744635723,1744635723),(3,1,'notifications','true',1744635723,1744635723),(4,1,'default_view','grid',1744635723,1744635723),(5,1,'receipt_footer','Cảm ơn quý khách đã mua hàng!',1744635723,1744635723),(6,2,'dark_mode','false',1744635723,1744635723),(7,2,'language','vi',1744635723,1744635723),(8,2,'notifications','true',1744635723,1744635723),(9,2,'default_view','list',1744635723,1744635723),(10,3,'dark_mode','true',1744635723,1744635723),(11,3,'language','en',1744635723,1744635723),(12,3,'notifications','false',1744635723,1744635723),(13,4,'dark_mode','false',1744635723,1744635723),(14,4,'language','vi',1744635723,1744635723),(15,4,'notifications','true',1744635723,1744635723),(16,4,'receipt_footer','Hẹn gặp lại quý khách!',1744635723,1744635723),(17,5,'dark_mode','true',1744635723,1744635723),(18,5,'language','vi',1744635723,1744635723),(19,5,'default_view','grid',1744635723,1744635723),(20,6,'dark_mode','false',1744635723,1744635723);
/*!40000 ALTER TABLE `pos_user_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `level` tinyint DEFAULT '1',
  `description` text COLLATE utf8mb4_general_ci,
  `status` tinyint DEFAULT '1',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-product_categories-parent_id` (`parent_id`),
  CONSTRAINT `fk-product_categories-parent_id` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,'Điện thoại',NULL,1,'Các loại điện thoại di động',1,1744635439),(2,'Máy tính',NULL,1,'Các loại máy tính, laptop',1,1744635439),(3,'Điện tử',NULL,1,'Thiết bị điện tử',1,1744635439),(4,'Điện lạnh',NULL,1,'Các thiết bị điện lạnh gia dụng',1,1744635439),(5,'Linh kiện',NULL,1,'Linh kiện các loại',1,1744635439),(6,'Smartphone',1,2,'Điện thoại thông minh',1,1744635439),(7,'Điện thoại phổ thông',1,2,'Điện thoại cơ bản',1,1744635439),(8,'Laptop',2,2,'Máy tính xách tay',1,1744635439),(9,'Máy tính bảng',2,2,'Tablet',1,1744635439),(10,'Desktop',2,2,'Máy tính để bàn',1,1744635439),(11,'Tai nghe',3,2,'Tai nghe âm thanh',1,1744635439),(12,'Loa',3,2,'Loa âm thanh',1,1744635439),(13,'Tủ lạnh',4,2,'Tủ lạnh gia đình',1,1744635439),(14,'Máy lạnh',4,2,'Điều hòa nhiệt độ',1,1744635439),(15,'Linh kiện điện thoại',5,2,'Linh kiện cho điện thoại',1,1744635439),(16,'Linh kiện máy tính',5,2,'Linh kiện cho máy tính',1,1744635439),(17,'iPhone',6,3,'Điện thoại iPhone của Apple',1,1744635439),(18,'Samsung Galaxy',6,3,'Điện thoại Samsung Galaxy',1,1744635439),(19,'Xiaomi',6,3,'Điện thoại Xiaomi',1,1744635439),(20,'OPPO',6,3,'Điện thoại OPPO',1,1744635439),(21,'Nokia',7,3,'Điện thoại Nokia cơ bản',1,1744635439),(22,'Itel',7,3,'Điện thoại Itel phổ thông',1,1744635439),(23,'Masstel',7,3,'Điện thoại Masstel phổ thông',1,1744635439),(24,'Laptop Gaming',8,3,'Laptop chuyên cho chơi game',1,1744635439),(25,'Laptop Văn phòng',8,3,'Laptop cho công việc văn phòng',1,1744635439),(26,'Laptop Đồ họa',8,3,'Laptop cho đồ họa, thiết kế',1,1744635439),(27,'iPad',9,3,'Máy tính bảng iPad của Apple',1,1744635439),(28,'Samsung Tab',9,3,'Máy tính bảng Samsung',1,1744635439),(29,'PC Gaming',10,3,'Máy tính để bàn cho chơi game',1,1744635439),(30,'PC Văn phòng',10,3,'Máy tính để bàn cho văn phòng',1,1744635439),(31,'Tai nghe không dây',11,3,'Tai nghe bluetooth',1,1744635439),(32,'Tai nghe có dây',11,3,'Tai nghe có dây kết nối',1,1744635439),(33,'Loa bluetooth',12,3,'Loa không dây bluetooth',1,1744635439),(34,'Loa máy tính',12,3,'Loa dành cho máy tính',1,1744635439),(35,'Tủ lạnh 2 cánh',13,3,'Tủ lạnh 2 cánh thông thường',1,1744635439),(36,'Tủ lạnh side by side',13,3,'Tủ lạnh nhiều cánh',1,1744635439),(37,'Điều hòa 1 chiều',14,3,'Điều hòa chỉ làm lạnh',1,1744635439),(38,'Điều hòa 2 chiều',14,3,'Điều hòa cả làm lạnh và sưởi',1,1744635439),(39,'Pin điện thoại',15,3,'Pin thay thế cho điện thoại',1,1744635439),(40,'Màn hình điện thoại',15,3,'Màn hình thay thế cho điện thoại',1,1744635439),(41,'CPU',16,3,'Bộ vi xử lý',1,1744635439),(42,'RAM',16,3,'Bộ nhớ truy cập ngẫu nhiên',1,1744635439),(43,'SSD',16,3,'Ổ cứng thể rắn',1,1744635439),(44,'HDD',16,3,'Ổ cứng cơ',1,1744635439),(45,'VGA',16,3,'Card đồ họa',1,1744635439),(46,'Mainboard',16,3,'Bo mạch chủ',1,1744635439),(47,'PSU',16,3,'Nguồn máy tính',1,1744635439),(48,'Case máy tính',16,3,'Vỏ máy tính',1,1744635439),(49,'Bàn phím',16,3,'Bàn phím máy tính',1,1744635439),(50,'Chuột',16,3,'Chuột máy tính',1,1744635439);
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `is_primary` tinyint DEFAULT '0',
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-product_images-product_id` (`product_id`),
  CONSTRAINT `fk-product_images-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_units`
--

DROP TABLE IF EXISTS `product_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `base_unit_id` int DEFAULT NULL,
  `conversion_rate` decimal(10,2) DEFAULT '1.00',
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-product_units-base_unit_id` (`base_unit_id`),
  CONSTRAINT `fk-product_units-base_unit_id` FOREIGN KEY (`base_unit_id`) REFERENCES `product_units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_units`
--

LOCK TABLES `product_units` WRITE;
/*!40000 ALTER TABLE `product_units` DISABLE KEYS */;
INSERT INTO `product_units` VALUES (1,'CAI','Cái',NULL,1.00,'Đơn vị đếm cơ bản'),(2,'BO','Bộ',NULL,1.00,'Đơn vị theo bộ sản phẩm'),(3,'CHIEC','Chiếc',NULL,1.00,'Đơn vị đếm cho các sản phẩm'),(4,'HOP','Hộp',NULL,1.00,'Đơn vị đóng gói hộp'),(5,'THUNG','Thùng',NULL,1.00,'Đơn vị đóng gói thùng'),(6,'GOI','Gói',NULL,1.00,'Đơn vị đóng gói gói'),(7,'CHAI','Chai',NULL,1.00,'Đơn vị đóng gói chai'),(8,'LO','Lọ',NULL,1.00,'Đơn vị đóng gói lọ'),(9,'KG','Kilogram',NULL,1.00,'Đơn vị khối lượng kilogram'),(10,'G','Gram',9,0.00,'Đơn vị khối lượng gram'),(11,'MG','Miligram',10,0.00,'Đơn vị khối lượng miligram'),(12,'TAN','Tấn',9,1000.00,'Đơn vị khối lượng tấn'),(13,'L','Lít',NULL,1.00,'Đơn vị thể tích lít'),(14,'ML','Mililít',13,0.00,'Đơn vị thể tích mililít'),(15,'M','Mét',NULL,1.00,'Đơn vị chiều dài mét'),(16,'CM','Centimét',15,0.01,'Đơn vị chiều dài centimét'),(17,'MM','Milimét',16,0.10,'Đơn vị chiều dài milimét'),(18,'KM','Kilomét',15,1000.00,'Đơn vị chiều dài kilomét'),(19,'M2','Mét vuông',NULL,1.00,'Đơn vị diện tích mét vuông'),(20,'CM2','Centimét vuông',19,0.00,'Đơn vị diện tích centimét vuông'),(21,'HA','Hecta',19,10000.00,'Đơn vị diện tích hecta'),(22,'M3','Mét khối',NULL,1.00,'Đơn vị thể tích mét khối'),(23,'CM3','Centimét khối',22,0.00,'Đơn vị thể tích centimét khối'),(24,'LON','Lon',NULL,1.00,'Đơn vị đóng gói lon'),(25,'MIENG','Miếng',NULL,1.00,'Đơn vị đếm miếng'),(26,'MANH','Mảnh',NULL,1.00,'Đơn vị đếm mảnh'),(27,'TO','Tờ',NULL,1.00,'Đơn vị đếm tờ'),(28,'CUON','Cuộn',NULL,1.00,'Đơn vị đếm cuộn'),(29,'QUYEN','Quyển',NULL,1.00,'Đơn vị đếm quyển sách'),(30,'TAP','Tập',NULL,1.00,'Đơn vị đếm tập'),(31,'THUNG10','Thùng 10',5,10.00,'Thùng chứa 10 đơn vị'),(32,'THUNG20','Thùng 20',5,20.00,'Thùng chứa 20 đơn vị'),(33,'THUNG50','Thùng 50',5,50.00,'Thùng chứa 50 đơn vị'),(34,'THUNG100','Thùng 100',5,100.00,'Thùng chứa 100 đơn vị'),(35,'HOP10','Hộp 10',4,10.00,'Hộp chứa 10 đơn vị'),(36,'HOP20','Hộp 20',4,20.00,'Hộp chứa 20 đơn vị'),(37,'HOP50','Hộp 50',4,50.00,'Hộp chứa 50 đơn vị'),(38,'HOP100','Hộp 100',4,100.00,'Hộp chứa 100 đơn vị'),(39,'TUYP','Tuýp',NULL,1.00,'Đơn vị đóng gói tuýp'),(40,'BAO','Bao',NULL,1.00,'Đơn vị đóng gói bao'),(41,'DAY','Dây',NULL,1.00,'Đơn vị đếm dây'),(42,'DOI','Đôi',NULL,1.00,'Đơn vị đếm đôi'),(43,'PHAN','Phần',NULL,1.00,'Đơn vị đếm phần'),(44,'VIEN','Viên',NULL,1.00,'Đơn vị đếm viên'),(45,'KHAY','Khay',NULL,1.00,'Đơn vị đóng gói khay'),(46,'KHAY10','Khay 10',45,10.00,'Khay chứa 10 đơn vị'),(47,'KHAY20','Khay 20',45,20.00,'Khay chứa 20 đơn vị'),(48,'TUYP10','Tuýp 10',39,10.00,'Tuýp chứa 10 đơn vị'),(49,'CHAI10','Chai 10',7,10.00,'Chai chứa 10 đơn vị'),(50,'CHAI20','Chai 20',7,20.00,'Chai chứa 20 đơn vị');
/*!40000 ALTER TABLE `product_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_warranties`
--

DROP TABLE IF EXISTS `product_warranties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_warranties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_item_id` int NOT NULL,
  `product_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `serial_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `warranty_start_date` date NOT NULL,
  `warranty_end_date` date NOT NULL,
  `warranty_type` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'standard',
  `warranty_duration_months` int NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'active',
  `original_purchase_date` date NOT NULL,
  `original_purchase_price` decimal(15,2) NOT NULL,
  `last_service_date` date DEFAULT NULL,
  `next_service_date` date DEFAULT NULL,
  `repair_count` int DEFAULT '0',
  `total_repair_cost` decimal(15,2) DEFAULT '0.00',
  `warranty_terms` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`),
  KEY `fk-product_warranties-order_item_id` (`order_item_id`),
  KEY `idx_warranty_serial` (`serial_number`),
  KEY `idx_warranty_product` (`product_id`),
  KEY `idx_warranty_customer` (`customer_id`),
  KEY `idx_warranty_status` (`status`),
  CONSTRAINT `fk-product_warranties-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `fk-product_warranties-order_item_id` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-product_warranties-product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_warranties`
--

LOCK TABLES `product_warranties` WRITE;
/*!40000 ALTER TABLE `product_warranties` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_warranties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `current_stock` int DEFAULT '0',
  `min_stock` int DEFAULT '0',
  `max_stock` int DEFAULT '0',
  `primary_unit_id` int NOT NULL,
  `related_product_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `is_direct_sale` tinyint DEFAULT '1',
  `description` text COLLATE utf8mb4_general_ci,
  `note` text COLLATE utf8mb4_general_ci,
  `location` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_component` tinyint DEFAULT '0',
  `warranty_months` int DEFAULT '0',
  `maintenance_period_months` int DEFAULT '0',
  `point_earn` int DEFAULT '0',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk-products-category_id` (`category_id`),
  KEY `fk-products-primary_unit_id` (`primary_unit_id`),
  KEY `idx_product_code` (`code`),
  KEY `idx_product_barcode` (`barcode`),
  CONSTRAINT `fk-products-category_id` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`),
  CONSTRAINT `fk-products-primary_unit_id` FOREIGN KEY (`primary_unit_id`) REFERENCES `product_units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (51,17,'SP001','8935222500010','iPhone 15 Pro Max 256GB','Apple',34990000.00,32000000.00,24,5,50,1,NULL,0.25,1,1,'iPhone 15 Pro Max 256GB, màn hình Super Retina XDR 6.7 inch',NULL,'Kệ A1',0,12,3,349,1744635521,1744635521),(52,17,'SP002','8935222500027','iPhone 15 Pro 128GB','Apple',28990000.00,26500000.00,27,5,50,1,NULL,0.22,1,1,'iPhone 15 Pro 128GB, màn hình Super Retina XDR 6.1 inch',NULL,'Kệ A1',0,12,3,289,1744635521,1744635521),(53,17,'SP003','8935222500034','iPhone 15 128GB','Apple',23990000.00,21500000.00,35,5,50,1,NULL,0.20,1,1,'iPhone 15 128GB, màn hình Super Retina XDR 6.1 inch',NULL,'Kệ A2',0,12,3,239,1744635521,1744635521),(54,17,'SP004','8935222500041','iPhone 14 Pro Max 256GB','Apple',29990000.00,27000000.00,15,5,30,1,NULL,0.24,1,1,'iPhone 14 Pro Max 256GB, màn hình Super Retina XDR 6.7 inch',NULL,'Kệ A2',0,12,3,299,1744635521,1744635521),(55,17,'SP005','8935222500058','iPhone 14 128GB','Apple',19990000.00,18000000.00,22,5,40,1,NULL,0.19,1,1,'iPhone 14 128GB, màn hình Super Retina XDR 6.1 inch',NULL,'Kệ A3',0,12,3,199,1744635521,1744635521),(56,18,'SP006','8935222500065','Samsung Galaxy S24 Ultra 256GB','Samsung',33990000.00,31000000.00,20,5,40,1,NULL,0.23,1,1,'Samsung Galaxy S24 Ultra 256GB, màn hình Dynamic AMOLED 2X 6.8 inch',NULL,'Kệ B1',0,12,3,339,1744635521,1744635521),(57,18,'SP007','8935222500072','Samsung Galaxy S24+ 256GB','Samsung',25990000.00,23500000.00,18,5,35,1,NULL,0.21,1,1,'Samsung Galaxy S24+ 256GB, màn hình Dynamic AMOLED 2X 6.7 inch',NULL,'Kệ B1',0,12,3,259,1744635521,1744635521),(58,18,'SP008','8935222500089','Samsung Galaxy S24 128GB','Samsung',22990000.00,20500000.00,22,5,40,1,NULL,0.20,1,1,'Samsung Galaxy S24 128GB, màn hình Dynamic AMOLED 2X 6.2 inch',NULL,'Kệ B2',0,12,3,229,1744635521,1744635521),(59,18,'SP009','8935222500096','Samsung Galaxy Z Fold5 512GB','Samsung',40990000.00,38000000.00,10,3,20,1,NULL,0.28,1,1,'Samsung Galaxy Z Fold5 512GB, màn hình gập',NULL,'Kệ B2',0,12,3,409,1744635521,1744635521),(60,18,'SP010','8935222500102','Samsung Galaxy Z Flip5 256GB','Samsung',25990000.00,23500000.00,15,3,25,1,NULL,0.18,1,1,'Samsung Galaxy Z Flip5 256GB, màn hình gập',NULL,'Kệ B3',0,12,3,259,1744635521,1744635521),(61,19,'SP011','8935222500119','Xiaomi 14 Pro 256GB','Xiaomi',19990000.00,18000000.00,20,5,40,1,NULL,0.22,1,1,'Xiaomi 14 Pro 256GB, màn hình AMOLED 6.73 inch',NULL,'Kệ C1',0,12,3,199,1744635521,1744635521),(62,19,'SP012','8935222500126','Xiaomi 14 128GB','Xiaomi',15990000.00,14500000.00,25,5,45,1,NULL,0.20,1,1,'Xiaomi 14 128GB, màn hình AMOLED 6.36 inch',NULL,'Kệ C1',0,12,3,159,1744635521,1744635521),(63,19,'SP013','8935222500133','Redmi Note 13 Pro+ 256GB','Xiaomi',9990000.00,8500000.00,30,10,60,1,NULL,0.19,1,1,'Redmi Note 13 Pro+ 256GB, màn hình AMOLED 6.67 inch',NULL,'Kệ C2',0,12,3,99,1744635521,1744635521),(64,19,'SP014','8935222500140','Xiaomi 13T Pro 256GB','Xiaomi',16990000.00,15000000.00,15,5,30,1,NULL,0.21,1,1,'Xiaomi 13T Pro 256GB, màn hình AMOLED 6.67 inch',NULL,'Kệ C2',0,12,3,169,1744635521,1744635521),(65,20,'SP015','8935222500157','OPPO Find X6 Pro 256GB','OPPO',24990000.00,22500000.00,12,5,25,1,NULL,0.22,1,1,'OPPO Find X6 Pro 256GB, màn hình AMOLED 6.82 inch',NULL,'Kệ D1',0,12,3,249,1744635521,1744635521),(66,20,'SP016','8935222500164','OPPO Reno10 Pro+ 5G 256GB','OPPO',19990000.00,18000000.00,18,5,35,1,NULL,0.20,1,1,'OPPO Reno10 Pro+ 5G 256GB, màn hình AMOLED 6.74 inch',NULL,'Kệ D1',0,12,3,199,1744635521,1744635521),(67,24,'SP017','8935222500171','Laptop Gaming Acer Nitro 5','Acer',25990000.00,23500000.00,10,2,20,1,NULL,2.50,1,1,'Laptop Gaming Acer Nitro 5, Intel Core i7, RAM 16GB, SSD 512GB, RTX 3060',NULL,'Kệ E1',0,24,6,259,1744635521,1744635521),(68,24,'SP018','8935222500188','Laptop Gaming ASUS TUF Gaming F15','ASUS',24990000.00,22500000.00,8,2,15,1,NULL,2.30,1,1,'Laptop Gaming ASUS TUF Gaming F15, Intel Core i7, RAM 16GB, SSD 512GB, RTX 3050Ti',NULL,'Kệ E1',0,24,6,249,1744635521,1744635521),(69,24,'SP019','8935222500195','Laptop Gaming MSI Katana 15','MSI',29990000.00,27500000.00,6,2,12,1,NULL,2.25,1,1,'Laptop Gaming MSI Katana 15, Intel Core i7, RAM 16GB, SSD 1TB, RTX 4060',NULL,'Kệ E2',0,24,6,299,1744635521,1744635521),(70,24,'SP020','8935222500201','Laptop Gaming Dell G15','Dell',27990000.00,25500000.00,7,2,15,1,NULL,2.40,1,1,'Laptop Gaming Dell G15, Intel Core i7, RAM 16GB, SSD 512GB, RTX 3060',NULL,'Kệ E2',0,24,6,279,1744635521,1744635521),(71,25,'SP021','8935222500218','Laptop Dell Inspiron 15','Dell',18990000.00,17000000.00,15,3,30,1,NULL,1.80,1,1,'Laptop Dell Inspiron 15, Intel Core i5, RAM 8GB, SSD 512GB',NULL,'Kệ F1',0,24,6,189,1744635521,1744635521),(72,25,'SP022','8935222500225','Laptop HP Pavilion 15','HP',17990000.00,16000000.00,18,3,35,1,NULL,1.75,1,1,'Laptop HP Pavilion 15, Intel Core i5, RAM 8GB, SSD 512GB',NULL,'Kệ F1',0,24,6,179,1744635521,1744635521),(73,25,'SP023','8935222500232','Laptop Lenovo ThinkPad E15','Lenovo',21990000.00,20000000.00,12,3,25,1,NULL,1.70,1,1,'Laptop Lenovo ThinkPad E15, Intel Core i5, RAM 16GB, SSD 512GB',NULL,'Kệ F2',0,24,6,219,1744635521,1744635521),(74,25,'SP024','8935222500249','Laptop ASUS VivoBook 15','ASUS',16990000.00,15000000.00,20,5,40,1,NULL,1.65,1,1,'Laptop ASUS VivoBook 15, Intel Core i5, RAM 8GB, SSD 512GB',NULL,'Kệ F2',0,24,6,169,1744635521,1744635521),(75,26,'SP025','8935222500256','MacBook Pro 14 M3 Pro','Apple',52990000.00,50000000.00,8,2,15,1,NULL,1.60,1,1,'MacBook Pro 14 inch, Apple M3 Pro, RAM 18GB, SSD 512GB',NULL,'Kệ G1',0,12,3,529,1744635521,1744635521),(76,26,'SP026','8935222500263','MacBook Pro 16 M3 Max','Apple',82990000.00,79000000.00,5,1,10,1,NULL,1.68,1,1,'MacBook Pro 16 inch, Apple M3 Max, RAM 32GB, SSD 1TB',NULL,'Kệ G1',0,12,3,829,1744635521,1744635521),(77,26,'SP027','8935222500270','MacBook Air 13 M2','Apple',32990000.00,30000000.00,15,3,30,1,NULL,1.24,1,1,'MacBook Air 13 inch, Apple M2, RAM 16GB, SSD 512GB',NULL,'Kệ G2',0,12,3,329,1744635521,1744635521),(78,27,'SP028','8935222500287','iPad Pro 12.9 inch M2 (2022) 256GB','Apple',35990000.00,33000000.00,8,2,20,1,NULL,0.68,1,1,'iPad Pro 12.9 inch M2 (2022) 256GB, Wifi',NULL,'Kệ H1',0,12,3,359,1744635521,1744635521),(79,27,'SP029','8935222500294','iPad Air 10.9 inch M1 (2022) 64GB','Apple',17990000.00,16000000.00,13,3,30,1,NULL,0.46,1,1,'iPad Air 10.9 inch M1 (2022) 64GB, Wifi',NULL,'Kệ H1',0,12,3,179,1744635521,1744635521),(80,27,'SP030','8935222500300','iPad 10.2 inch (2021) 64GB','Apple',10490000.00,9000000.00,19,5,40,1,NULL,0.49,1,1,'iPad 10.2 inch (2021) 64GB, Wifi',NULL,'Kệ H2',0,12,3,104,1744635521,1744635521),(81,28,'SP031','8935222500317','Samsung Galaxy Tab S9 Ultra 256GB','Samsung',28990000.00,26500000.00,8,2,15,1,NULL,0.73,1,1,'Samsung Galaxy Tab S9 Ultra 256GB, màn hình Dynamic AMOLED 2X 14.6 inch',NULL,'Kệ I1',0,12,3,289,1744635521,1744635521),(82,28,'SP032','8935222500324','Samsung Galaxy Tab S9+ 256GB','Samsung',24990000.00,22500000.00,10,2,20,1,NULL,0.59,1,1,'Samsung Galaxy Tab S9+ 256GB, màn hình Dynamic AMOLED 2X 12.4 inch',NULL,'Kệ I1',0,12,3,249,1744635521,1744635521),(83,31,'SP033','8935222500331','Tai nghe AirPods Pro 2','Apple',6990000.00,6000000.00,25,5,50,1,NULL,0.05,1,1,'Tai nghe không dây AirPods Pro 2 với khả năng chống ồn chủ động',NULL,'Kệ J1',0,12,3,69,1744635521,1744635521),(84,31,'SP034','8935222500348','Tai nghe Samsung Galaxy Buds2 Pro','Samsung',4990000.00,4200000.00,20,5,40,1,NULL,0.06,1,1,'Tai nghe không dây Samsung Galaxy Buds2 Pro với khả năng chống ồn',NULL,'Kệ J1',0,12,3,49,1744635521,1744635521),(85,31,'SP035','8935222500355','Tai nghe Sony WF-1000XM5','Sony',6790000.00,5800000.00,15,3,30,1,NULL,0.05,1,1,'Tai nghe không dây Sony WF-1000XM5 với chống ồn hàng đầu thị trường',NULL,'Kệ J2',0,12,3,67,1744635521,1744635521),(86,32,'SP036','8935222500362','Tai nghe Apple EarPods Lightning','Apple',590000.00,450000.00,50,10,100,1,NULL,0.02,1,1,'Tai nghe có dây Apple EarPods cổng Lightning cho iPhone',NULL,'Kệ J3',0,6,0,5,1744635521,1744635521),(87,32,'SP037','8935222500379','Tai nghe Sony MDR-XB55AP','Sony',790000.00,600000.00,40,10,80,1,NULL,0.03,1,1,'Tai nghe có dây Sony MDR-XB55AP Extra Bass',NULL,'Kệ J3',0,6,0,7,1744635521,1744635521),(88,33,'SP038','8935222500386','Loa Bluetooth JBL Charge 5','JBL',3490000.00,2900000.00,15,3,30,1,NULL,0.98,1,1,'Loa Bluetooth JBL Charge 5 chống nước IPX7',NULL,'Kệ K1',0,12,0,34,1744635521,1744635521),(89,33,'SP039','8935222500393','Loa Bluetooth Sony SRS-XB33','Sony',3290000.00,2700000.00,12,3,25,1,NULL,0.89,1,1,'Loa Bluetooth Sony SRS-XB33 chống nước IP67',NULL,'Kệ K1',0,12,0,32,1744635521,1744635521),(90,33,'SP040','8935222500409','Loa Bluetooth Marshall Emberton','Marshall',3990000.00,3300000.00,8,2,20,1,NULL,0.70,1,1,'Loa Bluetooth Marshall Emberton với chất âm chuẩn studio',NULL,'Kệ K2',0,12,0,39,1744635521,1744635521),(91,34,'SP041','8935222500416','Loa Logitech Z333','Logitech',1490000.00,1200000.00,20,5,40,2,NULL,4.10,1,1,'Bộ loa máy tính Logitech Z333 2.1 kênh công suất 80W',NULL,'Kệ K3',0,12,0,14,1744635521,1744635521),(92,35,'SP042','8935222500423','Tủ lạnh Samsung Inverter 236L','Samsung',7990000.00,6800000.00,10,2,20,1,NULL,55.00,1,1,'Tủ lạnh Samsung Inverter 236 lít, 2 cánh, tiết kiệm điện',NULL,'Kho A1',0,24,12,79,1744635521,1744635521),(93,35,'SP043','8935222500430','Tủ lạnh LG Inverter 255L','LG',8490000.00,7300000.00,8,2,15,1,NULL,57.00,1,1,'Tủ lạnh LG Inverter 255 lít, 2 cánh, tiết kiệm điện',NULL,'Kho A1',0,24,12,84,1744635521,1744635521),(94,36,'SP044','8935222500447','Tủ lạnh Samsung Side by Side 647L','Samsung',22990000.00,20500000.00,5,1,10,1,NULL,118.00,1,1,'Tủ lạnh Samsung Side by Side 647 lít, công nghệ làm lạnh kép',NULL,'Kho A2',0,24,12,229,1744635521,1744635521),(95,36,'SP045','8935222500454','Tủ lạnh LG Side by Side 601L','LG',21990000.00,19500000.00,5,1,10,1,NULL,115.00,1,1,'Tủ lạnh LG Side by Side 601 lít, có ngăn lấy nước ngoài',NULL,'Kho A2',0,24,12,219,1744635521,1744635521),(96,37,'SP046','8935222500461','Điều hòa Panasonic 1 chiều 12000BTU','Panasonic',10990000.00,9500000.00,15,3,30,1,NULL,9.00,1,1,'Điều hòa Panasonic 1 chiều 12000BTU, Inverter, tiết kiệm điện',NULL,'Kho B1',0,24,12,109,1744635521,1744635521),(97,37,'SP047','8935222500478','Điều hòa Daikin 1 chiều 9000BTU','Daikin',9990000.00,8500000.00,18,3,35,1,NULL,8.50,1,1,'Điều hòa Daikin 1 chiều 9000BTU, Inverter, tiết kiệm điện',NULL,'Kho B1',0,24,12,99,1744635521,1744635521),(98,38,'SP048','8935222500485','Điều hòa LG 2 chiều 12000BTU','LG',12990000.00,11500000.00,10,2,20,1,NULL,10.00,1,1,'Điều hòa LG 2 chiều 12000BTU, Inverter, có chức năng sưởi',NULL,'Kho B2',0,24,12,129,1744635521,1744635521),(99,38,'SP049','8935222500492','Điều hòa Mitsubishi 2 chiều 9000BTU','Mitsubishi',11990000.00,10500000.00,12,2,24,1,NULL,9.50,1,1,'Điều hòa Mitsubishi 2 chiều 9000BTU, Inverter, có chức năng sưởi',NULL,'Kho B2',0,24,12,119,1744635521,1744635521),(100,39,'SP050','8935222500508','Pin iPhone 14 Pro Max chính hãng','Apple',950000.00,750000.00,30,10,60,1,NULL,0.05,1,1,'Pin iPhone 14 Pro Max dung lượng 4323mAh, chính hãng',NULL,'Kệ L1',1,6,0,9,1744635521,1744635521);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `area` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ward` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tax_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_purchase` decimal(15,2) DEFAULT '0.00',
  `current_debt` decimal(15,2) DEFAULT '0.00',
  `group` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `total_purchase_net` decimal(15,2) DEFAULT '0.00',
  `creator` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` int NOT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_supplier_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'NCC001','Công ty TNHH Điện tử ABC','contact@abcelectronics.com','0912345678','123 Đường Lê Lợi, Quận 1','Quận 1','Phường Bến Nghé','0123456789','Công ty TNHH Điện tử ABC',1500000000.00,75000000.00,'Điện tử',1,1350000000.00,'admin',1744634315,'Nhà cung cấp điện thoại chính'),(2,'NCC002','Công ty CP Máy tính XYZ','info@xyzcomputers.com','0923456789','456 Đường Nguyễn Huệ, Quận 1','Quận 1','Phường Bến Thành','9876543210','Công ty CP Máy tính XYZ',980000000.00,45000000.00,'Máy tính',1,930000000.00,'admin',1744634315,'Nhà cung cấp laptop và linh kiện'),(3,'NCC003','Công ty TNHH Điện lạnh DEF','support@defcooling.com','0934567890','789 Đường Cách Mạng Tháng 8, Quận 3','Quận 3','Phường 7','1234509876','Công ty TNHH Điện lạnh DEF',750000000.00,30000000.00,'Điện lạnh',1,720000000.00,'admin',1744634315,'Nhà cung cấp sản phẩm điện lạnh'),(4,'NCC004','Công ty TNHH Phụ kiện GHI','sales@ghiaccessories.com','0945678901','101 Đường Hai Bà Trưng, Quận 1','Quận 1','Phường Cầu Ông Lãnh','5432167890','Công ty TNHH Phụ kiện GHI',320000000.00,15000000.00,'Phụ kiện',1,300000000.00,'admin',1744634315,'Nhà cung cấp phụ kiện điện thoại'),(5,'NCC005','Công ty CP Âm thanh JKL','info@jklsound.com','0956789012','202 Đường Võ Văn Tần, Quận 3','Quận 3','Phường 5','6789054321','Công ty CP Âm thanh JKL',450000000.00,20000000.00,'Âm thanh',1,420000000.00,'admin',1744634315,'Nhà cung cấp sản phẩm âm thanh'),(6,'NCC006','Công ty TNHH Thiết bị MNO','contact@mnodevices.com','0967890123','303 Đường Điện Biên Phủ, Quận Bình Thạnh','Quận Bình Thạnh','Phường 15','0987612345','Công ty TNHH Thiết bị MNO',680000000.00,30000000.00,'Thiết bị',1,650000000.00,'admin',1744634315,'Nhà cung cấp thiết bị gia dụng'),(7,'NCC007','Công ty CP Viễn thông PQR','sales@pqrtelecoms.com','0978901234','404 Đường Nguyễn Thị Minh Khai, Quận 3','Quận 3','Phường 2','5678901234','Công ty CP Viễn thông PQR',890000000.00,40000000.00,'Viễn thông',1,850000000.00,'admin',1744634315,'Nhà cung cấp thiết bị viễn thông'),(8,'NCC008','Công ty TNHH Điện tử STU','info@stuelectronics.com','0989012345','505 Đường Trần Hưng Đạo, Quận 5','Quận 5','Phường 6','4321098765','Công ty TNHH Điện tử STU',520000000.00,25000000.00,'Điện tử',1,500000000.00,'admin',1744634315,'Nhà cung cấp thiết bị điện tử'),(9,'NCC009','Công ty CP Máy tính VWX','support@vwxcomputers.com','0990123456','606 Đường Nguyễn Đình Chiểu, Quận 3','Quận 3','Phường 9','9876123450','Công ty CP Máy tính VWX',750000000.00,35000000.00,'Máy tính',1,720000000.00,'admin',1744634315,'Nhà cung cấp máy tính và linh kiện'),(10,'NCC010','Công ty TNHH Linh kiện YZA','contact@yzaparts.com','0901234567','707 Đường Lý Tự Trọng, Quận 1','Quận 1','Phường Bến Thành','0192837465','Công ty TNHH Linh kiện YZA',430000000.00,20000000.00,'Linh kiện',1,410000000.00,'admin',1744634315,'Nhà cung cấp linh kiện điện tử'),(11,'NCC011','Công ty CP Điện lạnh BCD','info@bcdcooling.com','0912345098','808 Đường Bạch Đằng, Quận Bình Thạnh','Quận Bình Thạnh','Phường 15','7654321098','Công ty CP Điện lạnh BCD',630000000.00,30000000.00,'Điện lạnh',1,600000000.00,'admin',1744634315,'Nhà cung cấp thiết bị điện lạnh'),(12,'NCC012','Công ty TNHH Điện tử EFG','sales@efgelectronics.com','0923450987','909 Đường Trần Quốc Thảo, Quận 3','Quận 3','Phường 7','2345678901','Công ty TNHH Điện tử EFG',520000000.00,25000000.00,'Điện tử',1,500000000.00,'admin',1744634315,'Nhà cung cấp điện tử gia dụng'),(13,'NCC013','Công ty CP Phụ kiện HIJ','contact@hijaccessories.com','0934509876','111 Đường Lê Lai, Quận 1','Quận 1','Phường Bến Thành','3456789012','Công ty CP Phụ kiện HIJ',380000000.00,18000000.00,'Phụ kiện',1,360000000.00,'admin',1744634315,'Nhà cung cấp phụ kiện công nghệ'),(14,'NCC014','Công ty TNHH Âm thanh KLM','info@klmsound.com','0945098765','222 Đường Nguyễn Công Trứ, Quận 1','Quận 1','Phường Nguyễn Thái Bình','4567890123','Công ty TNHH Âm thanh KLM',420000000.00,21000000.00,'Âm thanh',1,400000000.00,'admin',1744634315,'Nhà cung cấp thiết bị âm thanh'),(15,'NCC015','Công ty CP Thiết bị NOP','sales@nopdevices.com','0956987654','333 Đường Cao Thắng, Quận 3','Quận 3','Phường 12','5678901234','Công ty CP Thiết bị NOP',540000000.00,27000000.00,'Thiết bị',1,520000000.00,'admin',1744634315,'Nhà cung cấp thiết bị điện tử'),(16,'NCC016','Công ty TNHH Viễn thông QRS','contact@qrstelecoms.com','0967876543','444 Đường Nguyễn Trãi, Quận 5','Quận 5','Phường 7','6789012345','Công ty TNHH Viễn thông QRS',760000000.00,38000000.00,'Viễn thông',1,730000000.00,'admin',1744634315,'Nhà cung cấp thiết bị viễn thông'),(17,'NCC017','Công ty CP Điện tử TUV','info@tuvelectronics.com','0978765432','555 Đường 3/2, Quận 10','Quận 10','Phường 14','7890123456','Công ty CP Điện tử TUV',480000000.00,24000000.00,'Điện tử',1,460000000.00,'admin',1744634315,'Nhà cung cấp điện tử và điện lạnh'),(18,'NCC018','Công ty TNHH Máy tính WXY','support@wxycomputers.com','0989654321','666 Đường Sư Vạn Hạnh, Quận 10','Quận 10','Phường 12','8901234567','Công ty TNHH Máy tính WXY',690000000.00,34500000.00,'Máy tính',1,660000000.00,'admin',1744634315,'Nhà cung cấp máy tính và linh kiện'),(19,'NCC019','Công ty CP Linh kiện ZAB','sales@zabparts.com','0990543210','777 Đường Cống Quỳnh, Quận 1','Quận 1','Phường Nguyễn Cư Trinh','9012345678','Công ty CP Linh kiện ZAB',410000000.00,20500000.00,'Linh kiện',1,390000000.00,'admin',1744634315,'Nhà cung cấp linh kiện điện tử'),(20,'NCC020','Công ty TNHH Điện lạnh CDE','contact@cdecooling.com','0901543210','888 Đường Trần Quang Khải, Quận 1','Quận 1','Phường Tân Định','0123456789','Công ty TNHH Điện lạnh CDE',580000000.00,29000000.00,'Điện lạnh',1,550000000.00,'admin',1744634315,'Nhà cung cấp thiết bị điện lạnh'),(21,'NCC021','Công ty TNHH Apple Việt Nam','contact@appleauthorized.vn','0912345678','123 Đường Lê Lợi, Quận 1','Quận 1','Phường Bến Nghé','0123456789','Công ty TNHH Apple Việt Nam',5500000000.00,275000000.00,'Điện tử',1,5350000000.00,'admin',1744634315,'Đại lý Apple chính hãng'),(22,'NCC022','Công ty TNHH Samsung Việt Nam','contact@samsung.vn','0923456789','456 Đường Nguyễn Huệ, Quận 1','Quận 1','Phường Bến Thành','9876543210','Công ty TNHH Samsung Việt Nam',4800000000.00,240000000.00,'Điện tử',1,4700000000.00,'admin',1744634315,'Nhà phân phối Samsung'),(23,'NCC023','Công ty CP Thế Giới Di Động','supplier@tgdd.vn','0934567890','789 Đường Cách Mạng Tháng 8, Quận 3','Quận 3','Phường 7','1234509876','Công ty CP Thế Giới Di Động',3500000000.00,175000000.00,'Điện tử',1,3400000000.00,'admin',1744634315,'Nhà phân phối thiết bị di động'),(24,'NCC024','Công ty CP FPT Trading','trading@fpt.com.vn','0945678901','101 Đường Hai Bà Trưng, Quận 1','Quận 1','Phường Cầu Ông Lãnh','5432167890','Công ty CP FPT Trading',2800000000.00,140000000.00,'Điện tử',1,2700000000.00,'admin',1744634315,'Nhà phân phối FPT'),(25,'NCC025','Công ty TNHH Xiaomi Việt Nam','contact@xiaomi.vn','0956789012','202 Đường Võ Văn Tần, Quận 3','Quận 3','Phường 5','6789054321','Công ty TNHH Xiaomi Việt Nam',1950000000.00,97500000.00,'Điện tử',1,1900000000.00,'admin',1744634315,'Nhà phân phối Xiaomi'),(26,'NCC026','Công ty TNHH Dell Việt Nam','contact@dell.vn','0967890123','303 Đường Điện Biên Phủ, Quận Bình Thạnh','Quận Bình Thạnh','Phường 15','0987612345','Công ty TNHH Dell Việt Nam',2200000000.00,110000000.00,'Máy tính',1,2150000000.00,'admin',1744634315,'Nhà phân phối Dell'),(27,'NCC027','Công ty TNHH HP Việt Nam','contact@hp.vn','0978901234','404 Đường Nguyễn Thị Minh Khai, Quận 3','Quận 3','Phường 2','5678901234','Công ty TNHH HP Việt Nam',2150000000.00,107500000.00,'Máy tính',1,2100000000.00,'admin',1744634315,'Nhà phân phối HP'),(28,'NCC028','Công ty TNHH Asus Việt Nam','contact@asus.vn','0989012345','505 Đường Trần Hưng Đạo, Quận 5','Quận 5','Phường 6','4321098765','Công ty TNHH Asus Việt Nam',1850000000.00,92500000.00,'Máy tính',1,1800000000.00,'admin',1744634315,'Nhà phân phối Asus'),(29,'NCC029','Công ty TNHH Lenovo Việt Nam','contact@lenovo.vn','0990123456','606 Đường Nguyễn Đình Chiểu, Quận 3','Quận 3','Phường 9','9876123450','Công ty TNHH Lenovo Việt Nam',1750000000.00,87500000.00,'Máy tính',1,1700000000.00,'admin',1744634315,'Nhà phân phối Lenovo'),(30,'NCC030','Công ty TNHH LG Việt Nam','contact@lg.vn','0901234567','707 Đường Lý Tự Trọng, Quận 1','Quận 1','Phường Bến Thành','0192837465','Công ty TNHH LG Việt Nam',2350000000.00,117500000.00,'Điện tử',1,2300000000.00,'admin',1744634315,'Nhà phân phối LG');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_history`
--

DROP TABLE IF EXISTS `transaction_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `pos_session_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `final_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT '0.00',
  `cash_amount` decimal(15,2) DEFAULT '0.00',
  `card_amount` decimal(15,2) DEFAULT '0.00',
  `ewallet_amount` decimal(15,2) DEFAULT '0.00',
  `bank_transfer_amount` decimal(15,2) DEFAULT '0.00',
  `payment_status` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT 'pending',
  `transaction_type` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT 'sale',
  `notes` text COLLATE utf8mb4_bin,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_code` (`transaction_code`),
  KEY `idx-transaction_history-order_id` (`order_id`),
  KEY `idx-transaction_history-user_id` (`user_id`),
  KEY `idx-transaction_history-pos_session_id` (`pos_session_id`),
  KEY `idx-transaction_history-customer_id` (`customer_id`),
  KEY `idx-transaction_history-created_at` (`created_at`),
  KEY `idx-transaction_history-transaction_type` (`transaction_type`),
  KEY `idx-transaction_history-payment_status` (`payment_status`),
  CONSTRAINT `fk-transaction_history-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk-transaction_history-order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-transaction_history-pos_session_id` FOREIGN KEY (`pos_session_id`) REFERENCES `pos_session` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk-transaction_history-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_history`
--

LOCK TABLES `transaction_history` WRITE;
/*!40000 ALTER TABLE `transaction_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `auth_key` varchar(32) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `pin` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `position` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `last_login_at` int DEFAULT NULL,
  `verification_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin',NULL,'N6ZEIf8T30H15eF7kIT9gH5TcME7vJK4','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK',NULL,NULL,'admin@example.com',NULL,NULL,NULL,10,1744630491,1744630668,1744630668,NULL),(2,'manager1','Nguyễn Văn An','tBcryp4WkLJ8rX6hYsL9e5DfQZwE3j1f','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','123456',NULL,'manager1@example.com','0912345678','Quản lý',NULL,10,1744634482,1744634482,1744548082,NULL),(3,'manager2','Trần Thị Bình','f4eTgJ7HyN2m8p9q1r3s5t6v7w8x9y0z','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','234567',NULL,'manager2@example.com','0923456789','Quản lý',NULL,10,1744634482,1744634482,1744461682,NULL),(4,'cashier1','Lê Văn Cường','a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','345678',NULL,'cashier1@example.com','0934567890','Thu ngân',NULL,10,1744634482,1744634482,1744375282,NULL),(5,'cashier2','Phạm Thị Dung','q7r8s9t0u1v2w3x4y5z6a7b8c9d0e1f2','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','456789',NULL,'cashier2@example.com','0945678901','Thu ngân',NULL,10,1744634482,1744634482,1744288882,NULL),(6,'cashier3','Hoàng Văn Em','g3h4i5j6k7l8m9n0o1p2q3r4s5t6u7v8','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','567890',NULL,'cashier3@example.com','0956789012','Thu ngân',NULL,10,1744634482,1744634482,1744202482,NULL),(7,'staff1','Ngô Thị Phương','w9x0y1z2a3b4c5d6e7f8g9h0i1j2k3l4','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','678901',NULL,'staff1@example.com','0967890123','Nhân viên bán hàng',NULL,10,1744634482,1744634482,1744116082,NULL),(8,'staff2','Vũ Văn Giang','m5n6o7p8q9r0s1t2u3v4w5x6y7z8a9b0','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','789012',NULL,'staff2@example.com','0978901234','Nhân viên bán hàng',NULL,10,1744634482,1744634482,1744029682,NULL),(9,'staff3','Đinh Thị Hoa','c1d2e3f4g5h6i7j8k9l0m1n2o3p4q5r6','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','890123',NULL,'staff3@example.com','0989012345','Nhân viên bán hàng',NULL,10,1744634482,1744634482,1743943282,NULL),(10,'staff4','Đặng Văn Inh','s7t8u9v0w1x2y3z4a5b6c7d8e9f0g1h2','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','901234',NULL,'staff4@example.com','0990123456','Nhân viên kho',NULL,10,1744634482,1744634482,1743856882,NULL),(11,'staff5','Bùi Thị Kiều','i3j4k5l6m7n8o9p0q1r2s3t4u5v6w7x8','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','012345',NULL,'staff5@example.com','0901234567','Nhân viên kho',NULL,10,1744634482,1744634482,1743770482,NULL),(12,'cashier4','Lý Văn Lam','y9z0a1b2c3d4e5f6g7h8i9j0k1l2m3n4','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','123450',NULL,'cashier4@example.com','0912345098','Thu ngân',NULL,10,1744634482,1744634482,1743684082,NULL),(13,'cashier5','Phan Thị Mai','o5p6q7r8s9t0u1v2w3x4y5z6a7b8c9d0','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','234561',NULL,'cashier5@example.com','0923450987','Thu ngân',NULL,10,1744634482,1744634482,1743597682,NULL),(14,'staff6','Tô Văn Nga','e1f2g3h4i5j6k7l8m9n0o1p2q3r4s5t6','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','345672',NULL,'staff6@example.com','0934509876','Nhân viên kỹ thuật',NULL,10,1744634482,1744634482,1743511282,NULL),(15,'staff7','Hồ Thị Oanh','u7v8w9x0y1z2a3b4c5d6e7f8g9h0i1j2','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','456783',NULL,'staff7@example.com','0945098765','Nhân viên kỹ thuật',NULL,10,1744634482,1744634482,1743424882,NULL),(16,'staff8','Đoàn Văn Phúc','k3l4m5n6o7p8q9r0s1t2u3v4w5x6y7z8','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','567894',NULL,'staff8@example.com','0956987654','Nhân viên bảo trì',NULL,10,1744634482,1744634482,1743338482,NULL),(17,'manager3','Mai Văn Quang','a9b0c1d2e3f4g5h6i7j8k9l0m1n2o3p4','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','678905',NULL,'manager3@example.com','0967876543','Quản lý kho',NULL,10,1744634482,1744634482,1743252082,NULL),(18,'staff9','Chu Thị Rạng','q5r6s7t8u9v0w1x2y3z4a5b6c7d8e9f0','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','789016',NULL,'staff9@example.com','0978765432','Nhân viên bảo trì',NULL,10,1744634482,1744634482,1743165682,NULL),(19,'staff10','Đinh Văn Sơn','g1h2i3j4k5l6m7n8o9p0q1r2s3t4u5v6','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','890127',NULL,'staff10@example.com','0989654321','Nhân viên bảo hành',NULL,10,1744634482,1744634482,1743079282,NULL),(20,'staff11','Trần Văn Tân','w7x8y9z0a1b2c3d4e5f6g7h8i9j0k1l2','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','901238',NULL,'staff11@example.com','0990543210','Nhân viên bảo hành',NULL,10,1744634482,1744634482,NULL,NULL),(21,'cashier6','Lê Thị Uyên','m3n4o5p6q7r8s9t0u1v2w3x4y5z6a7b8','$2y$13$ifgjdy2yF.S2apIf2SqQpOGuvpc2p.yu/yZ4KfJPiNlx/KblBBcuK','012349',NULL,'cashier6@example.com','0901543210','Thu ngân',NULL,0,1744634482,1744634482,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login_history`
--

DROP TABLE IF EXISTS `user_login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_login_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `login_time` int NOT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_bin,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-user_login_history-user_id` (`user_id`),
  CONSTRAINT `fk-user_login_history-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login_history`
--

LOCK TABLES `user_login_history` WRITE;
/*!40000 ALTER TABLE `user_login_history` DISABLE KEYS */;
INSERT INTO `user_login_history` VALUES (1,1,1744630646,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(2,1,1744630668,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(3,2,1743635744,'192.168.1.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(4,3,1743645744,'192.168.1.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(5,4,1743655744,'192.168.1.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(6,5,1743665744,'192.168.1.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(7,6,1743675744,'192.168.1.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(8,7,1743685744,'192.168.1.60','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(9,8,1743695744,'192.168.1.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(10,9,1743705744,'192.168.1.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(11,10,1743715744,'192.168.1.90','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(12,1,1743725744,'192.168.1.100','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(13,2,1743735744,'192.168.1.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(14,3,1743745744,'192.168.1.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(15,11,1743755744,'192.168.1.110','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(16,12,1743765744,'192.168.1.120','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(17,13,1743775744,'192.168.1.130','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(18,4,1743785744,'192.168.1.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(19,5,1743795744,'192.168.1.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(20,6,1743805744,'192.168.1.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(21,14,1743815744,'192.168.1.140','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(22,15,1743825744,'192.168.1.150','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(23,1,1743835744,'192.168.1.100','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(24,16,1743845744,'192.168.1.160','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(25,17,1743855744,'192.168.1.170','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(26,7,1743865744,'192.168.1.60','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(27,8,1743875744,'192.168.1.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(28,9,1743885744,'192.168.1.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(29,18,1743895744,'192.168.1.180','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(30,19,1743905744,'192.168.1.190','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(31,10,1743915744,'192.168.1.90','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(32,2,1743925744,'192.168.1.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(33,3,1743935744,'192.168.1.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(34,4,1743945744,'192.168.1.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',0),(35,4,1743946744,'192.168.1.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(36,5,1743955744,'192.168.1.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(37,1,1743965744,'192.168.1.100','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(38,11,1743975744,'192.168.1.110','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(39,12,1743985744,'192.168.1.120','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(40,6,1743995744,'192.168.1.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',0),(41,6,1743996744,'192.168.1.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',0),(42,6,1743997744,'192.168.1.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(43,13,1744005744,'192.168.1.130','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(44,7,1744015744,'192.168.1.60','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(45,14,1744025744,'192.168.1.140','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(46,8,1744035744,'192.168.1.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(47,9,1744045744,'192.168.1.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(48,15,1744055744,'192.168.1.150','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(49,10,1744065744,'192.168.1.90','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(50,1,1744075744,'192.168.1.100','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(51,2,1744085744,'192.168.1.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',1),(52,4,1744549344,'192.168.1.30','Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',1);
/*!40000 ALTER TABLE `user_login_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warranty_repair_logs`
--

DROP TABLE IF EXISTS `warranty_repair_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warranty_repair_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `warranty_id` int NOT NULL,
  `repair_date` date NOT NULL,
  `technician` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `repair_location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `issue_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `repair_description` text COLLATE utf8mb4_general_ci,
  `parts_replaced` text COLLATE utf8mb4_general_ci,
  `repair_cost` decimal(15,2) DEFAULT '0.00',
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `next_service_recommendation` text COLLATE utf8mb4_general_ci,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_repair_log_warranty` (`warranty_id`),
  CONSTRAINT `fk-warranty_repair_logs-warranty_id` FOREIGN KEY (`warranty_id`) REFERENCES `product_warranties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warranty_repair_logs`
--

LOCK TABLES `warranty_repair_logs` WRITE;
/*!40000 ALTER TABLE `warranty_repair_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `warranty_repair_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-18 20:46:59
