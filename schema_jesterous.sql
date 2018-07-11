CREATE DATABASE  IF NOT EXISTS `jesterous` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `jesterous`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: jesterous
-- ------------------------------------------------------
-- Server version	8.0.11

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
-- Table structure for table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) DEFAULT NULL,
  `category_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_62A97E9D5B80441` (`category_name`),
  KEY `IDX_62A97E982F1BAF4` (`language_id`),
  KEY `IDX_62A97E9727ACA70` (`parent_id`),
  CONSTRAINT `FK_62A97E9727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `article_categories` (`id`),
  CONSTRAINT `FK_62A97E982F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_categories`
--

LOCK TABLES `article_categories` WRITE;
/*!40000 ALTER TABLE `article_categories` DISABLE KEYS */;
INSERT INTO `article_categories` VALUES (1,0,'All',NULL),(2,1,'Спорт',1),(3,2,'Finance',1);
/*!40000 ALTER TABLE `article_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `background_image_link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `main_content` longtext COLLATE utf8_unicode_ci,
  `is_visible` tinyint(1) NOT NULL,
  `views` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BFDD3168F675F31B` (`author_id`),
  KEY `IDX_BFDD316812469DE2` (`category_id`),
  CONSTRAINT `FK_BFDD316812469DE2` FOREIGN KEY (`category_id`) REFERENCES `article_categories` (`id`),
  CONSTRAINT `FK_BFDD3168F675F31B` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,2,1,'Ignat pak se nasra (Ignat shit his pants again) :/','2017-12-03 00:00:00','Does life exist on Mars...','https://cdn.wccftech.com/wp-content/uploads/2018/07/eBay-740x463.jpg','<div class=\"post-inner\">\r\n\r\n					\r\n						<div class=\"post-content\">\r\n\r\n							<p><em><strong>И понеже само пет не ни стигат, представям ви:</strong></em></p>\r\n<h3>Анализи на чалга песни (и подобни)… 2</h3>\r\n<p style=\"text-align:center;\"><strong><u>Гери Никол</u></strong></p>\r\n<p style=\"text-align:center;\">Откачена съм била</p>\r\n<p style=\"text-align:center;\">К’во ми се надуваш, да не съм ти жена?</p>\r\n<p style=\"text-align:center;\">Ще ти покажа кой е шефа на мига.</p>\r\n<p style=\"text-align:center;\">Аз &nbsp;– много ясно . Запомни ме така.</p>\r\n<p style=\"text-align:center;\">Яката дупара, яката дупара.</p>\r\n<p style=\"text-align:center;\">Сега ша ва запаля, сега ша ва запаля.</p>\r\n<p style=\"text-align:left;\">Опитвайки се да избяга от сивото ежедневие на следващия тълпата обикновен човек, авторката изпитва чувство на страх и параноя от това да бъде различна. Въпреки хорските предположения, че е откачена, тя смело им отвръща с „К’во ми се надуваш, да не съм ти жена?“, с което изразява несъгласието си спрямо половите норми, наложени от обществото. Според нея &nbsp;различните хора заслужават да бъдат запомнени „така“, а именно – като „шефове на мига“, т.е. &nbsp;не на миналото или бъдещето, а само и единствено на настоящето. По нейно мнение една достойна жена трябва да има прелестен голям седалищен мускул, за да може да подпалва хората само с неговото присъствие.</p>\r\n<p style=\"text-align:center;\"><strong><u>Теди Александрова</u></strong></p>\r\n<p style=\"text-align:center;\">Когато аз избухвам ще мълчите, да, да, да, да, да.</p>\r\n<p style=\"text-align:center;\">Когато леко тъй си хващам ритъма, да, да, да, да.</p>\r\n<p style=\"text-align:center;\">Айде, чалга пеперудки във а-а, да, да, да, да.</p>\r\n<p style=\"text-align:center;\">Стана време да ги мачкам в Пайнера, да, да, да, да.</p>\r\n<p style=\"text-align:center;\">О, бейби,&nbsp; казваше ти – тук да слушаш и да изпълняваш.</p>\r\n<p style=\"text-align:center;\">Мъката ми лека стои – ако не ти харесва, щти бия шута, ма.</p>\r\n<p style=\"text-align:center;\">И за теб съм чисто нова сега – това са моите правила.</p>\r\n<p style=\"text-align:center;\">Хейтърите ми са зад гърба, брат ми, като вятъра.</p>\r\n<p><strong><em>Бележка от автора: Първата ми мисъл, когато прочетох този текст беше „Защо да не си намеря друго занимание…“.</em></strong></p>\r\n<p>Авторката на този хит умело използва похвата на мозъчната ретардация. Като същинска атомна бомба („Когато аз избухвам, &nbsp;ще мълчите, да“), тя се свързва със своите слушатели, обещавайки да „мачка в Пайнера“. Когато леко тъй си хваща ритъма , тя оприличава себе си на какавида, готова да се превърне във феноменална чалга пеперудка. Силното желание да накара някой от своите последователи да слуша и да изпълнява, &nbsp;кара авторката да се чувства непобедима пред „хейтърите” си.</p>\r\n<p>&nbsp;</p>\r\n<p style=\"text-align:center;\"><strong><u>&nbsp;</u></strong><strong><u>Анелия и Галин</u></strong></p>\r\n<p style=\"text-align:center;\">Аз съм дяволът – да си внимавала!</p>\r\n<p style=\"text-align:center;\">Чакаш ли някоя? Да знаеш – няма я.</p>\r\n<p style=\"text-align:center;\">Убих я с погледа за нахални.</p>\r\n<p style=\"text-align:center;\">Достъп всякакъв съм и отрязала,</p>\r\n<p style=\"text-align:center;\">на разстояние да остане.</p>\r\n<p style=\"text-align:center;\">Чакаш ли кротката? Пристигна водката,</p>\r\n<p style=\"text-align:center;\">по мене тръгнал е като огън.</p>\r\n<p style=\"text-align:center;\">Аз съм в ролята на най-жестоката,</p>\r\n<p style=\"text-align:center;\">кажи на твоята да си тръгва.</p>\r\n<p>&nbsp;</p>\r\n<p>В жестокия свят, в който живеем, често срещаме хора, готови да отнемат човешката свобода и право на глас. Анелия и Галин много добре изразяват това – потресени от нещастията, случващите се на наоколо, те решават на напишат песен за жена, която има невероятната дарба да убива само с погледа си. Въпросната жена обаче не решава да използва дарбата си за добро, даже напротив – сравнена&nbsp; с дявол, тя предупреждава околните да внимават, защото винаги е готова да причини злини. След като отвлича съпругата на нещастен и самотен мъж, тя „отрязва достъпа й“ и я държи на разстояние само със своя „поглед за нахални“.&nbsp; Карайки всички жени наоколо да се чувстват застрашени, жената помръдва пръста си само, когато трябва да убива със своя поглед, като през останалата част от деня прекарва времето си като пие водка.</p>\r\n<p style=\"text-align:center;\"><strong><u>Галин</u></strong></p>\r\n<p style=\"text-align:center;\">Намери златна лампа и потърка,</p>\r\n<p style=\"text-align:center;\">а духът отвътре се побърка.</p>\r\n<p style=\"text-align:center;\">От 100 години тебе той те чака</p>\r\n<p style=\"text-align:center;\">и готов е за атака.</p>\r\n<p style=\"text-align:center;\">Аз съм на Египет фараона!</p>\r\n<p style=\"text-align:center;\">Ставам на отбора шампиона!</p>\r\n<p style=\"text-align:center;\">Луд съм, и това не е легенда,</p>\r\n<p style=\"text-align:center;\">мога всичко на момента!</p>\r\n<p>Човекът винаги е бил заинтересован от митовете и легендите. Още от древни времена, хората вярвали в богове, кръвожадни чудовища и духове в лампи. За да ни припомни тези славни, изпълнени с приключения времена, Галин написва „На Египет фараона“, който разказва невероятната история за жадният за отмъщение дух от златна лампа, който 100 години чака денят на своето възмездие.Освен за изпълняващ желания джин, в интригуващото произведение се разказва за египетски фараон, шампион на митичен отбор (и тук е възможно авторът да има предвид олимпийските игри, но поради неточности в преписванията, смисълът да се губи). Според самия автор любовта към мистериозното, свръхестественото ни кара да се чувстваме така, сякаш нищо не е невъзможно и можем да направим всичко на момента.</p>\r\n<p><em><strong>И така нататък….</strong></em></p>\r\n<div id=\"atatags-370373-5b467b6eef4da\">\r\n        <script type=\"text/javascript\">\r\n            __ATA.cmd.push(function() {\r\n                __ATA.initVideoSlot(\'atatags-370373-5b467b6eef4da\', {\r\n                    sectionId: \'370373\',\r\n                    format: \'inread\'\r\n                });\r\n            });\r\n        </script>\r\n    </div>		<div class=\"wpcnt\">\r\n			<div class=\"wpa wpmrec\">\r\n				<span class=\"wpa-about\">Advertisements</span>\r\n				<div class=\"u\">		<div style=\"padding-bottom:15px;width:300px;height:250px;float:left;margin-right:5px;margin-top:0px\">\r\n		<div id=\"atatags-26942-5b467b6eef504\">\r\n			<script type=\"text/javascript\">\r\n			__ATA.cmd.push(function() {\r\n				__ATA.initSlot(\'atatags-26942-5b467b6eef504\',  {\r\n					collapseEmpty: \'before\',\r\n					sectionId: \'26942\',\r\n					width: 300,\r\n					height: 250\r\n				});\r\n			});\r\n			</script>\r\n		</div></div>		<div style=\"padding-bottom:15px;width:300px;height:250px;float:left;margin-top:0px\">\r\n		<div id=\"atatags-114160-5b467b6eef507\">\r\n			<script type=\"text/javascript\">\r\n			__ATA.cmd.push(function() {\r\n				__ATA.initSlot(\'atatags-114160-5b467b6eef507\',  {\r\n					collapseEmpty: \'before\',\r\n					sectionId: \'114160\',\r\n					width: 300,\r\n					height: 250\r\n				});\r\n			});\r\n			</script>\r\n		</div></div></div>\r\n				\r\n			</div>\r\n		</div><div id=\"jp-post-flair\" class=\"sharedaddy sd-like-enabled sd-sharing-enabled\"><div class=\"sharedaddy sd-sharing-enabled\"><div class=\"robots-nocontent sd-block sd-social sd-social-icon sd-sharing\"><h3 class=\"sd-title\">Сподели това:</h3><div class=\"sd-content\"><ul><li class=\"share-twitter\"><a rel=\"nofollow\" data-shared=\"sharing-twitter-155\" class=\"share-twitter sd-button share-icon no-text\" href=\"https://tonipetrova.wordpress.com/2017/01/13/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8-%d0%bd%d0%b0-%d1%87%d0%b0%d0%bb%d0%b3%d0%b0-%d0%bf%d0%b5%d1%81%d0%bd%d0%b8-2/?share=twitter&amp;nb=1\" target=\"_blank\" title=\"Click to share on Twitter\"><span></span><span class=\"sharing-screen-reader-text\">Click to share on Twitter (Opens in new window)</span></a></li><li class=\"share-facebook\"><a rel=\"nofollow\" data-shared=\"sharing-facebook-155\" class=\"share-facebook sd-button share-icon no-text\" href=\"https://tonipetrova.wordpress.com/2017/01/13/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8-%d0%bd%d0%b0-%d1%87%d0%b0%d0%bb%d0%b3%d0%b0-%d0%bf%d0%b5%d1%81%d0%bd%d0%b8-2/?share=facebook&amp;nb=1\" target=\"_blank\" title=\"Click to share on Facebook\"><span><span class=\"share-count\">3</span></span><span class=\"sharing-screen-reader-text\">Click to share on Facebook (Opens in new window)<span class=\"share-count\">3</span></span></a></li><li class=\"share-google-plus-1\"><a rel=\"nofollow\" data-shared=\"sharing-google-155\" class=\"share-google-plus-1 sd-button share-icon no-text\" href=\"https://tonipetrova.wordpress.com/2017/01/13/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8-%d0%bd%d0%b0-%d1%87%d0%b0%d0%bb%d0%b3%d0%b0-%d0%bf%d0%b5%d1%81%d0%bd%d0%b8-2/?share=google-plus-1&amp;nb=1\" target=\"_blank\" title=\"Click to share on Google+\"><span></span><span class=\"sharing-screen-reader-text\">Click to share on Google+ (Opens in new window)</span></a></li><li><a href=\"#\" class=\"sharing-anchor sd-button share-more\"><span>Повече</span></a></li><li class=\"share-end\"></li></ul><div class=\"sharing-hidden\"><div class=\"inner\" style=\"display: none;\"><ul><li class=\"share-jetpack-whatsapp\"><a rel=\"nofollow\" data-shared=\"\" class=\"share-jetpack-whatsapp sd-button share-icon no-text\" href=\"https://api.whatsapp.com/send?text=%D0%90%D0%BD%D0%B0%D0%BB%D0%B8%D0%B7%D0%B8%20%D0%BD%D0%B0%20%D1%87%D0%B0%D0%BB%D0%B3%D0%B0%20%D0%BF%D0%B5%D1%81%D0%BD%D0%B8%202%20https%3A%2F%2Ftonipetrova.wordpress.com%2F2017%2F01%2F13%2F%25d0%25b0%25d0%25bd%25d0%25b0%25d0%25bb%25d0%25b8%25d0%25b7%25d0%25b8-%25d0%25bd%25d0%25b0-%25d1%2587%25d0%25b0%25d0%25bb%25d0%25b3%25d0%25b0-%25d0%25bf%25d0%25b5%25d1%2581%25d0%25bd%25d0%25b8-2%2F\" target=\"_blank\" title=\"Click to share on WhatsApp\"><span></span><span class=\"sharing-screen-reader-text\">Click to share on WhatsApp (Opens in new window)</span></a></li><li class=\"share-skype\"><a rel=\"nofollow\" data-shared=\"sharing-skype-155\" class=\"share-skype sd-button share-icon no-text\" href=\"https://tonipetrova.wordpress.com/2017/01/13/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8-%d0%bd%d0%b0-%d1%87%d0%b0%d0%bb%d0%b3%d0%b0-%d0%bf%d0%b5%d1%81%d0%bd%d0%b8-2/?share=skype&amp;nb=1\" target=\"_blank\" title=\"Click to share on Skype\"><span></span><span class=\"sharing-screen-reader-text\">Click to share on Skype (Opens in new window)</span></a></li><li class=\"share-end\"></li><li class=\"share-end\"></li></ul></div></div></div></div></div><div class=\"sharedaddy sd-block sd-like jetpack-likes-widget-wrapper jetpack-likes-widget-loaded\" id=\"like-post-wrapper-121559043-155-5b467b6ef1454\" data-src=\"//widgets.wp.com/likes/index.html?ver=20180319#blog_id=121559043&amp;post_id=155&amp;origin=tonipetrova.wordpress.com&amp;obj_id=121559043-155-5b467b6ef1454\" data-name=\"like-post-frame-121559043-155-5b467b6ef1454\"><h3 class=\"sd-title\">Like this:</h3><div class=\"likes-widget-placeholder post-likes-widget-placeholder\" style=\"height: 55px; display: none;\"><span class=\"button\"><span>Like</span></span> <span class=\"loading\">Зареждане...</span></div><iframe class=\"post-likes-widget jetpack-likes-widget\" name=\"like-post-frame-121559043-155-5b467b6ef1454\" height=\"55px\" width=\"100%\" frameborder=\"0\" src=\"//widgets.wp.com/likes/index.html?ver=20180319#blog_id=121559043&amp;post_id=155&amp;origin=tonipetrova.wordpress.com&amp;obj_id=121559043-155-5b467b6ef1454\"></iframe><span class=\"sd-text-color\"></span><a class=\"sd-link-color\"></a></div></div>\r\n							\r\n						</div>\r\n\r\n					\r\n					<footer class=\"post-footer\">\r\n\r\n						\r\n							<div class=\"post-tags\">\r\n\r\n								<a href=\"https://tonipetrova.wordpress.com/tag/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8/\" rel=\"tag\">Анализи</a><a href=\"https://tonipetrova.wordpress.com/tag/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8-%d0%bd%d0%b0-%d1%87%d0%b0%d0%bb%d0%b3%d0%b0-%d0%bf%d0%b5%d1%81%d0%bd%d0%b8/\" rel=\"tag\">Анализи на чалга песни</a><a href=\"https://tonipetrova.wordpress.com/tag/%d0%b0%d0%bd%d0%b0%d0%bb%d0%b8%d0%b7%d0%b8-%d0%bd%d0%b0-%d1%87%d0%b0%d0%bb%d0%b3%d0%b0-%d0%bf%d0%b5%d1%81%d0%bd%d0%b8-2/\" rel=\"tag\">Анализи на чалга песни 2</a><a href=\"https://tonipetrova.wordpress.com/tag/%d1%87%d0%b0%d0%bb%d0%b3%d0%b0/\" rel=\"tag\">Чалга</a>\r\n							</div>\r\n\r\n						\r\n							<div class=\"entry-author author-avatar-show\">\r\n				<div class=\"author-avatar\">\r\n			<img alt=\"\" src=\"https://1.gravatar.com/avatar/ae432486ede7309de7f6c5fed5a19c86?s=100&amp;d=identicon&amp;r=G\" class=\"avatar avatar-100 grav-hashed grav-hijack\" height=\"100\" width=\"100\" id=\"grav-ae432486ede7309de7f6c5fed5a19c86-0\">		</div><!-- .author-avatar -->\r\n		\r\n		<div class=\"author-heading\">\r\n			<h2 class=\"author-title\">Published by <span class=\"author-name\">Тони</span></h2>\r\n		</div><!-- .author-heading -->\r\n\r\n		<p class=\"author-bio\">\r\n						<a class=\"author-link\" href=\"https://tonipetrova.wordpress.com/author/krelekrele/\" rel=\"author\">\r\n				Всички публикации от Тони			</a>\r\n		</p><!-- .author-bio -->\r\n	</div><!-- .entry-auhtor -->\r\n\r\n					</footer>\r\n\r\n				</div>',1,11),(2,2,3,'Математиката','2018-07-19 07:16:16','Некви работи за математиката','https://tonipetrova.files.wordpress.com/2017/01/d0b8d0b7d182d0b5d0b3d0bbd0b5d0bd-d184d0b0d0b9d0bb-1.jpg','<div class=\"post-content\">\r\n\r\n							<p style=\"text-align:center;\"><strong><em>Три пъти „ура“ за математиката!</em></strong></p>\r\n<p><strong>П</strong>иша, смятам всякакви числа без ред.</p>\r\n<p>И какво излиза? Формулите на Виет!</p>\r\n<p>Че защо му бе притрябвало да ги измисля?</p>\r\n<p>Да седя аз тук сега и да се мъча, и да мисля?</p>\r\n<p>После ме извикват на дъската.</p>\r\n<p>„Да, така..”. (в главата си усещам празнотата).</p>\r\n<p>И накрая, като за поанта,</p>\r\n<p>забравих за проклетата дискриминанта!</p>\r\n<p>Корени, един куп дроби</p>\r\n<p>се нареждат, теглят израза ми като роби.</p>\r\n<p>Ужас е, направо някаква фанатика.</p>\r\n<p>Да си знаете, обичам математика! <img draggable=\"false\" class=\"emoji\" alt=\"\" src=\"https://s0.wp.com/wp-content/mu-plugins/wpcom-smileys/twemoji/2/svg/1f609.svg\"></p>\r\n<p>&nbsp;</p>\r\n<div id=\"atatags-370373-5b4685a901828\">\r\n        <script type=\"text/javascript\">\r\n            __ATA.cmd.push(function() {\r\n                __ATA.initVideoSlot(\'atatags-370373-5b4685a901828\', {\r\n                    sectionId: \'370373\',\r\n                    format: \'inread\'\r\n                });\r\n            });\r\n        </script>\r\n    </div>		<div class=\"wpcnt\">\r\n			<div class=\"wpa wpmrec\">\r\n				<span class=\"wpa-about\">Advertisements</span>\r\n				<div class=\"u\">		<div style=\"padding-bottom:15px;width:300px;height:250px;float:left;margin-right:5px;margin-top:0px\">\r\n		<div id=\"atatags-26942-5b4685a90184e\">\r\n			<script type=\"text/javascript\">\r\n			__ATA.cmd.push(function() {\r\n				__ATA.initSlot(\'atatags-26942-5b4685a90184e\',  {\r\n					collapseEmpty: \'before\',\r\n					sectionId: \'26942\',\r\n					width: 300,\r\n					height: 250\r\n				});\r\n			});\r\n			</script>\r\n		</div></div>		<div style=\"padding-bottom:15px;width:300px;height:250px;float:left;margin-top:0px\">\r\n		<div id=\"atatags-114160-5b4685a901850\">\r\n			<script type=\"text/javascript\">\r\n			__ATA.cmd.push(function() {\r\n				__ATA.initSlot(\'atatags-114160-5b4685a901850\',  {\r\n					collapseEmpty: \'before\',\r\n					sectionId: \'114160\',\r\n					width: 300,\r\n					height: 250\r\n				});\r\n			});\r\n			</script>\r\n		</div></div></div>\r\n				\r\n			</div>\r\n		</div>\r\n							\r\n				</div>',1,22),(3,2,2,'Deer Dance','2018-07-10 00:00:00',NULL,'https://tonipetrova.files.wordpress.com/2017/04/beautiful-nature-015.jpg?w=816','Ставаш сутрин, гледаш – мрак и облаци,\r\nобръщаш внимание на незначителни подробности.\r\nВсеки те дразни, всеки е ръб.\r\nНищо не тръгва добре, животът е тъп.\r\n\r\nИ единственото, което би оправило нещата\r\nе да забиеш юмрук в на някого главата.\r\nНо не се притеснявай. Върви по своя път.\r\nДнес просто такъв е денят.\r\n\r\nСтъпваш в кална локва,\r\nпанталонът ти е мокър,\r\nсякаш някой е направил зла магия\r\nи нарочно иска да ти прави гадории.\r\n\r\nХората те обиждат, навярно защото също не им върви.\r\nБил си скучен, мързелив…Я по-добре си тръгни.\r\nИска ти се да им докажеш, че преди теб ще умрат,\r\nзащото днес просто такъв е денят.\r\n\r\nХодиш на работа, училище,\r\nмислиш за пари, тревоги, същинско житейско мъчилище!\r\nБяла птица с разперени крила? Друг път.\r\nКрилата може да си завре в дупка.\r\n\r\nСвобода не очаквай.\r\nНяма я. Но не се и оплаквай.\r\nЩе продължават хората да те подритват,\r\nбезинтересно им е ти какви чувства изпитваш.\r\n\r\nБи било най-добре да се срути небето\r\nгръмогласно. На всички ще е ясно\r\nкого ще затрупа –\r\nонези, на които, нали, много им пука.\r\n\r\nЩе ти кажа какво.\r\nТегли една черта и върви по своя път,\r\nзащото днес просто такъв е денят.',1,553);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale_name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A0D15379B901B6D2` (`locale_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'bg'),(2,'en'),(0,'neutral');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6A57698A6A` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (3,'ROLE_ADMIN'),(2,'ROLE_AUTHOR'),(1,'ROLE_USER');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_registered` datetime NOT NULL,
  `user_description` longtext COLLATE utf8_unicode_ci,
  `profile_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  KEY `IDX_1483A5E9D60322AC` (`role_id`),
  CONSTRAINT `FK_1483A5E9D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'cyecize','ceci2205@abv.bg','$2y$13$amBDByBTcMLbNknxRWAAa.E2Rab/TJbKBEZzWp2baXO36wRUCwaS6','2018-07-08 17:46:54',NULL,NULL,3),(3,'ignat','ignat@abv.bg','$2y$13$jB2pywtOBta.sCXScHISsOrXw6cCY7giHl7sW7ZQqnCAltz8mbPRK','2018-07-08 17:54:39',NULL,NULL,1),(7,'elsmokio','smoke@abv.bg','$2y$13$iLXFWJWxA/.740TD0mAKtutPyUv5oELFPacUFKmz62609iyi25vqG','2018-07-11 23:37:29',NULL,NULL,1),(8,'golcho','golcho@abv.bg','$2y$13$9xQywmWHX/71Ju5rgIEbxO6Z/7DiFFasLMyKmQNRtRNfew1H2muKW','2018-07-12 00:02:57',NULL,NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'jesterous'
--

--
-- Dumping routines for database 'jesterous'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-12  1:45:35
