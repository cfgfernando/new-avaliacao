-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para new-church
CREATE DATABASE IF NOT EXISTS `new-church` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `new-church`;

-- Copiando estrutura para tabela gestao_celula_mda.accounting_audits
CREATE TABLE IF NOT EXISTS `accounting_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `journal_entry_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounting_audits_journal_entry_id_foreign` (`journal_entry_id`),
  KEY `accounting_audits_user_id_foreign` (`user_id`),
  CONSTRAINT `accounting_audits_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `accounting_audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.accounting_audits: ~5 rows (aproximadamente)
INSERT INTO `accounting_audits` (`id`, `journal_entry_id`, `user_id`, `action`, `old_values`, `new_values`, `ip_address`, `reason`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'created', NULL, '{"id": 1, "amount": "35.00", "cell_id": null, "user_id": 1, "reference": null, "created_at": "2026-03-24T22:02:11.000000Z", "entry_date": "2026-03-24T00:00:00.000000Z", "updated_at": "2026-03-24T22:02:11.000000Z", "description": "oferta", "is_voluntary": false, "transaction_id": 4, "debit_account_id": 3, "credit_account_id": 16}', '127.0.0.1', NULL, '2026-03-25 01:02:11', '2026-03-25 01:02:11'),
	(2, 2, 1, 'created', NULL, '{"id": 2, "amount": "500.00", "cell_id": null, "user_id": 1, "reference": null, "created_at": "2026-03-25T01:51:34.000000Z", "entry_date": "2026-03-25T00:00:00.000000Z", "updated_at": "2026-03-25T01:51:34.000000Z", "description": "Dízimo mês de abril", "is_voluntary": false, "transaction_id": 5, "debit_account_id": 4, "credit_account_id": 14}', '127.0.0.1', NULL, '2026-03-25 04:51:34', '2026-03-25 04:51:34'),
	(3, 3, 1, 'created', NULL, '{"id": 3, "amount": "136.00", "cell_id": null, "user_id": 1, "reference": null, "created_at": "2026-04-01T15:27:59.000000Z", "entry_date": "2026-04-01T00:00:00.000000Z", "updated_at": "2026-04-01T15:27:59.000000Z", "description": "pagamento de água copinhos", "is_voluntary": false, "transaction_id": 12, "debit_account_id": 25, "credit_account_id": 9}', '127.0.0.1', NULL, '2026-04-01 18:27:59', '2026-04-01 18:27:59'),
	(4, 4, 1, 'created', NULL, '{"id": 4, "amount": "19.00", "cell_id": null, "user_id": 1, "reference": null, "created_at": "2026-04-28T18:36:31.000000Z", "entry_date": "2026-04-28T00:00:00.000000Z", "updated_at": "2026-04-28T18:36:31.000000Z", "description": "Venda PDV (Vários Itens)", "is_voluntary": false, "transaction_id": 26, "debit_account_id": 4, "credit_account_id": 14}', '127.0.0.1', NULL, '2026-04-28 21:36:31', '2026-04-28 21:36:31'),
	(5, 5, 1, 'created', NULL, '{"id": 5, "amount": "3.00", "cell_id": null, "user_id": 1, "reference": null, "created_at": "2026-04-28T18:55:58.000000Z", "entry_date": "2026-04-28T00:00:00.000000Z", "updated_at": "2026-04-28T18:55:58.000000Z", "description": "Venda PDV (Vários Itens)", "is_voluntary": false, "transaction_id": 27, "debit_account_id": 4, "credit_account_id": 14}', '127.0.0.1', NULL, '2026-04-28 21:55:58', '2026-04-28 21:55:58');

-- Copiando estrutura para tabela gestao_celula_mda.areas
CREATE TABLE IF NOT EXISTS `areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `district_id` bigint unsigned NOT NULL,
  `campus_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `areas_district_id_foreign` (`district_id`),
  KEY `areas_supervisor_id_foreign` (`supervisor_id`),
  KEY `areas_campus_id_foreign` (`campus_id`),
  CONSTRAINT `areas_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `areas_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `areas_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.areas: ~6 rows (aproximadamente)
INSERT INTO `areas` (`id`, `district_id`, `campus_id`, `name`, `supervisor_id`, `created_at`, `updated_at`) VALUES
	(1, 2, NULL, 'Área Vinho Novo', 4, '2026-03-20 16:46:55', '2026-03-21 05:50:30'),
	(2, 3, NULL, 'Área Amarela', NULL, '2026-03-21 03:58:55', '2026-03-21 04:37:10'),
	(3, 4, NULL, 'Área Azul', NULL, '2026-03-21 03:59:15', '2026-03-21 04:37:24'),
	(5, 5, NULL, 'DIFLEN', NULL, '2026-03-21 19:42:16', '2026-03-21 19:42:16'),
	(6, 6, NULL, 'START', NULL, '2026-03-21 19:42:34', '2026-03-21 19:42:34'),
	(7, 7, NULL, 'KIDS', NULL, '2026-03-21 19:42:43', '2026-03-21 19:42:43');

