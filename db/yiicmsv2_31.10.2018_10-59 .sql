# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: 127.0.0.1 (MySQL 5.7.16)
# Схема: yiicmsv2
# Время создания: 2018-10-31 07:59:54 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы yii_icms_admin_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_admin_menu`;

CREATE TABLE `yii_icms_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` varchar(255) NOT NULL DEFAULT '0',
  `controller` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `isActive` int(1) NOT NULL DEFAULT '1',
  `in_button` int(1) NOT NULL DEFAULT '0',
  `parentName` varchar(255) NOT NULL DEFAULT '',
  `icon_class` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_admin_menu` WRITE;
/*!40000 ALTER TABLE `yii_icms_admin_menu` DISABLE KEYS */;

INSERT INTO `yii_icms_admin_menu` (`id`, `pid`, `controller`, `route`, `title`, `isActive`, `in_button`, `parentName`, `icon_class`, `sort`, `role`, `created_at`, `updated_at`)
VALUES
	(1,'0','structure','structure/index','Структура',1,0,'','icon_nav_structure',0,'manager',0,1449486632),
	(2,'1','structure','structure/add','Страница',1,1,'id_page','icon_nav_structure',0,'manager',0,1443705944),
	(3,'0','sliders','sliders/index','Слайдер',1,0,'','icon_nav_gallery',20,'manager',0,1444129859),
	(4,'3','sliders','sliders/slider_add','Слайдер',1,1,'','icon_nav_structure',0,'manager',0,1444130572),
	(5,'3','sliders','sliders/slide_add','Слайд',1,1,'slider_id','icon_nav_structure',0,'manager',0,1435918489),
	(6,'0','developer','developer/index','Разработчику',1,0,'','icon_nav_structure',300,'developer',0,1435842111),
	(7,'6','developer','developer/menu_add','Добавить пункт меню',1,1,'','icon_nav_structure',0,'developer',1433758009,1435842172),
	(8,'0','users','users/index','Пользователи',1,0,'','icon_nav_users',40,'admin',1433758048,1435840294),
	(9,'8','users','users/add','Новый пользователь',1,1,'','icon_nav_structure',0,'admin',1433758130,1435839233),
	(10,'0','contents','contents/index','Контент',1,0,'','icon_nav_news',10,'manager',1435230027,1469086732),
	(11,'10','contents','contents/categotie_add','Категорию',1,1,'','icon_nav_structure',0,'manager',1435230073,1435230073),
	(12,'10','contents','contents/content_add','Элемент',1,1,'content_categorie_id','icon_nav_structure',0,'manager',1435230118,1435926017),
	(13,'0','galleries','galleries/index','Галерея',1,0,'','icon_nav_gallery',0,'manager',1436355079,1469086720),
	(14,'13','galleries','galleries/categorie_add','Галерею',1,1,'pid','icon_nav_structure',0,'manager',1436355209,1436355568),
	(15,'13','galleries','galleries/gallery_add','Фотографии',1,1,'gallery_categorie_id','icon_nav_structure',0,'manager',1436355209,1436355568),
	(16,'0','catalog','catalog/index','Каталог',1,0,'','icon_nav_catalog',0,'manager',1436355079,1469086741),
	(17,'16','catalog','catalog/categorie_add','Категорию',1,1,'pid','icon_nav_structure',0,'manager',1436355209,1436355568),
	(18,'16','catalog','catalog/catalog_add','Товар',1,1,'catalog_categorie_id','icon_nav_structure',0,'manager',1436355209,1436355568),
	(19,'0','maps','maps/index','Карты',1,0,'','icon_nav_structure',0,'manager',1443094338,1443094338),
	(20,'19','maps','maps/map_add','Добавить карту',1,1,'','icon_nav_structure',0,'manager',1443097878,1443189364),
	(21,'19','maps','maps/mark_add','Добавить метку',1,1,'map_id','icon_nav_structure',0,'manager',1443097975,1443099056),
	(22,'0','seo','seo/index','SEO',1,0,'','icon_nav_structure',99,'admin',1443422767,1443532071),
	(23,'0','parameters','parameters/index','Параметры',1,0,'','icon_nav_structure',100,'admin',1445431397,1449659070),
	(24,'23','parameters','parameters/add','Параметр',1,1,'','icon_nav_structure',0,'developer',1445433500,1449658583),
	(25,'0','feedbacks','feedbacks/index','Отзывы',1,0,'','icon_nav_structure',0,'manager',1446796028,1446796054),
	(26,'25','feedbacks','feedbacks/add','Отзыв',1,1,'','icon_nav_structure',0,'manager',1446796097,1446796097),
	(27,'6','developer','developer/keys','Ключи',1,0,'','',0,'developer',1447658064,1447658064),
	(28,'6','developer','developer/index','Пункты меню',1,0,'','',0,'developer',1447658064,1447658064),
	(29,'6','developer','developer/key_add','Ключ',1,1,'','',0,'developer',1447658064,1447658064),
	(30,'16','catalog','catalog/props','Свойства',1,0,'','icon_nav_structure',100,'manager',1448350361,1457589454),
	(31,'16','catalog','catalog/prop_add','Свойство',1,1,'','icon_nav_structure',0,'developer',1448351611,1448363531),
	(32,'16','catalog','catalog/prop_groups','Группы свойств',1,0,'','icon_nav_structure',0,'developer',1448884178,1457589479),
	(33,'16','catalog','catalog/prop_group_add','Группу свойств',1,1,'','icon_nav_structure',0,'developer',1448884564,1457589483),
	(36,'0','orders','orders/index','Заказы',1,0,'','icon_nav_structure',0,'manager',1449830309,1449830413),
	(37,'36','orders','orders/deliverys','Доставка',1,0,'','icon_nav_structure',0,'manager',1453210480,1453210499),
	(38,'36','orders','orders/delivery_add','Способ доставки',1,1,'','icon_nav_structure',0,'manager',1453210531,1453210531),
	(39,'36','orders','orders/pays','Оплата',1,0,'','icon_nav_structure',0,'manager',1453210480,1453210499),
	(40,'36','orders','orders/pay_add','Способ оплаты',1,1,'','icon_nav_structure',0,'manager',1453210531,1453210531),
	(41,'0','banners','banners/index','Баннеры',1,0,'','icon_nav_structure',30,'manager',0,1492001555),
	(42,'41','banners','banners/group_add','Группу',1,1,'','icon_nav_structure',0,'manager',0,1492001576),
	(43,'41','banners','banners/banner_add','Баннер',1,1,'group_id','icon_nav_structure',0,'manager',0,1492001567),
	(44,'36','orders','orders/statuses','Статусы',1,0,'','icon_nav_structure',0,'manager',1453210480,1453210499),
	(45,'36','orders','orders/status_add','Статус',1,1,'','icon_nav_structure',0,'manager',1453210480,1453210499),
	(46,'6','developer','developer/modules','Модули',1,0,'','icon_nav_structure',0,'developer',1480578214,1480578214),
	(47,'6','developer','developer/module_add','Модуль',1,1,'','icon_nav_structure',0,'admin',1480578246,1480578246),
	(48,'6','developer','developer/dumps','Резервное копирование',1,0,'','icon_nav_structure',0,'developer',1488525191,1488525191);

/*!40000 ALTER TABLE `yii_icms_admin_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_auth_assignment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_auth_assignment`;

CREATE TABLE `yii_icms_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `yii_icms_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `yii_icms_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_auth_assignment` WRITE;
/*!40000 ALTER TABLE `yii_icms_auth_assignment` DISABLE KEYS */;

INSERT INTO `yii_icms_auth_assignment` (`item_name`, `user_id`, `created_at`)
VALUES
	('admin','2',1454589513),
	('developer','1',1454589283),
	('developer','4',1491829141),
	('developer','5',1478166465),
    ('developer','6',1454588790);

/*!40000 ALTER TABLE `yii_icms_auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_auth_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_auth_item`;

CREATE TABLE `yii_icms_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `yii_icms_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `yii_icms_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_auth_item` WRITE;
/*!40000 ALTER TABLE `yii_icms_auth_item` DISABLE KEYS */;

INSERT INTO `yii_icms_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
VALUES
	('admin',1,'Администратор',NULL,NULL,NULL,NULL),
	('developer',1,'Разработчик',NULL,NULL,NULL,NULL),
	('manager',1,'Менеджер',NULL,NULL,NULL,NULL),
	('user',1,'Пользователь',NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `yii_icms_auth_item` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_auth_item_child
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_auth_item_child`;

CREATE TABLE `yii_icms_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `yii_icms_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `yii_icms_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `yii_icms_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `yii_icms_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_auth_item_child` WRITE;
/*!40000 ALTER TABLE `yii_icms_auth_item_child` DISABLE KEYS */;

INSERT INTO `yii_icms_auth_item_child` (`parent`, `child`)
VALUES
	('developer','admin'),
	('admin','manager'),
	('admin','user');

/*!40000 ALTER TABLE `yii_icms_auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_auth_rule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_auth_rule`;

CREATE TABLE `yii_icms_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_banner
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_banner`;

CREATE TABLE `yii_icms_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `banner_group_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(500) NOT NULL DEFAULT '',
  `file` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_banner` WRITE;
/*!40000 ALTER TABLE `yii_icms_banner` DISABLE KEYS */;

INSERT INTO `yii_icms_banner` (`id`, `banner_group_id`, `name`, `link`, `file`, `sort`, `status`, `created_at`, `updated_at`, `type`, `width`, `height`)
VALUES
	(4,1,'Баннер 1','','banner_4.jpg',0,1,1454255873,1454255873,1,NULL,NULL),
	(5,1,'Баннер 2','','banner_5.jpg',0,1,1454255888,1454255888,1,NULL,NULL),
	(6,2,'Баннер','','banner_6.jpg',0,1,1454256826,1454256826,1,NULL,NULL);

/*!40000 ALTER TABLE `yii_icms_banner` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_banner_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_banner_group`;

CREATE TABLE `yii_icms_banner_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_banner_group` WRITE;
/*!40000 ALTER TABLE `yii_icms_banner_group` DISABLE KEYS */;

INSERT INTO `yii_icms_banner_group` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'Главная страница сверху',1453798608,1454255570),
	(2,'Главная страница снизу',1454255615,1454255615);

