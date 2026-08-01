-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 23 2026 г., 17:49
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yii2basic`
--

-- --------------------------------------------------------

--
-- Структура таблицы `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(64) DEFAULT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 4, 'Registration', 'Registered new user: Qurilish', '::1', 1784783963),
(2, 4, 'Problem Create', 'Created challenge: gjknmkjhvg', '::1', 1784784041),
(3, 4, 'Logout', 'Logged out successfully.', '::1', 1784784052),
(4, 11, 'Registration', 'Registered new user: rais', '::1', 1784784604),
(5, 11, 'Proposal Draft Save', 'Saved proposal draft ID: 1', '::1', 1784784672),
(6, 11, 'Proposal Submit', 'Submitted updated proposal ID: 1', '::1', 1784784874),
(7, 11, 'Logout', 'Logged out successfully.', '::1', 1784784880),
(8, 4, 'Login', 'Logged in successfully.', '::1', 1784784885),
(9, 4, 'Proposal Acceptance', 'Accepted proposal ID: 1 for problem: gjknmkjhvg', '::1', 1784784912),
(10, 4, 'Logout', 'Logged out successfully.', '::1', 1784788401),
(11, 4, 'Login', 'Logged in successfully.', '::1', 1784788423),
(12, 4, 'Logout', 'Logged out successfully.', '::1', 1784788884),
(13, 4, 'Login', 'Logged in successfully.', '::1', 1784789338),
(14, 4, 'Logout', 'Logged out successfully.', '::1', 1784789447);

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name_uz` varchar(255) NOT NULL,
  `name_ru` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name_uz`, `name_ru`, `name_en`, `description`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Muhandislik va Konstruksiyalar', 'Инженерия и Конструкции', 'Engineering & Structure', 'Structural analysis, design optimizations, seismic resistance, load calculations.', 1784700593, 1784700593, 10),
(2, 'Yashil Qurilish va Materiallar', 'Зеленое Строительство и Материалы', 'Green Building & Materials', 'Eco-friendly concrete, thermal insulation, sustainable composites, energy efficiency.', 1784700593, 1784700593, 10),
(3, 'Infratuzilma va Transport', 'Инфраструктура и Транспорт', 'Infrastructure & Transport', 'Roadways, bridges, smart pavement technologies, public transport integration.', 1784700593, 1784700593, 10),
(4, 'Geotexnika Muhandisligi', 'Геотехническая Инженерия', 'Geotechnical Engineering', 'Soil mechanics, deep foundations, slope stability, underground structures.', 1784700593, 1784700593, 10),
(5, 'Aqlli Qurilish va IoT', 'Умное Строительство и IoT', 'Smart Construction & IoT', 'BIM modeling, sensor integration, drones in surveying, site management automation.', 1784700593, 1784700593, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `company_profile`
--

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
  `organization_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `company_profile`
--

INSERT INTO `company_profile` (`id`, `company_name`, `industry`, `website`, `description`, `address`, `phone`, `logo`, `stir`, `responsible_name`, `organization_type`) VALUES
(2, 'Apex Construction LLC', 'Civil Infrastructure', 'https://apex.example.com', 'A leading infrastructure and civil engineering firm specialized in bridge and road works.', '100 Amir Temur Avenue, Tashkent', '+998 71 123 45 67', NULL, NULL, NULL, NULL),
(4, 'Qur mchj', 'sdsdsds', 'https://kun.uz/', 'sdfsdfdsfdsfdsfs', 'ghdfs', '9855499', NULL, '984566551452266655', 'Uz Dastur', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `favorite`
--

CREATE TABLE `favorite` (
  `user_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1784700400),
('m260722_060719_create_initial_tables', 1784700594),
('m260722_062055_add_functional_requirements_columns', 1784701276),
('m260722_063358_add_workflow_fields', 1784702053),
('m260722_064213_create_files_and_settings_tables', 1784702552),
('m260722_074857_add_status_to_category', 1784706667),
('m260722_121030_add_indexes_and_refactor_statuses', 1784722259),
('m260722_121520_add_access_token_to_user', 1784722544);

-- --------------------------------------------------------

