-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 23, 2026 at 02:59 PM
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
(4, 23, 15, 1, 6.50, 'Département Informatique', '26574110', '', 'en_attente', '2026-04-23 16:45:26', NULL);

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
(26, 1),
(27, 1),
(27, 2),
(3, 3),
(26, 3),
(27, 3),
(3, 6),
(26, 9),
(26, 10),
(3, 14),
(3, 16),
(26, 16),
(3, 17),
(26, 18),
(23, 19);

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
(4, 1, 3, 'Bonsoir , oui', 1, '2026-04-23 16:23:22');

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
(1, 'Set bracelets', 10.00, 3, 'uploads/img_69e2342bbf542.PNG', 'Set de 4 bracelets roses, élégants et faciles à assortir pour un look féminin au quotidien.', 0, NULL, 23, 4, 1, 4),
(2, 'Set bracelets', 8.00, 3, 'uploads/img_69e2345cea4f7.PNG', 'Set de 3 bracelets bleux , élégants et faciles à assortir pour un look féminin au quotidien.', 0, NULL, 23, 4, 1, 1),
(3, 'Set bracelets', 14.99, 6, 'uploads/img_69e234c9b7eda.PNG', 'Set de 6 bracelets multicolors , élégants et faciles à assortir pour un look féminin au quotidien.', 1, 12.00, 23, 4, 1, 4),
(4, 'Set bracelets', 7.00, 2, 'uploads/img_69e2352b0de9e.PNG', 'Set de 2 bracelets roses, élégants et faciles à assortir pour un look féminin au quotidien.', 0, NULL, 23, 4, 1, 0),
(5, 'Phone Charm', 8.00, 3, 'uploads/img_69e2360150bf8.jpg', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 0),
(6, 'Phone Charm', 8.00, 4, 'uploads/img_69e2361958e39.jpg', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 1),
(7, 'Phone Charm', 8.00, 0, 'uploads/img_69e2363052387.jpg', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 0),
(8, 'Phone Charm', 8.00, 0, 'uploads/img_69e2364ed037b.PNG', 'Bijou de téléphone tendance pour personnaliser votre smartphone avec style.', 0, NULL, 23, 9, 1, 1),
(9, 'sac en crochet', 23.00, 2, 'uploads/img_69e23936b54c5.PNG', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 0, NULL, 22, 33, 1, 1),
(10, 'sac en crochet', 28.00, 2, 'uploads/img_69e2395551501.PNG', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 1, 25.00, 22, 33, 0, 1),
(11, 'sac en crochet', 20.98, 2, 'uploads/img_69e23973231c5.PNG', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 0, NULL, 22, 33, 1, 1),
(12, 'sac en crochet', 18.00, 1, 'uploads/img_69e239af48e50.jpg', 'Sac en crochet fait main, léger et tendance, parfait pour un look naturel et élégant au quotidien.', 0, NULL, 22, 33, 1, 0),
(13, 'Porte montre', 8.00, 3, 'uploads/img_69e23a001b32e.jpg', 'Porte-montre élégant pour ranger et protéger votre montre avec style.', 0, NULL, 22, 37, 0, 0),
(14, 'Porte bijoux', 8.00, 3, 'uploads/img_69e23a2cc691c.PNG', 'Porte-bijouxélégant pour ranger et protéger votre bijoux avec style.', 0, NULL, 22, 37, 1, 1),
(15, 'Porte-monnaie', 8.00, 2, 'uploads/img_69e23a6aa5ade.PNG', 'Porte-montnaie élégant pour ranger et protéger votre monnaie avec style.', 1, 6.50, 22, 37, 0, 0),
(16, 'Porte-écouteurs', 8.00, 3, 'uploads/img_69e23aa3d6221.PNG', 'Porte-écouteurs élégant pour ranger et protéger votre écouteur avec style.', 0, NULL, 22, 37, 1, 2),
(17, 'Porte-lunette', 10.00, 3, 'uploads/img_69e23af43fa5c.PNG', 'Porte-lunette élégant pour ranger et protéger votre lunette avec style.', 0, NULL, 22, 37, 1, 2),
(18, 'Sous-tasse', 5.00, 10, 'uploads/img_69e23b3bc5a0e.PNG', 'Sous-tasse élégante pour protéger vos surfaces et apporter une touche décorative à votre table.', 0, NULL, 22, 36, 1, 1),
(19, 'Sous-tasse', 5.00, 5, 'uploads/img_69e23b5c2512c.PNG', 'Sous-tasse élégante pour protéger vos surfaces et apporter une touche décorative à votre table.', 0, NULL, 22, 36, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(225) NOT NULL,
  `role` enum('admin','client','supadm') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `tel`, `adresse`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Ben Hmida', 'Malek', '26574110', 'Rades', 'malkebenhmida@gmail.com', 'malek_bh_2005', 'supadm'),
(3, 'Gharbi ', 'Jihene ', '20021345', 'Sidi Rzuig ', 'jihengharbi@gmail.com', 'jihen_jihen123', 'client'),
(4, 'Trabelsi', 'Imed', '26574222', 'Ariana', 'imedtrabelsi@gmail.com', 'imed_imed23', 'admin'),
(22, 'balloumi', 'safa', '55881968', 'omrane', 'safeballoumi@gmail.com', 'safsoufa1', 'admin'),
(23, 'Ben Hmida', 'Malek', '26574110', 'Radés', 'malekbenhmida@gmail.com', 'malek_bh_2005', 'admin'),
(26, 'balloumi', 'safa', '55881968', 'المنار 2', 'safeballoumi263@gmail.com', 'safa123', 'client'),
(27, 'ben allem', 'karim', '23698745', '16 rue 45612', 'karim@gmail.com', 'karim123', 'client'),
(28, 'jjjjjjjjj', 'ddd', '95586502', 'dd', 'dd@gmail.com', 'oumaouma1', 'client'),
(29, 'zaghdoudi', 'takwa', '50920320', 'nouvelle medine 3', 'zaghdoudi.takwaa@gmail.com', 'toutou50', 'client');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_proprietaire`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