/*!40000 ALTER TABLE `yii_icms_banner_group` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog`;

CREATE TABLE `yii_icms_catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exchange_code` varchar(255) DEFAULT NULL,
  `catalog_categorie_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `article` varchar(255) NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `auto_url` int(11) DEFAULT NULL,
  `content` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `price_old` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `title_seo` text,
  `description_seo` text,
  `keywords_seo` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `is_popular` int(11) DEFAULT NULL,
  `quant` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_catalog_yii_icms_catalog_categorie_idx` (`catalog_categorie_id`),
  CONSTRAINT `fk_yii_icms_catalog_yii_icms_catalog_categorie` FOREIGN KEY (`catalog_categorie_id`) REFERENCES `yii_icms_catalog_categorie` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog` (`id`, `exchange_code`, `catalog_categorie_id`, `name`, `article`, `alias`, `auto_url`, `content`, `image`, `price`, `price_old`, `status`, `sort`, `title_seo`, `description_seo`, `keywords_seo`, `created_at`, `updated_at`, `is_popular`, `quant`)
VALUES
	(2,'2',1,'Кольцо 00001','','kolco_00001',1,'','catalog_2.jpg',10000,12000,1,100,'Товар Кольцо 00001  в категории Кольца- test!','цена 10000 ариткул  ','   ',1451121847,1518426793,1,NULL),
	(3,'3',1,'Кольцо 00000002','','kolco_00000002',1,'','',5000,NULL,1,100,'Товар Кольцо 00000002  в категории Кольца- te','цена 5000 ариткул  ','   ',1453117503,1518435972,1,NULL),
	(4,'4',1,'Без ску','','bez_sku',1,'',NULL,100,NULL,1,100,'Товар Без ску  в категории Кольца- test!','цена 100 ариткул  ','   ',1453117819,1467718098,1,NULL),
	(5,'5',2,'Бензопила','','benzopila',1,'',NULL,NULL,NULL,1,100,'','','',1454335276,1454402935,0,NULL),
	(7,'dee6e1a4-55bc-11d9-848a-00112f43529a',15,'Кондиционер ELEKTA','К-9881','Kondicioner_ELEKTA',NULL,NULL,'catalog_7.jpg',NULL,NULL,1,0,NULL,NULL,NULL,1455181095,1455280047,NULL,NULL),
	(8,'dee6e1a6-55bc-11d9-848a-00112f43529a',15,'Кондиционер FIRMSTAR 12М','К-980','Kondicioner_FIRMSTAR_12M',NULL,NULL,'catalog_8.jpg',NULL,NULL,1,0,NULL,NULL,NULL,1455181095,1455280047,NULL,NULL),
	(9,'dee6e1a8-55bc-11d9-848a-00112f43529a',15,'Кондиционер БК-2300','К-2300','Kondicioner_BK-2300',NULL,NULL,'catalog_9.jpg',NULL,NULL,1,0,NULL,NULL,NULL,1455181095,1455280047,NULL,NULL);

/*!40000 ALTER TABLE `yii_icms_catalog` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_categorie
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_categorie`;

CREATE TABLE `yii_icms_catalog_categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exchange_code` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `title_seo` text,
  `description_seo` text,
  `keywords_seo` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `content` text,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `auto_url` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_categorie` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_categorie` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_categorie` (`id`, `exchange_code`, `pid`, `name`, `alias`, `title_seo`, `description_seo`, `keywords_seo`, `created_at`, `updated_at`, `sort`, `content`, `image`, `status`, `auto_url`)
VALUES
	(1,NULL,0,'Кольца','kolca','','','',1448285224,1499682411,0,'<p>Описание</p>\r\n','',1,1),
	(2,NULL,0,'Пирсинг','pirsing','','','',1448345206,1466430883,0,'','',1,1),
	(3,NULL,0,'Серьги','sergi','','','',1448630574,1466430883,0,'','',1,1),
	(5,NULL,0,'Подвески','podveski','','','',1451120637,1451120637,0,'',NULL,1,1),
	(6,NULL,0,'Броши','broshi','','','',1451120748,1451120748,0,'',NULL,1,1),
	(7,NULL,0,'Колье','kole','','','',1451120760,1451120760,0,'',NULL,1,1),
	(8,NULL,0,'Кресты','kresty','','','',1451120774,1499682235,0,'',NULL,1,1),
	(14,'4bda4442-08dd-49c3-ae90-587e45ca65ce',0,'Классификатор (Основной каталог товаров)','klassifikator-osnovnoj-katalog-tovarov','test генератора Классификатор (Основной каталог товаров)',' Классификатор (Основной каталог товаров) Классификатор (Основной каталог товаров) Классификатор (Основной каталог товаров) Классификатор (Основной каталог товаров)asefasef Классификатор (Основной каталог товаров)',' Классификатор (Основной каталог товаров) Классификатор (Основной каталог товаров)asefasefasefasef',1455176524,1456826323,NULL,'',NULL,2,1),
	(15,'08170e7d-73a3-11df-b338-0011955cba6b',14,'Кондиционеры','kondicionery1','test генератора Кондиционеры',' Кондиционеры Кондиционеры Кондиционеры Кондиционерыasefasef Кондиционеры','aserfvafsrfvasr Кондиционеры Кондиционерыsaefasef Кондиционеры Кондиционерыasefase Кондиционеры Кондиционерыasefasef Кондиционеры',1455176524,1456826323,NULL,NULL,NULL,1,1);

/*!40000 ALTER TABLE `yii_icms_catalog_categorie` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_categotie_props
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_categotie_props`;

CREATE TABLE `yii_icms_catalog_categotie_props` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_categorie_id` int(11) NOT NULL,
  `props_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_catalog_categotie_props_yii_icms_catalog_catego_idx` (`catalog_categorie_id`),
  KEY `fk_yii_icms_catalog_categotie_props_yii_icms_props1_idx` (`props_id`),
  CONSTRAINT `fk_yii_icms_catalog_categotie_props_yii_icms_catalog_categorie1` FOREIGN KEY (`catalog_categorie_id`) REFERENCES `yii_icms_catalog_categorie` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_yii_icms_catalog_categotie_props_yii_icms_props1` FOREIGN KEY (`props_id`) REFERENCES `yii_icms_props` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_categotie_props` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_categotie_props` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_categotie_props` (`id`, `catalog_categorie_id`, `props_id`)
VALUES
	(1,1,21),
	(2,1,20),
	(3,1,19),
	(4,1,18),
	(5,1,17),
	(6,1,16),
	(7,1,15),
	(8,1,22),
	(9,15,25),
	(10,15,26);

/*!40000 ALTER TABLE `yii_icms_catalog_categotie_props` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_delivery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_delivery`;

CREATE TABLE `yii_icms_catalog_delivery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `price` int(11) NOT NULL,
  `have_address` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `is_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_delivery` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_delivery` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_delivery` (`id`, `name`, `content`, `price`, `have_address`, `status`, `sort`, `is_default`)
VALUES
	(1,'Самовывоз из магазина','',0,0,1,0,1),
	(2,'Доставка по городу','',200,1,1,0,0),
	(3,'Экспресс курьером','',400,1,1,0,0);

/*!40000 ALTER TABLE `yii_icms_catalog_delivery` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_delivery_gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_delivery_gallery`;

CREATE TABLE `yii_icms_catalog_delivery_gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_delivery_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_catalog_gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_gallery`;

CREATE TABLE `yii_icms_catalog_gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_catalog_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_order`;

CREATE TABLE `yii_icms_catalog_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` int(11) NOT NULL,
  `total_count` int(11) NOT NULL,
  `catalog_order_status_id` int(11) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `g_date` datetime DEFAULT NULL,
  `catalog_pay_id` int(11) NOT NULL,
  `catalog_delivery_id` int(11) NOT NULL,
  `delivery_price` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone` varchar(255) DEFAULT NULL,
  `user_city` varchar(255) DEFAULT NULL,
  `user_street` varchar(255) DEFAULT NULL,
  `user_home` varchar(100) DEFAULT NULL,
  `comment` text,
  `export` int(11) DEFAULT NULL,
  `export_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_catalog_order_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_order_items`;

CREATE TABLE `yii_icms_catalog_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_order_id` int(11) NOT NULL,
  `catalog_id` int(11) DEFAULT NULL,
  `catalog_sku_id` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `quant` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `info` text,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_catalog_order_items_yii_icms_catalog_order1_idx` (`catalog_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_catalog_order_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_order_status`;

CREATE TABLE `yii_icms_catalog_order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `can_cancel` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_order_status` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_order_status` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_order_status` (`id`, `name`, `can_cancel`)
VALUES
	(1,'Принят',1),
	(2,'Отменён пользователем',0),
	(3,'Создаётся пользователем',0),
	(4,'Отменен магазином',0),
	(5,'В доставке',0);

/*!40000 ALTER TABLE `yii_icms_catalog_order_status` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_pay
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_pay`;

CREATE TABLE `yii_icms_catalog_pay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` int(1) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_default` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_pay` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_pay` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_pay` (`id`, `name`, `content`, `status`, `sort`, `is_default`)
VALUES
	(1,'Наличными в магазине','',1,0,0),
	(2,'Банковской картой в магазине','',1,0,1);

/*!40000 ALTER TABLE `yii_icms_catalog_pay` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_pay_gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_pay_gallery`;

CREATE TABLE `yii_icms_catalog_pay_gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_pay_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_catalog_props
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_props`;

CREATE TABLE `yii_icms_catalog_props` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `props_id` int(11) NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_catalog_props_yii_icms_props1_idx` (`props_id`),
  KEY `fk_yii_icms_catalog_props_yii_icms_catalog1_idx` (`catalog_id`),
  CONSTRAINT `fk_yii_icms_catalog_props_yii_icms_catalog1` FOREIGN KEY (`catalog_id`) REFERENCES `yii_icms_catalog` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_yii_icms_catalog_props_yii_icms_props1` FOREIGN KEY (`props_id`) REFERENCES `yii_icms_props` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_props` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_props` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_props` (`id`, `catalog_id`, `props_id`, `value`)
VALUES
	(64,7,25,'29'),
	(65,7,26,'31'),
	(66,8,25,'30'),
	(67,8,26,'31'),
	(72,2,18,'23'),
	(73,3,18,'232');

/*!40000 ALTER TABLE `yii_icms_catalog_props` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_related
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_related`;

CREATE TABLE `yii_icms_catalog_related` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `catalog_related_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_related` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_related` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_related` (`id`, `catalog_id`, `catalog_related_id`)
VALUES
	(1,0,0),
	(3,0,0),
	(4,0,0),
	(5,0,0),
	(6,0,0),
	(7,0,0),
	(8,5,2),
	(9,0,0),
	(10,2,3);

/*!40000 ALTER TABLE `yii_icms_catalog_related` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_sku
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_sku`;

CREATE TABLE `yii_icms_catalog_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `article` varchar(45) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_catalog_sku_yii_icms_catalog1_idx` (`catalog_id`),
  CONSTRAINT `fk_yii_icms_catalog_sku_yii_icms_catalog1` FOREIGN KEY (`catalog_id`) REFERENCES `yii_icms_catalog` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_sku` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_sku` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_sku` (`id`, `catalog_id`, `article`, `price`, `status`)
VALUES
	(305,2,'2522',0,1),
	(306,2,'2622',0,1),
	(307,2,'2722',0,1),
	(308,2,'2523',0,1),
	(309,2,'2623',0,1),
	(310,2,'2723',0,1),
	(311,2,'2524',0,1),
	(312,2,'2624',0,1),
	(313,2,'2724',0,1),
	(314,2,'2525',0,1),
	(315,2,'2625',0,1),
	(316,2,'2725',0,1),
	(317,3,'3522',0,1),
	(318,3,'3622',0,1),
	(319,3,'3722',0,1),
	(320,3,'3523',0,1),
	(321,3,'3623',0,1),
	(322,3,'3723',0,1),
	(323,3,'3524',0,1),
	(324,3,'3624',0,1),
	(325,3,'3724',0,1),
	(326,3,'3525',0,1),
	(327,3,'3625',0,1),
	(328,3,'3725',0,1);

/*!40000 ALTER TABLE `yii_icms_catalog_sku` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_catalog_sku_gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_sku_gallery`;

CREATE TABLE `yii_icms_catalog_sku_gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_sku_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `is_main` int(1) unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_catalog_sku_props_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_catalog_sku_props_values`;

CREATE TABLE `yii_icms_catalog_sku_props_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_sku_id` int(11) NOT NULL,
  `props_id` int(11) NOT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_catalog_sku_props_values_yii_icms_props1_idx` (`props_id`),
  KEY `catalog_sku_id` (`catalog_sku_id`),
  CONSTRAINT `fk_yii_icms_catalog_sku_props_values_yii_icms_props1` FOREIGN KEY (`props_id`) REFERENCES `yii_icms_props` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_yii_icms_catalog_sku_props_values_yii_icms_sku1` FOREIGN KEY (`catalog_sku_id`) REFERENCES `yii_icms_catalog_sku` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_catalog_sku_props_values` WRITE;
/*!40000 ALTER TABLE `yii_icms_catalog_sku_props_values` DISABLE KEYS */;