-- Copiando estrutura para tabela gestao_celula_mda.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.audit_logs: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.banks
CREATE TABLE IF NOT EXISTS `banks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banks_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.banks: ~21 rows (aproximadamente)
INSERT INTO `banks` (`id`, `code`, `name`, `full_name`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, '001', 'Banco do Brasil', 'Banco do Brasil S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(2, '033', 'Santander', 'Banco Santander (Brasil) S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(3, '104', 'Caixa Econômica', 'Caixa Econômica Federal', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(4, '237', 'Bradesco', 'Banco Bradesco S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(5, '341', 'Itaú', 'Itaú Unibanco S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(6, '077', 'Inter', 'Banco Inter S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(7, '260', 'Nubank', 'Nu Pagamentos S.A.', 1, '2026-04-02 22:34:41', '2026-04-06 18:13:35'),
	(8, '422', 'Safra', 'Banco Safra S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(9, '633', 'Rendimento', 'Banco Rendimento S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(10, '748', 'Sicredi', 'Banco Cooperativo Sicredi S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(11, '756', 'Sicoob', 'Banco Cooperativo do Brasil S.A.', 1, '2026-04-02 22:34:41', '2026-04-06 18:13:35'),
	(12, '041', 'Banrisul', 'Banco do Estado do Rio Grande do Sul S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(13, '212', 'Original', 'Banco Original S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(14, '604', 'Industrial', 'Banco Industrial do Brasil S.A.', 1, '2026-04-02 22:34:41', '2026-04-02 22:34:41'),
	(15, '655', 'Neon', 'Neon Pagamentos S.A.', 1, '2026-04-02 22:34:41', '2026-04-04 04:05:53'),
	(16, '069', 'C6 Bank', 'Banco C6 S.A.', 1, '2026-04-04 04:05:53', '2026-04-04 04:05:53'),
	(17, '336', 'C6 Bank (Direct)', 'Banco C6 S.A.', 1, '2026-04-04 04:05:53', '2026-04-04 04:05:53'),
	(18, '707', 'Daycoval', 'Banco Daycoval S.A.', 1, '2026-04-04 04:05:53', '2026-04-04 04:05:53'),
	(19, '637', 'Sofisa', 'Banco Sofisa S.A.', 1, '2026-04-04 04:05:53', '2026-04-04 04:05:53'),
	(20, '085', 'Ailos', 'Cooperativa Central Ailos', 1, '2026-04-04 04:05:53', '2026-04-04 04:05:53'),
	(21, '004', 'BNB', 'Banco do Nordeste do Brasil S.A.', 1, '2026-04-04 04:05:53', '2026-04-04 04:05:53');

-- Copiando estrutura para tabela gestao_celula_mda.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.cache: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.cache_locks: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.campuses
CREATE TABLE IF NOT EXISTS `campuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('cathedral','nucleus') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nucleus',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campuses_name_unique` (`name`),
  UNIQUE KEY `campuses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.campuses: ~9 rows (aproximadamente)
INSERT INTO `campuses` (`id`, `name`, `slug`, `cnpj`, `email`, `phone`, `type`, `address`, `city`, `state`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Catedral Sede', 'catedral-sede', NULL, NULL, NULL, 'cathedral', NULL, 'Curitiba', 'PR', 1, '2026-04-04 03:09:03', '2026-04-06 17:04:37'),
	(3, 'Núcleo Contenda', 'nucleo-contenda', NULL, NULL, NULL, 'nucleus', NULL, NULL, NULL, 1, '2026-04-04 03:40:10', '2026-04-04 03:40:10'),
	(4, 'Núcleo Batel', 'nucleo-batel', NULL, NULL, NULL, 'nucleus', NULL, 'Curitiba', 'PR', 1, '2026-04-06 17:04:37', '2026-04-06 17:04:37'),
	(5, 'Núcleo Santa Felicidade', 'nucleo-santa-felicidade', NULL, NULL, NULL, 'nucleus', NULL, 'Curitiba', 'PR', 1, '2026-04-06 17:04:37', '2026-04-06 17:04:37'),
	(6, 'Núcleo Portão', 'nucleo-portao', NULL, NULL, NULL, 'nucleus', NULL, 'Curitiba', 'PR', 1, '2026-04-06 17:04:37', '2026-04-06 17:04:37'),
	(7, 'Núcleo CIC', 'nucleo-cic', NULL, NULL, NULL, 'nucleus', NULL, 'Curitiba', 'PR', 1, '2026-04-06 17:04:37', '2026-04-06 17:04:37'),
	(8, 'Núcleo Pinhais', 'nucleo-pinhais', NULL, NULL, NULL, 'nucleus', NULL, 'Pinhais', 'PR', 1, '2026-04-06 17:04:37', '2026-04-06 17:04:37'),
	(9, 'Missions Africa', 'missions-africa', NULL, NULL, NULL, 'nucleus', NULL, 'Luanda', 'AO', 1, '2026-04-06 17:04:37', '2026-04-06 17:04:37');

-- Copiando estrutura para tabela gestao_celula_mda.cells
CREATE TABLE IF NOT EXISTS `cells` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sector_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_day` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_time` time DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `whatsapp_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `leader_id` bigint unsigned DEFAULT NULL,
  `parent_cell_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cells_sector_id_foreign` (`sector_id`),
  KEY `cells_leader_id_foreign` (`leader_id`),
  KEY `cells_parent_cell_id_foreign` (`parent_cell_id`),
  CONSTRAINT `cells_leader_id_foreign` FOREIGN KEY (`leader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cells_parent_cell_id_foreign` FOREIGN KEY (`parent_cell_id`) REFERENCES `cells` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cells_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.cells: ~3 rows (aproximadamente)
INSERT INTO `cells` (`id`, `sector_id`, `name`, `address`, `meeting_day`, `meeting_time`, `description`, `whatsapp_group`, `is_active`, `latitude`, `longitude`, `leader_id`, `parent_cell_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Célula Em Seu Nome', 'Rua das Oliveiras, 153', NULL, NULL, NULL, NULL, 1, -23.55050000, -46.63330000, NULL, NULL, '2026-03-10 05:17:09', '2026-04-09 16:07:37'),
	(2, 1, 'Célula Pedaço do Céu', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, '2026-03-21 04:00:59', '2026-03-21 04:00:59'),
	(3, 1, 'Célula Jesus é o Caminho', 'Rua Dep. João Leopoldo Jacomel , 57 Iguaçu', 'Quinta-feira', '20:00:00', NULL, NULL, 1, NULL, NULL, 6, 1, '2026-03-21 04:01:17', '2026-04-09 16:10:08');

-- Copiando estrutura para tabela gestao_celula_mda.chart_of_accounts
CREATE TABLE IF NOT EXISTS `chart_of_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ex: 3.1.1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ativo, Passivo, Patrimonio Social, Receita, Despesa',
  `tax_classification` enum('exempt','taxable','neutral') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'neutral' COMMENT 'Imune/Isenta, Tributável, ou Neutro',
  `is_analytical` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'true=lança transações; false=síntese',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chart_of_accounts_code_unique` (`code`),
  KEY `chart_of_accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.chart_of_accounts: ~37 rows (aproximadamente)
INSERT INTO `chart_of_accounts` (`id`, `parent_id`, `code`, `name`, `type`, `tax_classification`, `is_analytical`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
	(1, NULL, '1', 'ATIVO', 'asset', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(2, 1, '1.1', 'Ativo Circulante', 'asset', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(3, 2, '1.1.1', 'Caixa e Equivalentes de Caixa', 'asset', 'neutral', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(4, 2, '1.1.2', 'Bancos Conta Movimento', 'asset', 'neutral', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(5, 1, '1.2', 'Ativo Não Circulante', 'asset', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(6, 5, '1.2.1', 'Imobilizado (Bens e Templos)', 'asset', 'neutral', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(7, NULL, '2', 'PASSIVO', 'liability', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(8, 7, '2.1', 'Passivo Circulante', 'liability', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(9, 8, '2.1.1', 'Fornecedores a Pagar', 'liability', 'neutral', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(10, NULL, '3', 'PATRIMÔNIO SOCIAL', 'equity', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(11, 10, '3.1', 'Fundo Patrimonial', 'equity', 'neutral', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(12, NULL, '4', 'RECEITAS', 'revenue', 'neutral', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(13, 12, '4.1', 'Receitas Imunes / Isentas', 'revenue', 'exempt', 0, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(14, 13, '4.1.1', 'Dízimos', 'revenue', 'exempt', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(15, 13, '4.1.2', 'Ofertas de Célula', 'revenue', 'exempt', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(16, 13, '4.1.3', 'Ofertas de Culto', 'revenue', 'exempt', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(17, 13, '4.1.4', 'Missões / Campanhas', 'revenue', 'exempt', 1, 1, NULL, '2026-03-24 19:23:14', '2026-03-24 19:23:14'),
	(18, 13, '4.1.5', 'Trabalho Voluntário (Valor Justo)', 'revenue', 'exempt', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(19, 12, '4.2', 'Receitas Tributáveis', 'revenue', 'taxable', 0, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(20, 19, '4.2.1', 'Cantina / Lanchonete', 'revenue', 'taxable', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(21, 19, '4.2.2', 'Livraria / Gráfica', 'revenue', 'taxable', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(22, 19, '4.2.3', 'Eventos Pagos', 'revenue', 'taxable', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(23, NULL, '5', 'DESPESAS', 'expense', 'neutral', 0, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(24, 23, '5.1', 'Despesas Ministeriais', 'expense', 'neutral', 0, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(25, 24, '5.1.1', 'Alimentação / Lanche de Célula', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(26, 24, '5.1.2', 'Material de Apoio', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(27, 24, '5.1.3', 'Eventos e Retiros', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(28, 24, '5.1.4', 'Missões (Envio)', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(29, 23, '5.2', 'Despesas Administrativas', 'expense', 'neutral', 0, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(30, 29, '5.2.1', 'Aluguel / IPTU', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(31, 29, '5.2.2', 'Água, Luz e Internet', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(32, 29, '5.2.3', 'Salários e Encargos', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(33, 29, '5.2.4', 'Manutenção Predial', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(34, 23, '5.3', 'Ação Social', 'expense', 'neutral', 0, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(35, 34, '5.3.1', 'Kg do Amor / Cestas Básicas', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(36, 34, '5.3.2', 'Auxílio Emergencial', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15'),
	(37, 23, '5.4', 'Trabalho Voluntário (Valor Justo)', 'expense', 'neutral', 1, 1, NULL, '2026-03-24 19:23:15', '2026-03-24 19:23:15');

-- Copiando estrutura para tabela gestao_celula_mda.cost_centers
CREATE TABLE IF NOT EXISTS `cost_centers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campus_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cost_centers_code_unique` (`code`),
  KEY `cost_centers_campus_id_foreign` (`campus_id`),
  CONSTRAINT `cost_centers_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.cost_centers: ~31 rows (aproximadamente)
INSERT INTO `cost_centers` (`id`, `code`, `name`, `description`, `campus_id`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, NULL, '1000 - OPERACIONAL E ADMINISTRATIVO (Sede/Núcleos)', NULL, NULL, 1, '2026-04-02 02:33:06', '2026-04-02 02:50:05'),
	(2, '1000', 'OPERACIONAL E ADMINISTRATIVO (Sede/Núcleos)', 'Categoria Principal', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(3, '1100', 'Secretaria e Adm', 'Despesas de escritório, papelaria, sistemas.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(4, '1200', 'Infraestrutura e Manutenção', 'Reformas, reparos, limpeza.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(5, '1300', 'Recursos Humanos', 'Salários pastorais, ajuda de custo, encargos.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(6, '1400', 'Comunicação e Marketing', 'Redes sociais, site, tráfego pago.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(7, '1500', 'Taxas e Impostos', 'Tarifas bancárias, contabilidade externa.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(8, '2000', 'MINISTÉRIOS E REDES (Coração da Visão MDA)', 'Categoria Principal', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(9, '2100', 'Rede de Crianças (Kids)', 'Material didático, eventos infantis.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(10, '2200', 'Rede de Jovens (Radicais)', 'Eventos, conferências, acampamentos.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(11, '2300', 'Rede de Homens / Mulheres', 'Chás, congressos, encontros específicos.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(12, '2400', 'Ministério de Louvor e Artes', 'Som, instrumentos, figurinos, iluminação.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(13, '2500', 'Hospitalidade / Boas-Vindas', 'Recepção, café, integração de novos membros.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(14, '3000', 'EDUCAÇÃO E TREINAMENTO (Trilho de Liderança)', 'Categoria Principal', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(15, '3100', 'Escola do Discípulo', 'Materiais, apostilas, coffee break.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(16, '3200', 'TADEL (Treinamento Avançado)', 'Formação de líderes e supervisores.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(17, '3300', 'CTL (Centro de Treinamento de Líderes)', 'Treinamentos intensivos.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(18, '3400', 'Encontro com Deus', 'Logística de pré-encontro, retiro e pós-encontro.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(19, '4000', 'EVENTOS E CELEBRAÇÕES', 'Categoria Principal', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(20, '4100', 'Cultos de Celebração', 'Decoração, aluguel de equipamentos extras.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(21, '4200', 'Deep Conference / Conferências', 'Grandes eventos anuais.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(22, '4300', 'Batismos', 'Logística de piscina, becas, certificados.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(23, '4400', 'Santa Ceia', 'Elementos e logística da ceia.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(24, '5000', 'AÇÃO SOCIAL E MISSÕES', 'Categoria Principal', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(25, '5100', 'Quilo do Amor', 'Cestas básicas e auxílio a membros em vulnerabilidade.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(26, '5200', 'Missões Locais', 'Projetos em favelas, hospitais ou presídios.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(27, '5300', 'Missões Transculturais', 'Repasses para missionários e bases externas.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(28, '5400', 'Generosidade / Visão do Futuro', 'Projetos de expansão e compra de templos.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(29, '6000', 'RECEITAS TRIBUTÁVEIS (Segregação Fiscal)', 'Categoria Principal', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(30, '6100', 'Cantina / Lanchonete', 'Compra de insumos e manutenção da cantina.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11'),
	(31, '6200', 'Livraria / Merchandising', 'Camisetas, livros, adesivos.', NULL, 1, '2026-04-02 02:46:11', '2026-04-02 02:46:11');

-- Copiando estrutura para tabela gestao_celula_mda.districts
CREATE TABLE IF NOT EXISTS `districts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `network_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_network_id_foreign` (`network_id`),
  KEY `districts_supervisor_id_foreign` (`supervisor_id`),
  CONSTRAINT `districts_network_id_foreign` FOREIGN KEY (`network_id`) REFERENCES `networks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `districts_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.districts: ~6 rows (aproximadamente)
INSERT INTO `districts` (`id`, `network_id`, `name`, `supervisor_id`, `created_at`, `updated_at`) VALUES
	(2, 1, 'Distrito Vinho Novo', 3, '2026-03-21 04:36:18', '2026-03-21 05:44:12'),
	(3, 1, 'Distrito Amarelo', NULL, '2026-03-21 04:37:05', '2026-03-21 04:37:05'),
	(4, 1, 'Distrito Azul', NULL, '2026-03-21 04:37:18', '2026-03-21 04:37:18'),
	(5, 1, 'Distrito DIFLEN', NULL, '2026-03-21 19:35:45', '2026-03-21 19:35:45'),
	(6, 1, 'Distrito START', NULL, '2026-03-21 19:36:08', '2026-03-21 19:36:08'),
	(7, 1, 'Distrito KIDS', NULL, '2026-03-21 19:36:23', '2026-03-21 19:36:23');

-- Copiando estrutura para tabela gestao_celula_mda.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int DEFAULT NULL,
  `is_course` tinyint(1) NOT NULL DEFAULT '0',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('published','draft','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'geral',
  `user_id` bigint unsigned NOT NULL,
  `district_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_user_id_foreign` (`user_id`),
  KEY `events_district_id_foreign` (`district_id`),
  CONSTRAINT `events_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.events: ~1 rows (aproximadamente)
INSERT INTO `events` (`id`, `title`, `description`, `date`, `time`, `location`, `capacity`, `is_course`, `image_path`, `status`, `type`, `user_id`, `district_id`, `created_at`, `updated_at`) VALUES
	(1, 'Evento teste jesus é o caminho', 'Evento teste jesus é o caminho', '2026-03-28', '20:00:00', 'Evento teste jesus é o caminho', NULL, 0, NULL, 'published', 'celula', 6, 2, '2026-03-21 06:55:06', '2026-03-21 06:55:06');

-- Copiando estrutura para tabela gestao_celula_mda.event_enrollments
CREATE TABLE IF NOT EXISTS `event_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `member_id` bigint unsigned NOT NULL,
  `status` enum('pending','confirmed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_enrollments_event_id_foreign` (`event_id`),
  KEY `event_enrollments_member_id_foreign` (`member_id`),
  CONSTRAINT `event_enrollments_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_enrollments_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.event_enrollments: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.failed_jobs: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.financial_accounts
CREATE TABLE IF NOT EXISTS `financial_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('bank','cash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ex: Caixa Econômica, Bradesco',
  `agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint unsigned DEFAULT NULL,
  `pix_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nucleus_id` bigint unsigned DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_cache` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Saldo calculado automaticamente',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `financial_accounts_nucleus_id_foreign` (`nucleus_id`),
  KEY `financial_accounts_bank_id_foreign` (`bank_id`),
  CONSTRAINT `financial_accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `financial_accounts_nucleus_id_foreign` FOREIGN KEY (`nucleus_id`) REFERENCES `networks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.financial_accounts: ~1 rows (aproximadamente)
INSERT INTO `financial_accounts` (`id`, `name`, `type`, `bank_name`, `agency`, `account_number`, `bank_id`, `pix_key`, `nucleus_id`, `opening_balance`, `balance_cache`, `is_active`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Banco Teste', 'bank', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 1, NULL, '2026-03-25 01:01:43', '2026-03-25 01:01:43');

-- Copiando estrutura para tabela gestao_celula_mda.financial_batches
CREATE TABLE IF NOT EXISTS `financial_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campus_id` bigint unsigned NOT NULL,
  `reference_period` char(7) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'MM/YYYY',
  `cash_reported` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Dinheiro em espécie declarado pelo líder',
  `pix_reported` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'PIX declarado pelo líder',
  `cash_confirmed` decimal(15,2) DEFAULT NULL COMMENT 'Dinheiro confirmado na conferência',
  `pix_confirmed` decimal(15,2) DEFAULT NULL COMMENT 'PIX confirmado no extrato',
  `cash_difference` decimal(15,2) DEFAULT NULL,
  `pix_difference` decimal(15,2) DEFAULT NULL,
  `status` enum('draft','submitted','reconciling','settled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `financial_account_id` bigint unsigned DEFAULT NULL,
  `submitted_by` bigint unsigned DEFAULT NULL,
  `confirmed_by` bigint unsigned DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `settled_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `financial_batches_code_unique` (`code`),
  KEY `financial_batches_campus_id_foreign` (`campus_id`),
  KEY `financial_batches_financial_account_id_foreign` (`financial_account_id`),
  KEY `financial_batches_submitted_by_foreign` (`submitted_by`),
  KEY `financial_batches_confirmed_by_foreign` (`confirmed_by`),
  CONSTRAINT `financial_batches_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `financial_batches_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `financial_batches_financial_account_id_foreign` FOREIGN KEY (`financial_account_id`) REFERENCES `financial_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `financial_batches_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.financial_batches: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.financial_closures
CREATE TABLE IF NOT EXISTS `financial_closures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campus_id` bigint unsigned NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_locked` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `financial_closures_campus_id_month_year_unique` (`campus_id`,`month`,`year`),
  KEY `financial_closures_user_id_foreign` (`user_id`),
  CONSTRAINT `financial_closures_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_closures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.financial_closures: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.financial_remittances
CREATE TABLE IF NOT EXISTS `financial_remittances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remittable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remittable_id` bigint unsigned NOT NULL,
  `total_cash` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_pix` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending_delivery','partially_conciliated','fully_conciliated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_delivery',
  `closed_at` timestamp NULL DEFAULT NULL,
  `conciliated_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `consolidation_snapshot` json DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `accepted_by_user_id` bigint unsigned DEFAULT NULL,
  `created_by_user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `financial_remittances_batch_code_unique` (`batch_code`),
  KEY `financial_remittances_remittable_type_remittable_id_index` (`remittable_type`,`remittable_id`),
  KEY `financial_remittances_accepted_by_user_id_foreign` (`accepted_by_user_id`),
  KEY `financial_remittances_created_by_user_id_foreign` (`created_by_user_id`),
  CONSTRAINT `financial_remittances_accepted_by_user_id_foreign` FOREIGN KEY (`accepted_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `financial_remittances_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.financial_remittances: ~3 rows (aproximadamente)
INSERT INTO `financial_remittances` (`id`, `batch_code`, `remittable_type`, `remittable_id`, `total_cash`, `total_pix`, `status`, `closed_at`, `conciliated_at`, `notes`, `consolidation_snapshot`, `accepted_at`, `created_at`, `updated_at`, `accepted_by_user_id`, `created_by_user_id`) VALUES
	(1, 'MAL-2026-001', 'App\\Models\\Campus', 1, 0.00, 206.00, 'fully_conciliated', '2026-04-07 20:19:41', '2026-04-07 22:50:38', NULL, NULL, NULL, '2026-04-07 20:19:41', '2026-04-07 22:51:14', NULL, NULL),
	(3, 'MAL-2026-002', 'App\\Models\\Cell', 3, 65.00, 35.00, 'partially_conciliated', '2026-04-07 22:58:38', '2026-04-08 14:53:59', NULL, '{"period": "04/2026", "declared_pix": 45, "declared_cash": 85, "reports_count": 2, "total_members": 2, "total_visitors": 3, "total_conversions": 0}', '2026-04-08 14:53:59', '2026-04-07 22:58:38', '2026-04-08 17:05:02', 1, NULL),
	(4, 'MAL-2026-003', 'App\\Models\\Cell', 3, 65.00, 47.00, 'partially_conciliated', '2026-04-08 16:38:18', '2026-04-08 16:44:36', NULL, '{"period": "04/2026", "declared_pix": 92, "declared_cash": 150, "reports_count": 3, "total_members": 3, "total_visitors": 4, "total_conversions": 0}', '2026-04-08 16:44:36', '2026-04-08 16:38:18', '2026-04-08 17:05:03', 1, 6);

-- Copiando estrutura para tabela gestao_celula_mda.fixed_assets
CREATE TABLE IF NOT EXISTS `fixed_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('building','vehicle','equipment','furniture','technology','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'equipment',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Placa, NF, tombamento',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sede, Núcleo, Célula',
  `acquisition_date` date NOT NULL,
  `acquisition_value` decimal(15,2) NOT NULL,
  `residual_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `depreciation_rate_annual` decimal(8,4) NOT NULL COMMENT 'Taxa anual CFC (%), ex: 20.0000 para veículos',
  `accumulated_depreciation` decimal(15,2) NOT NULL DEFAULT '0.00',
  `chart_account_id` bigint unsigned DEFAULT NULL,
  `last_depreciation_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `fully_depreciated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixed_assets_chart_account_id_foreign` (`chart_account_id`),
  CONSTRAINT `fixed_assets_chart_account_id_foreign` FOREIGN KEY (`chart_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.fixed_assets: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.jobs: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.job_batches: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.journal_entries
CREATE TABLE IF NOT EXISTS `journal_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned DEFAULT NULL,
  `fixed_asset_id` bigint unsigned DEFAULT NULL,
  `volunteer_work_entry_id` bigint unsigned DEFAULT NULL,
  `debit_account_id` bigint unsigned NOT NULL,
  `credit_account_id` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Histórico contábil',
  `amount` decimal(15,2) NOT NULL,
  `tax_classification` enum('immune','taxable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'immune',
  `entry_date` date NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nº documento / TXID PIX',
  `is_voluntary` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true = Valor Justo ITG 2002 Item 19 (sem fluxo de caixa)',
  `is_depreciation` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'true = lançamento de depreciação mensal',
  `cell_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entries_transaction_id_foreign` (`transaction_id`),
  KEY `journal_entries_fixed_asset_id_foreign` (`fixed_asset_id`),
  KEY `journal_entries_debit_account_id_foreign` (`debit_account_id`),
  KEY `journal_entries_credit_account_id_foreign` (`credit_account_id`),
  KEY `journal_entries_cell_id_foreign` (`cell_id`),
  KEY `journal_entries_user_id_foreign` (`user_id`),
  KEY `journal_entries_volunteer_work_entry_id_foreign` (`volunteer_work_entry_id`),
  CONSTRAINT `journal_entries_cell_id_foreign` FOREIGN KEY (`cell_id`) REFERENCES `cells` (`id`) ON DELETE SET NULL,
  CONSTRAINT `journal_entries_credit_account_id_foreign` FOREIGN KEY (`credit_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `journal_entries_debit_account_id_foreign` FOREIGN KEY (`debit_account_id`) REFERENCES `chart_of_accounts` (`id`),
  CONSTRAINT `journal_entries_fixed_asset_id_foreign` FOREIGN KEY (`fixed_asset_id`) REFERENCES `fixed_assets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `journal_entries_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `journal_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `journal_entries_volunteer_work_entry_id_foreign` FOREIGN KEY (`volunteer_work_entry_id`) REFERENCES `volunteer_work_entries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.journal_entries: ~5 rows (aproximadamente)
INSERT INTO `journal_entries` (`id`, `transaction_id`, `fixed_asset_id`, `volunteer_work_entry_id`, `debit_account_id`, `credit_account_id`, `description`, `amount`, `tax_classification`, `entry_date`, `reference`, `is_voluntary`, `is_depreciation`, `cell_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 4, NULL, NULL, 3, 16, 'oferta', 35.00, 'immune', '2026-03-24', NULL, 0, 0, NULL, 1, '2026-03-25 01:02:11', '2026-03-25 01:02:11'),
	(2, 5, NULL, NULL, 4, 14, 'Dízimo mês de abril', 500.00, 'immune', '2026-03-25', NULL, 0, 0, NULL, 1, '2026-03-25 04:51:34', '2026-03-25 04:51:34'),
	(3, 12, NULL, NULL, 25, 9, 'pagamento de água copinhos', 136.00, 'immune', '2026-04-01', NULL, 0, 0, NULL, 1, '2026-04-01 18:27:59', '2026-04-01 18:27:59'),
	(4, 26, NULL, NULL, 4, 14, 'Venda PDV (Vários Itens)', 19.00, 'immune', '2026-04-28', NULL, 0, 0, NULL, 1, '2026-04-28 21:36:31', '2026-04-28 21:36:31'),
	(5, 27, NULL, NULL, 4, 14, 'Venda PDV (Vários Itens)', 3.00, 'immune', '2026-04-28', NULL, 0, 0, NULL, 1, '2026-04-28 21:55:58', '2026-04-28 21:55:58');

-- Copiando estrutura para tabela gestao_celula_mda.members
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `last_contribution_at` date DEFAULT NULL COMMENT 'Data do último dízimo nominal identificado',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_type` enum('Evangelizando','Consolidação','Membro Oficial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Membro Oficial',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Assíduo',
  `discipler_id` bigint unsigned DEFAULT NULL,
  `trilho_lideranca` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `inactivation_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_log` json DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_neighborhood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_complement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `baptism_date` date DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_id` bigint unsigned DEFAULT NULL,
  `campus_id` bigint unsigned DEFAULT NULL,
  `mentor_id` bigint unsigned DEFAULT NULL,
  `first_visit_at` datetime DEFAULT NULL,
  `consolidated` tinyint(1) NOT NULL DEFAULT '0',
  `observations` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `portal_access_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `members_cell_id_foreign` (`cell_id`),
  KEY `members_discipler_id_foreign` (`mentor_id`),
  KEY `members_campus_id_foreign` (`campus_id`),
  KEY `members_user_id_foreign` (`user_id`),
  CONSTRAINT `members_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `members_cell_id_foreign` FOREIGN KEY (`cell_id`) REFERENCES `cells` (`id`) ON DELETE SET NULL,
  CONSTRAINT `members_discipler_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `members` (`id`) ON DELETE SET NULL,
  CONSTRAINT `members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.members: ~12 rows (aproximadamente)
INSERT INTO `members` (`id`, `last_contribution_at`, `name`, `cpf`, `member_type`, `status`, `discipler_id`, `trilho_lideranca`, `is_active`, `inactivation_reason`, `transfer_log`, `email`, `contact`, `whatsapp`, `facebook`, `instagram`, `secondary_contact`, `birth_date`, `address_street`, `address_neighborhood`, `city`, `address_state`, `zip_code`, `address_number`, `address_complement`, `baptism_date`, `latitude`, `longitude`, `role`, `cell_id`, `campus_id`, `mentor_id`, `first_visit_at`, `consolidated`, `observations`, `created_at`, `updated_at`, `user_id`, `portal_access_status`, `rejection_reason`) VALUES
	(1, NULL, 'Gabriel Mota', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'leader', 1, NULL, NULL, NULL, 0, NULL, '2026-03-10 05:17:09', '2026-03-10 05:17:09', NULL, 'approved', NULL),
	(2, NULL, 'Ana Clara', NULL, 'Membro Oficial', 'Preventiva', NULL, '[]', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Membro', 2, NULL, NULL, NULL, 0, NULL, '2026-03-10 05:17:09', '2026-03-21 06:02:46', NULL, 'approved', NULL),
	(3, NULL, 'Bruno Silva', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'member', 1, NULL, NULL, NULL, 0, NULL, '2026-03-10 05:17:09', '2026-03-10 05:17:09', NULL, 'approved', NULL),
	(4, NULL, 'Carla Souza', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'member', 1, NULL, NULL, NULL, 0, NULL, '2026-03-10 05:17:09', '2026-03-10 05:17:09', NULL, 'approved', NULL),
	(5, NULL, 'Diego Lima', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'member', 1, NULL, NULL, NULL, 0, NULL, '2026-03-10 05:17:09', '2026-03-10 05:17:09', NULL, 'approved', NULL),
	(6, NULL, 'Visitante Exemplo', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, '(11) 99999-8888', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'visitor', 1, NULL, NULL, NULL, 0, NULL, '2026-03-09 05:22:14', '2026-03-10 05:18:09', NULL, 'approved', NULL),
	(7, NULL, 'Visitante Crítico', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, '(21) 98888-7777', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'visitor', 1, NULL, NULL, NULL, 0, NULL, '2026-03-08 13:22:14', '2026-03-10 05:18:58', NULL, 'approved', NULL),
	(8, NULL, 'Visitante Expirado', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, '(21) 97777-6666', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'visitor', 1, NULL, NULL, NULL, 0, NULL, '2026-03-07 17:23:27', '2026-03-10 05:23:27', NULL, 'approved', NULL),
	(9, NULL, 'TESTE VISITA', NULL, 'Membro Oficial', 'Assíduo', NULL, NULL, 1, NULL, NULL, NULL, '41992651260', NULL, NULL, NULL, NULL, NULL, 'Rua Alberto Lesniowski 188 bairo costeira Araucaria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'visitor', NULL, NULL, NULL, '2026-03-12 16:28:46', 0, NULL, '2026-03-12 19:28:46', '2026-03-12 19:28:46', NULL, 'approved', NULL),
	(10, NULL, 'Visitante do Relatório 152', NULL, 'Membro Oficial', 'Preventiva Urgente', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'visitor', 1, NULL, NULL, '2026-03-14 00:00:00', 0, NULL, '2026-03-14 05:44:38', '2026-03-20 16:52:23', NULL, 'approved', NULL),
	(11, NULL, 'Carlos Fernando Gomes', '99276046020', 'Membro Oficial', 'Assíduo', NULL, '[]', 1, NULL, NULL, 'cfgfernando@gmail.com', '41992651260', NULL, NULL, NULL, NULL, '1982-05-28', 'Rua Alberto Lesniowski', 'Costeira', 'Araucária', 'PR', '83709100', '188', 'BLOCO 7 AP 3', NULL, -25.60603240, -49.35897000, 'Membro', 3, NULL, NULL, NULL, 0, 'TESTE', '2026-03-15 03:30:17', '2026-04-07 20:21:31', NULL, 'approved', NULL),
	(12, NULL, 'João Maria dos Santos', '17892953949', 'Membro Oficial', 'Afastado', NULL, '[]', 1, NULL, NULL, 'joao.maria@teste.com', '41992651260', '41992651260', NULL, NULL, NULL, '1979-05-15', 'Rua Alberto Lesniowski', 'Costeira', 'Araucária', NULL, '83709100', '188', NULL, NULL, -25.60603240, -49.35897000, 'Membro', 3, NULL, NULL, NULL, 0, NULL, '2026-04-10 17:12:00', '2026-04-10 21:12:47', 7, 'approved', NULL);

-- Copiando estrutura para tabela gestao_celula_mda.member_posts
CREATE TABLE IF NOT EXISTS `member_posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aviso',
  `author_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `expires_at` timestamp NULL DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_posts_author_id_foreign` (`author_id`),
  KEY `member_posts_category_id_foreign` (`category_id`),
  CONSTRAINT `member_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  CONSTRAINT `member_posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `member_post_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.member_posts: ~2 rows (aproximadamente)
INSERT INTO `member_posts` (`id`, `title`, `content`, `category_id`, `category`, `author_id`, `is_active`, `expires_at`, `image_path`, `created_at`, `updated_at`) VALUES
	(1, 'TESTE DE COMUNICADO', 'TESTE TESTE', NULL, 'Aviso', 1, 1, NULL, NULL, '2026-04-10 21:39:27', '2026-04-10 21:39:27'),
	(2, 'TESTE 2', 'TESTE TESTE TESTE', NULL, 'Aviso', 1, 1, NULL, '/storage/communications/posts/c9ZgecEHvhUehFl6cp9d4x6xYe90rjOy5EYZWBTC.png', '2026-04-10 21:40:21', '2026-04-10 21:40:21');

-- Copiando estrutura para tabela gestao_celula_mda.member_post_categories
CREATE TABLE IF NOT EXISTS `member_post_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blue',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'label',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.member_post_categories: ~4 rows (aproximadamente)
INSERT INTO `member_post_categories` (`id`, `name`, `color`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Aviso', 'amber', 'warning', 1, '2026-04-10 21:52:42', '2026-04-10 21:52:42'),
	(2, 'Notícia', 'blue', 'newspaper', 1, '2026-04-10 21:52:42', '2026-04-10 21:52:42'),
	(3, 'Carta Pastoral', 'purple', 'script', 1, '2026-04-10 21:52:42', '2026-04-10 21:52:42'),
	(4, 'Geral', 'emerald', 'label', 1, '2026-04-10 21:52:42', '2026-04-10 21:52:42');

-- Copiando estrutura para tabela gestao_celula_mda.member_post_views
CREATE TABLE IF NOT EXISTS `member_post_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_post_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_post_views_member_post_id_user_id_unique` (`member_post_id`,`user_id`),
  KEY `member_post_views_user_id_foreign` (`user_id`),
  CONSTRAINT `member_post_views_member_post_id_foreign` FOREIGN KEY (`member_post_id`) REFERENCES `member_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `member_post_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.member_post_views: ~2 rows (aproximadamente)
INSERT INTO `member_post_views` (`id`, `member_post_id`, `user_id`, `viewed_at`, `created_at`, `updated_at`) VALUES
	(1, 2, 7, '2026-04-10 21:49:54', '2026-04-10 21:49:54', '2026-04-10 21:49:54'),
	(2, 1, 7, '2026-04-10 21:50:35', '2026-04-10 21:50:35', '2026-04-10 21:50:35'),
	(3, 2, 1, '2026-05-07 19:39:44', '2026-05-07 19:39:44', '2026-05-07 19:39:44'),
	(4, 1, 1, '2026-05-07 20:42:12', '2026-05-07 20:42:12', '2026-05-07 20:42:12');

-- Copiando estrutura para tabela gestao_celula_mda.menus
CREATE TABLE IF NOT EXISTS `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'circle',
  `parent_id` bigint unsigned DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `theme_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allowed_roles` json DEFAULT NULL,
  `required_permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ex: finance.view, secretariat.edit',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.menus: ~52 rows (aproximadamente)
INSERT INTO `menus` (`id`, `name`, `route_name`, `url`, `icon`, `parent_id`, `sort_order`, `theme_color`, `allowed_roles`, `required_permission`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Principal', NULL, NULL, 'grid_view', NULL, 10, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(2, 'Dashboard', 'dashboard', NULL, 'dashboard', 1, 1, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(3, 'Operacional', NULL, NULL, 'lan', NULL, 20, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(4, 'Relatórios', 'reports.index', NULL, 'description', 3, 1, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(5, 'Consolidação', 'reports.consolidation', NULL, 'analytics', 3, 2, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(6, 'Radar 48h', 'radar.index', NULL, 'radar', 3, 3, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(7, 'Membros', 'members.index', NULL, 'group', 3, 4, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(8, 'Células', 'cells.index', NULL, 'hub', 3, 5, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(9, 'Mapeamento MDA', 'members.tree', NULL, 'account_tree', 3, 6, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(10, 'Organograma', 'hierarchy.index', NULL, 'lan', 3, 7, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(11, 'Secretaria', NULL, NULL, 'admin_panel_settings', NULL, 30, 'violet', '["@admin", "@secretariat.view", "@secretariat.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(12, 'Painel Secretaria', 'secretariat.dashboard', NULL, 'admin_panel_settings', 11, 1, 'violet', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(13, 'Crescimento', NULL, NULL, 'military_tech', NULL, 40, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(14, 'Gamificação', 'ranking.achievements', NULL, 'military_tech', 13, 1, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(15, 'Eventos', 'events.index', NULL, 'calendar_month', 13, 2, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(16, 'Financeiro Elite', 'finance.index', NULL, 'payments', 13, 3, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(17, 'MDA Finance V6.0', NULL, NULL, 'account_balance_wallet', NULL, 50, 'emerald', '["@admin", "@leader"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(18, 'Controle de Ofertas', 'finance.income.index', NULL, 'add_card', 17, 1, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(19, 'Despesas', 'finance.expense.index', NULL, 'payments', 17, 2, 'rose', '["@admin", "@finance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(20, 'Zona de Risco', 'finance.risk-zone', NULL, 'warning', 17, 3, 'amber', '["@admin", "@finance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(21, 'Config. Financeiro', 'finance.settings', NULL, 'settings_suggest', 17, 4, 'sky', '["@admin", "@finance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(22, 'Conciliação Unificada', NULL, NULL, 'inventory_2', NULL, 55, 'amber', '["@admin", "@leader"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(23, 'Fechar Malote', 'finance.remittance.create', NULL, 'inventory_2', 22, 1, 'amber', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(24, 'Tesouraria Central', NULL, NULL, 'account_balance', NULL, 60, 'blue', '["@admin", "@finance.view", "@finance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(25, 'Conciliar Malotes', 'finance.conciliation.index', NULL, 'account_balance_wallet', 24, 1, 'amber', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(26, 'Protocolos', 'finance.conciliation.consolidation-receipts', NULL, 'history_edu', 24, 2, 'blue', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(27, 'Tesouraria Catedral', 'finance.treasury.cathedral', NULL, 'account_balance', 24, 3, 'primary', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(28, 'Tesouraria de Núcleo', 'finance.treasury.nucleus', NULL, 'wallet', 24, 4, 'blue', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(29, 'Auditoria Financeira', NULL, NULL, 'query_stats', NULL, 65, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(30, 'Radar Financeiro', 'finance.radar', NULL, 'analytics', 29, 1, 'primary', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(31, 'DRE Institucional', 'finance.dre', NULL, 'description', 29, 2, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(32, 'Balanço Patrimonial', 'finance.bp', NULL, 'account_balance', 29, 3, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(33, 'DMPL', 'finance.dmpl', NULL, 'query_stats', 29, 4, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(34, 'Ativo Imobilizado', 'finance.assets.index', NULL, 'inventory_2', 29, 5, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(35, 'Recibos SPED', 'finance.receipts', NULL, 'receipt', 29, 6, 'emerald', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(36, 'Trabalho Voluntário', 'finance.volunteers.index', NULL, 'volunteer_activism', 29, 7, 'violet', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(37, 'Supervisão Regional', NULL, NULL, 'hub', NULL, 70, NULL, '["@admin", "@supervisor"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(38, 'Supervisão', 'supervision.index', NULL, 'hub', 37, 1, 'violet', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(39, 'Missões', 'missions.index', NULL, 'diversity_3', 37, 2, 'amber', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(40, 'Sistema & Segurança', NULL, NULL, 'settings_suggest', NULL, 80, 'sky', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(41, 'Gestão de Menus', 'config-menus.index', NULL, 'view_list', 40, 1, 'sky', '["@admin", "@governance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(42, 'Acessos Web', 'users.index', NULL, 'admin_panel_settings', 40, 2, 'rose', '["@admin", "@governance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(43, 'Perfis de Acesso', 'roles.index', NULL, 'category', 40, 3, 'amber', '["@admin", "@governance.edit"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 15:00:48'),
	(44, 'Meu Perfil', NULL, NULL, 'person', NULL, 90, NULL, '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(45, 'Editar Perfil', 'profile.edit', NULL, 'person_edit', 44, 1, 'primary', '["@admin"]', NULL, 1, '2026-04-10 15:00:48', '2026-04-10 17:28:04'),
	(46, 'Dashboard Financeiro', 'finance.dashboard', NULL, 'payments', 17, 0, NULL, NULL, 'finance.view', 1, '2026-04-10 15:49:52', '2026-04-10 15:49:52'),
	(47, 'Dashboard Operacional', 'operational.dashboard', NULL, 'monitoring', 3, 0, NULL, NULL, 'operational.view', 1, '2026-04-10 15:49:52', '2026-04-10 15:49:52'),
	(48, 'Portal do Membro', NULL, NULL, 'account_circle', NULL, 1, NULL, '["member", "admin"]', NULL, 1, '2026-04-10 16:46:05', '2026-04-10 16:46:05'),
	(49, 'Início', 'member.dashboard', NULL, 'home', 48, 1, NULL, '["member", "admin"]', NULL, 1, '2026-04-10 16:46:05', '2026-04-10 16:46:05'),
	(50, 'Meus Dados', 'member.profile', NULL, 'manage_accounts', 48, 2, NULL, '["member", "admin"]', NULL, 1, '2026-04-10 16:46:05', '2026-04-10 16:46:05'),
	(51, 'Minhas Contribuições', 'member.finances', NULL, 'receipt_long', 48, 3, NULL, '["member", "admin"]', NULL, 1, '2026-04-10 16:46:05', '2026-04-10 16:46:05'),
	(52, 'Eventos e Cursos', 'member.events', NULL, 'event', 48, 4, NULL, '["member", "admin"]', NULL, 1, '2026-04-10 16:46:06', '2026-04-10 16:46:06'),
	(53, 'Comunicação', 'secretariat.communications.index', NULL, 'campaign', 11, 10, 'blue', NULL, NULL, 1, '2026-04-10 21:38:11', '2026-04-10 21:38:11'),
	(54, 'PDV', 'finance.pos.index', NULL, 'star', 17, 5, NULL, '["@admin"]', NULL, 1, '2026-04-28 21:35:21', '2026-04-28 21:36:16');

-- Copiando estrutura para tabela gestao_celula_mda.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.migrations: ~67 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_03_09_194028_create_networks_table', 1),
	(5, '2026_03_09_194029_create_sectors_table', 1),
	(6, '2026_03_09_194033_create_cells_table', 1),
	(7, '2026_03_09_194034_create_members_table', 1),
	(8, '2026_03_09_194035_create_weekly_reports_table', 1),
	(9, '2026_03_10_004711_add_role_to_users_table', 1),
	(10, '2026_03_10_005429_update_members_table_for_mda_phase2', 1),
	(11, '2026_03_10_013613_update_weekly_reports_table_for_prd', 1),
	(12, '2026_03_10_024000_add_extra_fields_to_weekly_reports', 2),
	(13, '2026_03_10_034819_add_present_member_ids_to_weekly_reports', 3),
	(14, '2026_03_10_035908_add_visitor_names_to_weekly_reports', 4),
	(15, '2026_03_12_155546_create_pastoral_notes_table', 5),
	(16, '2026_03_12_161119_add_address_and_location_to_members_table', 6),
	(17, '2026_03_14_030651_add_observations_to_members_table', 7),
	(20, '2026_03_14_140015_create_visitors_table', 8),
	(21, '2026_03_14_165623_add_radar_fields_to_visitors_table', 9),
	(22, '2026_03_14_172604_refine_visitors_table_for_radar_48h', 10),
	(23, '2026_03_14_173329_split_address_in_members_table', 11),
	(24, '2026_03_14_183705_add_inviter_name_to_visitors_table', 12),
	(25, '2026_03_14_183000_add_counters_to_visitors_table', 13),
	(26, '2026_03_14_184500_create_visitor_contacts_table', 14),
	(27, '2026_03_14_220227_add_was_visited_to_visitor_contacts_table', 15),
	(28, '2026_03_14_231719_add_email_and_birth_date_to_members_table', 16),
	(29, '2026_03_14_234830_upgrade_members_table_to_v3', 17),
	(30, '2026_03_15_002326_fix_role_and_expand_address_in_members_table', 18),
	(31, '2026_03_15_002600_add_address_state_to_members_table', 19),
	(32, '2026_03_15_005033_expand_cells_table', 20),
	(33, '2026_03_20_134506_create_districts_table', 21),
	(34, '2026_03_20_134512_create_areas_table', 21),
	(35, '2026_03_20_134522_update_sectors_table_for_hierarchy', 21),
	(36, '2026_03_20_224649_add_supervisor_id_to_networks_table', 22),
	(37, '2026_03_21_004356_add_parent_cell_id_to_cells_table', 23),
	(38, '2026_03_21_010538_create_roles_table', 24),
	(39, '2026_03_21_010552_add_role_id_to_users_table_and_seed_roles', 24),
	(40, '2026_03_21_014508_add_district_id_to_roles_table', 25),
	(41, '2026_03_21_034034_create_events_table', 26),
	(42, '2026_03_21_034127_create_transactions_table', 27),
	(43, '2026_03_23_193506_make_cell_id_nullable_in_transactions_table', 28),
	(44, '2026_03_23_193949_add_weekly_report_id_to_transactions_table', 29),
	(45, '2026_03_24_161450_create_chart_of_accounts_table', 30),
	(46, '2026_03_24_161450_create_financial_accounts_table', 30),
	(47, '2026_03_24_161500_add_erp_fields_to_transactions_table', 31),
	(48, '2026_03_24_163424_create_fixed_assets_table', 32),
	(49, '2026_03_24_163424_create_journal_entries_table', 32),
	(50, '2026_03_24_171913_add_bank_reconciled_to_transactions_table', 33),
	(51, '2026_03_25_000000_create_volunteers_and_work_entries_table', 34),
	(52, '2026_03_25_000001_add_volunteer_work_id_to_journal_entries', 35),
	(53, '2026_03_24_191612_update_transactions_table_v3', 36),
	(54, '2026_03_24_191613_create_accounting_audits', 36),
	(55, '2026_03_24_192032_create_products_table', 37),
	(56, '2026_03_24_204802_add_pipeline_stage_to_visitors_table', 38),
	(57, '2026_03_24_205824_update_transactions_table_v3', 39),
	(58, '2026_03_25_230000_upgrade_finance_v4_ultimate', 40),
	(59, '2026_03_26_004751_update_transactions_type_column_v4_4', 41),
	(60, '2026_03_26_015527_add_campus_and_tax_to_finance', 42),
	(61, '2026_04_01_145928_add_recurring_generated_to_transactions_table', 43),
	(62, '2026_04_01_163229_create_campuses_table', 44),
	(63, '2026_04_01_203000_update_transactions_campus_foreign_key', 45),
	(64, '2026_04_01_204000_add_code_to_cost_centers', 46),
	(65, '2026_04_02_000001_create_campuses_table', 47),
	(66, '2026_04_02_000002_create_suppliers_table', 48),
	(67, '2026_04_02_000003_create_cost_centers_table', 48),
	(68, '2026_04_02_000004_create_financial_batches_table', 48),
	(69, '2026_04_02_000005_create_financial_closures_table', 48),
	(71, '2026_04_02_000006_add_v6_fields_to_transactions_table', 49),
	(72, '2026_04_02_000007_patch_campuses_add_v6_columns', 49),
	(73, '2026_04_02_000008_patch_suppliers_cost_centers_v6_columns', 50),
	(74, '2026_04_02_000009_patch_financial_closures_add_campus_id', 51),
	(75, '2026_04_02_191909_add_bank_fields_to_suppliers_table', 52),
	(76, '2026_04_02_192859_create_banks_table', 53),
	(77, '2026_04_02_193930_add_bank_id_to_suppliers_table', 54),
	(78, '2026_04_02_200118_add_extra_fields_to_suppliers_table', 55),
	(79, '2026_04_02_211217_add_area_and_sector_to_transactions_table', 56),
	(80, '2026_04_02_213000_add_campus_id_to_areas_table', 57),
	(81, '2026_04_03_230000_add_v6_fields_to_transactions_table', 58),
	(82, '2026_04_03_235000_add_v6_missing_columns', 59),
	(83, '2026_04_04_000000_create_campuses_table', 60),
	(84, '2026_04_04_000001_create_financial_closures_table', 60),
	(85, '2026_04_04_011358_add_bank_id_to_suppliers_and_financial_accounts_tables', 61),
	(86, '2026_04_06_124445_add_contact_phone_to_suppliers_table', 61),
	(87, '2026_04_06_150107_add_soft_deletes_to_transactions_table', 62),
	(88, '2026_04_07_165854_create_financial_remittances_table', 63),
	(89, '2026_04_07_165857_add_remittance_id_to_transactions', 63),
	(90, '2026_04_07_192947_add_consolidation_to_financial_remittances', 64),
	(91, '2026_04_08_131158_add_created_by_user_id_to_financial_remittances_table', 65),
	(92, '2026_04_09_160700_add_campus_and_cell_to_users_table', 66),
	(93, '2026_04_09_160701_add_campus_id_to_members_table', 66),
	(94, '2026_04_09_164050_create_menus_table', 67),
	(95, '2026_04_10_115318_add_permissions_to_roles_table', 68),
	(96, '2026_04_10_122402_add_required_permission_to_menus_table', 69),
	(97, '2026_04_10_124303_create_notifications_table', 70),
	(98, '2026_04_10_124727_create_audit_logs_table', 71),
	(99, '2026_04_10_133759_create_member_portal_tables', 72),
	(100, '2026_04_10_133800_add_portal_fields_to_members_table', 72),
	(101, '2026_04_10_134156_add_member_tier_to_roles_table', 73),
	(102, '2026_04_10_151626_add_extra_contacts_to_members_table', 74),
	(103, '2026_04_10_184518_update_member_posts_tracking', 75),
	(104, '2026_04_10_185203_create_member_post_categories_table', 76);

-- Copiando estrutura para tabela gestao_celula_mda.networks
CREATE TABLE IF NOT EXISTS `networks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_hex` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `networks_supervisor_id_foreign` (`supervisor_id`),
  CONSTRAINT `networks_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.networks: ~1 rows (aproximadamente)
INSERT INTO `networks` (`id`, `name`, `color_hex`, `created_at`, `updated_at`, `supervisor_id`) VALUES
	(1, 'Casa de Israel Church', '#0000FF', '2026-03-10 05:17:09', '2026-03-21 19:34:38', 1);

-- Copiando estrutura para tabela gestao_celula_mda.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.notifications: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.password_reset_tokens: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.pastoral_notes
CREATE TABLE IF NOT EXISTS `pastoral_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pastoral_notes_member_id_foreign` (`member_id`),
  KEY `pastoral_notes_author_id_foreign` (`author_id`),
  CONSTRAINT `pastoral_notes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pastoral_notes_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.pastoral_notes: ~0 rows (aproximadamente)
INSERT INTO `pastoral_notes` (`id`, `member_id`, `author_id`, `content`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Teste teste teste', '2026-03-12 19:08:32', '2026-03-12 19:08:32');

-- Copiando estrutura para tabela gestao_celula_mda.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `cost_price` decimal(15,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.products: ~22 rows (aproximadamente)
INSERT INTO `products` (`id`, `name`, `description`, `unit_price`, `cost_price`, `stock_quantity`, `category`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Coxinha de Frango', NULL, 6.00, 2.50, 49, 'Salgados', 1, '2026-03-24 22:26:03', '2026-04-28 21:36:31'),
	(2, 'Pastel de Carne', NULL, 7.00, 3.00, 39, 'Salgados', 1, '2026-03-24 22:26:03', '2026-04-28 21:36:31'),
	(3, 'Kibe', NULL, 6.00, 2.50, 29, 'Salgados', 1, '2026-03-24 22:26:03', '2026-04-28 21:36:31'),
	(4, 'Empada de Palmito', NULL, 5.50, 2.20, 25, 'Salgados', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(5, 'Coca-Cola 350ml', NULL, 5.00, 2.80, 100, 'Bebidas', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(6, 'Suco de Laranja Natural', NULL, 8.00, 3.50, 20, 'Bebidas', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(7, 'Água Mineral 500ml', NULL, 3.00, 1.00, 149, 'Bebidas', 1, '2026-03-24 22:26:03', '2026-04-28 21:55:58'),
	(8, 'Guaraná Antarctica 350ml', NULL, 5.00, 2.80, 80, 'Bebidas', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(9, 'Bolo de Pote (Chocolate)', NULL, 12.00, 5.00, 15, 'Doces', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(10, 'Brigadeiro Gourmet', NULL, 4.50, 1.50, 60, 'Doces', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(11, 'Pudim de Leite', NULL, 8.00, 3.00, 10, 'Doces', 1, '2026-03-24 22:26:03', '2026-03-24 22:26:03'),
	(12, 'Coxinha de Frango', NULL, 6.00, 2.50, 50, 'Salgados', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(13, 'Pastel de Carne', NULL, 7.00, 3.00, 40, 'Salgados', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(14, 'Kibe', NULL, 6.00, 2.50, 30, 'Salgados', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(15, 'Empada de Palmito', NULL, 5.50, 2.20, 25, 'Salgados', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(16, 'Coca-Cola 350ml', NULL, 5.00, 2.80, 100, 'Bebidas', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(17, 'Suco de Laranja Natural', NULL, 8.00, 3.50, 20, 'Bebidas', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(18, 'Água Mineral 500ml', NULL, 3.00, 1.00, 150, 'Bebidas', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(19, 'Guaraná Antarctica 350ml', NULL, 5.00, 2.80, 80, 'Bebidas', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(20, 'Bolo de Pote (Chocolate)', NULL, 12.00, 5.00, 15, 'Doces', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(21, 'Brigadeiro Gourmet', NULL, 4.50, 1.50, 60, 'Doces', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58'),
	(22, 'Pudim de Leite', NULL, 8.00, 3.00, 10, 'Doces', 1, '2026-03-24 22:26:58', '2026-03-24 22:26:58');

-- Copiando estrutura para tabela gestao_celula_mda.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `core_tier` enum('admin','supervisor','leader','member') COLLATE utf8mb4_unicode_ci DEFAULT 'member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `district_id` bigint unsigned DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  KEY `roles_district_id_foreign` (`district_id`),
  CONSTRAINT `roles_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.roles: ~13 rows (aproximadamente)
INSERT INTO `roles` (`id`, `name`, `core_tier`, `created_at`, `updated_at`, `district_id`, `permissions`) VALUES
	(1, 'Secretaria Geral', 'admin', '2026-03-21 04:06:40', '2026-03-21 04:06:40', NULL, NULL),
	(2, 'Pastor / Supervisor', 'supervisor', '2026-03-21 04:06:40', '2026-03-21 04:06:40', NULL, NULL),
	(3, 'Líder de Célula Jesus é o Caminho - Plenitude', 'leader', '2026-03-21 04:06:40', '2026-03-21 06:15:40', 2, NULL),
	(4, 'Coordenação Distrito Area Vinho Novo', 'supervisor', '2026-03-21 04:14:28', '2026-03-21 04:52:33', 2, NULL),
	(5, 'Supervisor Área Vinho Novo', 'supervisor', '2026-03-21 04:15:11', '2026-03-21 04:52:51', 2, NULL),
	(6, 'Supervisor Setor Plenitude', 'supervisor', '2026-03-21 04:16:05', '2026-03-21 06:16:10', 2, NULL),
	(7, 'Administrador Global', 'admin', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(8, 'Tesoureiro Geral', 'admin', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(9, 'Secretário Geral', 'admin', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(10, 'Tesoureiro de Núcleo', 'leader', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(11, 'Secretário de Núcleo', 'leader', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(12, 'Supervisor de Distrito', 'supervisor', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(13, 'Líder de Célula', 'leader', '2026-04-10 14:41:20', '2026-04-10 14:41:20', NULL, NULL),
	(14, 'Membro', 'member', '2026-04-10 16:42:22', '2026-04-10 17:14:53', NULL, '{"finance": 0, "operational": 0, "secretariat": 0}');

-- Copiando estrutura para tabela gestao_celula_mda.sectors
CREATE TABLE IF NOT EXISTS `sectors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `area_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sectors_supervisor_id_foreign` (`supervisor_id`),
  KEY `sectors_area_id_foreign` (`area_id`),
  CONSTRAINT `sectors_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sectors_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.sectors: ~3 rows (aproximadamente)
INSERT INTO `sectors` (`id`, `area_id`, `name`, `supervisor_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Setor Plenitude', 5, '2026-03-10 05:17:09', '2026-03-21 06:18:50'),
	(2, 1, 'Setor B', NULL, '2026-03-21 03:58:25', '2026-03-21 05:49:49'),
	(3, 1, 'Setor C', NULL, '2026-03-21 03:58:32', '2026-03-21 05:49:49');

-- Copiando estrutura para tabela gestao_celula_mda.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.sessions: ~1 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('TVURPfikp1RlYFVV3CoS9i19ZakTj2oZLZf1vfX2', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid0d1bExYcmRYR05iWThLY3J1RXRpQTJYQ3hEUmtFdGxWbTNwbWpLdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0NToiaHR0cHM6Ly9jZWx1bGEuZXNjcml0YS5vbmxpbmUudGVzdC9zZWNyZXRhcmlhIjtzOjU6InJvdXRlIjtzOjIxOiJzZWNyZXRhcmlhdC5kYXNoYm9hcmQiO319', 1778183912);

-- Copiando estrutura para tabela gestao_celula_mda.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trading_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('pj','pf') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pj' COMMENT 'PJ = Pessoa Jurídica; PF = Pessoa Física',
  `document` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CPF ou CNPJ',
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `neighborhood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint unsigned DEFAULT NULL,
  `bank_agency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_pix_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_info` text COLLATE utf8mb4_unicode_ci COMMENT 'Telefone, e-mail, endereço',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_bank_id_foreign` (`bank_id`),
  CONSTRAINT `suppliers_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.suppliers: ~3 rows (aproximadamente)
INSERT INTO `suppliers` (`id`, `name`, `cnpj`, `trading_name`, `contact_name`, `contact_phone`, `type`, `document`, `zip_code`, `address`, `address_number`, `neighborhood`, `email`, `phone`, `city`, `state`, `bank_name`, `bank_id`, `bank_agency`, `bank_account`, `bank_pix_key`, `contact_info`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 'CARLOS FERNANDO GOMES 99276046020', NULL, 'CARLOS FERNANDO GOMES 99276046020', NULL, NULL, 'pj', '31.413.456/0001-07', '83709-100', 'Rua Alberto Lesniowski', NULL, 'Costeira', NULL, NULL, 'Araucária', 'PR', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-03 00:01:17', '2026-04-03 00:01:17'),
	(2, 'Mk Tessari Sumida Ltda', '58.651.880/0001-46', 'Mkts Servicos Medicos', 'Milena Kimie Tessari Sumida', NULL, 'pj', NULL, '80730200', 'Alameda Doutor Carlos de Carvalho', '1461', 'Batel', NULL, NULL, 'Curitiba', 'PR', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-06 16:29:33', '2026-04-06 16:29:33'),
	(3, '64.408.023 Alexandre Rossi Debortoli', '64.408.023/0001-12', '64.408.023 Alexandre Rossi Debortoli', 'Teste', '4199994545', 'pj', NULL, '83326530', 'Rua Justiliano Mendes da Silva', NULL, 'Jardim Cláudia', NULL, NULL, 'Pinhais', 'PR', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-06 16:46:49', '2026-04-06 16:46:49');

-- Copiando estrutura para tabela gestao_celula_mda.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cell_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `extra_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Valor extra do mesmo envelope/ofertante',
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('pix','cash','voluntary','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurrence` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Se true, gera cópia para o mês seguinte ao ser marcado como pago',
  `recurrence_count` tinyint unsigned DEFAULT NULL COMMENT 'Número de parcelas restantes (null = indefinido)',
  `next_recurring_generated` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','paid','reconciled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `is_accrual` tinyint(1) NOT NULL DEFAULT '0',
  `is_inter_campus` tinyint(1) NOT NULL DEFAULT '0',
  `bank_reconciled` tinyint(1) NOT NULL DEFAULT '0',
  `contributor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_classification` enum('immune','taxable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'immune' COMMENT 'immune: Dízimos/Ofertas | taxable: Vendas/Serviços',
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `weekly_report_id` bigint unsigned DEFAULT NULL,
  `member_id` bigint unsigned DEFAULT NULL,
  `financial_account_id` bigint unsigned DEFAULT NULL,
  `chart_account_id` bigint unsigned DEFAULT NULL,
  `transaction_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'PIX TXID / E2E ID para conciliação',
  `auxiliary_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código auxiliar SPED/ECD',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deletion_reason` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ged_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Caminho do arquivo GED no Storage local',
  `batch_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `cost_center_id` bigint unsigned DEFAULT NULL,
  `campus_id` bigint unsigned DEFAULT NULL,
  `reference_period` char(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Período de competência MM/YYYY',
  `area_id` bigint unsigned DEFAULT NULL,
  `sector_id` bigint unsigned DEFAULT NULL,
  `paid_by_user_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `financial_remittance_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_cell_id_foreign` (`cell_id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  KEY `transactions_weekly_report_id_foreign` (`weekly_report_id`),
  KEY `transactions_member_id_foreign` (`member_id`),
  KEY `transactions_chart_account_id_foreign` (`chart_account_id`),
  KEY `transactions_batch_id_foreign` (`batch_id`),
  KEY `transactions_supplier_id_foreign` (`supplier_id`),
  KEY `transactions_cost_center_id_foreign` (`cost_center_id`),
  KEY `transactions_campus_id_foreign` (`campus_id`),
  KEY `transactions_area_id_foreign` (`area_id`),
  KEY `transactions_sector_id_foreign` (`sector_id`),
  KEY `transactions_paid_by_user_id_foreign` (`paid_by_user_id`),
  KEY `transactions_financial_remittance_id_foreign` (`financial_remittance_id`),
  CONSTRAINT `transactions_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `financial_batches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_cell_id_foreign` FOREIGN KEY (`cell_id`) REFERENCES `cells` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_chart_account_id_foreign` FOREIGN KEY (`chart_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_cost_center_id_foreign` FOREIGN KEY (`cost_center_id`) REFERENCES `cost_centers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_financial_remittance_id_foreign` FOREIGN KEY (`financial_remittance_id`) REFERENCES `financial_remittances` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_paid_by_user_id_foreign` FOREIGN KEY (`paid_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_weekly_report_id_foreign` FOREIGN KEY (`weekly_report_id`) REFERENCES `weekly_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.transactions: ~22 rows (aproximadamente)
INSERT INTO `transactions` (`id`, `cell_id`, `description`, `amount`, `extra_amount`, `type`, `payment_method`, `frequency`, `recurrence`, `recurrence_count`, `next_recurring_generated`, `status`, `is_accrual`, `is_inter_campus`, `bank_reconciled`, `contributor_name`, `category`, `tax_classification`, `date`, `due_date`, `payment_date`, `user_id`, `created_at`, `updated_at`, `weekly_report_id`, `member_id`, `financial_account_id`, `chart_account_id`, `transaction_code`, `auxiliary_code`, `notes`, `deletion_reason`, `attachment_path`, `ged_path`, `batch_id`, `supplier_id`, `cost_center_id`, `campus_id`, `reference_period`, `area_id`, `sector_id`, `paid_by_user_id`, `deleted_at`, `financial_remittance_id`) VALUES
	(1, 3, 'Oferta de Célula - Relatório #15', 40.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-24', NULL, NULL, 6, '2026-03-24 20:08:06', '2026-03-26 03:50:04', 15, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, NULL, 'teste', 10.00, 0.00, 'income', 'pix', 'none', 0, NULL, 0, 'reconciled', 0, 0, 0, 'teste', 'Dízimo/Oferta', 'immune', '2026-03-24', NULL, NULL, 1, '2026-03-24 23:56:00', '2026-03-25 05:14:53', NULL, NULL, NULL, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, NULL, 'teste', 20.00, 0.00, 'income', 'cash', 'none', 0, NULL, 0, 'paid', 0, 0, 0, 'Carlos', 'Dízimo/Oferta', 'immune', '2026-03-24', NULL, NULL, 1, '2026-03-24 23:57:25', '2026-03-24 23:57:25', NULL, NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, NULL, 'oferta', 35.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Despesa Operacional', 'immune', '2026-03-24', NULL, '2026-03-24', 1, '2026-03-25 01:02:11', '2026-03-25 01:02:11', NULL, NULL, 1, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, NULL, 'Dízimo mês de abril', 500.00, 0.00, 'income', 'pix', NULL, 0, NULL, 0, 'reconciled', 0, 0, 0, 'Carlos Fernando Gomes', 'Receita Operacional', 'immune', '2026-03-25', NULL, '2026-03-25', 1, '2026-03-25 04:51:34', '2026-03-25 05:14:47', NULL, 11, 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, 1, 'Oferta de Célula - Relatório #1', 167.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-11', NULL, NULL, 1, '2026-03-26 03:50:04', '2026-03-26 03:50:04', 1, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(7, 1, 'Oferta de Célula - Relatório #5', 20.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-10', NULL, NULL, 1, '2026-03-26 03:50:04', '2026-03-26 03:50:04', 5, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(8, 1, 'Oferta de Célula - Relatório #11', 40.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-12', NULL, NULL, 1, '2026-03-26 03:50:04', '2026-03-26 03:50:04', 11, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(9, 1, 'Oferta de Célula - Relatório #12', 41.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-14', NULL, NULL, 1, '2026-03-26 03:50:04', '2026-03-26 03:50:04', 12, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(10, 3, 'Oferta de Célula - Relatório #13', 34.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-21', NULL, NULL, 6, '2026-03-26 03:50:04', '2026-03-26 03:50:04', 13, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(11, 1, 'Oferta de Célula - Relatório #14', 37.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-03-21', NULL, NULL, 1, '2026-03-26 03:50:04', '2026-03-26 03:50:04', 14, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(12, NULL, 'pagamento de água copinhos', 136.00, 0.00, 'expense', 'pix', 'none', 0, NULL, 0, 'reconciled', 0, 0, 0, 'Pagamento de água', 'Variável', 'immune', '2026-04-01', '2026-04-01', '2026-04-01', 1, '2026-04-01 18:27:59', '2026-04-02 03:03:11', NULL, NULL, 1, 25, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(13, 3, 'Oferta de Célula - Relatório #16', 30.00, 0.00, 'income', 'cash', NULL, 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, 'Oferta', 'immune', '2026-04-01', NULL, NULL, 6, '2026-04-02 02:44:25', '2026-04-02 03:03:17', 16, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(14, NULL, 'asdasd', 10.00, 0.00, 'credit', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'oferta-celula', 'immune', '2026-04-02', NULL, NULL, 1, '2026-04-02 06:32:34', '2026-04-06 21:48:58', NULL, NULL, 1, 15, NULL, NULL, NULL, NULL, NULL, 'finance/ged/yqGZEftqxygnLUWbumyBRu9kOoyGqJeZJwCt9xqZ.png', NULL, NULL, NULL, 1, '2026-04', NULL, NULL, NULL, NULL, NULL),
	(15, NULL, 'Material de limpeza', 100.00, 0.00, 'debit', 'pix', 'monthly', 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, NULL, 'immune', '2026-04-06', '2026-04-10', '2026-04-07', 1, '2026-04-06 17:38:22', '2026-04-07 22:51:10', NULL, NULL, NULL, 26, NULL, NULL, NULL, NULL, NULL, 'finance/ged/pn0DCFxEcvY3a6jqMhUSjb7wrOrkZWwrgzHhJxQt.png', NULL, 3, NULL, 1, '2026-04', NULL, NULL, 1, NULL, 1),
	(16, NULL, 'Material teste', 50.00, 0.00, 'debit', 'pix', 'monthly', 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, NULL, 'immune', '2026-04-06', '2026-04-07', '2026-04-07', 1, '2026-04-06 18:06:07', '2026-04-07 22:51:12', NULL, NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, '2026-04', NULL, NULL, 1, NULL, 1),
	(17, NULL, 'Teste despesas', 56.00, 0.00, 'debit', 'pix', 'monthly', 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, NULL, 'immune', '2026-04-06', '2026-04-10', '2026-04-07', 1, '2026-04-06 20:31:55', '2026-04-07 22:51:14', NULL, NULL, NULL, 33, NULL, NULL, NULL, NULL, NULL, 'finance/ged/zgGFYJriDJjWyTTTQRvpfGPwpChZHL9Vc6YuhWLf.png', NULL, 1, NULL, 1, '2026-04', NULL, NULL, 1, NULL, 1),
	(18, NULL, 'Oferta', 10.00, 0.00, 'credit', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, 'Carlos', 'oferta-culto', 'immune', '2026-04-06', NULL, NULL, 1, '2026-04-06 22:10:31', '2026-04-06 22:10:31', NULL, NULL, 1, 16, NULL, NULL, NULL, NULL, NULL, 'finance/ged/1lL4oc837zPSZ9rBnRWoPvDXgkRsmvJuWv0tHszD.png', NULL, NULL, NULL, 1, '2026-04', NULL, NULL, NULL, NULL, NULL),
	(19, 3, 'Oferta Espécie - Relatório #17', 65.00, 0.00, 'credit', 'cash', NULL, 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, 'Dízimo/Oferta', 'immune', '2026-04-07', NULL, '2026-04-08', 6, '2026-04-07 20:23:18', '2026-04-08 17:05:03', 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 3),
	(20, 3, 'Oferta PIX - Relatório #17', 35.00, 0.00, 'credit', 'pix', NULL, 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, 'Dízimo/Oferta', 'immune', '2026-04-07', NULL, '2026-04-08', 6, '2026-04-07 20:23:18', '2026-04-08 15:59:30', 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 3),
	(21, 3, 'Oferta Espécie - Relatório #18', 65.00, 0.00, 'credit', 'cash', NULL, 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, 'Dízimo/Oferta', 'immune', '2026-04-08', NULL, '2026-04-08', 6, '2026-04-08 16:38:07', '2026-04-08 17:05:03', 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 4),
	(22, 3, 'Oferta PIX - Relatório #18', 47.00, 0.00, 'credit', 'pix', NULL, 0, NULL, 0, 'reconciled', 0, 0, 0, NULL, 'Dízimo/Oferta', 'immune', '2026-04-08', NULL, '2026-04-08', 6, '2026-04-08 16:38:07', '2026-04-08 16:39:05', 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 4),
	(23, 3, 'Oferta Espécie - Relatório #19', 54.00, 0.00, 'credit', 'cash', NULL, 0, NULL, 0, 'pending', 0, 0, 0, NULL, 'Dízimo/Oferta', 'immune', '2026-04-09', NULL, NULL, 6, '2026-04-09 16:41:17', '2026-04-09 16:41:17', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(24, 3, 'Oferta PIX - Relatório #19', 67.00, 0.00, 'credit', 'pix', NULL, 0, NULL, 0, 'pending', 0, 0, 0, NULL, 'Dízimo/Oferta', 'immune', '2026-04-09', NULL, NULL, 6, '2026-04-09 16:41:17', '2026-04-09 16:41:17', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(25, 3, 'RECEITA: João Maria dos Santos | Dízimos', 57.00, 0.00, 'credit', 'cash', NULL, 0, NULL, 0, 'paid', 0, 0, 0, 'João Maria dos Santos', NULL, 'immune', '2026-04-10', NULL, NULL, 1, '2026-04-10 21:28:38', '2026-04-10 21:28:38', NULL, 12, 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04', NULL, 1, NULL, NULL, NULL),
	(26, NULL, 'Venda PDV (Vários Itens)', 19.00, 0.00, 'credit', 'pix', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Receita Cantina/Eventos', 'immune', '2026-04-28', NULL, NULL, 1, '2026-04-28 21:36:31', '2026-04-28 21:36:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(27, NULL, 'Venda PDV (Vários Itens)', 3.00, 0.00, 'credit', 'pix', NULL, 0, NULL, 0, 'paid', 0, 0, 0, NULL, 'Receita Cantina/Eventos', 'immune', '2026-04-28', NULL, NULL, 1, '2026-04-28 21:55:58', '2026-04-28 21:55:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- Copiando estrutura para tabela gestao_celula_mda.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned NOT NULL,
  `campus_id` bigint unsigned DEFAULT NULL,
  `cell_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_campus_id_foreign` (`campus_id`),
  KEY `users_cell_id_foreign` (`cell_id`),
  CONSTRAINT `users_campus_id_foreign` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_cell_id_foreign` FOREIGN KEY (`cell_id`) REFERENCES `cells` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.users: ~5 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `campus_id`, `cell_id`) VALUES
	(1, 'Administrador', 'admin@casadeisrael.com', NULL, '$2y$12$ba8FlzZKxUMy5LXb7Mj7GuPnq5wVkbyxdmtKoCIFeAsxtjJKnHgCe', NULL, '2026-03-10 05:17:09', '2026-03-10 05:17:09', 1, NULL, NULL),
	(3, 'Coordenador Distrito Vinho Novo', 'coord.vinhonovo@church.com', NULL, '$2y$12$xSN4mcXqmi3RfchK6i6xAugem/hGgJ6/flx5Pz0/GDAK63o4IqBKm', NULL, '2026-03-21 05:06:06', '2026-03-21 05:06:06', 4, NULL, NULL),
	(4, 'Supervisor  Area Vinho Novo', 'area.vinhonovo@church.com', NULL, '$2y$12$33i/VycmgOvy.bajq4Mkl.Xy19x3rRdpRUAHOkCqpADHpCN9Gwsii', NULL, '2026-03-21 05:50:30', '2026-03-21 05:50:30', 5, NULL, NULL),
	(5, 'Supervisor de Setor Plenitude', 'setor.plenitude@church.com', NULL, '$2y$12$KCSGwdk8rYnDmzHnA8rzO.EAYrk9ADE9Zd0I7wNB7lSAFJcSHXwSG', NULL, '2026-03-21 06:18:50', '2026-03-21 06:18:50', 6, NULL, NULL),
	(6, 'Lider de Célula Jesus é o Caminho', 'cel.jesuscaminho@church.com', NULL, '$2y$12$kUt.Pw1LS6/wIsrpgvW.R.Os131oRHZnSnDfoNg5jcC0qjWsjF3Y2', NULL, '2026-03-21 06:24:38', '2026-03-21 06:24:38', 3, NULL, NULL),
	(7, 'João Maria dos Santos', '17892953949', NULL, '$2y$12$IM7OD3wmvNiT07cssV6bLurMdI3TsuI2xE4VkX70NraCCZLbIdWvO', NULL, '2026-04-10 17:12:00', '2026-04-10 17:12:00', 14, NULL, NULL);

-- Copiando estrutura para tabela gestao_celula_mda.visitors
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inviter_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_neighborhood` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_id` bigint unsigned DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `visited_at` datetime NOT NULL,
  `visits_count` int NOT NULL DEFAULT '1',
  `contacts_count` int NOT NULL DEFAULT '0',
  `consolidated` tinyint(1) NOT NULL DEFAULT '0',
  `pipeline_stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `contact_status` enum('pending','contacted','invalid_number','no_answer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `contact_notes` text COLLATE utf8mb4_unicode_ci,
  `contacted_at` datetime DEFAULT NULL,
  `contacted_by_user_id` bigint unsigned DEFAULT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitors_cell_id_foreign` (`cell_id`),
  KEY `visitors_contacted_by_foreign` (`contacted_by_user_id`),
  CONSTRAINT `visitors_cell_id_foreign` FOREIGN KEY (`cell_id`) REFERENCES `cells` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitors_contacted_by_foreign` FOREIGN KEY (`contacted_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.visitors: ~18 rows (aproximadamente)
INSERT INTO `visitors` (`id`, `name`, `inviter_name`, `phone`, `address_street`, `address_neighborhood`, `city`, `cell_id`, `latitude`, `longitude`, `visited_at`, `visits_count`, `contacts_count`, `consolidated`, `pipeline_stage`, `contact_status`, `contact_notes`, `contacted_at`, `contacted_by_user_id`, `observations`, `created_at`, `updated_at`) VALUES
	(1, 'Visitante Exemplo', NULL, '(11) 99999-8888', NULL, NULL, NULL, 1, NULL, NULL, '2026-03-09 02:22:14', 2, 3, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-19 22:05:52', 1, NULL, '2026-03-14 17:25:54', '2026-03-26 03:47:00'),
	(2, 'Visitante Crítico', NULL, '(21) 98888-7777', NULL, NULL, NULL, 1, NULL, NULL, '2026-03-08 10:22:14', 1, 0, 0, 'new', 'no_answer', 'teste', '2026-03-14 17:42:09', 1, NULL, '2026-03-14 17:25:54', '2026-03-14 20:42:09'),
	(3, 'Visitante Expirado', NULL, '(21) 97777-6666', NULL, NULL, NULL, 1, 0.00000000, 0.00000000, '2026-03-07 14:23:27', 1, 0, 0, 'new', 'no_answer', 'teste', '2026-03-14 17:42:02', 1, NULL, '2026-03-14 17:25:54', '2026-03-14 20:42:02'),
	(4, 'TESTE VISITA', NULL, '41992651260', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-12 16:28:46', 1, 1, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-22 18:28:08', 1, NULL, '2026-03-14 17:25:54', '2026-03-22 21:28:08'),
	(5, 'Visitante do Relatório 152', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-14 00:00:00', 1, 1, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-24 17:03:39', 1, NULL, '2026-03-14 17:25:54', '2026-03-24 20:03:39'),
	(6, 'Visitante 1303', NULL, 'N/A', NULL, NULL, NULL, 1, NULL, NULL, '2026-03-14 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-24 17:03:39', NULL, NULL, '2026-03-15 01:20:26', '2026-03-24 20:03:39'),
	(7, 'VIsitante teste 2', NULL, 'N/A', NULL, NULL, NULL, 1, NULL, NULL, '2026-03-14 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-24 17:03:39', NULL, NULL, '2026-03-15 01:20:26', '2026-03-24 20:03:39'),
	(8, 'Visita nova', NULL, 'N/A', NULL, NULL, NULL, 1, NULL, NULL, '2026-03-14 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-24 17:03:39', NULL, NULL, '2026-03-15 01:20:26', '2026-03-24 20:03:39'),
	(9, 'Carlos Gomes', NULL, 'N/A', NULL, NULL, NULL, 1, NULL, NULL, '2026-03-14 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-03-24 17:03:39', NULL, NULL, '2026-03-15 01:20:26', '2026-03-24 20:03:39'),
	(10, 'Fernando Gomes', NULL, '41992651260', NULL, NULL, NULL, 3, 0.00000000, 0.00000000, '2026-03-21 20:00:00', 1, 1, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-01 14:50:21', 6, 'Visitante registrado via Relatório Semanal em 21/03/2026', '2026-03-21 06:59:03', '2026-04-01 17:50:21'),
	(11, 'Fernando Gomes', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-21 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-01 14:50:21', NULL, 'Visitante registrado via Relatório Semanal em 21/03/2026', '2026-03-21 18:42:14', '2026-04-01 17:50:21'),
	(12, 'VISITA TESTE 24', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-03-24 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-07 19:01:25', NULL, 'Visitante registrado via Relatório Semanal em 24/03/2026', '2026-03-24 20:08:06', '2026-04-07 22:01:25'),
	(13, 'Joaquim', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-04-01 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-28 18:23:44', NULL, 'Visitante registrado via Relatório Semanal em 01/04/2026', '2026-04-02 02:44:25', '2026-04-28 21:23:44'),
	(14, 'FERNANDO GOMES', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-04-07 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-28 18:23:44', NULL, 'Visitante registrado via Relatório Semanal em 07/04/2026', '2026-04-07 20:23:18', '2026-04-28 21:23:44'),
	(15, 'GOMES', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-04-07 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-28 18:23:44', NULL, 'Visitante registrado via Relatório Semanal em 07/04/2026', '2026-04-07 20:23:18', '2026-04-28 21:23:44'),
	(16, 'GOMES', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-04-08 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-28 18:23:44', NULL, 'Visitante registrado via Relatório Semanal em 08/04/2026', '2026-04-08 16:38:07', '2026-04-28 21:23:44'),
	(17, 'JOAQUIM', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-04-09 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-28 18:23:44', NULL, 'Visitante registrado via Relatório Semanal em 09/04/2026', '2026-04-09 16:41:17', '2026-04-28 21:23:44'),
	(18, 'JOÃO', NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2026-04-09 00:00:00', 1, 0, 0, 'new', 'no_answer', 'Excedeu o prazo de 10 dias.', '2026-04-28 18:23:44', NULL, 'Visitante registrado via Relatório Semanal em 09/04/2026', '2026-04-09 16:41:17', '2026-04-28 21:23:44');

-- Copiando estrutura para tabela gestao_celula_mda.visitor_contacts
CREATE TABLE IF NOT EXISTS `visitor_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `visitor_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'save',
  `was_visited` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visitor_contacts_visitor_id_foreign` (`visitor_id`),
  KEY `visitor_contacts_user_id_foreign` (`user_id`),
  CONSTRAINT `visitor_contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visitor_contacts_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.visitor_contacts: ~19 rows (aproximadamente)
INSERT INTO `visitor_contacts` (`id`, `visitor_id`, `user_id`, `status`, `notes`, `type`, `was_visited`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'no_answer', 'sem resposta', 'save', 0, '2026-03-15 00:58:21', '2026-03-15 00:58:21'),
	(2, 5, 1, 'contacted', 'em contato esperando retorno', 'save', 0, '2026-03-15 01:00:59', '2026-03-15 01:00:59'),
	(3, 1, 1, 'contacted', 'feito visita', 'save', 1, '2026-03-15 01:04:58', '2026-03-15 01:04:58'),
	(4, 1, 1, 'no_answer', 'feito contato sem resposta', 'save', 0, '2026-03-15 05:00:54', '2026-03-15 05:00:54'),
	(5, 1, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-20 01:05:52', '2026-03-20 01:05:52'),
	(6, 4, 1, 'contacted', 'Feito contato informou que irá na proxima celula', 'save', 0, '2026-03-21 03:47:23', '2026-03-21 03:47:23'),
	(7, 10, 6, 'contacted', NULL, 'save', 0, '2026-03-21 06:59:44', '2026-03-21 06:59:44'),
	(8, 4, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-22 21:28:08', '2026-03-22 21:28:08'),
	(9, 5, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-24 20:03:39', '2026-03-24 20:03:39'),
	(10, 6, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-24 20:03:39', '2026-03-24 20:03:39'),
	(11, 7, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-24 20:03:39', '2026-03-24 20:03:39'),
	(12, 8, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-24 20:03:39', '2026-03-24 20:03:39'),
	(13, 9, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-03-24 20:03:39', '2026-03-24 20:03:39'),
	(14, 10, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-01 17:50:21', '2026-04-01 17:50:21'),
	(15, 11, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-01 17:50:21', '2026-04-01 17:50:21'),
	(16, 12, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-07 22:01:25', '2026-04-07 22:01:25'),
	(17, 13, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-28 21:23:44', '2026-04-28 21:23:44'),
	(18, 14, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-28 21:23:44', '2026-04-28 21:23:44'),
	(19, 15, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-28 21:23:44', '2026-04-28 21:23:44'),
	(20, 16, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-28 21:23:44', '2026-04-28 21:23:44'),
	(21, 17, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-28 21:23:44', '2026-04-28 21:23:44'),
	(22, 18, 1, 'no_answer', 'FINALIZAÇÃO AUTOMÁTICA: Excedeu o prazo de 10 dias no Radar sem consolidação.', 'checkin', 0, '2026-04-28 21:23:44', '2026-04-28 21:23:44');

-- Copiando estrutura para tabela gestao_celula_mda.volunteers
CREATE TABLE IF NOT EXISTS `volunteers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_id` bigint unsigned DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pastor, Obreiro, Músico, etc',
  `fair_value_hour` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Valor de mercado estimado por hora',
  `fair_value_monthly` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Valor de mercado estimado por mês',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `volunteers_member_id_foreign` (`member_id`),
  CONSTRAINT `volunteers_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.volunteers: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.volunteer_work_entries
CREATE TABLE IF NOT EXISTS `volunteer_work_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `volunteer_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `hours` decimal(8,2) NOT NULL DEFAULT '0.00',
  `estimated_value` decimal(15,2) NOT NULL COMMENT 'Valor calculado (horas * fair_value_hour) ou fixo',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Se já gerou lançamento no Diário',
  `journal_entry_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `volunteer_work_entries_volunteer_id_foreign` (`volunteer_id`),
  KEY `volunteer_work_entries_journal_entry_id_foreign` (`journal_entry_id`),
  CONSTRAINT `volunteer_work_entries_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `volunteer_work_entries_volunteer_id_foreign` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.volunteer_work_entries: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela gestao_celula_mda.weekly_reports
CREATE TABLE IF NOT EXISTS `weekly_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cell_id` bigint unsigned NOT NULL,
  `meeting_date` date NOT NULL,
  `word_theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `committed_members` int NOT NULL DEFAULT '0',
  `present_members` int NOT NULL DEFAULT '0',
  `present_member_ids` json DEFAULT NULL,
  `visitors` int NOT NULL DEFAULT '0',
  `visitor_names` json DEFAULT NULL,
  `children` int NOT NULL DEFAULT '0',
  `other_cell_visitors` int NOT NULL DEFAULT '0',
  `house_of_peace` int NOT NULL DEFAULT '0',
  `mdas_done` int NOT NULL DEFAULT '0',
  `kg_of_love` decimal(8,2) NOT NULL DEFAULT '0.00',
  `reconciliations` int NOT NULL DEFAULT '0',
  `conversions` int NOT NULL DEFAULT '0',
  `offer_pix` decimal(10,2) NOT NULL DEFAULT '0.00',
  `offer_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `weekly_reports_cell_id_foreign` (`cell_id`),
  CONSTRAINT `weekly_reports_cell_id_foreign` FOREIGN KEY (`cell_id`) REFERENCES `cells` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela gestao_celula_mda.weekly_reports: ~9 rows (aproximadamente)
INSERT INTO `weekly_reports` (`id`, `cell_id`, `meeting_date`, `word_theme`, `meeting_location`, `committed_members`, `present_members`, `present_member_ids`, `visitors`, `visitor_names`, `children`, `other_cell_visitors`, `house_of_peace`, `mdas_done`, `kg_of_love`, `reconciliations`, `conversions`, `offer_pix`, `offer_cash`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, '2026-03-11', 'PALAVRA TESTE', 'CELULA JESUS É O CAMINHO', 10, 6, '[2, 3, 4, 5, 6, 7]', 2, '[{"name": "visitante 1"}, {"name": "visitante 2"}]', 2, 2, 0, 7, 5.00, 1, 0, 132.00, 35.00, 'OBSERVAÇÃO TESTE', '2026-03-10 05:17:09', '2026-03-10 07:08:54'),
	(5, 1, '2026-03-10', 'TESTE palavra de Deus', 'JESUS É O CAMINHO', 8, 8, '[1, 2, 3, 4, 5, 6, 7, 8]', 0, '[]', 1, 0, 0, 0, 10.00, 0, 0, 10.00, 10.00, 'TESTE OBSERVAÇÃO', '2026-03-10 05:42:33', '2026-03-10 07:15:46'),
	(11, 1, '2026-03-12', 'Palavra 12/03', 'Célula Jesus é o Caminho', 8, 6, '[1, 3, 4, 5, 7, 8]', 4, '[{"name": "Teste 1"}, {"name": "Teste 2"}, {"name": "Teste 3"}, {"name": "Visitante 1203"}]', 6, 1, 0, 0, 16.00, 0, 0, 15.00, 25.00, 'Teste observação testes', '2026-03-12 18:34:27', '2026-03-12 23:33:29'),
	(12, 1, '2026-03-14', 'Jesus é o Caminho', 'Célula Jesus é o Caminho', 8, 6, '[1, 4, 7, 8, 6, 3]', 4, '[{"name": "Visitante 1303"}, {"name": "VIsitante teste 2"}, {"name": "Visita nova"}, {"name": "Carlos Gomes"}]', 6, 4, 0, 0, 20.00, 0, 0, 10.00, 31.00, 'teste de observação', '2026-03-14 03:15:12', '2026-03-15 01:17:42'),
	(13, 3, '2026-03-21', 'Teste palavra', 'Jesus é o Caminho', 1, 1, '[11]', 1, '[{"name": "Fernando Gomes"}]', 1, 0, 0, 0, 10.00, 0, 0, 10.00, 24.00, 'Teste de anotações', '2026-03-21 06:48:48', '2026-03-21 06:48:48'),
	(14, 1, '2026-03-21', 'Palavra do dia', 'Jesus é o Caminho', 0, 0, '[]', 1, '[{"name": "Fernando Gomes"}]', 2, 0, 0, 0, 11.00, 0, 0, 10.00, 27.00, 'Teste observação', '2026-03-21 18:42:14', '2026-03-21 18:42:14'),
	(15, 3, '2026-03-24', 'PALVRA DIA 24/03', 'JESUS É O CAMINHO', 1, 1, '[11]', 1, '[{"name": "VISITA TESTE 24"}]', 1, 0, 0, 0, 15.00, 0, 0, 30.00, 10.00, 'TESTE OBSERVAÇÃO', '2026-03-24 20:08:06', '2026-03-24 20:08:41'),
	(16, 3, '2026-04-01', 'A Salvação', 'Celula Jesus é o caminho', 1, 1, '[11]', 1, '[{"name": "Joaquim"}]', 1, 0, 1, 1, 7.00, 0, 0, 10.00, 20.00, 'teste observação', '2026-04-02 02:44:25', '2026-04-02 02:44:25'),
	(17, 3, '2026-04-07', 'PALAVRA DO DIA', 'JESUS É O CAMINHO', 1, 1, '[11]', 2, '[{"name": "FERNANDO GOMES"}, {"name": "GOMES"}]', 1, 0, 0, 0, 10.00, 0, 0, 35.00, 65.00, 'TESTE OBSERVAÇÕES', '2026-04-07 20:23:18', '2026-04-07 20:39:40'),
	(18, 3, '2026-04-08', 'PALAVRA DO DIA', 'JESUS É O CAMINHO', 1, 1, '[11]', 1, '[{"name": "GOMES"}]', 1, 1, 2, 1, 20.00, 0, 0, 47.00, 65.00, 'TESTE', '2026-04-08 16:38:07', '2026-04-08 16:38:07'),
	(19, 3, '2026-04-09', 'Palavra do Dia', 'Rua Dep. João Leopoldo Jacomel , 57 Iguaçu', 1, 1, '[11]', 2, '[{"name": "JOAQUIM"}, {"name": "JOÃO"}]', 1, 0, 0, 0, 8.00, 0, 0, 67.00, 54.00, 'TESTE DE OBSERVAÇÃO', '2026-04-09 16:41:17', '2026-04-09 16:42:12');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