--
-- Структура таблицы `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `title`, `message`, `is_read`, `link`, `created_at`) VALUES
(1, 4, 'Welcome to Antigravity!', 'Welcome to Antigravity Construction Innovation Ecosystem. Please complete your profile to start collaborating.', 1, '/profile/index', 1784783964),
(2, 1, 'Moderatsiyadagi muammolar', 'New problem submitted for moderation: gjknmkjhvg', 0, '/dashboard/index', 1784784041),
(3, 11, 'Welcome to Antigravity!', 'Welcome to Antigravity Construction Innovation Ecosystem. Please complete your profile to start collaborating.', 1, '/profile/index', 1784784604),
(4, 4, 'Proposal Updated', 'Scientist Uz Dastur updated solution proposal \'fsfsdf\'', 1, '/proposal/view?id=1', 1784784875),
(5, 11, 'Taklif qabul qilindi. Aloqa ma’lumotlari o‘rtoqlashildi.', 'Congratulations! Your proposal for \'gjknmkjhvg\' was accepted. Contact details are now available.', 0, '/proposal/view?id=1', 1784784912);

-- --------------------------------------------------------

--
-- Структура таблицы `problem`
--

CREATE TABLE `problem` (
  `id` int(11) NOT NULL,
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
  `attachment_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `problem`
--