INSERT INTO `yii_icms_catalog_sku_props_values` (`id`, `catalog_sku_id`, `props_id`, `value`)
VALUES
	(625,305,16,'5'),
	(626,305,22,'22'),
	(627,306,16,'6'),
	(628,306,22,'22'),
	(629,307,16,'7'),
	(630,307,22,'22'),
	(631,308,16,'5'),
	(632,308,22,'23'),
	(633,309,16,'6'),
	(634,309,22,'23'),
	(635,310,16,'7'),
	(636,310,22,'23'),
	(637,311,16,'5'),
	(638,311,22,'24'),
	(639,312,16,'6'),
	(640,312,22,'24'),
	(641,313,16,'7'),
	(642,313,22,'24'),
	(643,314,16,'5'),
	(644,314,22,'25'),
	(645,315,16,'6'),
	(646,315,22,'25'),
	(647,316,16,'7'),
	(648,316,22,'25'),
	(649,317,16,'5'),
	(650,317,22,'22'),
	(651,318,16,'6'),
	(652,318,22,'22'),
	(653,319,16,'7'),
	(654,319,22,'22'),
	(655,320,16,'5'),
	(656,320,22,'23'),
	(657,321,16,'6'),
	(658,321,22,'23'),
	(659,322,16,'7'),
	(660,322,22,'23'),
	(661,323,16,'5'),
	(662,323,22,'24'),
	(663,324,16,'6'),
	(664,324,22,'24'),
	(665,325,16,'7'),
	(666,325,22,'24'),
	(667,326,16,'5'),
	(668,326,22,'25'),
	(669,327,16,'6'),
	(670,327,22,'25'),
	(671,328,16,'7'),
	(672,328,22,'25');

