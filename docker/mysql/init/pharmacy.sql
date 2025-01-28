-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-01-2025 a las 19:28:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pharmacy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `a_commander`
--

CREATE TABLE `a_commander` (
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `last_modified` timestamp NULL DEFAULT NULL,
  `last_load_time` timestamp NULL DEFAULT NULL,
  `last_mongo_load` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `a_commander`
--

INSERT INTO `a_commander` (`produit_id`, `quantite`, `last_modified`, `last_load_time`, `last_mongo_load`) VALUES
(1, 3, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16'),
(2, 0, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16'),
(4, 0, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16'),
(5, 0, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16'),
(6, 0, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16'),
(7, 0, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16'),
(8, 0, '2024-11-18 00:49:16', NULL, '2024-11-18 00:49:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque`
--

CREATE TABLE `cheque` (
  `cheque_id` int(11) NOT NULL,
  `numero_cheque` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `etat` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cheque`
--

INSERT INTO `cheque` (`cheque_id`, `numero_cheque`, `client_id`, `montant`, `etat`) VALUES
(1, '', 2, 5.94, 'en_attente'),
(2, '', 2, 5.94, 'en_attente'),
(3, '025', 2, 2.00, 'en_attente'),
(4, '', 2, 5.94, 'en_attente'),
(5, '026', 2, 3.00, 'en_attente'),
(6, '026', 2, 3.94, 'en_attente'),
(7, '026', 2, 2.00, 'valide'),
(8, '027', 2, 3.44, 'valide'),
(9, '027', 2, 2.00, 'refuse'),
(10, '25', 2, 1.00, 'refuse'),
(11, '26', 2, 1.72, 'refuse'),
(12, '22', 2, 5.94, 'en_attente'),
(13, '22', 2, 3.00, 'en_attente'),
(14, '22', 2, 3.00, 'en_attente'),
(15, '45', 12, 1.23, 'valide'),
(16, '027', 6, 2.17, 'en_attente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `client_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `numero_carte_vitale` varchar(15) DEFAULT NULL,
  `cheques_impayes` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `client`
--

INSERT INTO `client` (`client_id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `commentaire`, `numero_carte_vitale`, `cheques_impayes`) VALUES
(2, 'Hermosilla', 'Edu', 'hehermosilla@gmail.com', '0660388810', '11 rue de l\'olivier', 'dfffffffffffff', '250', 1),
(6, 'Hermosillitas', 'Eduardo', 'hehermosilla@gmail.com', '0660388811', '11 rue de l\'olivier', NULL, NULL, 0),
(9, 'g12', 'df', 'g12@gmail.com', NULL, NULL, NULL, '023', 0),
(10, 'jair', 'jai', 'hehe@gmail.com', NULL, NULL, NULL, NULL, 0),
(11, 'karl', 'gg', 'efn@gmail.com', NULL, NULL, NULL, '32323568', 0),
(12, 'fre', 're', 'he@lp.co', NULL, NULL, NULL, NULL, 0),
(13, 'Francis', 'Dupont', 'dupont@gmail.com', '0660384515', '11 rue de l&#039;olivier', NULL, NULL, 0),
(14, 'Paul', 'Renard', 'dupont@gmail.com', '4045623835', '45 rue de la republique', NULL, '475689531215478', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commande`
--

CREATE TABLE `commande` (
  `commande_id` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` enum('En attente','En cours','Livrée','Annulée') NOT NULL DEFAULT 'En attente',
  `total` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `commande`
--

INSERT INTO `commande` (`commande_id`, `date_commande`, `statut`, `total`) VALUES
(27, '2024-10-01 07:54:03', 'En attente', 9.00),
(28, '2024-10-01 10:17:35', 'En attente', 0.00),
(29, '2024-11-10 00:00:00', 'Livrée', 368.56),
(31, '2024-10-07 09:59:19', 'Annulée', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commande_produit`
--

CREATE TABLE `commande_produit` (
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventaire`
--

CREATE TABLE `inventaire` (
  `produit_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventaire`
--

INSERT INTO `inventaire` (`produit_id`, `stock`, `last_modified`) VALUES
(1, 0, '2024-10-18 02:06:33'),
(2, 5, '2024-11-07 00:58:23'),
(4, 1, '2024-11-07 20:31:42'),
(5, 8, '2024-11-07 00:58:23'),
(6, 0, '2024-11-07 23:37:03'),
(7, 4, '2024-11-07 23:40:17'),
(8, 10, '2024-10-03 00:02:59'),
(10, 10, '2024-10-28 03:41:36'),
(27, 4, '2024-10-31 16:40:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordonnance`
--

CREATE TABLE `ordonnance` (
  `ordonnance_id` int(11) NOT NULL,
  `numero_ordonnance` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `numero_d'ordre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordonnance`
--

INSERT INTO `ordonnance` (`ordonnance_id`, `numero_ordonnance`, `image_path`, `numero_d'ordre`) VALUES
(1, '5', '', '5'),
(2, '5', '', '5'),
(3, '5', '', '5'),
(4, '55', '', '55'),
(5, '55', NULL, '55'),
(6, '5', NULL, '5'),
(7, '55', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f0e5606e6b1_bis_2.jpg', '55'),
(8, '66', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f0e59e1a581_pharm4.jpg', '66'),
(9, '66', NULL, '66'),
(10, '66', NULL, '66'),
(11, '55', NULL, '55'),
(12, '55', NULL, '55'),
(13, '55', NULL, '55'),
(14, '22', NULL, '22'),
(15, '22', NULL, '22'),
(16, '22', NULL, '22'),
(17, '22', NULL, '22'),
(18, '22', NULL, '22'),
(19, '5', NULL, '5'),
(20, '2', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f8932c64f75_pharm1.jpg', '2'),
(21, '6', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f893b581905_pharm6.jpg', '6'),
(22, '22', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f8940526f45_ordonn1.jpg', '22'),
(23, '22', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f897668faa7_pharm4.jpg', '22'),
(24, '11', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66f8976693ed7_pharm4.jpg', '11'),
(25, '22', NULL, '22'),
(26, '22', NULL, '22'),
(27, '22', NULL, '22'),
(28, '22', NULL, '22'),
(29, '5', NULL, '5'),
(30, '22', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/66fa0eb6f0683_pharm5.jpg', '22'),
(31, '11', NULL, '11'),
(32, '22', NULL, '22'),
(33, '22', NULL, '22'),
(34, '66', NULL, '55'),
(35, '22', NULL, '22'),
(36, '22', NULL, '22'),
(37, '66', 'C:/xampp/htdocs/Pharmacie_S/uploads/ordonnances/672ceadf64fe3_Capture2.PNG', '77'),
(38, '55', NULL, '456'),
(39, '48', NULL, '55'),
(40, '55', NULL, '55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordonnance_produit`
--

CREATE TABLE `ordonnance_produit` (
  `ordonnance_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordonnance_produit`
--

INSERT INTO `ordonnance_produit` (`ordonnance_id`, `produit_id`) VALUES
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 4),
(6, 6),
(7, 6),
(8, 6),
(9, 4),
(10, 4),
(11, 2),
(11, 5),
(12, 4),
(13, 5),
(14, 6),
(14, 7),
(15, 6),
(15, 7),
(16, 5),
(17, 4),
(17, 7),
(18, 5),
(19, 2),
(19, 4),
(20, 2),
(21, 4),
(21, 5),
(22, 2),
(22, 5),
(23, 2),
(24, 5),
(25, 2),
(26, 4),
(27, 2),
(28, 4),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 5),
(34, 4),
(35, 5),
(36, 2),
(37, 7),
(38, 4),
(39, 7),
(40, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametres`
--

CREATE TABLE `parametres` (
  `nom` varchar(100) NOT NULL,
  `valeur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `parametres`
--

INSERT INTO `parametres` (`nom`, `valeur`) VALUES
('TVA', '5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produit`
--

CREATE TABLE `produit` (
  `produit_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_vente_ht` decimal(10,2) NOT NULL,
  `prescription` enum('oui','non') NOT NULL DEFAULT 'non',
  `taux_remboursement` tinyint(3) UNSIGNED DEFAULT NULL,
  `alerte` int(11) DEFAULT NULL,
  `declencher_alerte` enum('oui','non') NOT NULL DEFAULT 'non',
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `produit`
--

INSERT INTO `produit` (`produit_id`, `nom`, `description`, `prix_vente_ht`, `prescription`, `taux_remboursement`, `alerte`, `declencher_alerte`, `is_deleted`) VALUES
(1, 'ACARBOSE BIOGARAN 100 mg ', 'comprimé sécable.\r\nACARBOSE BIOGARAN est un antidiabétique	', 13.87, 'oui', 65, NULL, 'non', 0),
(2, 'ACEBUTOLOL ARROW 400 mg', 'comprimé pelliculé sécable \r\nHypertension artérielle.\r\n Traitement au long cours après infarctus du myocarde', 10.74, 'oui', 65, NULL, 'oui', 0),
(4, 'ACECLOFENAC BIOGARAN 100 mg', 'comprimé pelliculé\r\nanti-inflammatoire non stéroïdien', 3.35, 'oui', 65, NULL, 'non', 0),
(5, 'ACETATE DE CYPROTERONE SANDOZ 100 mg', 'comprimé sécable.\r\ncancer de la prostate ;', 70.44, 'oui', 100, 20, 'oui', 0),
(6, 'ACETYLCYSTEINE EG 200 mg', 'poudre pour solution buvable en sachet-dose	poudre pour solution buvable	orale	Autorisation active	Procédure nationale	Commercialisée	26/12/2003			 EG LABO - LABORATOIRES EUROGENERICS	Non\r\n69896678	ACETYLCYSTEINE EG LABO CONSEIL 200 mg SANS SUCRE, poudre pour solution.\r\npour brochite', 5.82, 'non', NULL, 20, 'non', 0),
(7, 'ACETYLLEUCINE BIOGARAN 500 mg', 'comprimé.\r\ntraitement symptomatique de la crise vertigineuse.', 2.95, 'oui', 30, 20, 'non', 0),
(8, 'ACICLOVIR ALMUS 200 mg, ', 'traitement ou la prévention de certaines formes d’herpès', 8.18, 'oui', NULL, 65, 'oui', 0),
(9, 'ACICLOVIR ALMUS 5 %', 'Manifestations d’infections herpétiques génitales.\r\ncrème	cutanée.', 6.87, 'oui', 65, NULL, 'non', 0),
(10, 'ACIDE ACÉTYLSALICYLIQUE EG LABO CONSEIL 500 mg', 'indiqué en cas de douleurs d\'intensité légère à modérée et/ou de fièvre\r\ncomprimé', 3.04, 'non', NULL, NULL, 'oui', 0),
(11, 'ACIDE ALENDRONIQUE BIOGARAN 70 mg', 'prévient la perte osseuse qui survient chez les femmes ménopausées\r\ncomprimé.', 8.84, 'oui', 65, NULL, 'non', 0),
(12, 'ACIDE ALENDRONIQUE/VITAMINE D3 TEVA SANTE 70 mg/5600 UI,', 'comprimé\r\nboîte de 12', 12.84, 'oui', 65, NULL, 'non', 0),
(13, 'ACIDE FOLIQUE ARROW 5 mg', NULL, 1.33, 'oui', 65, NULL, 'non', 0),
(14, 'ACIDE FUSIDIQUE ARROW 2 %', 'crème	cutanée', 1.76, 'oui', 30, NULL, 'non', 0),
(15, 'ACIDE TIAPROFENIQUE ARROW 100 mg', 'comprimé sécable\r\nanti-inflammatoires non stéroïdiens', 3.92, 'non', NULL, NULL, 'non', 0),
(16, 'ACIDE URSODESOXYCHOLIQUE ARROW 250 mg', '\r\n\r\n\r\ncomprimé pelliculé.\r\ninflammation de la vésicule biliaire,\r\n\r\ninfection ou obstruction des voies biliaires.', 6.83, 'oui', 65, NULL, 'non', 0),
(17, 'DOLIPRANE 100 mg  poudre', 'poudre pour solution buvable en 12 sachet-dose', 2.45, 'non', 65, NULL, 'non', 0),
(18, 'DOLIPRANE 100 mg suppositoire', '10 suppositoire sécable', 2.35, 'non', 65, NULL, 'non', 0),
(19, 'DOLIPRANE 1000 mg comprimé', '8 comprimé', 2.18, 'non', 65, NULL, 'non', 0),
(20, 'DOLIPRANE 1000 mg, comprimé effervescent', 'comprimé effervescent sécable', 2.18, 'non', 65, NULL, 'non', 0),
(21, 'DOLIPRANE 1000 mg, gélule', '8 gélule	orale', 2.18, 'non', 65, NULL, 'non', 0),
(22, 'DOLIPRANE 1000 mg, poudre', 'poudre pour solution buvable en 8 sachet-dose', 2.25, 'non', 65, NULL, 'non', 0),
(23, 'DOLIPRANE 2,4 POUR CENT', ' suspension buvable', 2.50, 'non', 65, NULL, 'non', 0),
(24, 'DOLIPRANE 1000 mg suppositoire', '8suppositoire', 2.40, 'non', 65, NULL, 'non', 0),
(25, 'DOLIPRANELIQUIZ 1000 mg', 'suspension buvable en sachet édulcoré', 3.00, 'oui', 65, NULL, 'non', 0),
(26, 'DOLIPRANEVITAMINEC 500 mg/150 mg', '8 comprimé effervescent', 2.70, 'non', 65, NULL, 'non', 0),
(27, 'DOLIRHUME PARACETAMOL ET PSEUDOEPHEDRINE 500 mg/30 mg,', 'comprimé', 2.00, 'non', 65, NULL, 'non', 0),
(28, 'DORMICALM', 'comprimé enrobé\r\n Médicament traditionnel à base de plantes utilisé pour troubles du sommeil.', 7.84, 'non', NULL, NULL, 'non', 0),
(29, 'ACTIQ 1200 microgrammes', 'Stupéfiant,comprimé avec applicateur buccal\r\n traitement des accès douloureux paroxystiques', 18.17, 'oui', 65, NULL, 'non', 0),
(30, 'ACTISKENAN 10 mg', ' stupéfiant\r\ncomprimé orodispersible en boîte de 14 cp.', 2.26, 'oui', 65, NULL, 'non', 0),
(31, 'CTISOUFRE 4 mg/50 mg', 'états inflammatoires chroniques des voies respiratoires', 7.80, 'non', NULL, NULL, 'oui', 0),
(32, 'ACTONEL 75 mg', 'traitement de la maladie de Paget', 47.69, 'oui', 65, NULL, 'oui', 0),
(33, 'ADEMPAS 2,5 mg', NULL, 25.78, 'oui', 65, NULL, 'oui', 0),
(34, 'ADARTREL 2 mg', NULL, 24.00, 'oui', 65, NULL, 'non', 0),
(35, 'ADOPORT 5 mg', NULL, 29.70, 'oui', 65, NULL, 'non', 0),
(36, 'ALFUZOSINE EG L.P. 10 mg', 'traitement des troubles urinaires dus à un adénome de la prostate.', 9.78, 'oui', 65, NULL, 'non', 0),
(37, 'ALGINATE DE SODIUM/BICARBONATE DE SODIUM SANDOZ 500 mg/267 mg', 'suspension buvable en sachet\r\nreflux gastro-oesophagien ', 4.01, 'non', NULL, NULL, 'oui', 0),
(38, 'ALLOPURINOL BIOGARAN 300 mg', 'comprimé\r\nIl est utilisé pour traiter les excès d\'acide urique lorsqu\'ils sont responsables de goutte ou de calculs rénaux et pour prévenir ainsi ces maladies.', 3.06, 'oui', 65, NULL, 'non', 0),
(39, 'ALMOTRIPTAN TEVA 12,5 mg', 'comprimé pelliculé\r\nsoulager les maux de tête associés aux crises de migraine', 13.11, 'oui', 65, NULL, 'non', 0),
(40, 'ALPRAZOLAM ARROW 0,50 mg', 'comprimé sécable\r\nanxiolytique', 2.25, 'oui', 65, NULL, 'non', 0),
(41, 'AMBRISENTAN TEVA 10 mg', ' traiter l\'hypertension artérielle pulmonaire', 10.20, 'oui', 65, NULL, 'non', 0),
(42, 'AMBROXOL BIOGARAN CONSEIL 30 mg', 'comprimé sécable\r\nexpectorant', 3.99, 'non', NULL, NULL, 'non', 0),
(43, 'AMIODARONE BIOGARAN 200 mg', 'comprimé sécable\r\nantiarythmique', 8.22, 'oui', 65, NULL, 'non', 0),
(44, 'AMISULPRIDE BIOGARAN 200 mg', 'comprimé sécable\r\nantipsychotique', 37.22, 'oui', 65, NULL, 'non', 0),
(45, 'AMITRIPTYLINE SUBSTIPHARM 40 mg/mL', 'solution buvable en gouttes\r\nantidépresseur tricyclique', 4.17, 'oui', 65, NULL, 'non', 0),
(46, 'AMLODIPINE ARROW 10 mg', 'gélule	orale\r\nhypertension', 10.13, 'oui', 65, NULL, 'oui', 0),
(47, 'AMOROLFINE SUBSTIPHARM 5 %', 'vernis à ongles médicamenteux', 9.50, 'oui', 65, NULL, 'non', 0),
(48, 'AMOXICILLINE ARROW 500 mg', 'gélule	orale', 8.50, 'oui', 65, NULL, 'non', 0),
(49, 'AMOXICILLINE ARROW 250 mg/5 mL', 'poudre pour suspension buvable', 9.50, 'oui', 65, NULL, 'non', 0),
(50, 'AMOXICILLINE/ACIDE CLAVULANIQUE EG 500 mg/62,5 mg', 'comprimé pelliculé', 9.50, 'oui', 65, NULL, 'non', 0),
(51, 'AMOXICILLINE/ACIDE CLAVULANIQUE BIOGARAN 100 mg/12,50 mg', 'par ml NOURRISSONS, poudre pour suspension buvable en flacon', 7.80, 'oui', 65, NULL, 'non', 0),
(52, 'AMOXICILLINE/ACIDE CLAVULANIQUE TEVA 1 g/ 125 mg ADULTES', 'poudre pour suspension buvable en sachet-dose', 9.50, 'oui', 65, NULL, 'non', 0),
(53, 'ANAFRANIL 75 mg', 'comprimé pelliculé sécable', 15.78, 'oui', 65, NULL, 'non', 0),
(54, 'ANAGRELIDE SANDOZ 0,5 mg', 'gélule', 10.48, 'oui', 65, NULL, 'oui', 0),
(55, 'ANASTROZOLE EG 1 mg', ' Traitement du cancer du sein', 85.74, 'oui', 100, NULL, 'oui', 0),
(56, 'ANDROCUR 50 mg', 'comprimé sécable', 22.52, 'oui', 65, NULL, 'non', 0),
(57, 'ANTARENE 200 mg', 'comprimé pelliculé', 5.60, 'oui', 65, NULL, 'non', 0),
(58, 'APREPITANT ARROW 125 mg', ' gélule', 11.20, 'non', 65, NULL, 'non', 0),
(59, 'APROVEL 150 mg', 'comprimé pelliculé', 18.40, 'oui', 65, NULL, 'non', 0),
(60, 'AQUA MARINA BOIRON', 'degré de dilution compris entre 2CH et 30CH', 5.40, 'non', NULL, NULL, 'non', 0),
(61, 'ARALIA RACEMOSA LEHNING', 'degré de dilution compris entre 2CH et 30CH', 4.80, 'non', NULL, NULL, 'non', 0),
(62, 'ARANESP 150 microgrammes', 'solution injectable en seringue préremplie', 6.20, 'non', NULL, NULL, 'non', 0),
(63, 'ARBUTUS UNEDO BOIRON', 'degré de dilution compris entre 2CH et 30CH', 3.80, 'non', NULL, NULL, 'non', 0),
(64, 'ARCALION 200 mg', 'comprimé enrobé', 10.50, 'oui', 65, NULL, 'non', 0),
(65, 'ARGENTUM NITRICUM LEHNING', 'degré de dilution compris entre 2CH et 30CH ', 3.50, 'oui', NULL, NULL, 'non', 0),
(66, 'ARIPIPRAZOLE ALMUS 15 mg', 'comprimé', 3.40, 'non', NULL, NULL, 'non', 0),
(67, 'ARNICA MONTANA TEINTURE MERE BOIRON', 'liquide pour application cutanée', 6.30, 'non', NULL, NULL, 'non', 0),
(68, 'ARNICALME', 'comprimé orodispersible', 4.70, 'non', NULL, NULL, 'non', 0),
(69, 'ARNITROSIUM', 'comprimé sublingual', 3.85, 'non', NULL, NULL, 'non', 0),
(70, 'ARTHRODONT 1 POUR CENT', 'pâte gingivale', 5.80, 'non', NULL, NULL, 'non', 0),
(71, 'ASCABIOL 10 %', 'émulsion pour application cutanée', 3.90, 'oui', NULL, NULL, 'non', 0),
(72, 'ASPEGIC 500 mg', 'poudre pour solution buvable', 4.80, 'non', NULL, NULL, 'non', 0),
(73, 'ASPIRINE UPSA VITAMINEE C TAMPONNEE EFFERVESCENTE', 'comprimé effervescent', 5.71, 'oui', NULL, NULL, 'non', 0),
(74, 'ATAZANAVIR BIOGARAN 300 mg', 'gélule', 18.50, 'oui', 65, NULL, 'oui', 0),
(75, 'ATENOLOL ARROW 100 mg', 'comprimé pelliculé sécable', 10.75, 'oui', NULL, NULL, 'non', 0),
(76, 'AGOMELATINE BIOGARAN 25 mg', 'comprimé pelliculé', 5.50, 'oui', 65, NULL, 'non', 0),
(77, 'MERCRYL SOLUTION MOUSSANTE', 'solution pour application cutanée', 7.80, 'non', NULL, NULL, 'non', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pharmacien','vendeur') NOT NULL DEFAULT 'vendeur',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `nom`, `prenom`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'edu', 'edu', 'edu@gmail.com', '$2y$10$/HL5f7kHax8T7o0DuYofPOuQXP/uRWdJAxra69UpnQWNzqYmHQkBG', 'admin', '2024-08-06 07:54:05', '2025-01-27 23:58:17'),
(2, 'due', 'due', 'due@gmail.com', '$2y$10$5mVuC0hFSzO3HNum/Wi8d.7y6c4TfDHHxaaUoSJ4a1/FOR.ecacW.', 'vendeur', '2024-09-03 01:39:29', '2024-09-03 01:45:23'),
(4, 'deuse', 'deuu', 'deu@gmail.com', '$2y$10$FRrIzAezlCjdfN1g5tW2bOaNalzIRN4ZTUvB1d0G8NFh/0/m5Fafq', 'vendeur', '2024-09-03 02:15:29', '2024-10-31 03:06:38'),
(5, 'cae', 'cae', 'cae@gmail.com', '$2y$10$gRM0tobyqwO9VfIOywQ.ROvB.vS5PViAdpOfF5ydycH.MxU6RUJT2', 'vendeur', '2024-09-04 03:36:08', '2024-09-04 03:36:08'),
(8, 'klaus', 'klaus', 'klaus@gmail.com', '$2y$10$.b6ubzSvgioUn2B/8domgeacf.4/cXHm7mzyuNgvyswkLAn.YA4te', 'vendeur', '2024-09-08 11:03:22', '2024-10-23 00:21:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vente`
--

CREATE TABLE `vente` (
  `vente_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_regle` decimal(10,2) NOT NULL DEFAULT 0.00,
  `a_rembourser` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commentaire` text DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vente`
--

INSERT INTO `vente` (`vente_id`, `client_id`, `user_id`, `date`, `montant`, `montant_regle`, `a_rembourser`, `commentaire`, `is_deleted`) VALUES
(2, NULL, 2, '2024-09-22 01:54:15', 12.00, 0.00, 0.00, '', 0),
(3, NULL, 2, '2024-09-22 01:54:56', 6.00, 0.00, 0.00, '', 0),
(4, NULL, 2, '2024-09-22 01:56:50', 6.00, 0.00, 0.00, '', 0),
(5, 2, 2, '2024-09-22 01:58:05', 6.00, 0.00, 0.00, '', 0),
(6, 2, 2, '2024-09-22 01:59:42', 2.00, 0.00, 0.63, '', 0),
(7, 2, 2, '2024-09-22 02:00:29', 6.00, 0.00, 0.00, 'bien', 0),
(8, 2, 2, '2024-09-22 02:01:37', 1.00, 0.00, 0.00, '', 0),
(9, 2, 2, '2024-09-22 02:02:46', 2.00, 0.00, 0.00, '', 0),
(10, 2, 2, '2024-09-22 02:04:02', 2.00, 0.00, 0.63, '', 0),
(11, 2, 2, '2024-09-22 02:04:24', 2.00, 0.00, 0.63, '', 0),
(12, 2, 2, '2024-09-22 02:04:38', 2.00, 0.00, 0.63, '', 0),
(13, 2, 2, '2024-09-22 02:04:43', 2.00, 0.00, 0.63, '', 0),
(14, NULL, 2, '2024-09-22 16:46:22', 18.00, 0.00, 0.00, '', 0),
(15, NULL, 2, '2024-09-22 17:16:21', 12.00, 0.00, 4.20, '', 0),
(16, NULL, 2, '2024-09-22 17:21:40', 6.00, 0.00, 0.60, '', 0),
(17, 2, 2, '2024-09-22 18:56:58', 13.20, 0.00, 1.32, '', 0),
(18, NULL, 2, '2024-09-22 22:29:53', 6.60, 0.00, 0.66, '', 0),
(19, 2, 2, '2024-09-22 23:32:14', 6.60, 5.94, 0.66, '', 0),
(20, NULL, 2, '2024-09-22 23:47:24', 6.60, 5.94, 0.66, '', 0),
(21, 2, 2, '2024-09-22 23:53:59', 6.60, 5.94, 0.66, '', 0),
(22, 2, 2, '2024-09-23 00:10:48', 6.60, 5.94, 0.66, '', 0),
(23, NULL, 2, '2024-09-23 01:37:09', 2.20, 2.07, 0.13, '', 0),
(24, NULL, 2, '2024-09-23 01:48:08', 2.20, 2.07, 0.13, '', 0),
(25, NULL, 2, '2024-09-23 02:29:28', 1.10, 0.60, 0.50, '', 0),
(26, NULL, 2, '2024-09-23 02:32:30', 1.10, 0.60, 0.50, '', 0),
(27, 2, 2, '2024-09-23 02:33:03', 1.10, 0.60, 0.50, '', 0),
(28, NULL, 2, '2024-09-23 02:33:25', 1.10, 0.60, 0.50, '', 0),
(29, NULL, 2, '2024-09-23 02:34:44', 1.10, 0.60, 0.50, '', 0),
(30, 2, 2, '2024-09-23 02:45:09', 1.10, 0.60, 0.50, '', 0),
(31, 2, 2, '2024-09-23 02:45:53', 1.10, 0.60, 0.50, '', 0),
(32, 2, 2, '2024-09-23 02:55:35', 1.10, 0.60, 0.50, '', 0),
(33, 2, 2, '2024-09-23 02:56:07', 1.10, 0.60, 0.50, '', 0),
(34, 2, 2, '2024-09-23 02:59:02', 1.10, 0.60, 0.50, '', 0),
(35, 2, 2, '2024-09-23 03:01:47', 1.10, 0.60, 0.50, '', 0),
(36, NULL, 2, '2024-09-23 03:19:27', 1.10, 0.60, 0.50, '', 0),
(37, 2, 2, '2024-09-23 03:21:38', 1.10, 0.60, 0.50, '', 0),
(38, NULL, 2, '2024-09-23 03:39:03', 3.30, 0.94, 2.37, '', 0),
(39, 2, 2, '2024-09-23 03:49:52', 1.10, 0.60, 0.50, '', 0),
(40, 2, 2, '2024-09-23 03:50:54', 1.10, 0.60, 0.50, '', 0),
(41, NULL, 2, '2024-09-23 03:55:59', 2.20, 0.34, 1.87, '', 0),
(42, NULL, 2, '2024-09-23 03:56:32', 2.20, 0.34, 1.87, '', 0),
(43, NULL, 2, '2024-09-23 03:57:00', 13.20, 11.88, 1.32, '', 0),
(44, NULL, 2, '2024-09-23 03:57:55', 13.20, 11.88, 1.32, '', 0),
(45, NULL, 2, '2024-09-23 04:30:50', 10.12, 9.86, 0.26, '', 0),
(46, 6, 2, '2024-09-25 13:36:33', 6.60, 5.94, 0.66, '', 0),
(47, NULL, 2, '2024-09-26 01:22:07', 1.10, 0.17, 0.94, '', 0),
(48, NULL, 2, '2024-09-26 01:22:38', 8.80, 8.01, 0.79, '', 0),
(49, NULL, 2, '2024-09-26 01:34:49', 14.30, 2.50, 6.44, '', 0),
(50, NULL, 2, '2024-09-26 01:36:47', 14.30, 2.50, 6.44, '', 0),
(51, 2, 2, '2024-09-26 01:50:46', 8.80, 2.50, 0.79, '', 0),
(52, NULL, 2, '2024-09-26 01:52:06', 6.60, 2.50, 0.66, '', 0),
(53, 2, 2, '2024-09-26 01:53:06', 6.60, 2.50, 0.66, '', 0),
(54, NULL, 2, '2024-09-26 02:00:03', 6.60, 5.94, 0.66, '', 0),
(55, 2, 2, '2024-09-26 02:02:11', 20.90, 13.37, 7.54, '', 0),
(56, 2, 2, '2024-09-26 02:03:19', 6.60, 5.94, 0.66, '', 0),
(57, 2, 2, '2024-09-26 02:04:14', 8.80, 8.01, 0.79, '', 0),
(58, 2, 2, '2024-09-26 02:05:27', 6.60, 5.94, 0.66, '', 0),
(59, 2, 2, '2024-09-26 02:10:35', 6.60, 5.94, 0.66, '', 0),
(60, 2, 2, '2024-09-26 02:18:06', 6.60, 5.94, 0.66, '', 0),
(61, 2, 2, '2024-09-26 02:28:53', 6.60, 5.94, 0.66, '', 0),
(62, 2, 2, '2024-09-26 02:48:02', 6.60, 5.94, 0.66, '', 0),
(63, 2, 2, '2024-09-28 23:35:16', 6.82, 5.88, 0.94, '', 0),
(64, 2, 2, '2024-09-28 23:37:16', 5.72, 5.72, 0.00, '', 0),
(65, NULL, 2, '2024-09-28 23:37:51', 6.60, 5.94, 0.66, '', 0),
(66, 6, 2, '2024-09-28 23:38:15', 6.60, 5.94, 0.66, '', 0),
(67, 2, 2, '2024-09-28 23:38:42', 6.60, 5.94, 0.66, '', 0),
(68, NULL, 2, '2024-09-28 23:39:33', 3.30, 2.24, 1.07, '', 0),
(69, 6, 2, '2024-09-28 23:40:53', 7.92, 7.79, 0.13, '', 0),
(70, 2, 2, '2024-09-28 23:55:18', 7.92, 7.79, 0.13, '', 0),
(71, 2, 2, '2024-09-30 02:05:18', 6.82, 6.82, 0.94, '', 0),
(72, 2, 2, '2024-09-30 02:18:12', 6.82, 6.82, 0.94, '', 0),
(73, NULL, 2, '2024-09-30 02:22:46', 5.72, 5.72, 0.00, '', 1),
(74, 2, 2, '2024-09-30 02:25:15', 6.60, 6.60, 0.66, '', 0),
(75, 2, 2, '2024-09-30 02:27:02', 6.60, 6.60, 0.66, '', 0),
(76, 2, 2, '2024-09-30 02:28:00', 6.60, 6.60, 0.66, '', 0),
(77, 2, 2, '2024-09-30 02:34:28', 6.60, 5.94, 0.66, '', 0),
(78, 2, 2, '2024-09-30 02:36:38', 5.72, 5.72, 0.00, '', 0),
(79, 2, 2, '2024-09-30 03:48:35', 6.60, 5.94, 0.66, '', 0),
(80, 2, 2, '2024-09-30 03:48:50', 6.60, 5.94, 0.66, '', 0),
(81, 2, 2, '2024-09-30 03:53:34', 6.60, 5.94, 0.66, '', 0),
(82, NULL, 4, '2024-10-02 02:23:28', 6.60, 5.94, 0.66, '', 0),
(83, 2, 2, '2024-10-02 05:35:17', 6.60, 5.94, 0.66, '', 0),
(84, 2, 2, '2024-10-02 05:39:32', 6.60, 5.94, 0.66, '', 0),
(85, 2, 2, '2024-10-02 05:44:53', 6.60, 5.94, 0.66, '', 0),
(86, 2, 2, '2024-10-02 05:49:42', 6.60, 5.94, 0.66, '', 0),
(87, 2, 2, '2024-10-02 07:53:45', 6.60, 5.94, 0.66, '', 0),
(88, 2, 2, '2024-10-05 04:06:17', 6.60, 5.94, 0.66, '', 0),
(89, NULL, 2, '2024-10-06 10:07:32', 5.72, 5.72, 0.00, '', 0),
(90, 2, 2, '2024-10-06 10:07:54', 6.60, 5.94, 0.66, '', 0),
(91, NULL, 2, '2024-10-06 10:21:05', 6.60, 5.94, 0.66, '', 0),
(92, 2, 2, '2024-10-06 10:26:12', 6.60, 5.94, 0.66, 'jh', 0),
(93, NULL, 2, '2024-10-06 10:26:34', 6.60, 5.94, 0.66, '', 0),
(94, NULL, 2, '2024-10-06 10:27:08', 6.60, 5.94, 0.66, '', 0),
(95, 2, 2, '2024-10-09 12:39:23', 11.76, 11.13, 0.63, '', 1),
(96, 2, 2, '2024-10-18 02:06:33', 25.20, 22.68, 2.52, '', 0),
(97, 6, 2, '2024-10-22 16:22:07', 6.30, 5.67, 0.63, '', 0),
(98, 9, 2, '2024-10-22 16:42:09', 6.30, 5.67, 0.63, '', 0),
(99, 10, 2, '2024-10-22 16:49:45', 6.30, 5.67, 0.63, '', 0),
(100, 11, 2, '2024-10-22 17:03:08', 12.60, 11.34, 1.26, '', 0),
(101, 2, 2, '2024-10-22 17:14:57', 8.40, 7.66, 0.74, '', 0),
(102, 12, 2, '2024-10-22 21:42:38', 63.00, 56.70, 6.30, '', 0),
(103, 12, 4, '2024-10-31 11:07:58', 3.52, 1.23, 2.29, '', 0),
(105, NULL, 4, '2024-11-06 22:55:01', 6.11, 6.11, 0.00, '', 0),
(106, NULL, 4, '2024-11-07 00:58:23', 91.35, 10.06, 81.29, '', 0),
(107, 6, 4, '2024-11-07 16:29:19', 3.10, 2.17, 0.93, '', 0),
(108, 6, 2, '2024-11-07 20:31:42', 9.63, 7.34, 2.29, '', 0),
(109, 2, 2, '2024-11-07 23:37:03', 30.56, 30.55, 0.00, '', 0),
(110, NULL, 2, '2024-11-07 23:40:17', 15.49, 10.85, 4.65, '', 0),
(111, 2, 1, '2024-11-16 21:15:05', 14.56, 5.10, 9.47, '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vente_ordonnance`
--

CREATE TABLE `vente_ordonnance` (
  `vente_id` int(11) NOT NULL,
  `ordonnance_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vente_ordonnance`
--

INSERT INTO `vente_ordonnance` (`vente_id`, `ordonnance_id`) VALUES
(30, 1),
(33, 2),
(34, 3),
(35, 4),
(37, 5),
(38, 6),
(39, 7),
(40, 8),
(41, 9),
(42, 10),
(45, 11),
(47, 12),
(48, 13),
(49, 14),
(50, 15),
(51, 16),
(55, 17),
(57, 18),
(63, 19),
(64, 20),
(68, 21),
(69, 22),
(70, 23),
(70, 24),
(71, 25),
(71, 26),
(72, 27),
(72, 28),
(73, 29),
(78, 30),
(89, 31),
(95, 32),
(101, 33),
(103, 34),
(106, 35),
(106, 36),
(107, 37),
(108, 38),
(110, 39),
(111, 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vente_paiement`
--

CREATE TABLE `vente_paiement` (
  `paiement_id` int(11) NOT NULL,
  `vente_id` int(11) NOT NULL,
  `mode_paiement` set('especes','carte_bleu','cheque') DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `numero_cheque` varchar(50) DEFAULT NULL,
  `date_paiement` timestamp NOT NULL DEFAULT current_timestamp(),
  `cheque_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vente_paiement`
--

INSERT INTO `vente_paiement` (`paiement_id`, `vente_id`, `mode_paiement`, `montant`, `numero_cheque`, `date_paiement`, `cheque_id`) VALUES
(1, 2, 'especes', 10.92, NULL, '2024-09-22 01:54:15', NULL),
(2, 3, 'especes', 5.46, NULL, '2024-09-22 01:54:56', NULL),
(3, 4, 'especes', 5.46, NULL, '2024-09-22 01:56:50', NULL),
(4, 7, 'especes', 5.46, NULL, '2024-09-22 02:00:29', NULL),
(5, 19, 'especes', 5.94, NULL, '2024-09-22 23:32:15', NULL),
(6, 21, 'cheque', 5.94, NULL, '2024-09-22 23:53:59', 1),
(7, 22, 'cheque', 5.94, NULL, '2024-09-23 00:10:48', 2),
(8, 30, 'especes', 0.60, NULL, '2024-09-23 02:45:09', NULL),
(9, 33, 'especes', 0.60, NULL, '2024-09-23 02:56:07', NULL),
(10, 34, 'especes', 0.60, NULL, '2024-09-23 02:59:02', NULL),
(11, 35, 'especes', 0.60, NULL, '2024-09-23 03:01:47', NULL),
(12, 37, 'especes', 0.60, NULL, '2024-09-23 03:21:38', NULL),
(13, 40, 'especes', 0.60, NULL, '2024-09-23 03:50:54', NULL),
(14, 45, 'especes', 9.86, NULL, '2024-09-23 04:30:50', NULL),
(15, 46, 'especes', 5.94, NULL, '2024-09-25 13:36:33', NULL),
(16, 47, 'especes', 0.17, NULL, '2024-09-26 01:22:07', NULL),
(17, 48, 'especes', 8.01, NULL, '2024-09-26 01:22:38', NULL),
(18, 49, 'especes', 2.50, NULL, '2024-09-26 01:34:49', NULL),
(19, 50, 'especes', 2.50, NULL, '2024-09-26 01:36:47', NULL),
(20, 51, 'especes', 2.50, NULL, '2024-09-26 01:50:47', NULL),
(21, 51, 'carte_bleu', 5.51, NULL, '2024-09-26 01:50:47', NULL),
(22, 52, 'especes', 2.50, NULL, '2024-09-26 01:52:06', NULL),
(23, 52, 'carte_bleu', 3.44, NULL, '2024-09-26 01:52:06', NULL),
(24, 53, 'especes', 2.50, NULL, '2024-09-26 01:53:06', NULL),
(25, 53, 'carte_bleu', 3.44, NULL, '2024-09-26 01:53:06', NULL),
(26, 54, 'especes,carte_bleu', 5.94, NULL, '2024-09-26 02:00:03', NULL),
(27, 55, 'especes,carte_bleu,cheque', 13.37, NULL, '2024-09-26 02:02:11', 3),
(28, 56, 'cheque', 5.94, NULL, '2024-09-26 02:03:19', 4),
(29, 58, 'especes,cheque', 5.94, NULL, '2024-09-26 02:05:27', 5),
(30, 59, 'especes,cheque', 5.94, NULL, '2024-09-26 02:10:35', 6),
(31, 60, 'especes,cheque', 5.94, NULL, '2024-09-26 02:18:06', 7),
(32, 61, 'especes,cheque', 5.94, '027', '2024-09-26 02:28:53', 8),
(33, 62, 'especes,cheque', 5.94, '027', '2024-09-26 02:48:02', 9),
(34, 63, 'especes,carte_bleu', 5.88, NULL, '2024-09-28 23:35:16', NULL),
(35, 64, 'especes', 5.72, NULL, '2024-09-28 23:37:16', NULL),
(36, 65, 'especes,carte_bleu', 5.94, NULL, '2024-09-28 23:37:51', NULL),
(37, 66, 'especes,carte_bleu', 5.94, NULL, '2024-09-28 23:38:15', NULL),
(38, 67, 'especes,carte_bleu', 5.94, NULL, '2024-09-28 23:38:42', NULL),
(39, 68, 'especes,carte_bleu', 2.24, NULL, '2024-09-28 23:39:33', NULL),
(40, 69, 'especes,carte_bleu', 7.79, NULL, '2024-09-28 23:40:53', NULL),
(41, 70, 'especes,carte_bleu', 7.79, NULL, '2024-09-28 23:55:18', NULL),
(42, 72, 'especes', 6.82, '', '2024-09-30 02:18:12', NULL),
(43, 73, 'especes', 5.72, '', '2024-09-30 02:22:46', NULL),
(44, 74, 'especes', 6.60, '', '2024-09-30 02:25:15', NULL),
(45, 75, 'especes,carte_bleu', 6.60, '', '2024-09-30 02:27:02', NULL),
(46, 76, 'especes,carte_bleu,cheque', 6.60, '25', '2024-09-30 02:28:00', 10),
(47, 77, 'especes,carte_bleu', 5.94, '', '2024-09-30 02:34:28', NULL),
(48, 78, 'especes,carte_bleu,cheque', 5.72, '26', '2024-09-30 02:36:38', 11),
(49, 79, 'especes', 5.94, '', '2024-09-30 03:48:35', NULL),
(50, 80, 'especes', 5.94, '', '2024-09-30 03:48:50', NULL),
(51, 81, 'especes', 5.94, '', '2024-09-30 03:53:34', NULL),
(52, 82, 'especes', 5.94, '', '2024-10-02 02:23:28', NULL),
(53, 83, 'especes', 5.94, '', '2024-10-02 05:35:17', NULL),
(54, 84, 'especes', 5.94, '', '2024-10-02 05:39:32', NULL),
(55, 85, 'cheque', 5.94, '22', '2024-10-02 05:44:53', 12),
(56, 86, 'especes,cheque', 5.94, '22', '2024-10-02 05:49:42', 13),
(57, 87, 'especes', 5.94, '', '2024-10-02 07:53:45', NULL),
(58, 88, 'especes', 5.94, '', '2024-10-05 04:06:17', NULL),
(59, 89, 'especes', 5.72, '', '2024-10-06 10:07:32', NULL),
(60, 90, 'especes', 5.94, '', '2024-10-06 10:07:54', NULL),
(61, 91, 'especes', 5.94, '', '2024-10-06 10:21:05', NULL),
(62, 92, 'especes', 5.94, '', '2024-10-06 10:26:12', NULL),
(63, 93, 'especes', 5.94, '', '2024-10-06 10:26:34', NULL),
(64, 94, 'especes', 5.94, '', '2024-10-06 10:27:08', NULL),
(65, 95, 'especes,carte_bleu,cheque', 11.13, '22', '2024-10-09 12:39:23', 14),
(66, 96, 'especes', 22.68, '', '2024-10-18 02:06:33', NULL),
(67, 97, 'especes', 5.67, '', '2024-10-22 16:22:07', NULL),
(68, 98, 'especes', 5.67, '', '2024-10-22 16:42:09', NULL),
(69, 99, 'especes,carte_bleu', 5.67, '', '2024-10-22 16:49:45', NULL),
(70, 100, 'especes', 11.34, '', '2024-10-22 17:03:08', NULL),
(71, 101, 'especes,carte_bleu', 7.66, '', '2024-10-22 17:14:57', NULL),
(72, 102, 'especes,carte_bleu', 56.70, '', '2024-10-22 21:42:38', NULL),
(73, 103, 'cheque', 1.23, '45', '2024-10-31 11:07:58', 15),
(74, 105, 'especes', 6.11, NULL, '2024-11-06 22:55:01', NULL),
(75, 106, 'especes', 10.06, NULL, '2024-11-07 00:58:23', NULL),
(76, 107, 'cheque', 2.17, NULL, '2024-11-07 16:29:19', 16),
(77, 108, 'especes', 7.34, NULL, '2024-11-07 20:31:42', NULL),
(78, 109, 'especes', 30.55, NULL, '2024-11-07 23:37:03', NULL),
(79, 110, 'especes', 10.85, NULL, '2024-11-07 23:40:17', NULL),
(80, 111, 'especes', 5.10, NULL, '2024-11-16 21:15:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vente_produit`
--

CREATE TABLE `vente_produit` (
  `vente_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vente_produit`
--

INSERT INTO `vente_produit` (`vente_id`, `produit_id`, `quantite`) VALUES
(2, 1, 2),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 5, 1),
(7, 1, 1),
(8, 6, 1),
(9, 5, 1),
(10, 5, 1),
(11, 5, 1),
(12, 5, 1),
(13, 5, 1),
(14, 1, 3),
(15, 1, 2),
(16, 1, 1),
(17, 1, 2),
(18, 1, 1),
(19, 1, 1),
(20, 1, 1),
(21, 1, 1),
(22, 1, 1),
(23, 5, 1),
(24, 5, 1),
(25, 6, 1),
(26, 6, 1),
(27, 6, 1),
(28, 6, 1),
(29, 6, 1),
(30, 6, 1),
(31, 6, 1),
(32, 6, 1),
(33, 6, 1),
(34, 6, 1),
(35, 6, 1),
(36, 6, 1),
(37, 6, 1),
(38, 4, 1),
(38, 6, 1),
(39, 6, 1),
(40, 6, 1),
(41, 4, 1),
(42, 4, 1),
(43, 1, 1),
(44, 1, 1),
(45, 2, 1),
(45, 5, 2),
(46, 1, 1),
(47, 4, 1),
(48, 1, 1),
(48, 5, 1),
(49, 6, 1),
(49, 7, 1),
(50, 6, 1),
(50, 7, 1),
(51, 1, 1),
(51, 5, 1),
(52, 1, 1),
(53, 1, 1),
(54, 1, 1),
(55, 1, 1),
(55, 4, 1),
(55, 7, 1),
(56, 1, 1),
(57, 1, 1),
(57, 5, 1),
(58, 1, 1),
(59, 1, 1),
(60, 1, 1),
(61, 1, 1),
(62, 1, 1),
(63, 2, 1),
(63, 4, 1),
(64, 2, 1),
(65, 1, 1),
(66, 1, 1),
(67, 1, 1),
(68, 4, 1),
(68, 5, 1),
(69, 2, 1),
(69, 5, 1),
(70, 2, 1),
(70, 5, 1),
(71, 2, 1),
(71, 4, 1),
(72, 2, 1),
(72, 4, 1),
(73, 2, 1),
(74, 1, 1),
(75, 1, 1),
(76, 1, 1),
(77, 1, 1),
(78, 2, 1),
(79, 1, 1),
(80, 1, 1),
(81, 1, 1),
(82, 1, 1),
(83, 1, 1),
(84, 1, 1),
(85, 1, 1),
(86, 1, 1),
(87, 1, 1),
(88, 1, 1),
(89, 2, 1),
(90, 1, 1),
(91, 1, 1),
(92, 1, 1),
(93, 1, 1),
(94, 1, 1),
(95, 1, 1),
(95, 2, 1),
(96, 1, 4),
(97, 1, 1),
(98, 1, 1),
(99, 1, 1),
(100, 1, 2),
(101, 1, 1),
(101, 5, 1),
(102, 1, 10),
(103, 4, 1),
(105, 6, 1),
(106, 2, 1),
(106, 5, 1),
(106, 6, 1),
(107, 7, 1),
(108, 4, 1),
(108, 6, 1),
(109, 6, 5),
(110, 7, 5),
(111, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `a_commander`
--
ALTER TABLE `a_commander`
  ADD PRIMARY KEY (`produit_id`);

--
-- Indices de la tabla `cheque`
--
ALTER TABLE `cheque`
  ADD PRIMARY KEY (`cheque_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indices de la tabla `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indices de la tabla `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`commande_id`);

--
-- Indices de la tabla `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`commande_id`,`produit_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indices de la tabla `inventaire`
--
ALTER TABLE `inventaire`
  ADD PRIMARY KEY (`produit_id`);

--
-- Indices de la tabla `ordonnance`
--
ALTER TABLE `ordonnance`
  ADD PRIMARY KEY (`ordonnance_id`);

--
-- Indices de la tabla `ordonnance_produit`
--
ALTER TABLE `ordonnance_produit`
  ADD PRIMARY KEY (`ordonnance_id`,`produit_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Indices de la tabla `parametres`
--
ALTER TABLE `parametres`
  ADD PRIMARY KEY (`nom`);

--
-- Indices de la tabla `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`produit_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`vente_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `fk_vente_user` (`user_id`);

--
-- Indices de la tabla `vente_ordonnance`
--
ALTER TABLE `vente_ordonnance`
  ADD PRIMARY KEY (`vente_id`,`ordonnance_id`),
  ADD KEY `ordonnance_id` (`ordonnance_id`);

--
-- Indices de la tabla `vente_paiement`
--
ALTER TABLE `vente_paiement`
  ADD PRIMARY KEY (`paiement_id`),
  ADD KEY `fk_vente_paiement` (`vente_id`),
  ADD KEY `fk_vente_paiement_cheque` (`cheque_id`);

--
-- Indices de la tabla `vente_produit`
--
ALTER TABLE `vente_produit`
  ADD PRIMARY KEY (`vente_id`,`produit_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cheque`
--
ALTER TABLE `cheque`
  MODIFY `cheque_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `client`
--
ALTER TABLE `client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `commande`
--
ALTER TABLE `commande`
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `ordonnance`
--
ALTER TABLE `ordonnance`
  MODIFY `ordonnance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `produit`
--
ALTER TABLE `produit`
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vente`
--
ALTER TABLE `vente`
  MODIFY `vente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `vente_paiement`
--
ALTER TABLE `vente_paiement`
  MODIFY `paiement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cheque`
--
ALTER TABLE `cheque`
  ADD CONSTRAINT `cheque_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`);

--
-- Filtros para la tabla `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `commande_produit_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`),
  ADD CONSTRAINT `commande_produit_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`);

--
-- Filtros para la tabla `inventaire`
--
ALTER TABLE `inventaire`
  ADD CONSTRAINT `inventaire_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ordonnance_produit`
--
ALTER TABLE `ordonnance_produit`
  ADD CONSTRAINT `ordonnance_produit_ibfk_1` FOREIGN KEY (`ordonnance_id`) REFERENCES `ordonnance` (`ordonnance_id`),
  ADD CONSTRAINT `ordonnance_produit_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`);

--
-- Filtros para la tabla `vente`
--
ALTER TABLE `vente`
  ADD CONSTRAINT `fk_vente_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `vente_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vente_ordonnance`
--
ALTER TABLE `vente_ordonnance`
  ADD CONSTRAINT `fk_vente_ordonnance_ordonnance` FOREIGN KEY (`ordonnance_id`) REFERENCES `ordonnance` (`ordonnance_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vente_ordonnance_vente` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`vente_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vente_paiement`
--
ALTER TABLE `vente_paiement`
  ADD CONSTRAINT `fk_vente_paiement` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`vente_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_vente_paiement_cheque` FOREIGN KEY (`cheque_id`) REFERENCES `cheque` (`cheque_id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `vente_produit`
--
ALTER TABLE `vente_produit`
  ADD CONSTRAINT `fk_vente_produit_produit` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vente_produit_vente` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`vente_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
