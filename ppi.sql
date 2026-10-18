-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 26, 2026 at 01:09 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppi`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `parent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`, `parent_id`) VALUES
(1, 'Accessoires', NULL),
(2, 'Bijoux', 1),
(3, 'Colliers', 2),
(4, 'Bracelets', 2),
(5, 'Bagues', 2),
(6, 'Boucles d\'oreilles', 2),
(7, 'Accessoires Téléphone', 1),
(8, 'Coques', 7),
(9, 'Phone Charm', 7),
(10, 'Supports téléphone', 7),
(11, 'Chargeurs / câbles', 7),
(12, 'Accessoires Maison', 1),
(13, 'Décoration', 12),
(14, 'Bougies', 12),
(15, 'Cadres', 12),
(16, 'Objets artisanaux', 12),
(17, 'Produits Personnalisés', NULL),
(18, 'Stickers personnalisés', 17),
(19, 'Photos imprimées', 17),
(20, 'Posters', 17),
(21, 'T-shirts & Hoodies', 17),
(22, 'Tote bag', 17),
(23, 'Cadeaux & Lifestyle', NULL),
(24, 'Idées cadeaux', 23),
(25, 'Produits lifestyle', 23),
(26, 'Crochet', NULL),
(27, 'Sacs', 26),
(28, 'Vêtements', 26),
(29, 'PC', 26),
(30, 'Décoration', 26),
(31, 'Support d\'accessoire', 26),
(32, 'Crochet', 1),
(33, 'Sacs', 32),
(34, 'Vêtements', 32),
(35, 'PC', 32),
(36, 'Décoration', 32),
(37, 'Support d\'accessoire', 32);

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `id` int NOT NULL,
  `id_client` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `adresse_livraison` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `description` text,
  `statut` enum('en_attente','confirmee','annulee') DEFAULT 'en_attente',
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_confirmation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id`, `id_client`, `id_produit`, `quantite`, `prix_unitaire`, `adresse_livraison`, `telephone`, `description`, `statut`, `date_commande`, `date_confirmation`) VALUES
(1, 3, 1, 1, 10.00, 'Parking principal', '20021345', '', 'confirmee', '2026-04-23 16:04:15', '2026-04-23 16:06:02'),
(2, 3, 6, 1, 8.00, 'Département Informatique', '20021345', 'je suis mardi et jeudi et vendredi', 'confirmee', '2026-04-23 16:31:23', '2026-04-23 16:32:00'),
(3, 3, 7, 1, 8.00, 'Département Informatique', '20021345', 'je suis mardi et jeudi et vendredi', 'confirmee', '2026-04-23 16:31:23', '2026-04-23 16:31:57'),
(4, 23, 15, 1, 6.50, 'Département Informatique', '26574110', '', 'en_attente', '2026-04-23 16:45:26', NULL),
(5, 26, 17, 1, 10.00, 'Entrée principale', '55881968', '', 'confirmee', '2026-04-25 21:19:15', '2026-04-25 22:23:09'),
(6, 26, 10, 1, 25.00, 'Entrée principale', '55881968', '', 'confirmee', '2026-04-25 21:57:46', '2026-04-25 22:20:04');

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int NOT NULL,
  `id_produit` int NOT NULL,
  `id_user` int NOT NULL,
  `contenu` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `commentaire`
--