/*!40000 ALTER TABLE `yii_icms_catalog_sku_props_values` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_content`;

CREATE TABLE `yii_icms_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_categorie_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL,
  `anons` text NOT NULL,
  `content` text NOT NULL,
  `g_date` datetime NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title_seo` text NOT NULL,
  `keywords_seo` text NOT NULL,
  `description_seo` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `author_link` text NOT NULL,
  `status` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `count_views` int(11) NOT NULL DEFAULT '0',
  `count_comments` int(11) NOT NULL DEFAULT '0',
  `auto_alias` int(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_content` WRITE;
/*!40000 ALTER TABLE `yii_icms_content` DISABLE KEYS */;

INSERT INTO `yii_icms_content` (`id`, `content_categorie_id`, `name`, `alias`, `anons`, `content`, `g_date`, `image`, `title_seo`, `keywords_seo`, `description_seo`, `author`, `author_link`, `status`, `sort`, `created_at`, `updated_at`, `count_views`, `count_comments`, `auto_alias`)
VALUES
	(1,1,'Место обучения кадров','mesto_obucheniya_kadrov','Не следует, однако забывать, что рамки и место обучения кадров способствует подготовки и реализации существенных финансовых и административных условий. Разнообразный и богатый опыт дальнейшее развитие различных форм деятельности играет важную роль в формировании форм развития.','<p>Не следует, однако забывать, что рамки и место обучения кадров способствует подготовки и реализации существенных финансовых и административных условий. Разнообразный и богатый опыт дальнейшее развитие различных форм деятельности играет важную роль в формировании форм развития.</p>\r\n\r\n<p>Задача организации, в особенности же консультация с широким активом позволяет оценить значение новых предложений. Товарищи! консультация с широким активом позволяет выполнять важные задания по разработке позиций, занимаемых участниками в отношении поставленных задач. Идейные соображения высшего порядка, а также новая модель организационной деятельности влечет за собой процесс внедрения и модернизации существенных финансовых и административных условий.</p>\r\n','2015-06-14 00:00:00','','1','3','23','','',1,3,1434887840,1488185235,0,0,1),
	(10,1,'Идейные соображения высшего порядка','ideynye_soobrazheniya_vysshego_poryadka','Идейные соображения высшего порядка, а также рамки и место обучения кадров представляет собой интересный эксперимент проверки системы обучения кадров, соответствует насущным потребностям.','<p>Идейные соображения высшего порядка, а также рамки и место обучения кадров представляет собой интересный эксперимент проверки системы обучения кадров, соответствует насущным потребностям. Значимость этих проблем настолько очевидна, что новая модель организационной деятельности способствует подготовки и реализации систем массового участия.</p>\r\n\r\n<p>С другой стороны реализация намеченных плановых заданий обеспечивает широкому кругу (специалистов) участие в формировании соответствующий условий активизации. Повседневная практика показывает, что постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия.</p>\r\n','2015-01-01 12:00:00','','123','3213123','112','','',1,222,1435229308,1454309588,0,0,1),
	(11,2,'saetsaet','asegsaeg','asegseg','asegsaeg','2015-06-26 17:21:00','','','','','asges','asegseg',1,1,1435242144,1435244065,0,0,1),
	(12,1,'Дальнейшее развитие ','dalneyshee_razvitie','Таким образом дальнейшее развитие различных форм деятельности в значительной степени обуславливает создание соответствующий условий активизации.','<p>Таким образом дальнейшее развитие различных форм деятельности в значительной степени обуславливает создание соответствующий условий активизации. Повседневная практика показывает, что начало повседневной работы по формированию позиции влечет за собой процесс внедрения и модернизации соответствующий условий активизации. С другой стороны новая модель организационной деятельности способствует подготовки и реализации существенных финансовых и административных условий.</p>\r\n\r\n<p>Товарищи! начало повседневной работы по формированию позиции требуют определения и уточнения форм развития. Задача организации, в особенности же рамки и место обучения кадров влечет за собой процесс внедрения и модернизации системы обучения кадров, соответствует насущным потребностям. Товарищи! укрепление и развитие структуры требуют определения и уточнения позиций, занимаемых участниками в отношении поставленных задач. Идейные соображения высшего порядка, а также консультация с широким активом в значительной степени обуславливает создание форм развития.</p>\r\n','2016-02-01 07:53:00','','','','','','',1,0,1454309621,1454309639,0,0,1),
	(13,1,'Постоянный количественный рост','postoyannyy_kolichestvennyy_rost','Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия','<p>Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности позволяет оценить значение систем массового участия. Товарищи! реализация намеченных плановых заданий обеспечивает широкому кругу (специалистов) участие в формировании форм развития. Товарищи! начало повседневной работы по формированию позиции позволяет оценить значение позиций, занимаемых участниками в отношении поставленных задач. Не следует, однако забывать, что новая модель организационной деятельности требуют определения и уточнения соответствующий условий активизации.</p>\r\n\r\n<p>Идейные соображения высшего порядка, а также консультация с широким активом позволяет выполнять важные задания по разработке форм развития. Товарищи! реализация намеченных плановых заданий требуют определения и уточнения систем массового участия. Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности представляет собой интересный эксперимент проверки направлений прогрессивного развития. Задача организации, в особенности же дальнейшее развитие различных форм деятельности позволяет оценить значение форм развития. Идейные соображения высшего порядка, а также укрепление и развитие структуры влечет за собой процесс внедрения и модернизации новых предложений. Значимость этих проблем настолько очевидна, что постоянный количественный рост и сфера нашей активности требуют от нас анализа системы обучения кадров, соответствует насущным потребностям.</p>\r\n','2016-02-01 07:54:00','','','','','','',1,0,1454309673,1454309673,0,0,1);

