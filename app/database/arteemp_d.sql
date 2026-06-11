-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/06/2026 às 23:51
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `arteemp_d`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `little_description` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `content` text DEFAULT NULL,
  `video` varchar(255) DEFAULT '',
  `image_file` varchar(255) DEFAULT '',
  `image_path` varchar(255) DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `about_us`
--

INSERT INTO `about_us` (`id`, `title`, `little_description`, `description`, `content`, `video`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'ABOUT US', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temp orincididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'asasas', 'https://www.youtube.com/watch?v=pPv3cU61_-M', 'about.jpg', '/wwwroot/images/about.jpg', 1, '2025-06-10 14:46:18', 1, '2026-05-07 23:49:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `text_02` text NOT NULL,
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
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `blog`
--

INSERT INTO `blog` (`id`, `title`, `date`, `description`, `text`, `text_02`, `category`, `redactor`, `video`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `image_file_03`, `image_path_03`, `image_file_04`, `image_path_04`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'as', '2026-05-14', 'as', 'as', 'as', 'as', 'as', '', 'preview.png', 'wwwroot/images/preview.png', 'preview.png', 'wwwroot/images/preview.png', 'preview.png', 'wwwroot/images/preview.png', 'preview.png', 'wwwroot/images/preview.png', 1, '2026-05-12 18:32:07', 1, '2026-05-12 21:47:37'),
(3, 'Assembleia geral', '2025-09-13', '', 'A AG ira ocorrer no dia 14/10 na AssociaÃ§Ã£o. O horÃ¡rio de inÃ­cio serÃ¡ Ã  partir das 12h.', '', 'Volei', 'Ederson', 'https://www.youtube.com/watch?v=iiW2ImJLW0c', 'news_external.jpg', '/wwwroot/images/news_external.jpg', 'news_slide_01.jpg', '/wwwroot/images/news_slide_01.jpg', 'news_slide_02.jpg', '/wwwroot/images/news_slide_02.jpg', 'news_slide_03.jpg', '/wwwroot/images/news_slide_03.jpg', 1, '2025-09-02 22:33:43', 1, '2025-10-29 20:03:03'),
(10, 'title', '2025-11-03', 'description', 'text', 'text 02', 'category', 'author', 'https://www.youtube.com/watch?v=yqWTs42rgS4&list=RDyqWTs42rgS4&start_radio=1', 'news_external.jpg', '/wwwroot/images/news_external.jpg', 'news_slide_01.jpg', '/wwwroot/images/news_slide_01.jpg', 'news_slide_02.jpg', '/wwwroot/images/news_slide_02.jpg', 'news_slide_03.jpg', '/wwwroot/images/news_slide_03.jpg', 1, '2025-11-01 07:27:35', 1, '2026-05-12 21:21:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `budget`
--

CREATE TABLE `budget` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `internal_client_id` int(11) NOT NULL,
  `number` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sale` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `budget`
--

INSERT INTO `budget` (`id`, `company_id`, `internal_client_id`, `number`, `status`, `cost`, `sale`, `profit`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(7, 1, 3, '1', 'approved', 465.00, 750.00, 285.00, 'img_6a2b0bf6c5f2b5.03503400.jpg', 'wwwroot/images/img_6a2b0bf6c5f2b5.03503400.jpg', 1, '2026-06-11 13:27:44', 1, '2026-06-11 18:50:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `budget_item`
--

CREATE TABLE `budget_item` (
  `id` int(11) NOT NULL,
  `budget_id` int(11) NOT NULL,
  `budget_item_type` varchar(50) NOT NULL,
  `material_id` int(11) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `fixed_cost` int(11) DEFAULT NULL,
  `freight` decimal(10,0) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `width` decimal(15,2) DEFAULT NULL,
  `height` decimal(15,2) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT 0.00,
  `total` decimal(15,2) DEFAULT 0.00,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `budget_item`
--

INSERT INTO `budget_item` (`id`, `budget_id`, `budget_item_type`, `material_id`, `hours`, `fixed_cost`, `freight`, `description`, `quantity`, `width`, `height`, `unit_price`, `total`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(15, 7, 'MATERIAL', 25, NULL, NULL, NULL, 'aaa', 5, 4.00, 5.00, 25.00, 125.00, NULL, NULL, 1, '2026-06-11 14:17:14', 1, '2026-06-11 14:17:14'),
(16, 7, 'FIXED COST', NULL, NULL, 10, NULL, '', 0, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 1, '2026-06-11 14:20:15', 1, '2026-06-11 14:20:15'),
(17, 7, 'LABOR', NULL, 5, NULL, NULL, '', 0, 0.00, 0.00, 5.00, 25.00, NULL, NULL, 1, '2026-06-11 14:21:42', 1, '2026-06-11 14:21:42'),
(18, 7, 'FREIGHT', NULL, NULL, NULL, 300, '', 0, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 1, '2026-06-11 14:22:34', 1, '2026-06-11 14:22:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carousel`
--

CREATE TABLE `carousel` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_file_02` varchar(255) NOT NULL,
  `image_path_02` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carousel`
--

INSERT INTO `carousel` (`id`, `title`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `video`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'Main', 'transferir (1).png', 'wwwroot/images/transferir (1).png', 'transferir (1).png', '/wwwroot/images/transferir (1).png', 'https://www.youtube.com/watch?v=E8gmARGvPlI', 1, '2026-04-28 07:29:07', 1, '2026-04-30 18:01:58'),
(3, 'qwqw', 'arte-em-planejados.png', 'wwwroot/images/arte-em-planejados.png', 'arte-em-planejados.png', '/wwwroot/images/arte-em-planejados.png', 'vbvbv', 1, '2026-04-28 07:30:26', 1, '2026-04-28 12:50:12'),
(4, 'asasa', 'arte-em-planejados.png', 'wwwroot/images/arte-em-planejados.png', 'arte-em-planejados.png', 'wwwroot/images/arte-em-planejados.png', 'asasas', 1, '2026-04-28 09:45:15', 1, '2026-04-28 09:45:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `city` varchar(150) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `image_file_02` varchar(255) DEFAULT NULL,
  `image_path_02` varchar(255) DEFAULT NULL,
  `image_file_03` varchar(255) DEFAULT NULL,
  `image_path_03` varchar(255) DEFAULT NULL,
  `image_file_04` varchar(255) DEFAULT NULL,
  `image_path_04` varchar(255) DEFAULT NULL,
  `image_file_05` varchar(255) DEFAULT NULL,
  `image_path_05` varchar(255) DEFAULT NULL,
  `image_file_06` varchar(255) DEFAULT NULL,
  `image_path_06` varchar(255) DEFAULT NULL,
  `image_file_07` varchar(255) DEFAULT NULL,
  `image_path_07` varchar(255) DEFAULT NULL,
  `image_file_08` varchar(255) DEFAULT NULL,
  `image_path_08` varchar(255) DEFAULT NULL,
  `image_file_09` varchar(255) DEFAULT NULL,
  `image_path_09` varchar(255) DEFAULT NULL,
  `image_file_10` varchar(255) DEFAULT NULL,
  `image_path_10` varchar(255) DEFAULT NULL,
  `image_file_11` varchar(255) DEFAULT NULL,
  `image_path_11` varchar(255) DEFAULT NULL,
  `image_file_12` varchar(255) DEFAULT NULL,
  `image_path_12` varchar(255) DEFAULT NULL,
  `image_file_13` varchar(255) DEFAULT NULL,
  `image_path_13` varchar(255) DEFAULT NULL,
  `image_file_14` varchar(255) DEFAULT NULL,
  `image_path_14` varchar(255) DEFAULT NULL,
  `image_file_15` varchar(255) DEFAULT NULL,
  `image_path_15` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `client`
--

INSERT INTO `client` (`id`, `name`, `date`, `address`, `phone`, `email`, `active`, `city`, `state`, `description`, `video`, `image_file`, `image_path`, `image_file_02`, `image_path_02`, `image_file_03`, `image_path_03`, `image_file_04`, `image_path_04`, `image_file_05`, `image_path_05`, `image_file_06`, `image_path_06`, `image_file_07`, `image_path_07`, `image_file_08`, `image_path_08`, `image_file_09`, `image_path_09`, `image_file_10`, `image_path_10`, `image_file_11`, `image_path_11`, `image_file_12`, `image_path_12`, `image_file_13`, `image_path_13`, `image_file_14`, `image_path_14`, `image_file_15`, `image_path_15`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(2, 'Vital Imobiliária', '2026-05-06', 'Av. Marcehal Castelo branco, 265', '31994070754', 'edersonfs@gmail.com', 1, 'Belo Horizonte', 'MG', 'A realização de uma luta do UFC na Casa Branca já havia sido mencionada por Trump em outubro do ano passado.\r\n\r\nTrump mantém relação próxima com o presidente do UFC, Dana White, aliado político do republicano. A ligação entre os dois começou nos anos 2000, quando o Trump Taj Mahal, em Atlantic City, foi um dos poucos locais nos Estados Unidos a sediar eventos do UFC.\r\n\r\nWhite afirmou ao Sports Business Journal que a organização pretende gastar cerca de US$ 700 mil para substituir a grama do jardim sul da Casa Branca após o evento.\r\n\r\nDesde que voltou à presidência, Trump tem comparecido com frequência a lutas do UFC. A empresa controladora do campeonato, a TKO Group Holdings, não comentou o evento.', 'https://www.youtube.com/watch?v=4CiyKeSnSxk', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '010.jpg', 'wwwroot/images/010.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', '03.c6e1bd4348af6e420eba.jpg', 'wwwroot/images/03.c6e1bd4348af6e420eba.jpg', 1, '2026-05-06 14:33:24', 1, '2026-05-07 00:17:42'),
(3, 'a', '2026-05-07', 'as', '1', '1a', 1, '12', 'aws', 'as', 'as', '1.jpg', 'wwwroot/images/1.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-05-06 18:42:06', 1, '2026-05-06 18:42:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `collaborator`
--

CREATE TABLE `collaborator` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `image_file` varchar(255) NOT NULL DEFAULT '',
  `image_path` varchar(255) NOT NULL DEFAULT '',
  `order` int(11) DEFAULT NULL,
  `instagram` varchar(50) DEFAULT NULL,
  `facebook` varchar(50) DEFAULT NULL,
  `linkedin` varchar(50) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `collaborator`
--

INSERT INTO `collaborator` (`id`, `name`, `position`, `description`, `image_file`, `image_path`, `order`, `instagram`, `facebook`, `linkedin`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'te', 'aasdf', 'sdfsdfsdf', 'person01.09d2aa97.png_20260512_185908', 'wwwroot/images/person01.09d2aa97.png_20260512_185908', NULL, NULL, NULL, NULL, 1, '2026-05-12 15:59:08', 1, '2026-05-12 15:59:08'),
(4, 'Evaldo 2', 'Presidente', 'Sua funÃ§Ã£o Ã© zelar pelo bom funcionamento da associaÃ§Ã£o, tomar decisÃµes estratÃ©gicas, articular parcerias e representar a comunidade junto aos Ã³rgÃ£os pÃºblicos e instituiÃ§Ãµes.', 'person02.5ca453ab.png_20260512_185816', '/wwwroot/images/person02.5ca453ab.png_20260512_185816', NULL, NULL, NULL, NULL, 1, '2025-06-26 09:57:17', 1, '2026-05-12 18:58:16'),
(5, 'John ', 'Diretor Financeiro', 'Entre suas principais atribuiÃ§Ãµes estÃ£o o controle de receitas e despesas, elaboraÃ§Ã£o de relatÃ³rios financeiros, prestaÃ§Ã£o de contas aos associados e apoio na definiÃ§Ã£o de orÃ§amentos para projetos e eventos.', 'person01.09d2aa97.png_20260512_185753', '/wwwroot/images/person01.09d2aa97.png_20260512_185753', NULL, NULL, NULL, NULL, 1, '2025-09-17 22:19:00', 1, '2026-05-12 18:57:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `company`
--

INSERT INTO `company` (`id`, `name`, `cnpj`, `email`, `phone`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'Arte em Planejados', '987128721987', 'ARTE', '13123', 'preview.png', 'wwwroot/images/preview.png', 1, '2026-05-13 11:39:17', 1, '2026-05-13 11:40:23');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `button` varchar(100) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_01` varchar(50) DEFAULT NULL,
  `contact_02` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `instagram` varchar(150) DEFAULT NULL,
  `youtube` varchar(150) DEFAULT NULL,
  `site` varchar(150) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contact`
--

INSERT INTO `contact` (`id`, `title`, `button`, `image_file`, `image_path`, `address`, `contact_01`, `contact_02`, `email`, `instagram`, `youtube`, `site`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'send message', 'transferir (1).png', 'wwwroot/images/transferir (1).png', '1211 Awesome Avaenue, <br/>  NY USD', '+00 123 - 456 -78', '+00 987 - 654 -32', 'mint@mintmail.com', 'https://www.instagram.com/arte.emplanejados', 'https://www.youtube.com/@ArteemPlanejados', 'https://www.arteemplanejados.com.br', 1, '2026-05-01 10:09:33', 1, '2026-05-01 22:17:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `expense_type_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `expense_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `expense`
--

INSERT INTO `expense` (`id`, `company_id`, `order_id`, `order_item_id`, `supplier_id`, `material_id`, `expense_type_id`, `description`, `quantity`, `value`, `expense_date`, `payment_date`, `status`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(37, 1, NULL, NULL, 2, 25, 7, 'MDF Branco', 10, 2.50, '2026-06-11', '2026-06-11', 'PAID', NULL, NULL, 1, '2026-06-11 13:58:50', 1, '2026-06-11 13:58:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `expense_type`
--

CREATE TABLE `expense_type` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `expense_type`
--

INSERT INTO `expense_type` (`id`, `name`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'Rent', 1, '2026-05-28 12:44:22', 1, '2026-05-29 08:46:58'),
(2, 'Water', 1, '2026-05-29 08:47:04', 1, '2026-05-29 08:47:04'),
(3, 'Electricity', 1, '2026-05-29 08:47:09', 1, '2026-05-29 08:47:09'),
(4, 'Freight', 1, '2026-05-29 08:47:15', 1, '2026-05-29 08:47:15'),
(5, 'Labor', 1, '2026-05-29 08:47:20', 1, '2026-05-29 08:47:20'),
(6, 'Installation', 1, '2026-05-29 08:47:28', 1, '2026-05-29 08:47:28'),
(7, 'Material Purchase', 1, '2026-05-29 08:47:35', 1, '2026-05-29 08:47:35'),
(8, 'Maintenance', 1, '2026-05-29 08:47:40', 1, '2026-05-29 08:47:40'),
(9, 'Food', 1, '2026-05-29 08:47:46', 1, '2026-05-29 08:47:46'),
(10, 'Fuel', 1, '2026-05-29 08:47:51', 1, '2026-05-29 08:47:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `group`
--

INSERT INTO `group` (`id`, `name`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'Admin', 1, '2025-06-07 08:36:24', 1, '2025-09-16 15:31:54'),
(2, 'Gestor', 1, '2025-06-09 16:15:06', 1, '2026-05-13 11:41:07'),
(3, 'Usuário', 1, '2026-04-30 07:28:19', 1, '2026-05-13 11:41:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `home`
--

CREATE TABLE `home` (
  `id` int(11) NOT NULL,
  `carousel_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL DEFAULT '',
  `button` varchar(255) NOT NULL DEFAULT '',
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_user_id` int(11) NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Despejando dados para a tabela `home`
--

INSERT INTO `home` (`id`, `carousel_id`, `description`, `sub_title`, `button`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(3, 1, 'Olá! Sejam <br/>bem-vindo(as)!', 'Fique a vontade para conhecer nossos serviços, dicas de decoração e informações sobre o decor.', 'enviar mensagem', 1, '2025-09-24 10:22:38', 1, '2026-04-30 15:08:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `internal_client_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `image_file` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `internal_client`
--

CREATE TABLE `internal_client` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cpf_cnpj` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `internal_client`
--

INSERT INTO `internal_client` (`id`, `company_id`, `name`, `cpf_cnpj`, `address`, `city`, `state`, `phone`, `email`, `notes`, `active`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(3, 1, 'Vital Imobiliaria', '061.789.586-41', 'Rua Joao de Matos Silva, 582', 'Belo Horizonte', 'MG', '31994070754', 'edersonfs@gmail.com', 'Nenhuma', 1, 'img_6a2affa3f3e8b5.67595772.jpg', 'wwwroot/images/img_6a2affa3f3e8b5.67595772.jpg', 1, '2026-05-31 10:25:25', 1, '2026-06-11 15:34:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `invoicing`
--

CREATE TABLE `invoicing` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` smallint(6) NOT NULL,
  `total_income` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_expense` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_profit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `material_type_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `material`
--

INSERT INTO `material` (`id`, `company_id`, `supplier_id`, `material_type_id`, `name`, `description`, `unit_cost`, `sale_price`, `stock`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(25, 1, 2, 1, 'MDF Branco', 'branco palido com manchas', 25.00, 25.00, 10, NULL, NULL, 1, '2026-06-11 13:58:50', 1, '2026-06-11 15:33:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `material_historical`
--

CREATE TABLE `material_historical` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `quantity` int(50) DEFAULT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sales_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock` int(50) NOT NULL DEFAULT 0,
  `movement_type` varchar(50) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `material_historical`
--

INSERT INTO `material_historical` (`id`, `material_id`, `company_id`, `supplier_id`, `order_item_id`, `explanation`, `quantity`, `unit_cost`, `sales_price`, `stock`, `movement_type`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(98, 25, 1, 2, NULL, 'branco palido com manchas', 10, 25.00, 25.00, 10, 'ADJUSTMENT', 1, '2026-06-11 13:58:50', 1, '2026-06-11 15:33:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `material_type`
--

CREATE TABLE `material_type` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `material_type`
--

INSERT INTO `material_type` (`id`, `company_id`, `name`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 1, 'MDF', 1, '2026-06-01 14:22:53', 1, '2026-06-01 14:22:53'),
(2, 1, 'Pregos', 1, '2026-06-01 15:07:35', 1, '2026-06-01 15:07:35'),
(3, 1, 'Dobradica', 1, '2026-06-01 15:07:48', 1, '2026-06-02 19:21:21'),
(4, 1, 'Corredica', 1, '2026-06-01 15:08:01', 1, '2026-06-02 19:21:14'),
(5, 1, 'Rodizo', 1, '2026-06-01 15:08:09', 1, '2026-06-01 15:08:09'),
(6, 1, 'Trilhos', 1, '2026-06-01 15:08:16', 1, '2026-06-01 15:08:16'),
(7, 1, 'Fita Borda', 1, '2026-06-01 15:08:26', 1, '2026-06-01 15:08:48'),
(8, 1, 'Outros', 1, '2026-06-01 15:08:58', 1, '2026-06-01 15:08:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `internal_client_id` int(11) NOT NULL,
  `budget_id` int(11) DEFAULT NULL,
  `number` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `start_date` date DEFAULT NULL,
  `install_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `fixed_cost` int(11) NOT NULL,
  `freight` decimal(10,0) NOT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `priority` varchar(50) DEFAULT NULL,
  `estimated_days` int(11) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `order`
--

INSERT INTO `order` (`id`, `company_id`, `internal_client_id`, `budget_id`, `number`, `status`, `start_date`, `install_date`, `delivery_date`, `fixed_cost`, `freight`, `total`, `notes`, `priority`, `estimated_days`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(8, 1, 3, 7, '1', 'scheduled', '0000-00-00', '0000-00-00', '0000-00-00', 15, 300, 750.00, '', '', 0, NULL, NULL, 1, '2026-06-11 13:27:44', 1, '2026-06-11 18:50:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT 0.00,
  `width` decimal(15,2) DEFAULT NULL,
  `height` decimal(15,2) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_for_stock` decimal(15,2) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `partner`
--

CREATE TABLE `partner` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_user_id` int(11) NOT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `partner`
--

INSERT INTO `partner` (`id`, `name`, `image_file`, `image_path`, `created_date`, `created_user_id`, `updated_date`, `updated_user_id`) VALUES
(1, 'parceiro 01', 'partner01.7d2b415e88fe2765dcb99d4f656240ce.svg', 'wwwroot/images/partner01.7d2b415e88fe2765dcb99d4f656240ce.svg', '2026-05-07 13:32:24', 1, '2026-05-07 13:32:24', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `supplier`
--

INSERT INTO `supplier` (`id`, `company_id`, `name`, `contact`, `phone`, `email`, `address`, `city`, `state`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(2, 1, 'Palowa', 'Jerusa - (31) 99407-0754', '31994070754', 'edersonfs@gmail.com', 'etest', 'Contagem', 'MG', 'img_6a1c371618e780.68491866.png', 'wwwroot/images/img_6a1c371618e780.68491866.png', 1, '2026-05-31 10:26:45', 1, '2026-06-04 14:43:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
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
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `user`
--

INSERT INTO `user` (`id`, `company_id`, `group_id`, `name`, `email`, `password`, `active`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 1, 1, 'ederson', 'edersonfs@gmail.com', '$2y$10$HjRgm/8THIZ/5DFqwlkJje14.iCMPgGtTlqhah273693GpTF.WRPC', b'1', '1.34eedf58c0876517e858.jpg', 'wwwroot/images/1.34eedf58c0876517e858.jpg', 1, '2025-06-04 18:58:24', 1, '2026-05-13 12:57:57'),
(4, 2, 1, 'samuel', 'samuel@gmail.com', '$2y$10$mPrrQ6toYyrDPreiZHIkCucvkrIH88U1slA1..jrbfx6rvs2HN1s6', b'1', 'arte-em-planejados.png', 'wwwroot/images/arte-em-planejados.png', 1, '2025-06-04 18:58:24', 1, '2026-05-13 12:59:31'),
(5, 2, 2, 'test 286', 'test@gmail.com', '$2y$10$FsMIVXoZX.gJmatYoDOVKevBpjW26xNVYJ4FLEqI2QtuRltY6AbQy', b'1', 'img_6a05aab62f3f77.87329623.jpg', 'wwwroot/images/img_6a05aab62f3f77.87329623.jpg', 1, '2026-05-14 07:55:36', 1, '2026-05-14 07:57:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `work`
--

CREATE TABLE `work` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `neighborhood` varchar(150) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_user_id` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `work`
--

INSERT INTO `work` (`id`, `type`, `neighborhood`, `city`, `image_file`, `image_path`, `created_user_id`, `created_date`, `updated_user_id`, `updated_date`) VALUES
(1, 'COZINHA', 'barreiro', 'Belo Horizonte', 'Untitled-1.png', 'wwwroot/images/Untitled-1.png', 1, '2026-05-05 15:55:49', 1, '2026-05-13 19:25:19'),
(2, 'SALA', 'Betania', 'Belo Horizonte', 'transferir (2).png', 'wwwroot/images/transferir (2).png', 1, '2026-05-05 17:04:25', 1, '2026-05-05 17:04:25');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_budget_company` (`company_id`),
  ADD KEY `fk_budget_internal_client` (`internal_client_id`),
  ADD KEY `fk_budget_created_user` (`created_user_id`),
  ADD KEY `fk_budget_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `budget_item`
--
ALTER TABLE `budget_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_budget_item_budget` (`budget_id`),
  ADD KEY `fk_budget_item_material` (`material_id`),
  ADD KEY `fk_budget_item_created_user` (`created_user_id`),
  ADD KEY `fk_budget_item_updated_user` (`updated_user_id`);

--
-- Índices de tabela `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carousel_created_user` (`created_user_id`),
  ADD KEY `fk_carousel_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_client_created_user` (`created_user_id`),
  ADD KEY `fk_client_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `collaborator`
--
ALTER TABLE `collaborator`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE;

--
-- Índices de tabela `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_created_user` (`created_user_id`),
  ADD KEY `fk_company_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_created_user` (`created_user_id`),
  ADD KEY `fk_contact_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_expense_company` (`company_id`),
  ADD KEY `fk_expense_order` (`order_id`),
  ADD KEY `fk_expense_supplier` (`supplier_id`),
  ADD KEY `fk_expense_expense_type` (`expense_type_id`),
  ADD KEY `fk_expense_created_user` (`created_user_id`),
  ADD KEY `fk_expense_updated_user` (`updated_user_id`);

--
-- Índices de tabela `expense_type`
--
ALTER TABLE `expense_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_expense_type_created_user` (`created_user_id`),
  ADD KEY `fk_expense_type_updated_user` (`updated_user_id`);

--
-- Índices de tabela `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `home`
--
ALTER TABLE `home`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_income_company` (`company_id`),
  ADD KEY `fk_income_internal_client` (`internal_client_id`),
  ADD KEY `fk_income_order` (`order_id`),
  ADD KEY `fk_income_created_user` (`created_user_id`),
  ADD KEY `fk_income_updated_user` (`updated_user_id`);

--
-- Índices de tabela `internal_client`
--
ALTER TABLE `internal_client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_internal_client_company` (`company_id`),
  ADD KEY `fk_internal_client_created_user` (`created_user_id`),
  ADD KEY `fk_internal_client_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `invoicing`
--
ALTER TABLE `invoicing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_invoicing_company_month_year` (`company_id`,`month`,`year`),
  ADD KEY `fk_invoicing_created_user` (`created_user_id`),
  ADD KEY `fk_invoicing_updated_user` (`updated_user_id`);

--
-- Índices de tabela `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_material_company` (`company_id`),
  ADD KEY `fk_material_supplier` (`supplier_id`),
  ADD KEY `fk_material_created_user` (`created_user_id`),
  ADD KEY `fk_material_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`),
  ADD KEY `fk_material_material_type` (`material_type_id`);

--
-- Índices de tabela `material_historical`
--
ALTER TABLE `material_historical`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_material_historical_material` (`material_id`),
  ADD KEY `fk_material_historical_company` (`company_id`),
  ADD KEY `fk_material_historical_supplier` (`supplier_id`),
  ADD KEY `fk_material_historical_order_item` (`order_item_id`),
  ADD KEY `fk_material_historical_created_user` (`created_user_id`),
  ADD KEY `fk_material_historical_updated_user` (`updated_user_id`);

--
-- Índices de tabela `material_type`
--
ALTER TABLE `material_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_material_type_created_user` (`created_user_id`),
  ADD KEY `fk_material_type_updated_user` (`updated_user_id`),
  ADD KEY `idx_material_type_name` (`name`),
  ADD KEY `fk_material_type_company` (`company_id`);

--
-- Índices de tabela `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_company` (`company_id`),
  ADD KEY `fk_order_internal_client` (`internal_client_id`),
  ADD KEY `fk_order_budget` (`budget_id`),
  ADD KEY `fk_order_created_user` (`created_user_id`),
  ADD KEY `fk_order_updated_user` (`updated_user_id`);

--
-- Índices de tabela `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_item_company` (`company_id`),
  ADD KEY `fk_order_item_order` (`order_id`),
  ADD KEY `fk_order_item_material` (`material_id`),
  ADD KEY `fk_order_item_created_user` (`created_user_id`),
  ADD KEY `fk_order_item_updated_user` (`updated_user_id`);

--
-- Índices de tabela `partner`
--
ALTER TABLE `partner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_partner_created_user` (`created_user_id`),
  ADD KEY `fk_partner_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_supplier_company` (`company_id`),
  ADD KEY `fk_supplier_created_user` (`created_user_id`),
  ADD KEY `fk_supplier_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Índices de tabela `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_work_created_user` (`created_user_id`),
  ADD KEY `fk_work_updated_user` (`updated_user_id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `budget`
--
ALTER TABLE `budget`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `budget_item`
--
ALTER TABLE `budget_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `carousel`
--
ALTER TABLE `carousel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `collaborator`
--
ALTER TABLE `collaborator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `expense_type`
--
ALTER TABLE `expense_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `home`
--
ALTER TABLE `home`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `internal_client`
--
ALTER TABLE `internal_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `invoicing`
--
ALTER TABLE `invoicing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `material_historical`
--
ALTER TABLE `material_historical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de tabela `material_type`
--
ALTER TABLE `material_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `partner`
--
ALTER TABLE `partner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `work`
--
ALTER TABLE `work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `fk_budget_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_budget_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_budget_internal_client` FOREIGN KEY (`internal_client_id`) REFERENCES `internal_client` (`id`),
  ADD CONSTRAINT `fk_budget_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `budget_item`
--
ALTER TABLE `budget_item`
  ADD CONSTRAINT `fk_budget_item_budget` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`),
  ADD CONSTRAINT `fk_budget_item_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_budget_item_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`),
  ADD CONSTRAINT `fk_budget_item_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `carousel`
--
ALTER TABLE `carousel`
  ADD CONSTRAINT `fk_carousel_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_carousel_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `fk_client_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_client_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_company_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_contact_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_contact_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `fk_expense_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_expense_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_expense_expense_type` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_type` (`id`),
  ADD CONSTRAINT `fk_expense_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `fk_expense_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `fk_expense_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `expense_type`
--
ALTER TABLE `expense_type`
  ADD CONSTRAINT `fk_expense_type_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_expense_type_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `income`
--
ALTER TABLE `income`
  ADD CONSTRAINT `fk_income_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_income_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_income_internal_client` FOREIGN KEY (`internal_client_id`) REFERENCES `internal_client` (`id`),
  ADD CONSTRAINT `fk_income_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `fk_income_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `internal_client`
--
ALTER TABLE `internal_client`
  ADD CONSTRAINT `fk_internal_client_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_internal_client_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_internal_client_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `invoicing`
--
ALTER TABLE `invoicing`
  ADD CONSTRAINT `fk_invoicing_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_invoicing_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_invoicing_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `fk_material_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_material_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_material_material_type` FOREIGN KEY (`material_type_id`) REFERENCES `material_type` (`id`),
  ADD CONSTRAINT `fk_material_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `fk_material_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `material_historical`
--
ALTER TABLE `material_historical`
  ADD CONSTRAINT `fk_material_historical_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_material_historical_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_material_historical_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`),
  ADD CONSTRAINT `fk_material_historical_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`id`),
  ADD CONSTRAINT `fk_material_historical_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  ADD CONSTRAINT `fk_material_historical_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `material_type`
--
ALTER TABLE `material_type`
  ADD CONSTRAINT `fk_material_type_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_material_type_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_material_type_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_budget` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`),
  ADD CONSTRAINT `fk_order_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_order_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_order_internal_client` FOREIGN KEY (`internal_client_id`) REFERENCES `internal_client` (`id`),
  ADD CONSTRAINT `fk_order_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_order_item_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_order_item_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`),
  ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `fk_order_item_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `partner`
--
ALTER TABLE `partner`
  ADD CONSTRAINT `fk_partner_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_partner_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_supplier_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_supplier_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_supplier_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);

--
-- Restrições para tabelas `work`
--
ALTER TABLE `work`
  ADD CONSTRAINT `fk_work_created_user` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_work_updated_user` FOREIGN KEY (`updated_user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
