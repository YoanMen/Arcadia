-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 23 avr. 2024 à 15:46
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `arcadia`
--

-- --------------------------------------------------------

--
-- Structure de la table `advice`
--

CREATE TABLE `advice` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `advice` text NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `advice`
--

INSERT INTO `advice` (`id`, `pseudo`, `advice`, `approved`) VALUES
(1, 'Sarah', 'Je suis passée ce weekend avec ma classe et je suis absolument ravie de l\'expérience ! Les enclos sont vastes et bien conçus pour refléter les habitats naturels des animaux.', 1),
(2, 'Thomas', 'Arcadia est un havre pour les photographes ! Des enclos bien positionnés pour des clichés impeccables, avec un respect maximal pour les animaux.', 1),
(3, 'Lisa', 'Ce zoo est une oasis pour les amateurs de faune ! Les nouveaux enclos sont impressionnants et avec un personnel au fait de chaque espèce.', 1);

-- --------------------------------------------------------

--
-- Structure de la table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `race` varchar(40) NOT NULL,
  `habitatID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animal`
--

INSERT INTO `animal` (`id`, `name`, `race`, `habitatID`) VALUES
(1, 'Riki', 'Tigre', 1),
(2, 'Roko', 'Panthère noir', 1),
(16, 'Perock', 'Ara', 1),
(17, 'Bazoo', 'Gorille', 1),
(18, 'Zoe', 'Chimpanzé', 1),
(19, 'Pandy', 'Panda', 1),
(20, 'Pandoudou', 'Panda roux', 1),
(21, 'Anty', 'Antilope', 2),
(22, 'Areyure', 'Zèbre', 2),
(23, 'Lionnel', 'Lion', 2),
(24, 'Rhino', 'Rhinocéros', 2),
(25, 'Croco', 'Crocodile des marais', 5),
(26, 'Hipo', 'Hippopotame', 5),
(27, 'Louloutte', 'Loutre', 5);

-- --------------------------------------------------------

--
-- Structure de la table `animal_image`
--