/*!40000 ALTER TABLE `yii_icms_content` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_content_categorie
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_content_categorie`;

CREATE TABLE `yii_icms_content_categorie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `title_seo` text NOT NULL,
  `keywords_seo` text NOT NULL,
  `description_seo` text NOT NULL,
  `status` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `in_preview` int(11) NOT NULL,
  `in_list` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_content_categorie` WRITE;
/*!40000 ALTER TABLE `yii_icms_content_categorie` DISABLE KEYS */;

INSERT INTO `yii_icms_content_categorie` (`id`, `name`, `title_seo`, `keywords_seo`, `description_seo`, `status`, `sort`, `created_at`, `updated_at`, `in_preview`, `in_list`)
VALUES
	(1,'Новости','11123','312123','123',1,2,1432902795,1454309603,2,5),
	(2,'Статьи','','','',1,1,1432902795,1447912903,0,0);

/*!40000 ALTER TABLE `yii_icms_content_categorie` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_feedbacks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_feedbacks`;

CREATE TABLE `yii_icms_feedbacks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` int(1) unsigned NOT NULL,
  `created_date` datetime NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_gallery`;

CREATE TABLE `yii_icms_gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gallery_categorie_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_gallery` WRITE;
/*!40000 ALTER TABLE `yii_icms_gallery` DISABLE KEYS */;

INSERT INTO `yii_icms_gallery` (`id`, `gallery_categorie_id`, `name`, `image`, `status`, `sort`, `created_at`, `updated_at`)
VALUES
	(1,1,'tes',NULL,1,0,0,0),
	(2,2,'tes',NULL,1,0,0,0);

/*!40000 ALTER TABLE `yii_icms_gallery` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_gallery_categorie
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_gallery_categorie`;

CREATE TABLE `yii_icms_gallery_categorie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `title_seo` text NOT NULL,
  `description_seo` text NOT NULL,
  `keywords_seo` text NOT NULL,
  `status` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_gallery_categorie` WRITE;
/*!40000 ALTER TABLE `yii_icms_gallery_categorie` DISABLE KEYS */;

INSERT INTO `yii_icms_gallery_categorie` (`id`, `pid`, `name`, `image`, `content`, `title_seo`, `description_seo`, `keywords_seo`, `status`, `sort`, `created_at`, `updated_at`)
VALUES
	(1,0,'Галерея 1','','','','','',1,0,0,1488264212),
	(2,1,'Галерея 2','','','','','',1,123,1435742763,1435742763),
	(3,2,'Галерея 3','','','','','',1,90,1435742966,1488205333);

/*!40000 ALTER TABLE `yii_icms_gallery_categorie` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_keys
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_keys`;

CREATE TABLE `yii_icms_keys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_keys` WRITE;
/*!40000 ALTER TABLE `yii_icms_keys` DISABLE KEYS */;

INSERT INTO `yii_icms_keys` (`id`, `pid`, `name`, `value`, `created_at`, `updated_at`)
VALUES
	(1,5,'yandex_metrika_client_id','',1447659000,1454580964),
	(2,5,'yandex_metrika_client_secret','',1447659404,1454580965),
	(3,5,'yandex_metrika_access_token','',1447659504,1454580965),
	(4,5,'yandex_metrika_counter_id','',1447659604,1454580949),
	(5,14,'Яндекс метрика','',1447669499,1493299949),
	(7,0,'email\'ы для рассылки','',1454067316,1454067316),
	(8,7,'jobabc@mail.ru','jobabc@mail.ru',1454067343,1454067343),
	(9,7,'blaga@aisol.ru','blaga@aisol.ru',1454067355,1454067358),
	(10,7,'suhenko@aisol.ru','suhenko@aisol.ru',1454067410,1454067410),
	(11,0,'emailFrom','noanswer@example.com',1454068061,1454069762),
	(12,0,'Коды метрик (для добавления через модуль SEO)','',1455969468,1493300642),
	(13,12,'yandex','',1455969518,1455973716),
	(14,0,'Сервисы Yandex','',1493299940,1493299940);

