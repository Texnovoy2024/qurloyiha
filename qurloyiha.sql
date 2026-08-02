-- Antigravity Database Dump
-- Generated: 2026-07-22 14:22:44

CREATE DATABASE IF NOT EXISTS `qurloyiha` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `qurloyiha`;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `role` varchar(32) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `language` varchar(5) NOT NULL DEFAULT 'uz',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `access_token` (`access_token`),
  KEY `idx-user-role` (`role`),
  KEY `idx-user-status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` VALUES 
(1, 'admin', 'admin@antigravity.uz', '$2y$13$.cjcrdMArPivg9FGO2uJ2eH8RJPcjrCWe4b9Z9ijJkM.Bhyqqs1e6', '3HkS5qEXCeeZu5kifsS74FQmAsBcnZQS', 'administrator', 10, 'uz', 1784700593, 1784700593, 'admin_bearer_token'),
(2, 'company1', 'company@example.com', '$2y$13$6LbboQf5XZnVcO5XxCzMIuArREp3vnkNCl/Bm9N52EO/7WiEyUqfW', 'LxNnCO1awCsGrn4ZJDJMtgBCMKwAhIld', 'company', 10, 'uz', 1784700593, 1784700593, 'company_bearer_token'),
(3, 'scientist1', 'scientist@example.com', '$2y$13$o5B/Pk2Eh4nIkKmO1/vtEeXAoGZTs9BKAkSuxDUqaRMlTY3EBjwhK', 'nMi16_vgOeQl49SeITFUYQkFzhY8nee7', 'scientist', 10, 'uz', 1784700593, 1784700593, 'scientist_bearer_token');

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_uz` varchar(255) NOT NULL,
  `name_ru` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `category` VALUES 
(1, 'Muhandislik va Konstruksiyalar', 'Инженерия и Конструкции', 'Engineering & Structure', 'Structural analysis, design optimizations, seismic resistance, load calculations.', 1784700593, 1784700593, 10),
(2, 'Yashil Qurilish va Materiallar', 'Зеленое Строительство и Материалы', 'Green Building & Materials', 'Eco-friendly concrete, thermal insulation, sustainable composites, energy efficiency.', 1784700593, 1784700593, 10),
(3, 'Infratuzilma va Transport', 'Инфраструктура и Транспорт', 'Infrastructure & Transport', 'Roadways, bridges, smart pavement technologies, public transport integration.', 1784700593, 1784700593, 10),
(4, 'Geotexnika Muhandisligi', 'Геотехническая Инженерия', 'Geotechnical Engineering', 'Soil mechanics, deep foundations, slope stability, underground structures.', 1784700593, 1784700593, 10),
(5, 'Aqlli Qurilish va IoT', 'Умное Строительство и IoT', 'Smart Construction & IoT', 'BIM modeling, sensor integration, drones in surveying, site management automation.', 1784700593, 1784700593, 10);

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `setting` VALUES 
(1, 'site_name', 'Antigravity', 'Site Title name'),
(2, 'session_timeout', '1440', 'Session timeout in seconds'),
(3, 'max_file_size', '52428800', 'Maximum file upload limit in bytes (50MB)'),
(4, 'maintenance_mode', '0', 'Maintenance Mode (1 = Enabled, 0 = Disabled)');

DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migration` VALUES 
('m000000_000000_base', 1784700400),
('m260722_060719_create_initial_tables', 1784700594),
('m260722_062055_add_functional_requirements_columns', 1784701276),
('m260722_063358_add_workflow_fields', 1784702053),
('m260722_064213_create_files_and_settings_tables', 1784702552),
('m260722_074857_add_status_to_category', 1784706667),
('m260722_121030_add_indexes_and_refactor_statuses', 1784722259),
('m260722_121520_add_access_token_to_user', 1784722544);

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(64) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_audit_log_user` (`user_id`),
  CONSTRAINT `fk_audit_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `company_profile`;
CREATE TABLE `company_profile` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `stir` varchar(32) DEFAULT NULL,
  `responsible_name` varchar(255) DEFAULT NULL,
  `organization_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_company_profile_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `company_profile` VALUES 
(2, 'Apex Construction LLC', 'Civil Infrastructure', 'https://apex.example.com', 'A leading infrastructure and civil engineering firm specialized in bridge and road works.', '100 Amir Temur Avenue, Tashkent', '+998 71 123 45 67', NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `scientist_profile`;
CREATE TABLE `scientist_profile` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `academic_degree` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `dob` varchar(64) DEFAULT NULL,
  `academic_title` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `portfolio` text DEFAULT NULL,
  `publications` text DEFAULT NULL,
  `certificates` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_scientist_profile_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `scientist_profile` VALUES 
(3, 'Alisher', 'Usmanov', 'Doctor of Science (DSc)', 'Tashkent State Technical University', 'Innovative Construction Materials & Nanotechnology', 'Over 15 years of research experience in carbon nanotube-reinforced concrete structures.', '+998 90 987 65 43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notification_user` (`user_id`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `problem`;
CREATE TABLE `problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `deadline` int(11) DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `expected_result` text DEFAULT NULL,
  `attachment_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_problem_company` (`company_id`),
  KEY `fk_problem_category` (`category_id`),
  KEY `idx-problem-status` (`status`),
  KEY `idx-problem-created_at` (`created_at`),
  KEY `idx-problem-deadline` (`deadline`),
  FULLTEXT KEY `ft_problem_title_desc` (`title`,`description`),
  CONSTRAINT `fk_problem_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_problem_company` FOREIGN KEY (`company_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `problem_file`;
CREATE TABLE `problem_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `uploaded_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_problem_file_problem` (`problem_id`),
  CONSTRAINT `fk_problem_file_problem` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `proposal`;
CREATE TABLE `proposal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL,
  `scientist_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `solution_details` text NOT NULL,
  `budget_offer` decimal(15,2) DEFAULT NULL,
  `time_offer` varchar(255) DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_proposal_problem` (`problem_id`),
  KEY `fk_proposal_scientist` (`scientist_id`),
  KEY `idx-proposal-status` (`status`),
  KEY `idx-proposal-created_at` (`created_at`),
  FULLTEXT KEY `ft_proposal_desc` (`description`),
  CONSTRAINT `fk_proposal_problem` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_proposal_scientist` FOREIGN KEY (`scientist_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `proposal_file`;
CREATE TABLE `proposal_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposal_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `uploaded_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_proposal_file_proposal` (`proposal_id`),
  CONSTRAINT `fk_proposal_file_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `favorite`;
CREATE TABLE `favorite` (
  `user_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`problem_id`),
  KEY `fk_favorite_problem` (`problem_id`),
  CONSTRAINT `fk_favorite_problem` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
