-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 27 mai 2025 à 15:17
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `prêt materiel`
--

-- --------------------------------------------------------

--
-- Structure de la table `materiels`
--

CREATE TABLE `materiels` (
  `id` int(11) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `date_achat` date NOT NULL,
  `etat` varchar(50) NOT NULL,
  `quantite` int(11) NOT NULL,
  `descriptif` text DEFAULT NULL,
  `lien_demonstration` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `materiels`
--

INSERT INTO `materiels` (`id`, `reference`, `designation`, `photo`, `type`, `date_achat`, `etat`, `quantite`, `descriptif`, `lien_demonstration`) VALUES
(1, 'VR-001', 'Casque et Manettes VR', 'casque_vr.jpg', 'VR', '2023-01-15', 'Très bon', 5, 'Casque et manettes VR pour jouer ou travailler.', 'https://youtu.be/sUUguz4J-KU'),
(2, 'MIC-001', 'Casque micro', 'casque_micro.jpg', 'Audio', '2023-02-20', 'Bon', 10, 'Casque micro, pour écouter le son et parler.', 'https://www.youtube.com/watch?v=micro_demo'),
(3, 'CAM-001', 'GoPro', 'go_pro.jpg', 'Caméra', '2023-03-10', 'Très bon', 3, 'Pour se filmer.', 'https://www.youtube.com/watch?v=gopro_demo'),
(4, 'TRI-001', 'Trépied haut', 'trepied_haut.jpg', 'Accessoire', '2023-04-05', 'Bon', 7, 'Un morceau d\'équipement pour le trépied.', 'https://www.youtube.com/watch?v=trepied_demo'),
(5, 'CAM-002', 'Caméra 360°', 'camera_360.jpg', 'Caméra', '2023-05-12', 'Très bon', 2, 'Caméra 360° qui permet de filmer tout autour d\'elle même.', 'https://www.youtube.com/watch?v=camera_360_demo');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `materiel_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `horaire` varchar(50) NOT NULL,
  `motif` text NOT NULL,
  `etudiants` text DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `statut` enum('en attente','validée','refusée') NOT NULL DEFAULT 'en attente',
  `date_validation` datetime DEFAULT NULL,
  `commentaire_admin` text DEFAULT NULL,
  `salle_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `nom_salle` varchar(100) NOT NULL,
  `capacite` int(11) DEFAULT NULL,
  `type_salle` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `nom_salle`, `capacite`, `type_salle`) VALUES
(1, '316', 30, 'informatique'),
(2, '317', 30, 'informatique'),
(3, '318', 30, 'classique'),
(4, '319', 30, 'classique'),
(5, '311', 30, 'informatique'),
(6, '204', 30, 'informatique'),
(7, 'B4', 30, 'informatique'),
(8, '141', 30, 'informatique');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse_postale` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `groupe_td` int(11) DEFAULT NULL,
  `groupe_tp` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `nom`, `prenom`, `email`, `date_naissance`, `adresse_postale`, `mot_de_passe`, `role`, `groupe_td`, `groupe_tp`) VALUES
(8, 'eleve', 'eleve', 'eleve', 'eleve@gmail.com', '2005-06-27', '73 Rue du Commandant Berge', '$2y$10$TRn74T9Yx9bLuRRcv1PbHeWRS68P1N31J9cEdjgdZBPE5TIDyy5A2', 'étudiant', 3, 'F'),
(9, 'prof', 'Prof', 'Prof', 'prof@gmail.com', '1998-07-12', '10 Rue du coup de boule de zizou', '$2y$10$4PvS/Zft0kaxM8O1x0E28e4c6avFeBeamaaZHT.8AzpZeUNo1iteC', 'enseignant', 0, ''),
(10, 'agent', 'agent', 'agent', 'agent@gmail.com', '1984-06-07', '73 Rue du Commandant Berge', '$2y$10$pCnlQhjrNSkusUBwIvquyuI9Qscn2DEUVT8VV0wWH.nlqCjJ4jPey', 'agent', 0, ''),
(11, 'admin', 'admin', 'admin', 'admin@gmail.com', '2000-06-27', '10 Rue des admins', '$2y$10$pCIy/43sN6ekdvxgqwLuCu4ItrkXZvqh5yP0/3y1K9MTWOFRbzcOu', 'administrateur', 0, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `materiels`
--
ALTER TABLE `materiels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `materiel_id` (`materiel_id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `materiels`
--
ALTER TABLE `materiels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`materiel_id`) REFERENCES `materiels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