/*!40000 ALTER TABLE `yii_icms_keys` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_log_console
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_log_console`;

CREATE TABLE `yii_icms_log_console` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL DEFAULT '0' COMMENT 'От кого выполнен',
  `controller` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Контроллер',
  `action` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Действие',
  `command` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Команда',
  `start` datetime DEFAULT NULL COMMENT 'Запущен',
  `end` datetime DEFAULT NULL COMMENT 'Закончен',
  `exit_code` int(1) unsigned DEFAULT NULL COMMENT 'Код завершения',
  `created_at` int(11) unsigned DEFAULT NULL COMMENT 'Дата создания',
  `updated_at` int(11) unsigned DEFAULT NULL COMMENT 'Дата изменения',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_map_marks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_map_marks`;

CREATE TABLE `yii_icms_map_marks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `map_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `coordinate_x` double unsigned NOT NULL,
  `coordinate_y` double unsigned NOT NULL,
  `image_x` int(11) DEFAULT NULL,
  `image_y` int(11) DEFAULT NULL,
  `image_width` int(11) DEFAULT NULL,
  `image_height` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_map_marks` WRITE;
/*!40000 ALTER TABLE `yii_icms_map_marks` DISABLE KEYS */;

INSERT INTO `yii_icms_map_marks` (`id`, `map_id`, `name`, `content`, `image`, `coordinate_x`, `coordinate_y`, `image_x`, `image_y`, `image_width`, `image_height`, `status`, `created_at`, `updated_at`, `color`)
VALUES
	(2,1,'testmark2','srgsnfhm4th','marks_2.png',57.765582155241,40.916577070561,24,70,48,76,1,1443174897,1488264597,'1'),
	(3,1,'testasefs','asge','',57.765294377326,40.918106741938,0,0,0,0,1,1481633660,1491920719,'');

/*!40000 ALTER TABLE `yii_icms_map_marks` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_maps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_maps`;

CREATE TABLE `yii_icms_maps` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `mark_count` int(10) unsigned NOT NULL,
  `center_x` double unsigned NOT NULL,
  `center_y` double unsigned NOT NULL,
  `zoom` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `mark_default_color` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_maps` WRITE;
/*!40000 ALTER TABLE `yii_icms_maps` DISABLE KEYS */;

INSERT INTO `yii_icms_maps` (`id`, `name`, `content`, `mark_count`, `center_x`, `center_y`, `zoom`, `status`, `created_at`, `updated_at`, `mark_default_color`)
VALUES
	(1,'Карта','asegseg\r\nsagsar',2,57.765242774878,40.917602486643,17,1,1443170745,1491920740,'');

/*!40000 ALTER TABLE `yii_icms_maps` ENABLE KEYS */;
UNLOCK TABLES;

# Дамп таблицы yii_icms_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_modules`;

CREATE TABLE `yii_icms_modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `route` varchar(255) NOT NULL DEFAULT '',
  `tree_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_modules` WRITE;
/*!40000 ALTER TABLE `yii_icms_modules` DISABLE KEYS */;

INSERT INTO `yii_icms_modules` (`id`, `name`, `url`, `route`, `tree_id`)
VALUES
	(1,'Каталог','catalog/','site/catalog',3),
	(2,'Новости','novosti/','site/news',2),
	(4,'Выход','logout/','account/logout',NULL),
	(5,'Авторизация','account/login/','account/login',6),
	(6,'Восстановление пароля','account/lost_password/','account/lost_password',8),
	(7,'Галерея','galereya/','site/gallery',12),
	(8,'Личный кабинет','account/','account/account',4),
	(9,'Корзина','catalog/basket/','basket/basket',10),
	(12,'Поиск по каталогу','catalog/search/','site/catalog_search',11),
	(13,'История','account/history/','account/account_history',5),
	(14,'Список желаний','account/spisok_zhelaniy/','account/wishlist',13),
	(15,'Карта сайта','sitemap/','site/sitemap',14);

/*!40000 ALTER TABLE `yii_icms_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_parameters
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_parameters`;

CREATE TABLE `yii_icms_parameters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text,
  `type` int(1) unsigned NOT NULL DEFAULT '1',
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_parameters` WRITE;
/*!40000 ALTER TABLE `yii_icms_parameters` DISABLE KEYS */;

INSERT INTO `yii_icms_parameters` (`id`, `name`, `value`, `type`, `created_at`, `updated_at`)
VALUES
	(1,'email\'ы для получения уведомлений','',5,1454072325,1488877216),
	(2,'Логотип','image_2.png',3,1454575464,1454575475);

/*!40000 ALTER TABLE `yii_icms_parameters` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_parameters_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_parameters_values`;

CREATE TABLE `yii_icms_parameters_values` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parameter_id` int(11) NOT NULL,
  `value` text,
  `sort` int(11) NOT NULL DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_parameters_values` WRITE;
/*!40000 ALTER TABLE `yii_icms_parameters_values` DISABLE KEYS */;

INSERT INTO `yii_icms_parameters_values` (`id`, `parameter_id`, `value`, `sort`, `created_at`, `updated_at`)
VALUES
	(1,1,'blaga@aisol.ru',0,1488877224,1488877224),
	(2,1,'test@aisol.ru',0,1488877230,1488877230);

/*!40000 ALTER TABLE `yii_icms_parameters_values` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_prop_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_prop_type`;

CREATE TABLE `yii_icms_prop_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_multy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_prop_type` WRITE;
/*!40000 ALTER TABLE `yii_icms_prop_type` DISABLE KEYS */;

INSERT INTO `yii_icms_prop_type` (`id`, `name`, `is_multy`)
VALUES
	(1,'Строка',0),
	(2,'Целое число',0),
	(3,'Дробное число',0),
	(4,'Список',1),
	(7,'Логический тип (bool)',0);

/*!40000 ALTER TABLE `yii_icms_prop_type` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_prop_type_list
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_prop_type_list`;

CREATE TABLE `yii_icms_prop_type_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_prop_type_list` WRITE;
/*!40000 ALTER TABLE `yii_icms_prop_type_list` DISABLE KEYS */;

INSERT INTO `yii_icms_prop_type_list` (`id`, `name`)
VALUES
	(1,'Select'),
	(2,'Checkbox'),
	(3,'RadioGroup');

/*!40000 ALTER TABLE `yii_icms_prop_type_list` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_props
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_props`;

CREATE TABLE `yii_icms_props` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exchange_code` varchar(255) DEFAULT NULL,
  `prop_type_id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `is_sku` int(11) DEFAULT NULL,
  `is_filter` int(11) DEFAULT NULL,
  `is_most` int(11) NOT NULL,
  `list_type` int(11) NOT NULL,
  `prop_type_list_id` int(11) NOT NULL,
  `props_groups_id` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_props_yii_icms_prop_type1_idx` (`prop_type_id`),
  CONSTRAINT `fk_yii_icms_props_yii_icms_prop_type1` FOREIGN KEY (`prop_type_id`) REFERENCES `yii_icms_prop_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_props` WRITE;
/*!40000 ALTER TABLE `yii_icms_props` DISABLE KEYS */;

