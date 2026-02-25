-- ============================================================
-- ADMISSION OFFICE SYSTEM - MySQL Database Schema
-- Version: 1.0.0
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `admission_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `admission_db`;

-- ============================================================
-- TABLE: users
-- Stores all system users (admin, staff, applicants)
-- ============================================================
CREATE TABLE `users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(150) NOT NULL,
  `email`         VARCHAR(191) NOT NULL,
  `password`      VARCHAR(255) NOT NULL,
  `role`          ENUM('admin','staff','applicant') NOT NULL DEFAULT 'applicant',
  `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: refresh_tokens
-- JWT refresh token store (allows token revocation)
-- ============================================================
CREATE TABLE `refresh_tokens` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       INT UNSIGNED NOT NULL,
  `token_hash`    VARCHAR(255) NOT NULL,
  `expires_at`    TIMESTAMP NOT NULL,
  `revoked`       TINYINT(1) NOT NULL DEFAULT 0,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refresh_tokens_token_hash_unique` (`token_hash`),
  KEY `refresh_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `refresh_tokens_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: programs
-- Academic programs / courses offered
-- ============================================================
CREATE TABLE `programs` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code`          VARCHAR(20) NOT NULL,
  `name`          VARCHAR(200) NOT NULL,
  `department`    VARCHAR(150) NOT NULL,
  `description`   TEXT NULL,
  `duration_years` TINYINT UNSIGNED NOT NULL DEFAULT 4,
  `slots`         SMALLINT UNSIGNED NOT NULL DEFAULT 0  COMMENT 'Available slots per intake',
  `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `programs_code_unique` (`code`),
  KEY `programs_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: applicants
-- Extended profile for applicant-role users (1-to-1 with users)
-- ============================================================
CREATE TABLE `applicants` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         INT UNSIGNED NOT NULL,
  `applicant_no`    VARCHAR(30) NOT NULL COMMENT 'System-generated reference number',
  -- Personal info
  `first_name`      VARCHAR(80) NOT NULL,
  `middle_name`     VARCHAR(80) NULL,
  `last_name`       VARCHAR(80) NOT NULL,
  `suffix`          VARCHAR(10) NULL,
  `gender`          ENUM('male','female','other','prefer_not_to_say') NOT NULL,
  `birthdate`       DATE NOT NULL,
  `birthplace`      VARCHAR(150) NULL,
  `civil_status`    ENUM('single','married','widowed','separated') NOT NULL DEFAULT 'single',
  `nationality`     VARCHAR(80) NULL,
  `religion`        VARCHAR(80) NULL,
  -- Contact
  `phone`           VARCHAR(20) NULL,
  `address_line1`   VARCHAR(200) NOT NULL,
  `address_line2`   VARCHAR(200) NULL,
  `city`            VARCHAR(100) NOT NULL,
  `province`        VARCHAR(100) NULL,
  `postal_code`     VARCHAR(10) NULL,
  `country`         VARCHAR(80) NOT NULL DEFAULT 'Philippines',
  -- Educational background
  `last_school`     VARCHAR(200) NULL,
  `school_address`  VARCHAR(200) NULL,
  `year_graduated`  YEAR NULL,
  `honors`          VARCHAR(150) NULL,
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applicants_user_id_unique` (`user_id`),
  UNIQUE KEY `applicants_applicant_no_unique` (`applicant_no`),
  KEY `applicants_last_name_index` (`last_name`),
  CONSTRAINT `applicants_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: applications
-- One applicant can apply to multiple programs / intakes
-- ============================================================
CREATE TABLE `applications` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_no`  VARCHAR(30) NOT NULL COMMENT 'Human-readable reference',
  `applicant_id`    INT UNSIGNED NOT NULL,
  `program_id`      INT UNSIGNED NOT NULL,
  `academic_year`   VARCHAR(10) NOT NULL COMMENT 'e.g. 2024-2025',
  `semester`        ENUM('1st','2nd','summer') NOT NULL DEFAULT '1st',
  `application_type` ENUM('new','transferee','returnee','cross_enrollee') NOT NULL DEFAULT 'new',
  `remarks`         TEXT NULL,
  `submitted_at`    TIMESTAMP NULL DEFAULT NULL,
  `reviewed_by`     INT UNSIGNED NULL COMMENT 'staff user_id',
  `reviewed_at`     TIMESTAMP NULL DEFAULT NULL,
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applications_application_no_unique` (`application_no`),
  KEY `applications_applicant_id_foreign` (`applicant_id`),
  KEY `applications_program_id_foreign` (`program_id`),
  KEY `applications_reviewed_by_foreign` (`reviewed_by`),
  KEY `applications_academic_year_index` (`academic_year`),
  CONSTRAINT `applications_applicant_id_foreign`
    FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applications_program_id_foreign`
    FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`),
  CONSTRAINT `applications_reviewed_by_foreign`
    FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: application_statuses
-- Full audit trail of status changes per application
-- ============================================================
CREATE TABLE `application_statuses` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_id`  INT UNSIGNED NOT NULL,
  `status`          ENUM('draft','submitted','under_review','pending_docs',
                         'for_interview','accepted','rejected','waitlisted',
                         'enrolled','cancelled') NOT NULL,
  `notes`           TEXT NULL,
  `changed_by`      INT UNSIGNED NULL COMMENT 'user_id who made the change',
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `app_statuses_application_id_foreign` (`application_id`),
  KEY `app_statuses_changed_by_foreign` (`changed_by`),
  KEY `app_statuses_status_index` (`status`),
  CONSTRAINT `app_statuses_application_id_foreign`
    FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_statuses_changed_by_foreign`
    FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VIEW: latest status per application (convenience)
CREATE OR REPLACE VIEW `v_application_current_status` AS
  SELECT application_id, status, notes, changed_by, created_at
  FROM application_statuses s1
  WHERE created_at = (
    SELECT MAX(created_at)
    FROM application_statuses s2
    WHERE s2.application_id = s1.application_id
  );

-- ============================================================
-- TABLE: documents
-- Uploaded files linked to an application
-- ============================================================
CREATE TABLE `documents` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_id`  INT UNSIGNED NOT NULL,
  `document_type`   ENUM('tor','birth_certificate','good_moral','form137',
                         'honorable_dismissal','medical_certificate','id_photo',
                         'other') NOT NULL,
  `original_name`   VARCHAR(255) NOT NULL,
  `stored_name`     VARCHAR(255) NOT NULL COMMENT 'UUID-based filename on disk',
  `mime_type`       VARCHAR(100) NOT NULL,
  `file_size`       INT UNSIGNED NOT NULL COMMENT 'bytes',
  `disk_path`       VARCHAR(500) NOT NULL COMMENT 'relative path from storage root',
  `uploaded_by`     INT UNSIGNED NULL,
  `verified`        TINYINT(1) NOT NULL DEFAULT 0,
  `verified_by`     INT UNSIGNED NULL,
  `verified_at`     TIMESTAMP NULL DEFAULT NULL,
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `documents_application_id_foreign` (`application_id`),
  KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `documents_application_id_foreign`
    FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_uploaded_by_foreign`
    FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_verified_by_foreign`
    FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: notifications (email log)
-- ============================================================
CREATE TABLE `notifications` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED NULL,
  `type`        VARCHAR(80) NOT NULL,
  `subject`     VARCHAR(255) NOT NULL,
  `body`        TEXT NOT NULL,
  `sent_at`     TIMESTAMP NULL,
  `failed_at`   TIMESTAMP NULL,
  `error`       TEXT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_index` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED: Default admin user
-- Password: Admin@123  (bcrypt hash)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `email_verified_at`)
VALUES (
  'System Administrator',
  'admin@admission.edu',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'admin',
  1,
  NOW()
);

-- Seed: Sample programs
INSERT INTO `programs` (`code`, `name`, `department`, `duration_years`, `slots`) VALUES
  ('BSIT', 'Bachelor of Science in Information Technology', 'College of Computing', 4, 80),
  ('BSCS', 'Bachelor of Science in Computer Science', 'College of Computing', 4, 60),
  ('BSN',  'Bachelor of Science in Nursing', 'College of Allied Health', 4, 50),
  ('BSED', 'Bachelor of Secondary Education', 'College of Education', 4, 70),
  ('BSBA', 'Bachelor of Science in Business Administration', 'College of Business', 4, 100);

SET FOREIGN_KEY_CHECKS = 1;
