-- --------------------------------------------------------
-- Servidor:                     179.188.16.30
-- Versão do servidor:           5.7.32-35-log - Percona Server (GPL), Release 35, Revision 5688520
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para amcatd_d
CREATE DATABASE IF NOT EXISTS `amcatd_d` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;
USE `amcatd_d`;

-- --------------------------------------------------------
-- Servidor:                     179.188.16.30
-- Versão do servidor:           5.7.32-35-log - Percona Server (GPL), Release 35, Revision 5688520
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura para tabela amcatd_d.about_us
CREATE TABLE IF NOT EXISTS `about_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `sub_title` varchar(255) COLLATE latin1_general_ci DEFAULT '',
  `content` text COLLATE latin1_general_ci,
  `video` varchar(255) COLLATE latin1_general_ci DEFAULT '',
  `image_file` varchar(255) COLLATE latin1_general_ci DEFAULT '',
  `image_path` varchar(255) COLLATE latin1_general_ci DEFAULT '',
  `image_file_02` varchar(255) COLLATE latin1_general_ci DEFAULT '',
  `image_path_02` varchar(255) COLLATE latin1_general_ci DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.about_us: ~1 rows (aproximadamente)
INSERT INTO `about_us` (`id`, `title`, `sub_title`, `content`, `video`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 'About us', 'Better Design Better Experience', 'Somos uma associaÃ§Ã£o de moradores dedicada a promover o bem-estar e a qualidade de vida em nossa comunidade. Unidos, buscamos soluÃ§Ãµes para questÃµes como infraestrutura, seguranÃ§a e serviÃ§os essenciais, alÃ©m de fomentar a realizaÃ§Ã£o de eventos culturais e atividades de lazer. Atuamos como a voz dos moradores junto aos Ã³rgÃ£os pÃºblicos, reivindicando melhorias que atendam Ã s necessidades coletivas. Nosso trabalho inclui a organizaÃ§Ã£o de campanhas de reciclagem, eventos festivos e a busca constante por soluÃ§Ãµes para problemas como iluminaÃ§Ã£o, pavimentaÃ§Ã£o e seguranÃ§a no bairro. Juntos, fortalecemos nossa comunidade e construÃ­mos um ambiente melhor para todos.', 'https://www.youtube.com/watch?v=pPv3cU61_-M', 'abf6d9dc-d0ff-4296-9b2b-e098db940512.jpg', '/wwwroot/images/abf6d9dc-d0ff-4296-9b2b-e098db940512.jpg', 'c7bb7885-f395-4b93-b2f6-2e0d167e59bc.jpg', '/wwwroot/images/c7bb7885-f395-4b93-b2f6-2e0d167e59bc.jpg', 1, '2025-06-10 14:46:18', 1, '2025-09-17 22:06:36');

-- Copiando estrutura para tabela amcatd_d.accountability
CREATE TABLE IF NOT EXISTS `accountability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE latin1_general_ci,
  `entry` decimal(15,2) DEFAULT '0.00',
  `exit` decimal(15,2) DEFAULT '0.00',
  `balance` decimal(15,2) DEFAULT '0.00',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.accountability: ~1 rows (aproximadamente)
INSERT INTO `accountability` (`id`, `name`, `date`, `description`, `entry`, `exit`, `balance`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 'mes e agosto 2025', '2025-11-15', 'descricao', 3.00, 4.00, 4.00, 1, '2025-08-26 10:11:16', 1, '2025-08-28 08:43:14');