INSERT INTO `yii_icms_props` (`id`, `exchange_code`, `prop_type_id`, `alias`, `name`, `sort`, `is_sku`, `is_filter`, `is_most`, `list_type`, `prop_type_list_id`, `props_groups_id`, `created_at`, `updated_at`)
VALUES
	(15,NULL,4,'metal','Металл',100,0,1,1,0,1,0,1451121069,1451121069),
	(16,NULL,4,'vstavka','Вставка',100,1,1,0,0,2,0,1451121225,1453021705),
	(17,NULL,4,'dlya_kogo','Для кого',100,0,1,1,0,1,0,1451121309,1457600158),
	(18,NULL,3,'weight','Вес',100,0,1,1,0,0,0,1451121583,1451121583),
	(19,NULL,1,'length','Длина',100,0,0,0,0,0,0,1451121620,1451121620),
	(20,NULL,1,'width','Ширина',100,0,0,0,0,0,0,1451121660,1451121660),
	(21,NULL,4,'proba','Проба',100,0,0,0,0,1,0,1451121717,1451121717),
	(22,NULL,4,'size_ring','Размер колец',100,1,1,0,0,3,0,1451122105,1496835250),
	(25,'22b0ced1-cb40-11e5-aa55-08002718b619',4,'zhirnost_kondicionera','Жирность кондиционера',0,0,0,0,0,1,0,1455178194,1455280047),
	(26,'22b0ced3-cb40-11e5-aa55-08002718b619',4,'zhirnost_vozduha','Жирность воздуха',0,0,0,0,0,1,0,1455178194,1455280047);

/*!40000 ALTER TABLE `yii_icms_props` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_props_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_props_groups`;

CREATE TABLE `yii_icms_props_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_props_values
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_props_values`;

CREATE TABLE `yii_icms_props_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `props_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `exchange_code` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_yii_icms_props_values_yii_icms_props1_idx` (`props_id`),
  CONSTRAINT `fk_yii_icms_props_values_yii_icms_props1` FOREIGN KEY (`props_id`) REFERENCES `yii_icms_props` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_props_values` WRITE;
/*!40000 ALTER TABLE `yii_icms_props_values` DISABLE KEYS */;

INSERT INTO `yii_icms_props_values` (`id`, `props_id`, `name`, `sort`, `created_at`, `updated_at`, `exchange_code`, `image`)
VALUES
	(1,15,'Красное золото',0,1451121081,1451121081,NULL,NULL),
	(2,15,'Желтое золото',0,1451121089,1451121089,NULL,NULL),
	(3,15,'Белое золото',0,1451121108,1451121108,NULL,NULL),
	(4,15,'Серебро',0,1451121117,1451121117,NULL,NULL),
	(5,16,'Бриллианты',0,1451121228,1451121228,NULL,NULL),
	(6,16,'Фианиты',0,1451121234,1451121234,NULL,NULL),
	(7,16,'Без вставок',0,1451121241,1451121241,NULL,NULL),
	(8,17,'Для женщин',1,1451121318,1457599562,NULL,''),
	(9,17,'Для мужчин',0,1451121382,1451121382,NULL,NULL),
	(10,17,'Для детей',10,1451121389,1457603550,NULL,'props_value_10.png'),
	(11,21,'585',0,1451121722,1451121722,NULL,NULL),
	(12,21,'750',0,1451121729,1451121729,NULL,NULL),
	(13,21,'958',0,1451121740,1451121740,NULL,NULL),
	(14,21,'999',0,1451121744,1451121744,NULL,NULL),
	(15,22,'15',0,1451122124,1451122124,NULL,NULL),
	(16,22,'15.5',0,1451122130,1451122130,NULL,NULL),
	(17,22,'16',0,1451122134,1451122134,NULL,NULL),
	(18,22,'16.5',0,1451122140,1451122140,NULL,NULL),
	(19,22,'17',0,1451122142,1451122142,NULL,NULL),
	(20,22,'17.5',0,1451122147,1451122147,NULL,NULL),
	(21,22,'18',0,1451122157,1451122157,NULL,NULL),
	(22,22,'18.5',0,1451122160,1451122160,NULL,NULL),
	(23,22,'19',0,1451122164,1451122164,NULL,NULL),
	(24,22,'19.5',0,1451122168,1451122168,NULL,NULL),
	(25,22,'20',0,1451122171,1451122171,NULL,NULL),
	(29,25,'0.5',NULL,1455178194,1455280047,'22b0ced4-cb40-11e5-aa55-08002718b619',NULL),
	(30,25,'1.5',NULL,1455178194,1455280047,'22b0ced6-cb40-11e5-aa55-08002718b619',NULL),
	(31,26,'1.3',NULL,1455178194,1455280047,'22b0ced5-cb40-11e5-aa55-08002718b619',NULL);

/*!40000 ALTER TABLE `yii_icms_props_values` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_slide
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_slide`;

CREATE TABLE `yii_icms_slide` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slider_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `link` varchar(500) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_slide` WRITE;
/*!40000 ALTER TABLE `yii_icms_slide` DISABLE KEYS */;

INSERT INTO `yii_icms_slide` (`id`, `slider_id`, `name`, `content`, `link`, `image`, `sort`, `status`, `created_at`, `updated_at`)
VALUES
	(2,1,'WE ARE UNIFY CREATIVE TECHNOLOGY COMPANY','<p style=\"text-align:center\">Creative freedom matters user experience.<br />\r\nWe minimize the gap between technology and its audience.</p>\r\n','#','slide_2.jpg',0,1,1451126506,1454056296),
	(3,1,'Quality Service & Support','<p style=\"text-align:center\">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>\r\n\r\n<p style=\"text-align:center\">Lorem Ipsum has been the industry&#39;s</p>\r\n','','slide_3.jpg',0,1,1451132154,1454056353),
	(4,2,'слайд 1','','','slide_4.png',0,1,1454258018,1454258018),
	(5,2,'слайд2','','','slide_5.png',0,1,1454258667,1454258667),
	(6,2,'слайд3','','','slide_6.png',0,1,1454258680,1454258680),
	(7,2,'слайд4','','','slide_7.png',0,1,1454258693,1454258693),
	(8,2,'слайд7','','','slide_8.png',0,1,1454258706,1454258706),
	(9,2,'слайд6','','','slide_9.png',0,1,1454258719,1454258719),
	(10,2,'слайд5','','','slide_10.png',0,1,1454258741,1454258741),
	(11,3,'1','','','slide_11.jpg',0,1,1455603424,1455603424),
	(12,3,'2','','','slide_12.jpg',0,1,1455603445,1455603445);

/*!40000 ALTER TABLE `yii_icms_slide` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_slider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_slider`;

CREATE TABLE `yii_icms_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_slider` WRITE;
/*!40000 ALTER TABLE `yii_icms_slider` DISABLE KEYS */;

INSERT INTO `yii_icms_slider` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1,'Слайдер для лендинга главный',1451125802,1451125805),
	(2,'Слайдер спонсоры и бренды',1454257984,1454257984),
	(3,'Слайдер в списке категорий',1455603364,1455603364);

/*!40000 ALTER TABLE `yii_icms_slider` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_tree
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_tree`;

CREATE TABLE `yii_icms_tree` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `main_id` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `auto_url` int(1) NOT NULL DEFAULT '1',
  `name_menu` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `h1_seo` varchar(255) NOT NULL DEFAULT '',
  `title_seo` text NOT NULL,
  `description_seo` text NOT NULL,
  `keywords_seo` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `in_menu_bottom` int(11) NOT NULL,
  `in_menu` int(1) NOT NULL,
  `in_map` int(1) NOT NULL,
  `nofollow` int(1) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_safe` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_tree` WRITE;
/*!40000 ALTER TABLE `yii_icms_tree` DISABLE KEYS */;