INSERT INTO `problem` (`id`, `company_id`, `category_id`, `title`, `description`, `requirements`, `budget`, `deadline`, `status`, `views_count`, `created_at`, `updated_at`, `expected_result`, `attachment_file`) VALUES
(1, 4, 1, 'gjknmkjhvg', 'oiugyfdtxfghvjbknlm;,\'.', 'fsdfsfds', 5000.00, 1785448800, 0, 8, 1784784041, 1784784912, 'fdsssssfsfsd', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `problem_file`
--

CREATE TABLE `problem_file` (
  `id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `uploaded_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `proposal`
--

CREATE TABLE `proposal` (
  `id` int(11) NOT NULL,
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
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `proposal`
--

INSERT INTO `proposal` (`id`, `problem_id`, `scientist_id`, `title`, `description`, `solution_details`, `budget_offer`, `time_offer`, `document_file`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 'fsfsdf', 'sdfsdf', 'ddfsdf', NULL, '', NULL, 30, 1784784672, 1784784912);

-- --------------------------------------------------------

--
-- Структура таблицы `proposal_file`
--

CREATE TABLE `proposal_file` (
  `id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `uploaded_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `scientist_profile`
--

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
  `certificates` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `scientist_profile`
--

INSERT INTO `scientist_profile` (`id`, `first_name`, `last_name`, `academic_degree`, `institution`, `specialization`, `bio`, `phone`, `cv_file`, `dob`, `academic_title`, `photo`, `skills`, `portfolio`, `publications`, `certificates`) VALUES
(3, 'Alisher', 'Usmanov', 'Doctor of Science (DSc)', 'Tashkent State Technical University', 'Innovative Construction Materials & Nanotechnology', 'Over 15 years of research experience in carbon nanotube-reinforced concrete structures.', '+998 90 987 65 43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Uz', 'Dastur', 'gfgf', 'fggfgfgf', 'gfgfgf', 'fghzxvzxvxzvx', 'gfgfgf', NULL, '2026-07-21', 'gfgfgfg', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `setting`
--

INSERT INTO `setting` (`id`, `key`, `value`, `description`) VALUES
(1, 'site_name', 'Qurilish-loyiha.uz', 'Site Title name'),
(2, 'session_timeout', '1440', 'Session timeout in seconds'),
(3, 'max_file_size', '52428800', 'Maximum file upload limit in bytes (50MB)'),
(4, 'maintenance_mode', '0', 'Maintenance Mode (1 = Enabled, 0 = Disabled)');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `role` varchar(32) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `language` varchar(5) NOT NULL DEFAULT 'uz',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password_hash`, `auth_key`, `role`, `status`, `language`, `created_at`, `updated_at`, `access_token`) VALUES
(1, 'admin', 'admin@antigravity.uz', '$2y$13$.cjcrdMArPivg9FGO2uJ2eH8RJPcjrCWe4b9Z9ijJkM.Bhyqqs1e6', '3HkS5qEXCeeZu5kifsS74FQmAsBcnZQS', 'administrator', 10, 'uz', 1784700593, 1784700593, 'admin_bearer_token'),
(2, 'company1', 'company@example.com', '$2y$13$6LbboQf5XZnVcO5XxCzMIuArREp3vnkNCl/Bm9N52EO/7WiEyUqfW', 'LxNnCO1awCsGrn4ZJDJMtgBCMKwAhIld', 'company', 10, 'uz', 1784700593, 1784700593, 'company_bearer_token'),
(3, 'scientist1', 'scientist@example.com', '$2y$13$o5B/Pk2Eh4nIkKmO1/vtEeXAoGZTs9BKAkSuxDUqaRMlTY3EBjwhK', 'nMi16_vgOeQl49SeITFUYQkFzhY8nee7', 'scientist', 10, 'uz', 1784700593, 1784700593, 'scientist_bearer_token'),
(4, 'Qurilish', 'uzdastur.uz23@gmail.com', '$2y$13$D5zTRIXAuPMurgNfEjxINelrG8jGDhYOiUtNepQzHGqiwziFS/ol6', '3cIkSADWJAsxci4Pz7Yi5I5psCZHprQT', 'company', 10, 'ru', 1784783963, 1784789434, NULL),
(11, 'rais', 'uzdastur.uz123@gmail.com', '$2y$13$DCwf/fjCJnDt4FKHVeRkMOr8I9yd5X9/rdYA0NFrsVEVXjrkPtRva', 'EDZt-5Xtx_J10ruQCUemy417CJegspEd', 'scientist', 10, 'uz', 1784784604, 1784784604, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_log_user` (`user_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `company_profile`
--
ALTER TABLE `company_profile`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`user_id`,`problem_id`),
  ADD KEY `fk_favorite_problem` (`problem_id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notification_user` (`user_id`);

--
-- Индексы таблицы `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_problem_company` (`company_id`),
  ADD KEY `fk_problem_category` (`category_id`),
  ADD KEY `idx-problem-status` (`status`),
  ADD KEY `idx-problem-created_at` (`created_at`),
  ADD KEY `idx-problem-deadline` (`deadline`);
ALTER TABLE `problem` ADD FULLTEXT KEY `ft_problem_title_desc` (`title`,`description`);

--
-- Индексы таблицы `problem_file`
--
ALTER TABLE `problem_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_problem_file_problem` (`problem_id`);

--
-- Индексы таблицы `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proposal_problem` (`problem_id`),
  ADD KEY `fk_proposal_scientist` (`scientist_id`),
  ADD KEY `idx-proposal-status` (`status`),
  ADD KEY `idx-proposal-created_at` (`created_at`);
ALTER TABLE `proposal` ADD FULLTEXT KEY `ft_proposal_desc` (`description`);

--
-- Индексы таблицы `proposal_file`
--
ALTER TABLE `proposal_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proposal_file_proposal` (`proposal_id`);

--
-- Индексы таблицы `scientist_profile`
--
ALTER TABLE `scientist_profile`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `access_token` (`access_token`),
  ADD KEY `idx-user-role` (`role`),
  ADD KEY `idx-user-status` (`status`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `problem`
--
ALTER TABLE `problem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `problem_file`
--
ALTER TABLE `problem_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `proposal_file`
--
ALTER TABLE `proposal_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `fk_audit_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `company_profile`
--
ALTER TABLE `company_profile`
  ADD CONSTRAINT `fk_company_profile_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `fk_favorite_problem` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `fk_problem_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_problem_company` FOREIGN KEY (`company_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `problem_file`
--
ALTER TABLE `problem_file`
  ADD CONSTRAINT `fk_problem_file_problem` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `fk_proposal_problem` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_proposal_scientist` FOREIGN KEY (`scientist_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `proposal_file`
--
ALTER TABLE `proposal_file`
  ADD CONSTRAINT `fk_proposal_file_proposal` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `scientist_profile`
--
ALTER TABLE `scientist_profile`
  ADD CONSTRAINT `fk_scientist_profile_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