-- Copiando estrutura para tabela amcatd_d.collaborator
CREATE TABLE IF NOT EXISTS `collaborator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `position` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `image_file` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `image_path` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.collaborator: ~5 rows (aproximadamente)
INSERT INTO `collaborator` (`id`, `name`, `position`, `description`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(2, 'Ludmila', 'SecretÃ¡ria', 'Ela atua como ponto de contato entre os moradores e a diretoria, garantindo o fluxo de informaÃ§Ãµes e a comunicaÃ§Ã£o eficiente dentro da associaÃ§Ã£o.\r\n\r\n', 'LUDI.jpg', 'wwwroot/images/LUDI.jpg', 1, '2025-06-23 18:01:33', 1, '2025-09-17 22:11:12'),
	(3, 'Tico', 'Zelador', 'Ele Ã© responsÃ¡vel por garantir que as Ã¡reas compartilhadas estejam sempre em bom estado de conservaÃ§Ã£o, realizando serviÃ§os de limpeza, pequenos reparos e zelando pela seguranÃ§a local.', 'tico', 'wwwroot/images/tico', 1, '2025-06-25 18:17:59', 1, '2025-09-17 22:13:12'),
	(4, 'Evaldo', 'Presidente', 'Sua funÃ§Ã£o Ã© zelar pelo bom funcionamento da associaÃ§Ã£o, tomar decisÃµes estratÃ©gicas, articular parcerias e representar a comunidade junto aos Ã³rgÃ£os pÃºblicos e instituiÃ§Ãµes.', 'evaldo.jpg', 'wwwroot/images/evaldo.jpg', 1, '2025-06-26 09:57:17', 1, '2025-09-17 22:17:35'),
	(5, 'John ', 'Diretor Financeiro', 'Entre suas principais atribuiÃ§Ãµes estÃ£o o controle de receitas e despesas, elaboraÃ§Ã£o de relatÃ³rios financeiros, prestaÃ§Ã£o de contas aos associados e apoio na definiÃ§Ã£o de orÃ§amentos para projetos e eventos.', 'IMG_6247.jpg', 'wwwroot/images/IMG_6247.jpg', 1, '2025-09-17 22:19:00', 1, '2025-09-17 22:19:00'),
	(6, 'SilvÃ¢nia', 'Diretora de ComunicaÃ§Ã£o', 'Ã‰ responsÃ¡vel por cuidar da imagem da associaÃ§Ã£o e garantir que as informaÃ§Ãµes cheguem de forma clara e acessÃ­vel a todos os moradores. Sua funÃ§Ã£o Ã© divulgar as aÃ§Ãµes, eventos, projetos e comunicados por meio de diferentes canais, como redes s', 'IMG_6219.jpg', 'wwwroot/images/IMG_6219.jpg', 1, '2025-09-17 23:12:11', 1, '2025-09-17 23:12:11');

-- Copiando estrutura para tabela amcatd_d.group
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.group: ~3 rows (aproximadamente)
INSERT INTO `group` (`id`, `name`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 'Admin', 1, '2025-06-07 08:36:24', 1, '2025-09-16 15:31:54'),
	(3, 'Comum', 1, '2025-06-09 16:15:06', 1, '2025-06-09 16:15:06'),
	(4, 'test 2', 1, '2025-09-16 14:00:32', 1, '2025-09-17 18:39:24');

-- Copiando estrutura para tabela amcatd_d.home
CREATE TABLE IF NOT EXISTS `home` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mini_title_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `sub_title` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `content` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `image_file` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `image_path` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `video` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `button` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `active` bit(1) NOT NULL DEFAULT b'1',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.home: ~2 rows (aproximadamente)
INSERT INTO `home` (`id`, `mini_title_id`, `title`, `sub_title`, `content`, `image_file`, `image_path`, `video`, `button`, `active`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 1, 'Bem vindo ao portal da AssociaÃ§Ã£o', 'AssociaÃ§Ã£o de Moradores do Bairro Teixeira Dias', 'Seja bem-vindo ao site da nossa AssociaÃ§Ã£o!\r\nAqui vocÃª fica por dentro de todas as novidades, atualizaÃ§Ãµes, eventos e aÃ§Ãµes que estÃ£o acontecendo em nossa comunidade. Acompanhe, participe e ajude a construir um bairro cada vez melhor para todos nÃ', 'IMG_6208.jpg', 'wwwroot/images/IMG_6208.jpg', 'RD3RtRmtSA8', 'Explore Work', b'1', 1, '2025-06-12 13:08:53', 1, '2025-09-17 22:27:28'),
	(2, 1, 'Juntos somos mais fortes!', 'AssociaÃ§Ã£o de Moradores do Bairro Teixeira Dias', 'Juntos, fortalecemos nossa comunidade e construÃ­mos um ambiente melhor para todos.', 'e7020880-4c5a-477d-a0e5-0c238cfed0d3.jpg', 'wwwroot/images/e7020880-4c5a-477d-a0e5-0c238cfed0d3.jpg', 'https://www.youtube.com/watch?v=1IMEKF1rPqY', 'AMCATD', b'1', 1, '2025-09-17 23:31:00', 1, '2025-09-17 23:31:00');

-- Copiando estrutura para tabela amcatd_d.journal
CREATE TABLE IF NOT EXISTS `journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` int(11) NOT NULL,
  `content` text COLLATE latin1_general_ci,
  `image_file` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.journal: ~1 rows (aproximadamente)
INSERT INTO `journal` (`id`, `title`, `month`, `year`, `content`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(2, '2', 2, 2, '2', 'cupon-JRB3M51AQGAX.png', 'wwwroot/images/cupon-JRB3M51AQGAX.png', 1, '2025-09-04 11:21:53', 1, '2025-09-04 17:57:10');

-- Copiando estrutura para tabela amcatd_d.mini_title
CREATE TABLE IF NOT EXISTS `mini_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_01` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title_02` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title_03` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title_04` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `title_05` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.mini_title: ~1 rows (aproximadamente)
INSERT INTO `mini_title` (`id`, `title_01`, `title_02`, `title_03`, `title_04`, `title_05`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 'Bem vindo so site AMCATD', 'Aqui vocÃª encontrarÃ¡ todas as informaÃ§Ãµes', 'NotÃ­cias, eventos, prestaÃ§Ã£o de contas', 'Fique bem informado sobre tudo o que acontece no bairro', 'E na AssociaÃ§Ã£o dos Moradores do Conjunto AntÃ´nio Teixeira Dias', 1, '2025-06-11 21:10:15', 1, '2025-09-17 22:31:10');