INSERT INTO `yii_icms_tree` (`id`, `main_id`, `pid`, `level`, `name`, `url`, `auto_url`, `name_menu`, `content`, `h1_seo`, `title_seo`, `description_seo`, `keywords_seo`, `image`, `in_menu_bottom`, `in_menu`, `in_map`, `nofollow`, `created_at`, `updated_at`, `sort`, `status`, `is_safe`)
VALUES
	(1,0,0,0,'','/',0,'Главная','<p>Разнообразный и богатый опыт постоянный количественный рост и сфера нашей активности в значительной степени обуславливает создание соответствующий условий активизации. Таким образом консультация с широким активом требуют определения и уточнения соответствующий условий активизации. Равным образом реализация намеченных плановых заданий влечет за собой процесс внедрения и модернизации системы обучения кадров, соответствует насущным потребностям. Не следует, однако забывать, что начало повседневной работы по формированию позиции играет важную роль в формировании системы обучения кадров, соответствует насущным потребностям. Не следует, однако забывать, что реализация намеченных плановых заданий играет важную роль в формировании системы обучения кадров, соответствует насущным потребностям.</p>\r\n\r\n<p>Идейные соображения высшего порядка, а также постоянный количественный рост и сфера нашей активности в значительной степени обуславливает создание новых предложений. Таким образом постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение модели развития. Идейные соображения высшего порядка, а также сложившаяся структура организации в значительной степени обуславливает создание новых предложений. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры представляет собой интересный эксперимент проверки существенных финансовых и административных условий.</p>\r\n\r\n<p>Не следует, однако забывать, что сложившаяся структура организации представляет собой интересный эксперимент проверки систем массового участия. С другой стороны начало повседневной работы по формированию позиции требуют определения и уточнения дальнейших направлений развития. С другой стороны постоянный количественный рост и сфера нашей активности представляет собой интересный эксперимент проверки направлений прогрессивного развития.</p>\r\n\r\n<p>{{map::1}}</p>\r\n','Главная','','','','',1,1,1,0,2147483647,1507102993,-100,1,1),
	(2,1,1,1,'novosti','/novosti',0,'Новости','','Волшебные новости','','','','',1,1,1,0,2147483647,1498543080,3,1,1),
	(3,1,1,1,'catalog','/catalog',0,'Каталог','','Каталог волшебный','','','','',1,1,1,0,2147483647,1496737601,4,1,1),
	(4,1,1,1,'account','/account',0,'Аккаунт','','','','','','',0,0,1,0,1453201349,1492172790,0,1,1),
	(5,4,4,2,'history','/account/history',0,'История','','','','','','',0,0,0,0,1453202761,1492172790,0,1,1),
	(6,4,4,2,'login','/account/login',0,'Вход','','','','','','',0,0,0,0,1453889260,1492172790,0,1,1),
	(8,4,4,2,'lost_password','/account/lost_password',0,'Восстановление пароля','','','','','','',0,0,0,0,1453890080,1467986334,0,1,1),
	(10,3,3,2,'basket','/catalog/basket',0,'Корзина','','','','','','',0,0,0,0,1453890162,1496737578,0,1,1),
	(11,3,3,2,'search','/catalog/search',0,'Поиск по каталогу','','','','','','',0,0,0,0,1453979605,1496736303,0,1,1),
	(12,1,1,1,'galereya','/galereya',0,'Галерея','','','','','','',0,1,0,0,1453990462,1467986003,0,1,1),
	(13,4,4,2,'spisok_zhelaniy','/account/spisok_zhelaniy',0,'Список желаний','','','','','','',0,0,0,0,1454052269,1467986334,0,1,1),
	(14,1,1,1,'sitemap','/sitemap',0,'Карта сайта','','','','','','',0,0,0,0,1454319807,1488186848,0,1,1);

/*!40000 ALTER TABLE `yii_icms_tree` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_tree_gallery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_tree_gallery`;

CREATE TABLE `yii_icms_tree_gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tree_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Дамп таблицы yii_icms_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_user`;

CREATE TABLE `yii_icms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `auth_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `yii_icms_user` WRITE;
/*!40000 ALTER TABLE `yii_icms_user` DISABLE KEYS */;

INSERT INTO `yii_icms_user` (`id`, `login`, `name`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `phone`, `city`, `street`, `home`)
VALUES
	(1,'aisol','aisol','KY1N0rvm3O95owsnzS-cGZh4uzq_VX-n','$2y$13$hr0X6fyCZmGyzH2hqmnKy.wgmeyCOlUxFHn50glK6ay49SxFBQi.K',NULL,'info@aisol.ru',10,1432285516,1454589283,NULL,NULL,NULL,NULL),
	(4,'blag','Разработчик 1','','$2y$13$I23vSKPq.Edahvclq4lPoujgCnrGiNNUjCE0MuB1dsLK2QiAd3CaC',NULL,'blaga@aisol.ru',10,1432294682,1491829141,NULL,NULL,NULL,NULL),
	(5,'alex','Александр Павлов','','$2y$13$IRWLGFZHLX8Gavh11A3vyOypudtfopHq/syeYeU4PZd7/CALwP5Au',NULL,'pavlov@aisol.ru',10,1478166465,1478166465,NULL,NULL,NULL,NULL),
	(6,'maxim','Разработчик 2','','$2y$13$2WTuL6eVcS9Ddhb83wIzweI01TAkQWZwRYlHtMvI9qjS7wS/3fUYu',NULL,'maxim@aisol.ru',10,1478166465,1478166465,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `yii_icms_user` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_user_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_user_log`;

CREATE TABLE `yii_icms_user_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(30) NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `developer_only` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_user_log` WRITE;
/*!40000 ALTER TABLE `yii_icms_user_log` DISABLE KEYS */;

INSERT INTO `yii_icms_user_log` (`id`, `user_id`, `name`, `ip`, `created_at`, `updated_at`, `developer_only`)
VALUES
	(30,6,'new_test_user','127.0.0.1',1509541373,1509541373,0),
	(31,4,'Разработчик 1','127.0.0.1',1511956541,1511956541,1),
	(32,4,'Разработчик 1','127.0.0.1',1518425705,1518425705,1);

/*!40000 ALTER TABLE `yii_icms_user_log` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы yii_icms_wishlist
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yii_icms_wishlist`;

CREATE TABLE `yii_icms_wishlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `yii_icms_wishlist` WRITE;
/*!40000 ALTER TABLE `yii_icms_wishlist` DISABLE KEYS */;

INSERT INTO `yii_icms_wishlist` (`id`, `user_id`, `catalog_id`)
VALUES
	(1,0,0),
	(2,0,0),
	(3,4,2),
	(4,0,0);

/*!40000 ALTER TABLE `yii_icms_wishlist` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
