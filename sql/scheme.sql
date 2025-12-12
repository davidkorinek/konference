SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `konference`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_czech_ci;

USE `konference`;

CREATE TABLE `files` (
                         `ID_file` int(11) NOT NULL AUTO_INCREMENT,
                         `authors` varchar(255) NOT NULL,
                         `filename` varchar(255) NOT NULL,
                         `original_name` varchar(255) NOT NULL,
                         `mime_type` varchar(100) NOT NULL,
                         `size` int(11) NOT NULL,
                         `upload_date` datetime DEFAULT current_timestamp(),
                         `uploaded_by` int(11) NOT NULL,
                         `ID_status` int(11) NOT NULL DEFAULT 1,
                         `title` varchar(255) NOT NULL,
                         `abstract` text DEFAULT NULL,
                         PRIMARY KEY (`ID_file`),
                         KEY `idx_files_uploaded_by` (`uploaded_by`),
                         KEY `idx_files_status` (`ID_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `filestatuses` (
                                `ID_status` int(11) NOT NULL AUTO_INCREMENT,
                                `status_name` varchar(50) NOT NULL,
                                PRIMARY KEY (`ID_status`),
                                UNIQUE KEY `status_name` (`status_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `filestatushistory` (
                                     `ID_history` int(11) NOT NULL AUTO_INCREMENT,
                                     `ID_file` int(11) NOT NULL,
                                     `ID_status` int(11) NOT NULL,
                                     `changed_by` int(11) NOT NULL,
                                     `changed_at` datetime DEFAULT current_timestamp(),
                                     PRIMARY KEY (`ID_history`),
                                     KEY `fk_fsh_status` (`ID_status`),
                                     KEY `idx_fsh_file` (`ID_file`),
                                     KEY `idx_fsh_user` (`changed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `reviewassignments` (
                                     `ID_assignment` int(11) NOT NULL AUTO_INCREMENT,
                                     `ID_file` int(11) NOT NULL,
                                     `ID_reviewer` int(11) NOT NULL,
                                     `assigned_at` datetime DEFAULT current_timestamp(),
                                     PRIMARY KEY (`ID_assignment`),
                                     KEY `idx_ra_file` (`ID_file`),
                                     KEY `idx_ra_reviewer` (`ID_reviewer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `reviewdecisions` (
                                   `ID_decision` int(11) NOT NULL AUTO_INCREMENT,
                                   `decision_name` varchar(50) NOT NULL,
                                   PRIMARY KEY (`ID_decision`),
                                   UNIQUE KEY `decision_name` (`decision_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `reviews` (
                           `ID_review` int(11) NOT NULL AUTO_INCREMENT,
                           `ID_assignment` int(11) NOT NULL,
                           `score1` decimal(2,1) DEFAULT NULL,
                           `score2` decimal(2,1) DEFAULT NULL,
                           `score3` decimal(2,1) DEFAULT NULL,
                           `ID_decision` int(11) NOT NULL,
                           `comment` text DEFAULT NULL,
                           `created_at` datetime DEFAULT current_timestamp(),
                           PRIMARY KEY (`ID_review`),
                           KEY `fk_reviews_assign` (`ID_assignment`),
                           KEY `fk_reviews_decision` (`ID_decision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `roles` (
                         `ID_roles` int(11) NOT NULL AUTO_INCREMENT,
                         `role_name` varchar(50) NOT NULL,
                         PRIMARY KEY (`ID_roles`),
                         UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `userroles` (
                             `ID_user` int(11) NOT NULL,
                             `ID_role` int(11) NOT NULL,
                             PRIMARY KEY (`ID_user`, `ID_role`),
                             KEY `fk_userroles_role` (`ID_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

CREATE TABLE `users` (
                         `ID_user` int(11) NOT NULL AUTO_INCREMENT,
                         `username` varchar(100) NOT NULL,
                         `email` varchar(150) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `created_at` datetime DEFAULT current_timestamp(),
                         `blocked` tinyint(1) NOT NULL DEFAULT 0,
                         PRIMARY KEY (`ID_user`),
                         UNIQUE KEY `username` (`username`),
                         UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

ALTER TABLE `files`
    ADD CONSTRAINT `fk_files_status` FOREIGN KEY (`ID_status`) REFERENCES `filestatuses` (`ID_status`),
  ADD CONSTRAINT `fk_files_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`ID_user`);

ALTER TABLE `filestatushistory`
    ADD CONSTRAINT `fk_fsh_file` FOREIGN KEY (`ID_file`) REFERENCES `files` (`ID_file`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fsh_status` FOREIGN KEY (`ID_status`) REFERENCES `filestatuses` (`ID_status`),
  ADD CONSTRAINT `fk_fsh_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`ID_user`);

ALTER TABLE `reviewassignments`
    ADD CONSTRAINT `fk_ra_file` FOREIGN KEY (`ID_file`) REFERENCES `files` (`ID_file`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ra_reviewer` FOREIGN KEY (`ID_reviewer`) REFERENCES `users` (`ID_user`);

ALTER TABLE `reviews`
    ADD CONSTRAINT `fk_reviews_assign` FOREIGN KEY (`ID_assignment`) REFERENCES `reviewassignments` (`ID_assignment`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_decision` FOREIGN KEY (`ID_decision`) REFERENCES `reviewdecisions` (`ID_decision`);

ALTER TABLE `userroles`
    ADD CONSTRAINT `fk_userroles_role` FOREIGN KEY (`ID_role`) REFERENCES `roles` (`ID_roles`),
  ADD CONSTRAINT `fk_userroles_user` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`) ON DELETE CASCADE;