-- Copiando estrutura para tabela amcatd_d.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `text` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `redactor` varchar(150) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `image_file_02` varchar(255) DEFAULT NULL,
  `image_path_02` varchar(500) DEFAULT NULL,
  `image_file_03` varchar(255) DEFAULT NULL,
  `image_path_03` varchar(500) DEFAULT NULL,
  `image_file_04` varchar(255) DEFAULT NULL,
  `image_path_04` varchar(500) DEFAULT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela amcatd_d.news: ~3 rows (aproximadamente)
INSERT INTO `news` (`id`, `title`, `date`, `text`, `category`, `redactor`, `video`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `image_file_03`, `image_path_03`, `image_file_04`, `image_path_04`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(3, 'sento e me acabo', '2025-09-13', 'eu sou maria cruzeirense, e 11/09 hulk vai cabar cmg ', 'Volei', 'test', 'https://www.youtube.com/watch?v=iiW2ImJLW0c', 'news_external.jpg', '/wwwroot/images/news_external.jpg', 'news_slide_01.jpg', '/wwwroot/images/news_slide_01.jpg', 'news_slide_02.jpg', '/wwwroot/images/news_slide_02.jpg', 'news_slide_03.jpg', '/wwwroot/images/news_slide_03.jpg', 1, '2025-09-02 22:33:43', 1, '2025-09-10 23:13:00'),
	(4, '3', '2025-09-05', '3', '3', '3', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-09-05 12:50:17', 1, '2025-09-05 12:50:36'),
	(8, '4', '2025-09-06', '3', '3', '3', '3', 'cupon-JRB3M51AQGAX.png', '/wwwroot/images/cupon-JRB3M51AQGAX.png', 'cupon-JRB3M51AQGAX.png', '/wwwroot/images/cupon-JRB3M51AQGAX.png', 'cupon-JRB3M51AQGAX.png', '/wwwroot/images/cupon-JRB3M51AQGAX.png', 'cupon-JRB3M51AQGAX.png', '/wwwroot/images/cupon-JRB3M51AQGAX.png', 1, '2025-09-06 07:48:00', 1, '2025-09-06 07:48:00');

-- Copiando estrutura para tabela amcatd_d.project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `description` text COLLATE latin1_general_ci,
  `description_internal` text COLLATE latin1_general_ci,
  `start` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `contact` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_02` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_02` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_03` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_03` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_04` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_04` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `name_responsible` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `position` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `video` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.project: ~3 rows (aproximadamente)
INSERT INTO `project` (`id`, `name`, `description`, `description_internal`, `start`, `active`, `contact`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `image_file_03`, `image_path_03`, `image_file_04`, `image_path_04`, `name_responsible`, `position`, `video`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 'CURSO DE PINTURA & DECOUPAGEM', 'O curso de Pintura e Decoupage para senhoras Ã© uma atividade voltada ao desenvolvimento da criatividade, bem-estar e convivÃªncia social. Nele, as participantes aprendem tÃ©cnicas de pintura em diferentes superfÃ­cies (como madeira, tecido e vidro) e a arte da decoupage â€“ que consiste em decorar objetos com recortes de papel, criando peÃ§as Ãºnicas e personalizadas.  AlÃ©m de estimular a expressÃ£o artÃ­stica, o curso promove momentos de relaxamento, troca de experiÃªncias e fortalecimento de laÃ§os entre as moradoras. Ã‰ uma excelente oportunidade para adquirir novas habilidades, desenvolver talentos e transformar a arte em fonte de renda ou lazer.', 'TerÃ§as e Quintas', '2025-07-11', 0, '(31) 99952-6501', 'IMG_1616.jpg', '/wwwroot/images/IMG_1616.jpg', 'IMG_1601.jpg', '/wwwroot/images/IMG_1601.jpg', 'IMG_1564.jpg', '/wwwroot/images/IMG_1564.jpg', 'IMG_1593.jpg', '/wwwroot/images/IMG_1593.jpg', 'Laura', '14h00 Ã s 15h00 - 15h00 Ã s16h00', 'https://www.youtube.com/watch?v=t1FPv3YyxjA', 1, '2025-07-01 11:15:14', 1, '2025-09-17 22:55:33'),
	(2, 'AULA DE CAPOEIRA', 'As aulas gratuitas de capoeira sÃ£o uma atividade cultural e esportiva aberta a adultos e crianÃ§as da comunidade. A capoeira Ã© uma expressÃ£o brasileira que mistura luta, danÃ§a, mÃºsica e acrobacias, promovendo o desenvolvimento fÃ­sico, mental e social dos participantes.  Durante as aulas, os alunos aprendem movimentos corporais, ritmos com instrumentos tÃ­picos (como berimbau e atabaque), alÃ©m de valores como respeito, disciplina, cooperaÃ§Ã£o e inclusÃ£o.  Ã‰ uma Ã³tima oportunidade para praticar atividade fÃ­sica, preservar a cultura afro-brasileira e fortalecer os laÃ§os entre moradores de diferentes idades, em um ambiente saudÃ¡vel, educativo e acolhedor.', 'Segundas e Quartas', '2025-09-17', 0, '(31) 99952-6501', 'bc629132-5c72-4d27-9069-24b02055d801.jpg', '/wwwroot/images/bc629132-5c72-4d27-9069-24b02055d801.jpg', 'bc629132-5c72-4d27-9069-24b02055d801.jpg', '/wwwroot/images/bc629132-5c72-4d27-9069-24b02055d801.jpg', 'bc629132-5c72-4d27-9069-24b02055d801.jpg', '/wwwroot/images/bc629132-5c72-4d27-9069-24b02055d801.jpg', 'bc629132-5c72-4d27-9069-24b02055d801.jpg', '/wwwroot/images/bc629132-5c72-4d27-9069-24b02055d801.jpg', 'Mestrando CamaleÃ£o', '19h00 Ã s 21h00', 'https://www.youtube.com/watch?v=1IMEKF1rPqY', 1, '2025-09-17 23:02:16', 1, '2025-09-17 23:02:16'),
	(3, 'PROJETO CUIDAR', 'Carona SolidÃ¡ria', 'O Projeto Cuidar se trata de uma carona solidÃ¡ria para pessoas que nÃ£o possuem condiÃ§Ãµes de arcar com o transporte para suas consultas mÃ©dicas .', '2025-09-17', 0, '(31) 99952-6501', 'IMG_6264.jpg', '/wwwroot/images/IMG_6264.jpg', 'IMG_6262.jpg', '/wwwroot/images/IMG_6262.jpg', 'd14d923d-1e76-4412-bbed-9bfc7ca02c97.jpg', '/wwwroot/images/d14d923d-1e76-4412-bbed-9bfc7ca02c97.jpg', 'cb6f2ec7-4e13-4bb4-8433-0e21f144142b.jpg', '/wwwroot/images/cb6f2ec7-4e13-4bb4-8433-0e21f144142b.jpg', 'Chileno e Marcelo', 'Seg Ã  Sex - 08h00 Ã s 17h00', 'https://www.youtube.com/watch?v=1IMEKF1rPqY', 1, '2025-09-17 23:24:19', 1, '2025-09-17 23:24:19');

-- Copiando estrutura para tabela amcatd_d.property
CREATE TABLE IF NOT EXISTS `property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `address` text COLLATE latin1_general_ci,
  `rented` bit(1) DEFAULT NULL,
  `value` decimal(15,2) DEFAULT NULL,
  `tenant` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `rental_start_date` date DEFAULT NULL,
  `rental_end_date` date DEFAULT NULL,
  `category` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `comment` text COLLATE latin1_general_ci,
  `image_file` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_02` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_02` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_03` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_03` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_04` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_04` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_05` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_05` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_06` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_06` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_07` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_07` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_08` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_08` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_09` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_09` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_10` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_10` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_11` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_11` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_12` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_12` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_13` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_13` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_14` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_14` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_file_15` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `image_path_15` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Copiando dados para a tabela amcatd_d.property: ~1 rows (aproximadamente)
INSERT INTO `property` (`id`, `name`, `address`, `rented`, `value`, `tenant`, `contact`, `rental_start_date`, `rental_end_date`, `category`, `comment`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `image_file_03`, `image_path_03`, `image_file_04`, `image_path_04`, `image_file_05`, `image_path_05`, `image_file_06`, `image_path_06`, `image_file_07`, `image_path_07`, `image_file_08`, `image_path_08`, `image_file_09`, `image_path_09`, `image_file_10`, `image_path_10`, `image_file_11`, `image_path_11`, `image_file_12`, `image_path_12`, `image_file_13`, `image_path_13`, `image_file_14`, `image_path_14`, `image_file_15`, `image_path_15`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(3, 'Bar do ClÃ³vis', 'Av. Antonio Teixeira Dias, 2050', b'1', 850.00, 'Clovis', '31994070754', '2025-08-18', '2025-08-20', 'Bar', 'O bar do ClÃ³vis Ã© um ponto tÃ­pico do Teixeira Dias.', 'property.jpg', '/wwwroot/images/property.jpg', 'property_02.jpg', '/wwwroot/images/property_02.jpg', 'property_03.jpg', '/wwwroot/images/property_03.jpg', 'property_04.jpg', '/wwwroot/images/property_04.jpg', 'property_05.jpg', '/wwwroot/images/property_05.jpg', 'property_06.jpg', '/wwwroot/images/property_06.jpg', 'property_07.jpg', '/wwwroot/images/property_07.jpg', 'property_08.jpg', '/wwwroot/images/property_08.jpg', 'Captura de tela 2025-05-08 160614.png', '/wwwroot/images/Captura de tela 2025-05-08 160614.png', 'Captura de tela 2025-05-27 151630.png', '/wwwroot/images/Captura de tela 2025-05-27 151630.png', 'Captura de tela 2025-05-27 151630.png', '/wwwroot/images/Captura de tela 2025-05-27 151630.png', 'Captura de tela 2025-05-08 160614.png', '/wwwroot/images/Captura de tela 2025-05-08 160614.png', 'Captura de tela 2025-05-08 160614.png', '/wwwroot/images/Captura de tela 2025-05-08 160614.png', 'Captura de tela 2025-05-08 160614.png', '/wwwroot/images/Captura de tela 2025-05-08 160614.png', 'Captura de tela 2025-05-08 160614.png', '/wwwroot/images/Captura de tela 2025-05-08 160614.png', 1, '2025-08-19 11:31:08', 1, '2025-08-28 13:54:44');

-- Copiando estrutura para tabela amcatd_d.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `active` bit(1) NOT NULL DEFAULT b'1',
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `created_user_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela amcatd_d.user: ~7 rows (aproximadamente)
INSERT INTO `user` (`id`, `group_id`, `name`, `email`, `password`, `active`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
	(1, 1, 'ederson', 'edersonfs@gmail.com', '$2y$10$HjRgm/8THIZ/5DFqwlkJje14.iCMPgGtTlqhah273693GpTF.WRPC', b'1', '61neGUt64ZL._AC_SL1000_.jpg', 'wwwroot/images/61neGUt64ZL._AC_SL1000_.jpg', 1, '2025-06-04 18:58:24', 1, '2025-09-15 16:32:28'),
	(4, 1, 'samuel', 'samuel@gmail.com', '$2y$10$mPrrQ6toYyrDPreiZHIkCucvkrIH88U1slA1..jrbfx6rvs2HN1s6', b'0', NULL, NULL, 1, '2025-06-04 18:58:24', 1, '2025-06-09 16:19:33'),
	(8, 3, 'asas', 'sasas@gmail.com', '$2y$10$2Se.DycsgIE34YXfYQrQWumjV8O0iEfQ29r33/D/zwTXRw4Mv/qLy', b'1', 'news_slide_03.jpg', 'wwwroot/images/news_slide_03.jpg', 1, '2025-09-12 09:04:55', 1, '2025-09-12 09:04:55'),
	(9, 3, 'asas', 'sasas4@gmail.com', '$2y$10$c5VCkWDNvWLtJ2BekE5Is.LK74cQNm0oCHhV6867j/phE/G42spSa', b'1', NULL, NULL, 1, '2025-09-12 09:04:55', 1, '2025-09-12 09:04:55'),
	(11, 3, 'asas', 'sasas455@gmail.com', '$2y$10$FA0tO6qmTUiAZWTmyKON7O2COIAsMk5ibMrwcLXkK4gO01MsSTGPu', b'1', NULL, NULL, 1, '2025-09-12 09:04:55', 1, '2025-09-12 09:04:55'),
	(12, 3, 'asas', 'sasas4555@gmail.com', '$2y$10$B2sb2sZrnHUzMw.wYKfyW.EXHPAzE7StS39t0SHHrbh88gnDZCyDu', b'0', NULL, NULL, 1, '2025-09-12 09:04:55', 1, '2025-09-12 09:04:55'),
	(14, 1, 'asas', 'asas222@gmaill.com', '$2y$10$WoJaoZvWbMQ.RE7SZ/3m/u072ANVArlkKCek2AGxMCDfiecgfAe4O', b'1', 'news_slide_01.jpg', 'wwwroot/images/news_slide_01.jpg', 1, '2025-09-15 17:02:46', 1, '2025-09-15 17:02:46');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
