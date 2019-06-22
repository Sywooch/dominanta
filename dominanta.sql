-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: dominanta
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `css`
--

DROP TABLE IF EXISTS `css`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `css` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `css_name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `css`
--

LOCK TABLES `css` WRITE;
/*!40000 ALTER TABLE `css` DISABLE KEYS */;
INSERT INTO `css` VALUES (1,'Основной стиль сайта','/css/style.css',''),(2,'Fontawesome','/css/font-awesome.min.css',''),(3,'Roboto','https://fonts.googleapis.com/css?family=Roboto&display=swap','');
/*!40000 ALTER TABLE `css` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `js`
--

DROP TABLE IF EXISTS `js`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `js` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `js_name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `js`
--

LOCK TABLES `js` WRITE;
/*!40000 ALTER TABLE `js` DISABLE KEYS */;
INSERT INTO `js` VALUES (1,'Основной скрипт','/js/main.js',''),(2,'Маска ввода','/js/jquery.maskedinput.min.js','');
/*!40000 ALTER TABLE `js` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL DEFAULT '0',
  `mail_setting_id` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `send_time` datetime DEFAULT NULL,
  `to_email` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body_text` text,
  `body_html` text,
  `send_errors` text,
  PRIMARY KEY (`id`),
  KEY `idx-mail-mail_setting_id` (`mail_setting_id`),
  CONSTRAINT `fk-mail-mail_setting_id` FOREIGN KEY (`mail_setting_id`) REFERENCES `mail_setting` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail`
--

LOCK TABLES `mail` WRITE;
/*!40000 ALTER TABLE `mail` DISABLE KEYS */;
INSERT INTO `mail` VALUES (1,0,NULL,'2019-06-22 13:38:32',NULL,'info@inter-projects.ruеее','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/RHpD3P_kY8xlM1wjFj2VZvCIPpRHI2wg\">http://dominanta.loc/activate/RHpD3P_kY8xlM1wjFj2VZvCIPpRHI2wg</a>',NULL,NULL),(2,0,NULL,'2019-06-22 14:36:55',NULL,'nfo@inter-projects.ru','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/site/activate?token=Tr4TmakVXC977njFwVda9Uv-kKaKBLIL\">http://dominanta.loc/site/activate?token=Tr4TmakVXC977njFwVda9Uv-kKaKBLIL</a>',NULL,NULL),(3,0,NULL,'2019-06-22 14:38:47',NULL,'info@inter-projects.rutt','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/0LqqkUr3aStzFJtDVpIXULzWf0nJ3i0R\">http://dominanta.loc/activate/0LqqkUr3aStzFJtDVpIXULzWf0nJ3i0R</a>',NULL,NULL),(4,0,NULL,'2019-06-22 15:24:30',NULL,'info@inter-projects.ruqqq','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/gIMkN7078wJn7DbtJsV-Z0wHhB7_0yZM\">http://dominanta.loc/activate/gIMkN7078wJn7DbtJsV-Z0wHhB7_0yZM</a>',NULL,NULL),(5,0,NULL,'2019-06-22 15:33:59',NULL,'info@inter-projects.ru2','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/c6L0dXiZWWzpw9qOc5k3-fkbNHCoWI4-\">http://dominanta.loc/activate/c6L0dXiZWWzpw9qOc5k3-fkbNHCoWI4-</a>',NULL,NULL),(6,0,NULL,'2019-06-22 16:26:53',NULL,'33info@inter-projects.ru','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/_tNL3HuEbJK2UlzdyxnxcpgUNiMNLJkU\">http://dominanta.loc/activate/_tNL3HuEbJK2UlzdyxnxcpgUNiMNLJkU</a>',NULL,NULL),(7,0,NULL,'2019-06-22 16:46:52',NULL,'info@inter-projects.ru444','Регистрация на сайте Dominanta.loc','Вы успешно зарегистрировались на сайте dominanta.loc.\n\nДля активации аккаунта перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/qzIkSDPOP3PNwqzNbGEfMGJTLAxdkamu\">http://dominanta.loc/activate/qzIkSDPOP3PNwqzNbGEfMGJTLAxdkamu</a>',NULL,NULL),(8,0,NULL,'2019-06-22 18:44:54',NULL,'info@inter-projects.ru','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/activate/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/activate/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\">http://dominanta.loc/activate/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(9,0,NULL,'2019-06-22 18:52:33',NULL,'info@inter-projects.ru','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\">http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(10,0,NULL,'2019-06-22 18:54:38',NULL,'info@inter-projects.ru','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\">http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(11,0,NULL,'2019-06-22 19:22:22',NULL,'info@inter-projects.ru444','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/restore/8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/restore/8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK\">http://dominanta.loc/restore/8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(12,0,NULL,'2019-06-22 19:25:14',NULL,'info@inter-projects.ru444','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/restore/8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/restore/8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK\">http://dominanta.loc/restore/8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(13,0,NULL,'2019-06-22 19:51:48',NULL,'info@inter-projects.ru','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr\">http://dominanta.loc/restore/g3vpEFjwA7KZKGLIx1bZkLfoOgj-3Vdr</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(14,0,NULL,'2019-06-22 19:53:45',NULL,'info@inter-projects.ru','Восстановление пароля на сайте Dominanta.loc','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.\n\nДля восстановления пароля перейдите, пожалуйста, по ссылке - http://dominanta.loc/restore/ONaC2b1GaLMOiuUMGMVuYXDZagkxAU0x\n\nЕсли вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.','Для вашего аккаунта на сайте dominanta.loc было запрошено восстановление пароля.<br /><br />Для восстановления пароля перейдите, пожалуйста, по ссылке - <a href=\"http://dominanta.loc/restore/ONaC2b1GaLMOiuUMGMVuYXDZagkxAU0x\">http://dominanta.loc/restore/ONaC2b1GaLMOiuUMGMVuYXDZagkxAU0x</a><br /><br />Если вы не запрашивали восстановление пароля, просто проигнорируйте это письмо.',NULL),(15,0,NULL,'2019-06-22 19:54:30',NULL,'info@inter-projects.ru','Восстановление пароля на сайте Dominanta.loc','Новый пароль вашего аккаунта на сайте dominanta.loc:\n\ngOyD6bD7','Новый пароль вашего на сайте dominanta.loc:<br /><br />gOyD6bD7',NULL);
/*!40000 ALTER TABLE `mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_attachment`
--

DROP TABLE IF EXISTS `mail_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_id` int(11) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `embed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-mail_attachment-mail_id` (`mail_id`),
  CONSTRAINT `fk-mail_attachment-mail_id` FOREIGN KEY (`mail_id`) REFERENCES `mail` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_attachment`
--

LOCK TABLES `mail_attachment` WRITE;
/*!40000 ALTER TABLE `mail_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_setting`
--

DROP TABLE IF EXISTS `mail_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL DEFAULT '1',
  `service_name` varchar(255) NOT NULL DEFAULT '',
  `smtp_host` varchar(255) NOT NULL DEFAULT '',
  `smtp_port` int(5) NOT NULL DEFAULT '25',
  `smtp_user` varchar(255) NOT NULL DEFAULT '',
  `smtp_password` varchar(255) NOT NULL DEFAULT '',
  `smtp_secure` varchar(5) NOT NULL DEFAULT '',
  `from_email` varchar(255) NOT NULL DEFAULT '',
  `from_name` varchar(255) NOT NULL DEFAULT '',
  `reply_to` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_setting`
--

LOCK TABLES `mail_setting` WRITE;
/*!40000 ALTER TABLE `mail_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `item` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `item_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-menu-pid` (`pid`),
  CONSTRAINT `fk-menu-pid` FOREIGN KEY (`pid`) REFERENCES `menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1560085902),('m180915_104910_create_role_table',1560085907),('m180915_115617_create_user_table',1560085910),('m180917_185330_create_mail_setting_table',1560085910),('m180917_210222_create_mail_table',1560085912),('m180917_210455_create_mail_attachment_table',1560085914),('m180917_211309_create_variable_table',1560085914),('m180917_211645_create_option_table',1560085915),('m180926_191608_create_template_table',1560085915),('m180926_194855_create_page_table',1560085918),('m181006_135812_create_menu_table',1560085920),('m181012_173905_add_last_update_column_to_page_table',1560085921),('m181226_192531_add_notify_column_to_user_table',1560085922),('m190311_200320_add_sitemap_inc_column_to_page_table',1560085922),('m190324_110336_add_page_order_column_to_page_table',1560085923),('m190324_110834_add_create_time_column_to_page_table',1560085924),('m190419_195420_create_rule_table',1560085927),('m190503_171559_create_js_table',1560085927),('m190503_171631_create_css_table',1560085928),('m190503_172028_create_template_js_table',1560085931),('m190503_172107_create_template_css_table',1560085934),('m190503_172404_create_page_css_table',1560085937),('m190503_172504_create_page_js_table',1560085941);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `option`
--

DROP TABLE IF EXISTS `option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option` varchar(255) NOT NULL DEFAULT '',
  `option_name` varchar(255) NOT NULL DEFAULT '',
  `option_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `option`
--

LOCK TABLES `option` WRITE;
/*!40000 ALTER TABLE `option` DISABLE KEYS */;
INSERT INTO `option` VALUES (1,'site_title','Site title','Торговый дом доминанта'),(2,'site_title_position','Site title position','before'),(3,'site_title_separator','Site title separator',' - '),(4,'main_page','Main page','index'),(5,'page_extension','Page extension',''),(6,'scheme','Scheme','http'),(8,'user_reg_role','User role for registration','2');
/*!40000 ALTER TABLE `option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL DEFAULT '0',
  `pid` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `meta_keywords` text,
  `meta_description` text,
  `page_content` text,
  `settings` text,
  `last_update` datetime DEFAULT NULL,
  `sitemap_inc` int(1) NOT NULL DEFAULT '0',
  `page_order` int(11) NOT NULL DEFAULT '1',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-page-pid` (`pid`),
  KEY `idx-page-template_id` (`template_id`),
  CONSTRAINT `fk-page-pid` FOREIGN KEY (`pid`) REFERENCES `page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-page-template_id` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (1,0,NULL,NULL,'Системные страницы','','system_pages','','','',NULL,'2019-06-19 22:23:16',0,1,'2019-06-19 22:23:16'),(2,0,1,NULL,'Вход / Регистрация','','login_reg_form','','','    <!-- Modal login/registration Begin -->\r\n    <div class=\"modal fade\" id=\"modal_auth\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"ModalAuthLabel\">\r\n        <div class=\"modal-dialog\" role=\"document\">\r\n            <div class=\"modal-content\">\r\n                <div class=\"modal-header\">\r\n                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"></button>\r\n                    <h4 class=\"modal-title\" id=\"ModalAuthLabel\">Вход в личный кабинет</h4>\r\n                </div>\r\n                <div class=\"modal-body\">\r\n                    <div class=\"modal_tabs\">\r\n                        <a href=\"#tab_panel_login\" class=\"active_modal_tab modal_tab\">Вход</a>\r\n                        <a href=\"#tab_panel_reg\" class=\"modal_tab\">Регистрация</a>\r\n                    </div>\r\n                    <div class=\"modal_tabpanels\">\r\n                        <div id=\"tab_panel_login\" class=\"modal_tab_panel active_modal_tab_panel\">\r\n                            {{{widget|login}}}\r\n                        </div>\r\n                        <div id=\"tab_panel_reg\" class=\"modal_tab_panel\">\r\n                            {{{widget|registration}}}\r\n                        </div>\r\n                        <div id=\"tab_panel_restore\" class=\"modal_tab_panel\">\r\n                            {{{widget|restore}}}\r\n                        </div>\r\n                    </div>\r\n\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <!-- Modal login/registration End -->',NULL,'2019-06-22 18:37:58',0,1,'2019-06-19 22:24:21'),(3,0,1,NULL,'Верхнее меню','','top_menu','','','    <!-- Header thin blue line Begin -->\r\n    <div class=\"container-fluid\">\r\n        <div class=\"row\" id=\"header_top_line\">\r\n            <div class=\"container\">\r\n                <div class=\"row\">\r\n                    <div class=\"top_map_city col-lg-3 col-md-3 col-sm-6 col-xs-6\">\r\n                        <span class=\"top_map_marker\"></span>\r\n                        <span class=\"top_map_city\">г. Казань</span>\r\n                    </div>\r\n                    <div class=\"top_short_menu col-lg-6 col-md-6 hidden-sm hidden-xs text-center\">\r\n                        <a href=\"#\">Оплата</a>\r\n                        <a href=\"#\">Доставка</a>\r\n                        <a href=\"#\">Возврат</a>\r\n                        <a href=\"#\">Оптовикам</a>\r\n                    </div>\r\n                    <div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-6\">\r\n                        <div class=\"top_personal pull-right\">\r\n                            {{{widget|accountmenu}}}\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <!-- Header thin blue line End -->',NULL,'2019-06-20 22:35:48',0,1,'2019-06-19 22:25:13'),(4,0,1,NULL,'Шапка сайта','','header','','','    <!-- Header main line begin -->\r\n    <div class=\"container-fluid\">\r\n        <div class=\"row\" id=\"header_main_line\">\r\n            <div class=\"container\">\r\n                <div class=\"row\">\r\n\r\n                    <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">\r\n\r\n                        <!-- Logo Begin -->\r\n                        <a href=\"#\" class=\"pull-left\">\r\n                            <img src=\"/images/dominanta_logo.png\" alt=\"Доминанта\" />\r\n                        </a>\r\n                        <!-- Logo End -->\r\n\r\n                        <!-- Serchform Begin -->\r\n                        <form class=\"top_searchform\">\r\n                            <div class=\"form-group\">\r\n                                <input type=\"text\" class=\"form-control top_searchfield\" placeholder=\"Поиск по сайту...\">\r\n                                <button type=\"submit\" class=\"top_searchbutton\"></button>\r\n                            </div>\r\n                        </form>\r\n                        <!-- Searchform End -->\r\n\r\n                        <!-- Shopping cart Begin -->\r\n                        <a href=\"#\" class=\"top_shopping_cart pull-right\">\r\n                            <div class=\"shopping_cart_text_amount\">\r\n                                <span class=\"shopping_cart_text\">Корзина</span><br />\r\n                                <span class=\"shopping_cart_amount\">25 000 <i class=\"fa fa-rub\"></i></span>\r\n                            </div>\r\n                            <div class=\"text-right\">\r\n                                <span class=\"shopping_cart_badge\">30</span>\r\n                            </div>\r\n                        </a>\r\n                        <!-- Shopping cart end -->\r\n\r\n                        <!-- Phones Begin -->\r\n                        <div class=\"pull-right top_col_phones\">\r\n                            <span class=\"pull-left phoneicon\"></span>\r\n                            <div class=\"top_phones\">\r\n                                <span class=\"top_phone\">8 (843) 212-27-26</span>&nbsp;&nbsp;\r\n                                <a href=\"#\">Заказать&nbsp;звонок</a><br />\r\n                                <span class=\"top_workhours\">Прием звонков: 9:00-19:00</span>\r\n                            </div>\r\n                        </div>\r\n                        <!-- Phones End -->\r\n\r\n                    </div>\r\n                </div>\r\n                <div class=\"row top_mobile_phones\">\r\n                    <div class=\"text-left col-sm-6 col-xs-6\">\r\n                        <span class=\"pull-left mobile_phoneicon\"></span>\r\n                        <span class=\"top_mobile_phone\">8 (843) 212-27-26</span><br />\r\n                        <a href=\"#\">Заказать&nbsp;звонок</a>\r\n                    </div>\r\n                    <div class=\"text-right col-sm-6 col-xs-6\">\r\n                        <span class=\"top_mobile_workhours\">Прием звонков:<br />9:00-19:00</span>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <!-- Header main line end -->',NULL,'2019-06-19 22:27:17',0,1,'2019-06-19 22:25:56'),(5,0,1,NULL,'Главное меню','','main_menu','','','    <!-- Menu line Begin -->\r\n    <div class=\"container-fluid\">\r\n        <div class=\"row\" id=\"menu_top_line\">\r\n            <div class=\"container\">\r\n                <div class=\"row\">\r\n                    <div class=\"col-lg-10 col-md-10 col-sm-9 col-xs-7\">\r\n                        <a href=\"#\" class=\"menu_icon pull-left\"><span class=\"hidden-sm hidden-xs\">Каталог товаров</span></a>\r\n                        <a href=\"#\" class=\"menu_item hidden-sm hidden-xs\">О компании</a>\r\n                        <a href=\"#\" class=\"menu_item hidden-sm hidden-xs\">Акции</a>\r\n                        <a href=\"#\" class=\"menu_item hidden-sm hidden-xs\">Услуги</a>\r\n                        <a href=\"#\" class=\"menu_item hidden-sm hidden-xs\">Советы</a>\r\n                        <a href=\"#\" class=\"menu_item hidden-sm hidden-xs\">Контакты</a>\r\n\r\n                        <!-- Serchform Begin -->\r\n                        <form class=\"mobile_searchform hidden-lg hidden-md\">\r\n                            <input type=\"text\" class=\"form-control mobile_searchfield\" placeholder=\"Поиск по сайту...\">\r\n                            <button type=\"submit\" class=\"mobile_searchbutton\"></button>\r\n                        </form>\r\n                        <!-- Searchform End -->\r\n                    </div>\r\n                    <div class=\"col-lg-2 col-md-2 col-sm-3 col-xs-5 top_socials\">\r\n                        <a href=\"#\"><i class=\"fa fa-odnoklassniki\"></i></a>\r\n                        <a href=\"#\"><i class=\"fa fa-vk\"></i></a>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <!-- Menu line End -->',NULL,'2019-06-19 22:29:34',0,1,'2019-06-19 22:29:34'),(6,0,1,NULL,'Подменю','','submenu','','','    <!-- Dropdown menu Begin -->\r\n    <div class=\"container dropdown_menu\">\r\n        <div class=\"row\">\r\n\r\n            <div class=\"col-lg-4 col-md-4 col-sm-6 hidden-xs\">\r\n                <div class=\"dropdown_left_col\">\r\n                    <a href=\"#\">Инженерные системы</a>\r\n                    <a href=\"#\">Крепёж</a>\r\n                    <a href=\"#\">ЛКМ, грунты, пены, герметики</a>\r\n                    <a href=\"#\">Отделка</a>\r\n                    <a href=\"#\">Сантехника</a>\r\n                    <a href=\"#\">Строительные материалы</a>\r\n                    <a href=\"#\">Электротехнические товары</a>\r\n                </div>\r\n            </div>\r\n\r\n            <div class=\"col-lg-4 col-md-4 col-sm-6 hidden-xs\">\r\n                <div class=\"dropdown_right_col\">\r\n                    <a href=\"#\">Инженерные системы</a>\r\n                    <a href=\"#\">Крепёж</a>\r\n                    <a href=\"#\">ЛКМ, грунты, пены, герметики</a>\r\n                    <a href=\"#\">Отделка</a>\r\n                    <a href=\"#\">Сантехника</a>\r\n                    <a href=\"#\">Строительные материалы</a>\r\n                    <a href=\"#\">Электротехнические товары</a>\r\n                </div>\r\n            </div>\r\n\r\n            <div class=\"hidden-lg hidden-md col-sm-6 hidden-xs\">\r\n\r\n            </div>\r\n\r\n            <div class=\"col-lg-4 col-md-4 col-sm-6 hidden-xs\">\r\n                <div class=\"dropdown_right_col\">\r\n                    <a href=\"#\">Инженерные системы</a>\r\n                    <a href=\"#\">Крепёж</a>\r\n                    <a href=\"#\">ЛКМ, грунты, пены, герметики</a>\r\n                    <a href=\"#\">Отделка</a>\r\n                    <a href=\"#\">Сантехника</a>\r\n                    <a href=\"#\">Строительные материалы</a>\r\n                    <a href=\"#\">Электротехнические товары</a>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <!-- Dropdown menu End -->',NULL,'2019-06-19 22:30:04',0,1,'2019-06-19 22:30:04'),(7,0,1,NULL,'Подвал','','footer','','','    <!-- Footer line Begin -->\r\n    <div class=\"container-fluid\">\r\n        <div class=\"row\" id=\"footer_line\">\r\n            <div class=\"container\">\r\n\r\n                <!-- Logo, subscribe form row Begin -->\r\n                <div class=\"row\">\r\n\r\n                    <!-- Footer logo Begin -->\r\n                    <div class=\"col-lg-3 col-md-3 col-sm-3 hidden-xs text-left\">\r\n                        <img src=\"/images/footer_logo.png\" alt=\"\" />\r\n                    </div>\r\n                    <!-- Footer logo End -->\r\n\r\n                    <!-- Subscripe form Begin -->\r\n                    <div class=\"col-lg-9 col-md-9 col-sm-9 col-xs-12\">\r\n                        <div class=\"footer_subsribe_text pull-left\">\r\n                            Подпишитесь на нашу рассылку<br />\r\n                            <span>Чтобы получать информацию об акциях и скидках</span>\r\n                        </div>\r\n\r\n                        <form class=\"footer_subsribe_form\">\r\n                            <button type=\"submit\" class=\"footer_subsribe_button pull-right hidden-xs\">Подписаться на рассылку</button>\r\n\r\n                            <div class=\"form-group\">\r\n                                <input type=\"text\" class=\"form-control footer_subsribe_field\" placeholder=\"Ваша электронная почта\">\r\n                            </div>\r\n\r\n                            <button type=\"submit\" class=\"footer_subsribe_button hidden-lg hidden-md hidden-sm\">Подписаться на рассылку</button>\r\n                        </form>\r\n                    </div>\r\n                    <!-- Subscribe form End -->\r\n\r\n                </div>\r\n                <!-- Logo, subscribe form End -->\r\n\r\n                <!-- Contacts, footer menu row Begin -->\r\n                <div class=\"row contacts_bottom_menu_row\">\r\n\r\n                    <!-- Contacts Begin -->\r\n                    <div class=\"col-lg-3 col-md-4 col-sm-5 col-xs-12\">\r\n                        <div class=\"bottom_col_phones hidden-xs\">\r\n                            <span class=\"pull-left phoneicon\"></span>\r\n                            <div class=\"top_phones\">\r\n                                <span class=\"top_phone\">8 (843) 212-27-26</span>&nbsp;&nbsp;\r\n                                <a href=\"#\">Заказать&nbsp;звонок</a><br />\r\n                                <span class=\"top_workhours\">Прием звонков: 9:00-19:00</span>\r\n                            </div>\r\n                        </div>\r\n\r\n                        <div class=\"bottom_col_email\">\r\n                            <span class=\"pull-left mailicon\"></span>\r\n                            <div class=\"contact_email\">\r\n                                info@dominanta.ru\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <!-- Contacts End -->\r\n\r\n                    <!-- Bottom menu Begin -->\r\n                    <div class=\"col-lg-9 col-md-8 col-sm-7 col-xs-12\">\r\n                        <div class=\"bottom_menu\">\r\n                            <div class=\"bottom_menu_block pull-left\">\r\n                                <div class=\"bottom_menu_header\">О компании</div>\r\n                                <a href=\"#\">Вакансии</a><br />\r\n                                <a href=\"#\">Акции</a><br />\r\n                                <a href=\"#\">Услуги</a>\r\n                            </div>\r\n\r\n                            <div class=\"bottom_menu_block pull-left\">\r\n                                <div class=\"bottom_menu_header\">&nbsp;</div>\r\n                                <a href=\"#\">Советы</a><br />\r\n                                <a href=\"#\">Контакты</a>\r\n                            </div>\r\n\r\n                            <div class=\"bottom_menu_block pull-left\">\r\n                                <div class=\"bottom_menu_header\">Сервис</div>\r\n                                <a href=\"#\">Доставка</a><br />\r\n                                <a href=\"#\">Оплата</a><br />\r\n                                <a href=\"#\">Оптовикам</a>\r\n                            </div>\r\n\r\n                            <div class=\"bottom_menu_block pull-left\">\r\n                                <div class=\"bottom_menu_header\">&nbsp;</div>\r\n                                <a href=\"#\">Карта сайта</a><br />\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                    <!-- Bottom menu End -->\r\n\r\n                </div>\r\n                <!-- Contacts, footer menu row End -->\r\n            </div>\r\n\r\n            <!-- Copyright Begin -->\r\n            <div class=\"footer_copyright text-center\">\r\n                &copy; ООО «Доминанта», 2019\r\n            </div>\r\n            <!-- Copyright End -->\r\n        </div>\r\n    </div>',NULL,'2019-06-19 22:30:58',0,1,'2019-06-19 22:30:58'),(8,1,NULL,1,'Главная страница','Строительные и отделочные материалы','index','','','Главная',NULL,'2019-06-19 22:33:35',0,1,'2019-06-19 22:32:34'),(9,0,1,NULL,'Меню личного кабинета','','account_menu','','','                                <a href=\"#\">Личные данные</a>\r\n                                <a href=\"#\">История заказов</a>\r\n                                <a href=\"#\">Адреса доставок</a>\r\n                                <a href=\"/logout\">Выйти</a>',NULL,'2019-06-20 22:25:52',0,1,'2019-06-20 22:25:52'),(10,0,NULL,NULL,'Информационные страницы','','info_pages','','','',NULL,'2019-06-22 16:54:22',0,2,'2019-06-22 16:54:22'),(11,0,10,1,'Успешная активация пользователя','','activate_success','','','<div class=\"alert alert-success\" role=\"alert\">\r\n  <i class=\"fa fa-check\"></i> Аккаунт успешно активирован!\r\n</div>\r\n\r\n<a href=\"/\">Приступить к покупкам</a>',NULL,'2019-06-22 18:11:43',0,1,'2019-06-22 16:56:41'),(12,-1,10,1,'Успешная регистрация','','reg_success','','','',NULL,'2019-06-22 18:59:31',0,1,'2019-06-22 17:55:24'),(13,-1,10,1,'Успешный вход','','login_success','','','<div class=\"alert alert-success\" role=\"alert\">\r\n    <i class=\"fa fa-check\"></i> Вход успешно выполнен!\r\n</div>',NULL,'2019-06-22 18:58:18',0,1,'2019-06-22 17:59:34'),(14,0,10,1,'Ошибка активации','','activate_fail','','','<div class=\"alert alert-danger\" role=\"alert\">\r\n  <i class=\"fa fa-remove\"></i> Не удалось активировать пользователя. Пользователь не найден или код активации некорректен.\r\n</div>',NULL,'2019-06-22 18:12:04',0,1,'2019-06-22 18:04:58'),(15,0,10,1,'Восстановление пароля','','restore_success','','','<div class=\"alert alert-success\" role=\"alert\">\r\n  <i class=\"fa fa-check\"></i> На вашу электронную почту был отправлен новый пароль аккаунта. Поменять его всегда можно в настройках личного кабинета.\r\n</div>',NULL,'2019-06-22 19:56:09',0,1,'2019-06-22 18:53:57'),(16,0,10,1,'Неудачное восстановление пароля','','restore_fail','','','<div class=\"alert alert-danger\" role=\"alert\">\r\n  <i class=\"fa fa-remove\"></i> Не удалось восстановить пароль пользователя. Пользователь не найден или код активации некорректен.\r\n</div>',NULL,'2019-06-22 19:56:19',0,1,'2019-06-22 19:43:42');
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_css`
--

DROP TABLE IF EXISTS `page_css`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_css` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `css_id` int(11) DEFAULT NULL,
  `position` varchar(255) NOT NULL DEFAULT '',
  `s_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-page_css-page_id` (`page_id`),
  KEY `idx-page_css-css_id` (`css_id`),
  CONSTRAINT `fk-page_css-css_id` FOREIGN KEY (`css_id`) REFERENCES `css` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-page_css-page_id` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_css`
--

LOCK TABLES `page_css` WRITE;
/*!40000 ALTER TABLE `page_css` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_css` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_js`
--

DROP TABLE IF EXISTS `page_js`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_js` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `js_id` int(11) DEFAULT NULL,
  `position` varchar(255) NOT NULL DEFAULT '3',
  `s_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-page_js-page_id` (`page_id`),
  KEY `idx-page_js-js_id` (`js_id`),
  CONSTRAINT `fk-page_js-js_id` FOREIGN KEY (`js_id`) REFERENCES `js` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-page_js-page_id` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_js`
--

LOCK TABLES `page_js` WRITE;
/*!40000 ALTER TABLE `page_js` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_js` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL DEFAULT '0',
  `pid` int(11) DEFAULT NULL,
  `role_name` varchar(255) NOT NULL,
  `is_admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-role-pid` (`pid`),
  CONSTRAINT `fk-role-pid` FOREIGN KEY (`pid`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,1,NULL,'Administrator',1),(2,1,NULL,'Пользователь',0);
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rule`
--

DROP TABLE IF EXISTS `rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `model` varchar(255) NOT NULL,
  `is_view` int(1) NOT NULL DEFAULT '0',
  `is_add` int(1) NOT NULL DEFAULT '0',
  `is_edit` int(11) NOT NULL DEFAULT '0',
  `is_delete` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-rule-role_id` (`role_id`),
  CONSTRAINT `fk-rule-role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rule`
--

LOCK TABLES `rule` WRITE;
/*!40000 ALTER TABLE `rule` DISABLE KEYS */;
INSERT INTO `rule` VALUES (1,1,'Css',1,1,1,1),(2,1,'Js',1,1,1,1),(3,1,'Mail',1,1,1,1),(4,1,'MailAttachment',1,1,1,1),(5,1,'MailSetting',1,1,1,1),(6,1,'Menu',1,1,1,1),(7,1,'Option',1,1,1,1),(8,1,'Page',1,1,1,1),(9,1,'PageCss',1,1,1,1),(10,1,'PageJs',1,1,1,1),(11,1,'Role',1,1,1,1),(12,1,'Rule',1,1,1,1),(13,1,'Template',1,1,1,1),(14,1,'TemplateCss',1,1,1,1),(15,1,'TemplateJs',1,1,1,1),(16,1,'User',1,1,1,1),(17,1,'Variable',1,1,1,1);
/*!40000 ALTER TABLE `rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template`
--

DROP TABLE IF EXISTS `template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL DEFAULT '0',
  `layout` varchar(255) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_content` text,
  `settings` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template`
--

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS */;
INSERT INTO `template` VALUES (1,1,'main','Основной шаблон','{{{page|2}}}\r\n{{{page|3}}}\r\n{{{page|4}}}\r\n{{{page|5}}}\r\n{{{page|6}}}\r\n{{{content}}}\r\n{{{page|7}}}',NULL);
/*!40000 ALTER TABLE `template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_css`
--

DROP TABLE IF EXISTS `template_css`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_css` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `css_id` int(11) DEFAULT NULL,
  `position` varchar(255) NOT NULL DEFAULT '',
  `s_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-template_css-template_id` (`template_id`),
  KEY `idx-template_css-css_id` (`css_id`),
  CONSTRAINT `fk-template_css-css_id` FOREIGN KEY (`css_id`) REFERENCES `css` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-template_css-template_id` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_css`
--

LOCK TABLES `template_css` WRITE;
/*!40000 ALTER TABLE `template_css` DISABLE KEYS */;
INSERT INTO `template_css` VALUES (1,1,1,'',1),(2,1,2,'',3),(3,1,3,'',2);
/*!40000 ALTER TABLE `template_css` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_js`
--

DROP TABLE IF EXISTS `template_js`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_js` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `js_id` int(11) DEFAULT NULL,
  `position` varchar(255) NOT NULL DEFAULT '3',
  `s_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx-template_js-template_id` (`template_id`),
  KEY `idx-template_js-js_id` (`js_id`),
  CONSTRAINT `fk-template_js-js_id` FOREIGN KEY (`js_id`) REFERENCES `js` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-template_js-template_id` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_js`
--

LOCK TABLES `template_js` WRITE;
/*!40000 ALTER TABLE `template_js` DISABLE KEYS */;
INSERT INTO `template_js` VALUES (1,1,1,'3',3),(2,1,2,'3',4);
/*!40000 ALTER TABLE `template_js` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL DEFAULT '',
  `role_id` int(11) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'ru-RU',
  `timeZone` varchar(255) NOT NULL DEFAULT 'Europe/Moscow',
  `realname` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `notify` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx-user-role_id` (`role_id`),
  CONSTRAINT `fk-user-role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,'info@inter-projects.ru','$2y$13$50A7J37nAisj0eF6LGWFqOiz94lkkE9y1ur6t5tvSUXFCAi78PHNi','IDsiAcrNvCxoN-bILFaYzhZsVdEaU5_3',1,'2019-06-09 16:11:50','2019-06-22 19:56:20','ru-RU','Europe/Moscow','','',''),(2,0,'info@inter-projecs.ru','root123456','gNYkG8V1OknsWuLbFTRh4lQ8-hPG4i0W',NULL,'2019-06-22 00:24:32',NULL,'ru-RU','Europe/Moscow','Роман','112',''),(3,0,'info@inter-projects.rur','111111','nBU8t8UUJLpgqiPKZEifcvqCqRawXeag',NULL,'2019-06-22 13:01:30',NULL,'ru-RU','Europe/Moscow','Роман','112',''),(4,0,'info@inter-projects.r','111111','T6tF1uB9KczI7zK8mcPWCXLD9gnauXCh',NULL,'2019-06-22 13:06:31',NULL,'ru-RU','Europe/Moscow','Роман','112',''),(5,0,'info@inter-projects.ruеее','$2y$13$dPAnXkZ/7vO7QmVoLsUzX./s/sqDjQySaJxk5ERo9pKjznwGaMtV6','4ArUgJQmR3rFyOR59O_JeKk3UJQHWRvB',NULL,'2019-06-22 13:38:32',NULL,'ru-RU','Europe/Moscow','Роман','112',''),(6,0,'nfo@inter-projects.ru','$2y$13$KWXRtcFXQXYnkR067JxxceHBZsVHCWOBiFTG2NqCRvMiCW06HcUnm','Tr4TmakVXC977njFwVda9Uv-kKaKBLIL',NULL,'2019-06-22 14:36:55',NULL,'ru-RU','Europe/Moscow','Роман','111',''),(7,1,'info@inter-projects.rutt','$2y$13$Q22c5JzFkwTmajpDKj9AHuwGebzmH0wdHy7v1rt/1UEXC9bQOw3Ke','499V0ETfb7TkkYViCYbKTIERBrABIU2t',2,'2019-06-22 14:38:46','2019-06-22 14:53:17','ru-RU','Europe/Moscow','Роман','info@inter-projects.ru',''),(8,0,'info@inter-projects.ruqqq','$2y$13$bKZFtacxrOnZgVWurmw/DOR/l2YGNvMo8qSy8r0Bdr6bBl3ylinZG','gIMkN7078wJn7DbtJsV-Z0wHhB7_0yZM',NULL,'2019-06-22 15:24:30',NULL,'ru-RU','Europe/Moscow','Роман','info@inter-projects.ruq',''),(9,1,'info@inter-projects.ru2','$2y$13$Q9IHZ1QJJc7SZk60UgaZBueyeBLhVrbbRyJDbQvupQIfJMnljxSRO','C_jlxDmxRI2LjFb6cKvgpxKtYQ_5yy_Z',2,'2019-06-22 15:33:59','2019-06-22 16:02:12','ru-RU','Europe/Moscow','Роман','112',''),(10,0,'33info@inter-projects.ru','$2y$13$sDcJWfEhABc8yCx/C9C3xe93sFSvMI6BwwRikHRkuuQJsaO0Ltk4y','_tNL3HuEbJK2UlzdyxnxcpgUNiMNLJkU',2,'2019-06-22 16:26:53',NULL,'ru-RU','Europe/Moscow','Роман','+72123132131',''),(11,1,'info@inter-projects.ru444','$2y$13$d2QpN2Duu7u7ZL5f1R26OexV51543Uz2jf4UWAu45.qzsQXDmvZy2','8L9ZISi4A-MGhTOcZWu0CQ4e-mmTQiOK',2,'2019-06-22 16:46:52','2019-06-22 16:53:37','ru-RU','Europe/Moscow','Роман','+72123132132','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variable`
--

DROP TABLE IF EXISTS `variable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variable`
--

LOCK TABLES `variable` WRITE;
/*!40000 ALTER TABLE `variable` DISABLE KEYS */;
/*!40000 ALTER TABLE `variable` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-22 22:01:33