INSERT INTO `commentaire` (`id`, `id_produit`, `id_user`, `contenu`, `created_at`) VALUES
(5, 10, 26, 'quel beauté!', '2026-04-26 00:13:15'),
(6, 15, 26, 'so cute', '2026-04-26 00:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `demande_vendeur`
--

CREATE TABLE `demande_vendeur` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `message` text,
  `statut` enum('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `nom_complet` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `type_produits` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
  `id_user` int NOT NULL,
  `id_produit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `favoris`
--

INSERT INTO `favoris` (`id_user`, `id_produit`) VALUES
(3, 1),
(27, 1),
(27, 2),
(3, 3),
(27, 3),
(26, 5),
(3, 6),
(26, 10),
(3, 14),
(26, 14),
(26, 15),
(3, 16),
(26, 16),
(3, 17),
(26, 17),
(26, 18),
(23, 19),
(26, 19);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `id_commande` int NOT NULL,
  `id_expediteur` int NOT NULL,
  `contenu` text NOT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `id_commande`, `id_expediteur`, `contenu`, `lu`, `created_at`) VALUES
(1, 1, 23, 'Bonjour , votre commande sera prete dans 3 jours , merci', 1, '2026-04-23 16:13:04'),
(2, 1, 3, 'Merci inchallah .', 1, '2026-04-23 16:13:54'),
(3, 1, 23, 'Bonsoir votre commande est prete , on se voit demain a 10h ?', 1, '2026-04-23 16:22:32'),
(4, 1, 3, 'Bonsoir , oui', 1, '2026-04-23 16:23:22'),
(5, 5, 22, 'bonsoir', 1, '2026-04-25 21:20:04'),
(6, 5, 22, 'hii', 1, '2026-04-25 21:57:16'),
(7, 5, 26, 'bonsoir', 1, '2026-04-25 21:58:05'),
(8, 5, 22, '<3', 1, '2026-04-25 21:58:51'),
(9, 5, 26, 'hiiiiii', 1, '2026-04-25 21:59:41'),
(10, 6, 26, 'hii', 1, '2026-04-25 22:07:18'),
(11, 6, 22, 'hiii', 1, '2026-04-25 22:09:47'),
(12, 6, 26, '<3333', 1, '2026-04-25 22:12:41'),
(13, 6, 22, '.....', 1, '2026-04-25 22:13:02'),
(14, 6, 26, '......', 1, '2026-04-25 22:14:40'),
(15, 6, 26, 'hihi', 1, '2026-04-25 22:16:04'),
(16, 6, 22, 'hihihihihihi', 1, '2026-04-25 22:16:24'),
(17, 6, 26, 'winkkkk', 1, '2026-04-25 23:05:41'),
(18, 6, 22, 'haniii', 1, '2026-04-25 23:05:59'),
(19, 5, 26, 'hiiii', 1, '2026-04-25 23:09:50'),
(20, 6, 26, 'aaaaaa', 1, '2026-04-25 23:12:31'),
(21, 5, 26, 'where are youuuu', 1, '2026-04-25 23:19:17'),
(22, 6, 26, 'aaaaaa', 1, '2026-04-25 23:20:55'),
(23, 6, 22, 'aaaa', 1, '2026-04-25 23:23:03'),
(24, 6, 26, 'ouiiii', 1, '2026-04-25 23:23:22'),
(25, 5, 26, 'aaaaaa', 1, '2026-04-25 23:55:08'),
(26, 6, 22, 'aaaaa', 1, '2026-04-25 23:56:01'),
(27, 6, 26, 'ouiiiii', 1, '2026-04-26 00:00:48'),
(28, 6, 22, 'aaaaaa', 1, '2026-04-26 00:03:10'),
(29, 6, 26, 'waaaaa', 1, '2026-04-26 00:05:42');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `contenu` text NOT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `id_user`, `contenu`, `lu`, `created_at`) VALUES
(1, 26, 'Votre commande \"sac en crochet\" a été ✅ confirmée par le vendeur.', 1, '2026-04-25 22:20:04'),
(2, 26, 'Votre commande \"Porte-lunette\" a été ✅ confirmée par le vendeur.', 1, '2026-04-25 22:23:09'),
(3, 22, '❤️ safa balloumi a aimé votre produit \"sac en crochet\".', 1, '2026-04-25 22:38:48'),
(4, 22, '❤️ safa balloumi a aimé votre produit \"sac en crochet\".', 1, '2026-04-25 22:41:39'),
(5, 22, '❤️ safa balloumi a aimé votre produit \"Porte bijoux\".', 1, '2026-04-25 23:09:21'),
(6, 22, '❤️ safa balloumi a aimé votre produit \"Porte-lunette\".', 1, '2026-04-25 23:25:16'),
(7, 22, '❤️ safa balloumi a aimé votre produit \"Sous-tasse\".', 1, '2026-04-25 23:27:57'),
(8, 22, '❤️ safa balloumi a aimé votre produit \"Porte-lunette\".', 1, '2026-04-25 23:30:26'),
(9, 22, '❤️ safa balloumi a aimé votre produit \"Sous-tasse\".', 1, '2026-04-25 23:32:16'),
(10, 22, '❤️ safa balloumi a aimé votre produit \"Porte-monnaie\".', 1, '2026-04-25 23:33:27'),
(11, 22, '❤️ safa balloumi a aimé votre produit \"Porte-écouteurs\".', 1, '2026-04-25 23:35:45'),
(12, 22, '❤️ safa balloumi a aimé votre produit \"Porte-écouteurs\".', 1, '2026-04-25 23:38:05'),
(13, 22, '❤️ safa balloumi a aimé votre produit \"Porte-écouteurs\".', 1, '2026-04-25 23:39:25'),
(14, 22, '❤️ safa balloumi a aimé votre produit \"Porte-écouteurs\".', 1, '2026-04-25 23:41:11'),
(15, 22, '❤️ safa balloumi a aimé votre produit \"Porte bijoux\".', 1, '2026-04-25 23:42:38'),
(16, 22, '❤️ safa balloumi a aimé votre produit \"Porte-monnaie\".', 1, '2026-04-25 23:45:12'),
(17, 22, '❤️ safa balloumi a aimé votre produit \"Porte bijoux\".', 1, '2026-04-25 23:46:40'),
(18, 22, '❤️ safa balloumi a aimé votre produit \"sac en crochet\".', 1, '2026-04-25 23:49:07'),
(19, 22, '❤️ safa balloumi a aimé votre produit \"Porte-monnaie\".', 1, '2026-04-25 23:51:12'),
(20, 22, '❤️ safa balloumi a aimé votre produit \"Porte bijoux\".', 1, '2026-04-25 23:54:14'),
(21, 22, '💬 safa balloumi a commenté votre produit \"sac en crochet\" : \"beauté\"', 1, '2026-04-26 00:13:05'),
(22, 22, '💬 safa balloumi a commenté votre produit \"sac en crochet\" : \"quel beauté\"', 1, '2026-04-26 00:13:10'),
(23, 22, '💬 safa balloumi a commenté votre produit \"sac en crochet\" : \"quel beauté!\"', 1, '2026-04-26 00:13:15'),
(24, 22, '💬 safa balloumi a commenté votre produit \"Porte-monnaie\" : \"so cute\"', 1, '2026-04-26 00:19:33');

-- --------------------------------------------------------

--
-- Table structure for table `panier`
--

CREATE TABLE `panier` (
  `id_user` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id` int NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `promo` tinyint(1) DEFAULT '0',
  `prix_promo` decimal(10,2) DEFAULT NULL,
  `id_proprietaire` int NOT NULL,
  `id_categorie` int DEFAULT NULL,
  `handmade` tinyint(1) DEFAULT '0',
  `nb_likes` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `libelle`, `prix`, `quantite`, `image`, `description`, `promo`, `prix_promo`, `id_proprietaire`, `id_categorie`, `handmade`, `nb_likes`) VALUES
(1, 'Set bracelets', 10.00, 3, 'uploads/img_69e2342bbf542.PNG', 'Set de 4 bracelets roses, élégants et faciles à assortir pour un look féminin au quotidien.', 0, NULL, 23, 4, 1, 3),
(2, 'Set bracelets', 8.00, 3, 'uploads/img_69e2345cea4f7.PNG', 'Set de 3 bracelets bleux , élégants et faciles à assortir pour un look féminin au quotidien.', 0, NULL, 23, 4, 1, 1),
(3, 'Set bracelets', 14.99, 6, 'uploads/img_69e234c9b7eda.PNG', 'Set de 6 bracelets multicolors , élégants et faciles à assortir pour un look féminin au quotidien.', 1, 12.00, 23, 4, 1, 4),
(4, 'Set bracelets', 7.00, 2, 'uploads/img_69e2352b0de9e.PNG', 'Set de 2 bracelets roses, élégants et faciles à assortir pour un look féminin au quotidien.', 0, NULL, 23, 4, 1, 0),
(5, 'Phone Charm', 8.00, 3, 'uploads/img_69e2360150bf8.jpg', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 1),
(6, 'Phone Charm', 8.00, 4, 'uploads/img_69e2361958e39.jpg', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 1),
(7, 'Phone Charm', 8.00, 0, 'uploads/img_69e2363052387.jpg', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 0),
(8, 'Phone Charm', 8.00, 0, 'uploads/img_69e2364ed037b.PNG', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 2),
(9, 'sac en crochet', 23.00, 2, 'uploads/img_69e23936b54c5.PNG', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 0, NULL, 22, 33, 1, 0),
(10, 'sac en crochet', 28.00, 1, 'uploads/img_69e2395551501.PNG', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 1, 25.00, 22, 33, 0, 2),
(11, 'sac en crochet', 20.98, 2, 'uploads/img_69e23973231c5.PNG', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 0, NULL, 22, 33, 1, 1),
(12, 'sac en crochet', 18.00, 1, 'uploads/img_69e239af48e50.jpg', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 0, NULL, 22, 33, 1, 0),
(13, 'Porte montre', 8.00, 3, 'uploads/img_69e23a001b32e.jpg', 'Porte-montre élégant pour ranger et protéger votre montre avec style.', 0, NULL, 22, 37, 0, 0),
(14, 'Porte bijoux', 8.00, 3, 'uploads/img_69e23a2cc691c.PNG', 'Porte-bijouxélégant pour ranger et protéger votre bijoux avec style.', 0, NULL, 22, 37, 1, 2),
(15, 'Porte-monnaie', 8.00, 2, 'uploads/img_69e23a6aa5ade.PNG', 'Porte-montnaie élégant pour ranger et protéger votre monnaie avec style.', 1, 6.50, 22, 37, 0, 1),
(16, 'Porte-écouteurs', 8.00, 3, 'uploads/img_69e23aa3d6221.PNG', 'Porte-écouteurs élégant pour ranger et protéger votre écouteur avec style.', 0, NULL, 22, 37, 1, 2),
(17, 'Porte-lunette', 10.00, 2, 'uploads/img_69e23af43fa5c.PNG', 'Porte-lunette élégant pour ranger et protéger votre lunette avec style.', 0, NULL, 22, 37, 1, 3),
(18, 'Sous-tasse', 5.00, 10, 'uploads/img_69e23b3bc5a0e.PNG', 'Sous-tasse élégante pour protéger vos surfaces et apporter une touche décorative à votre table.', 0, NULL, 22, 36, 1, 1),
(19, 'Sous-tasse', 5.00, 5, 'uploads/img_69e23b5c2512c.PNG', 'Sous-tasse élégante pour protéger vos surfaces et apporter une touche décorative à votre table.', 0, NULL, 22, 36, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `adresse` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(225) NOT NULL,
  `role` enum('admin','client','supadm') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `tel`, `adresse`, `email`, `mot_de_passe`, `role`, `photo`) VALUES
(1, 'Ben Hmida', 'Malek', '26574110', 'Rades', 'malkebenhmida@gmail.com', '$2y$10$NSHAighoEJdyXiCB6vIKrOi1KE7lk0FvpDQ79Dnftdgs6N/R4PBzC', 'supadm', NULL),
(3, 'Gharbi ', 'Jihene ', '20021345', 'Sidi Rzuig ', 'jihengharbi@gmail.com', '$2y$10$9zYHNAZMQNy5ev0JrgMJkO7W5NVT/xfTQZYcva.ikd3n/F8HV7yrG', 'client', NULL),
(4, 'Trabelsi', 'Imed', '26574222', 'Ariana', 'imedtrabelsi@gmail.com', '$2y$10$azXzRCQhDBgEq92p6pCjg.PKbD/OydTn6bWYAKWfyH1bzuiaVs7Jy', 'admin', NULL),
(22, 'balloumi', 'safa', '55881968', 'omrane', 'safeballoumi@gmail.com', '$2y$10$lU67l2A/X6NJfSSfY2CCTObYeOw0./USRINxtu3pVRqDXXzlzosS2', 'admin', 'uploads/profil_22.png'),
(23, 'Ben Hmida', 'Malek', '26574110', 'Radés', 'malekbenhmida@gmail.com', '$2y$10$Rirzp3pv3Ri4eBsBp2B1LObL5F/U3YpnkpdIFALxarUFOc5ioL.nG', 'admin', NULL),
(26, 'balloumi', 'safa', '55881968', 'المنار 2', 'safeballoumi263@gmail.com', '$2y$10$mL5HIHK4Sj9pAsSyw/b.De1UPIeGSnnoPjlNkK8N8n6SpPMBLlc42', 'client', 'uploads/profil_26.jpg'),
(27, 'ben allem', 'karim', '23698745', '16 rue 45612', 'karim@gmail.com', '$2y$10$Q/mIOWCdw4uE9PCY0NhQOObnC96CndS85w1pa.9YO5sOgFFDw.gAO', 'client', NULL),
(28, 'jjjjjjjjj', 'ddd', '95586502', 'dd', 'dd@gmail.com', '$2y$10$x8rn1DpwOmewC6KB3eB4nu73srXbR6F1Qt8TwTjlKPluThNbM6Yy.', 'client', NULL),
(29, 'zaghdoudi', 'takwa', '50920320', 'nouvelle medine 3', 'zaghdoudi.takwaa@gmail.com', '$2y$10$ol2j3415inI5NnB7pPnSVeeMaIA5Sz16Mq7MbWTdLdxDWs6COEKPa', 'client', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `demande_vendeur`
--
ALTER TABLE `demande_vendeur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id_user`,`id_produit`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_expediteur` (`id_expediteur`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id_user`,`id_produit`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proprietaire` (`id_proprietaire`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `demande_vendeur`
--
ALTER TABLE `demande_vendeur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `categorie_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demande_vendeur`
--
ALTER TABLE `demande_vendeur`
  ADD CONSTRAINT `demande_vendeur_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_expediteur`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_proprietaire`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