CREATE TABLE `animal_image` (
  `animalID` int(11) NOT NULL,
  `imageID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animal_image`
--

INSERT INTO `animal_image` (`animalID`, `imageID`) VALUES
(1, 4),
(1, 51),
(2, 5),
(16, 53),
(17, 54),
(18, 55),
(19, 56),
(20, 65),
(21, 58),
(22, 59),
(23, 60),
(24, 61),
(25, 62),
(26, 63),
(27, 64);

-- --------------------------------------------------------

--
-- Structure de la table `foodAnimal`
--

CREATE TABLE `foodAnimal` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  `food` varchar(20) NOT NULL,
  `quantity` float NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `habitat`
--

CREATE TABLE `habitat` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `habitat`
--

INSERT INTO `habitat` (`id`, `name`, `description`) VALUES
(1, 'Jungle', 'Plongez dans l&#039;habitat de la jungle de notre zoo, un monde luxuriant et vibrant qui offre une expérience immersive au cœur de la forêt tropicale. \r\n\r\nExplorez des sentiers ombragés, découvrez des arbres majestueux et écoutez les bruits envoûtants de la faune exotique. Des singes espiègles aux félins mystérieux, la jungle regorge de vie. Chaque coin offre une nouvelle surprise, une nouvelle espèce colorée ou une plante exotique. \r\n\r\nExplorez ce royaume mystérieux où l&#039;aventure vous attend à chaque détour, et où la biodiversité éclate dans toute sa splendeur.'),
(2, 'Savane', 'Bienvenue dans l&#039;habitat de la savane de notre zoo, une vaste étendue où la majesté de la nature règne en maître. \r\n\r\nIci, les vastes plaines accueillent des créatures emblématiques telles que les lions aux crinières majestueuses, les éléphants gracieux et les girafes élancées. \r\n\r\nVous pourrez observer ces magnifiques animaux évoluer dans un environnement ouvert, recréant l&#039;équilibre naturel de la savane africaine. Les moments de chasse, les rassemblements d&#039;herbivores et la beauté brute de la faune sauvage vous transportent au cœur de l&#039;Afrique.'),
(5, 'Marais', 'Découvrez le magnifique monde des marais, un écosystème unique et prospère qui abrite une variété d&#039;espèces exotiques et fascinantes. Le marais du zoo fictif est conçu pour refléter l&#039;environnement naturel de ces zones humides, offrant aux animaux la possibilité d&#039;évoluer dans un milieu qui leur est proche.\r\n\r\nLes marais du zoo ont été méticuleusement conçus pour reproduire ce milieu naturel, avec une combinaison d&#039;eau douce et de terre qui offre un refuge idéal pour les animaux aquatiques et terrestres.\r\n\r\nLe paysage du habitat marais est dominé par des zones d&#039;eau stagnante et des zones de végétation dense, composées de petits arbres, de buissons et de plantes aquatiques. Ces éléments créent un environnement naturel qui permet à nos animaux d&#039;explorer, de jouer et de se nourrir dans des conditions optimales.');

-- --------------------------------------------------------

--
-- Structure de la table `habitatComment`
--

CREATE TABLE `habitatComment` (
  `habitatID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `habitat_image`
--

CREATE TABLE `habitat_image` (
  `habitatID` int(11) NOT NULL,
  `imageID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `habitat_image`
--

INSERT INTO `habitat_image` (`habitatID`, `imageID`) VALUES
(1, 1),
(1, 52),
(2, 3),
(5, 25);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `path` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `path`) VALUES
(1, '51511.webp'),
(3, '13739181.webp'),
(4, '248314-5fdccad0f507.webp'),
(5, '792358.webp'),
(6, '751e61644891.png'),
(7, '5b97bb3f6ed4.png'),
(9, '82857e8266b4.png'),
(10, '96938ca92ef2.png'),
(18, 'c12d0f3aab5b.png'),
(19, '903a3bb84bd6.png'),
(20, '3bd23614267f.png'),
(21, 'adf2768ec435.png'),
(24, '0dddf23c17d4.png'),
(25, '77e88e810e46.webp'),
(30, '1e16dd0ce4fb.png'),
(31, '7220d9cf05d3.jpg'),
(37, 'd582492c502b.png'),
(41, '376ac1a5dbb5.jpg'),
(42, '740e65a6fbf0.png'),
(43, '1147a3c90cb1.png'),
(44, '7247e3e11d8c.jpg'),
(45, '53da4503f8de.png'),
(50, '85f8cd5e2d9f.png'),
(51, 'db3b79c266d0.jpg'),
(52, '0155fb321ed5.jpg'),
(53, 'ee33fd0a5473.webp'),
(54, 'b72ee64b9060.webp'),
(55, '215f9ceaebed.webp'),
(56, 'e4b9ddc54605.webp'),
(58, 'b3cc685cfab8.webp'),
(59, 'e80f2c89ecbc.jpg'),
(60, '9f98b6c9e455.jpg'),
(61, '929f25662cdd.jpg'),
(62, '65b0dd6f1ea7.jpg'),
(63, '9ffefa0693fe.jpg'),
(64, '6c3664f4a234.jpg'),
(65, '5eddea62e7a6.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `reportAnimal`
--

CREATE TABLE `reportAnimal` (
  `id` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `statut` varchar(20) NOT NULL,
  `food` varchar(60) NOT NULL,
  `weight` decimal(5,3) NOT NULL,
  `date` date NOT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `day` varchar(10) NOT NULL,
  `open` time DEFAULT NULL,
  `close` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `schedule`
--

INSERT INTO `schedule` (`id`, `day`, `open`, `close`) VALUES
(1, 'lundi', '08:30:00', '19:00:00'),
(2, 'mardi', '08:30:00', '19:00:00'),
(3, 'mercredi', '08:30:00', '19:00:00'),
(4, 'jeudi', '08:30:00', '19:00:00'),
(5, 'vendredi', '08:30:00', '19:00:00'),
(6, 'samedi', NULL, NULL),
(7, 'dimanche', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `name`, `description`) VALUES
(1, 'Restauration', 'Profitez d&#039;un délicieux repas devant l&#039;enclos de nos éléphants qui s&#039;étend sur une vaste plaine. \r\n\r\nNous vous proposons une cuisine savoureuse et variée, issue des producteurs locaux.'),
(2, 'Visite des habitats avec un guide', 'Participez à une expérience immersive lors de notre visite des habitats, guidée par nos experts passionnés.\r\n\r\nCette visite gratuite vous permettra de découvrir et d&#039;explorer divers habitats, tout en bénéficiant des connaissances approfondies de nos guides expérimentés.\r\n\r\nPlongez dans la richesse de la nature et apprenez-en davantage sur les écosystèmes fascinants qui entourent chaque habitat. Une aventure éducative et divertissante vous attend, sans frais supplémentaires !'),
(3, 'Visite du zoo en train', 'Embarquez pour une aventure unique avec notre passionnant voyage à travers le zoo en train. \r\n\r\nProfitez du confort du train tout en explorant les divers habitats et en observant de près nos incroyables animaux. \r\n\r\nNotre guide expert partagera des informations fascinantes sur chaque espèce, vous offrant une expérience éducative et divertissante.\r\n\r\nDétendez-vous et laissez-vous emporter par cette visite inoubliable du zoo, une manière pittoresque et enrichissante de découvrir la diversité de la vie animale, tout cela à bord de notre train exclusif.');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`) VALUES
(1, 'admin@arcadia.com', '$2y$10$gHkVx1RYZz7VPwUqb1qv4uMJFYwUKAph1Fp7CIvXC1jGmzgEhxIRi', 'admin'),
(2, 'employee@arcadia.com', '$2y$10$GhC4gyJMm0r7.6eQdwadMuJL2cO1gVq1Ngq5xOOv79zN1MqiQ42Qu', 'employee'),
(3, 'veterinary@arcadia.com', '$2y$10$HeX.o3ZAtqICxUYseYUoIeEjpZt18itNe9kAWOV4j7lJH3YDtQXFm', 'veterinary');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `advice`
--
ALTER TABLE `advice`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitatID` (`habitatID`);

--
-- Index pour la table `animal_image`
--
ALTER TABLE `animal_image`
  ADD PRIMARY KEY (`imageID`),
  ADD KEY `animalID` (`animalID`);

--
-- Index pour la table `foodAnimal`
--
ALTER TABLE `foodAnimal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `foodAnimal_ibfk_2` (`animalID`);

--
-- Index pour la table `habitat`
--
ALTER TABLE `habitat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `habitatComment`
--
ALTER TABLE `habitatComment`
  ADD PRIMARY KEY (`habitatID`),
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `habitat_image`
--
ALTER TABLE `habitat_image`
  ADD PRIMARY KEY (`imageID`),
  ADD KEY `habitatID` (`habitatID`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reportAnimal`
--
ALTER TABLE `reportAnimal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animalID` (`animalID`),
  ADD KEY `reportAnimal_ibfk_2` (`userID`);

--
-- Index pour la table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `advice`
--
ALTER TABLE `advice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `foodAnimal`
--
ALTER TABLE `foodAnimal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `habitat`
--
ALTER TABLE `habitat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT pour la table `reportAnimal`
--
ALTER TABLE `reportAnimal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`habitatID`) REFERENCES `habitat` (`id`);

--
-- Contraintes pour la table `animal_image`
--
ALTER TABLE `animal_image`
  ADD CONSTRAINT `animal_image_ibfk_1` FOREIGN KEY (`animalID`) REFERENCES `animal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `animal_image_ibfk_2` FOREIGN KEY (`imageID`) REFERENCES `image` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `foodAnimal`
--
ALTER TABLE `foodAnimal`
  ADD CONSTRAINT `foodAnimal_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `foodAnimal_ibfk_2` FOREIGN KEY (`animalID`) REFERENCES `animal` (`id`);

--
-- Contraintes pour la table `habitatComment`
--
ALTER TABLE `habitatComment`
  ADD CONSTRAINT `habitatComment_ibfk_1` FOREIGN KEY (`habitatID`) REFERENCES `habitat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `habitatComment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `habitat_image`
--
ALTER TABLE `habitat_image`
  ADD CONSTRAINT `habitat_image_ibfk_1` FOREIGN KEY (`habitatID`) REFERENCES `habitat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `habitat_image_ibfk_2` FOREIGN KEY (`imageID`) REFERENCES `image` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reportAnimal`
--
ALTER TABLE `reportAnimal`
  ADD CONSTRAINT `reportAnimal_ibfk_1` FOREIGN KEY (`animalID`) REFERENCES `animal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reportAnimal_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
