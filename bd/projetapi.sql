-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 31 mars 2023 à 19:02
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetapi`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `titre` varchar(50) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `titre`, `contenu`, `date_publication`, `id_utilisateur`) VALUES
(1, 'Les avantages du télétravail', 'Le télétravail présente de nombreux avantages...', '2022-03-15', 5),
(2, 'Comment bien gérer son temps', 'Voici quelques astuces pour mieux gérer son temps...', '2022-03-16', 3),
(3, 'Les bienfaits de la méditation', 'La méditation permet de se détendre et de se recentrer...', '2022-03-16', 3),
(4, 'Les avantages d\'une alimentation végétarienne', 'Une alimentation végétarienne peut réduire le risque de maladies chroniques et améliorer la santé globale...', '2022-06-04', 4),
(5, 'L\'importance de la lecture pour le développement p', 'La lecture peut stimuler l\'imagination, améliorer la communication et augmenter les connaissances...', '2022-07-19', 5),
(6, 'Comment la musique peut améliorer l\'humeur et rédu', 'La musique peut être une source de réconfort et de soulagement du stress, en plus de stimuler la créativité et la concentration...', '2022-08-22', 3),
(7, 'Les bienfaits de la gratitude sur le bien-être men', 'Pratiquer la gratitude peut améliorer l\'estime de soi, réduire la dépression et favoriser des relations positives...', '2022-09-14', 4);

-- --------------------------------------------------------

--
-- Structure de la table `liker`
--

CREATE TABLE `liker` (
  `id_article` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type` enum('like','dislike') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `liker`
--

INSERT INTO `liker` (`id_article`, `id_utilisateur`, `type`) VALUES
(1, 3, 'dislike'),
(1, 4, 'like'),
(2, 3, 'like'),
(2, 4, 'like'),
(4, 5, 'like'),
(5, 4, 'like'),
(6, 5, 'dislike'),
(7, 4, 'dislike');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `mdp` varchar(50) DEFAULT NULL,
  `role` enum('moderator','publisher') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `mdp`, `role`) VALUES
(1, 'Admin', 'admin', 'moderator'),
(2, 'Test', 'azerty', 'moderator'),
(3, 'Alice', 'Alice123', 'publisher'),
(4, 'Bob', 'Bob123', 'publisher'),
(5, 'Carl', 'Carl123', 'publisher');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `liker`
--
ALTER TABLE `liker`
  ADD PRIMARY KEY (`id_article`,`id_utilisateur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `liker`
--
ALTER TABLE `liker`
  ADD CONSTRAINT `liker_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `liker_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
