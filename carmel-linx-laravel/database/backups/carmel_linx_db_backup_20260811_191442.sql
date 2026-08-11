/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: carmel_linx_db
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-5ubuntu0.1 from Ubuntu

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `academic_calendars`
--

DROP TABLE IF EXISTS `academic_calendars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_calendars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `activities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`activities`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_calendars`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `academic_calendars` WRITE;
/*!40000 ALTER TABLE `academic_calendars` DISABLE KEYS */;
/*!40000 ALTER TABLE `academic_calendars` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `academic_marks`
--

DROP TABLE IF EXISTS `academic_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_marks` (
  `mark_id` char(36) NOT NULL DEFAULT uuid(),
  `reg_no` varchar(50) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `batch_subject_id` int(10) unsigned DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `co_tag` varchar(10) NOT NULL,
  `max_marks` int(11) NOT NULL,
  `marks_obtained` decimal(5,2) NOT NULL,
  `entered_by` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`mark_id`),
  KEY `academic_marks_subject_code_foreign` (`subject_code`),
  KEY `academic_marks_entered_by_foreign` (`entered_by`),
  KEY `academic_marks_reg_no_subject_code_index` (`reg_no`,`subject_code`),
  KEY `academic_marks_batch_subject_id_index` (`batch_subject_id`),
  CONSTRAINT `academic_marks_entered_by_foreign` FOREIGN KEY (`entered_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `academic_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `academic_marks_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `syllabus_registry` (`subject_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `academic_marks` WRITE;
/*!40000 ALTER TABLE `academic_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `academic_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `activity_point_claims`
--

DROP TABLE IF EXISTS `activity_point_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_point_claims` (
  `id` char(36) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL DEFAULT 1,
  `activity_segment` varchar(255) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `points_claimed` int(11) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `rejection_note` varchar(255) DEFAULT NULL,
  `document_reference` text DEFAULT NULL,
  `verified_by` varchar(50) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_point_claims_reg_no_foreign` (`reg_no`),
  CONSTRAINT `activity_point_claims_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_point_claims`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `activity_point_claims` WRITE;
/*!40000 ALTER TABLE `activity_point_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_point_claims` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `performed_by` varchar(50) DEFAULT NULL,
  `performed_by_name` varchar(255) DEFAULT NULL,
  `target_id` varchar(50) NOT NULL,
  `target_name` varchar(255) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_performed_by_index` (`performed_by`),
  KEY `audit_logs_target_id_index` (`target_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES
(1,'System','Self Registration','9947666371','Dhanush .A','Registered','Staff registration created for role: Lecturer with status: Pending','127.0.0.1','2026-08-02 19:06:16','2026-08-02 19:06:16'),
(2,'9000000000','Super Admin User','9947666371','Dhanush .A','Approved','Account status changed to: Approved','127.0.0.1','2026-08-02 19:06:56','2026-08-02 19:06:56'),
(3,'9999999999','Chairman','9999999999','Chairman','Profile Updated','Updated executive profile settings (Name, Login ID, Password/Photo).','127.0.0.1','2026-08-09 09:40:10','2026-08-09 09:40:10'),
(4,'9000000001','Dr. Principal','9999999999','Chairman','Role Changed','Role designation changed from Chairman to Admin','127.0.0.1','2026-08-09 19:58:55','2026-08-09 19:58:55'),
(5,'9000000001','Dr. Principal','9000000004','Academic Coordinator','Role Changed','Role designation changed from Academic_Coordinator to Admin','127.0.0.1','2026-08-09 19:59:02','2026-08-09 19:59:02'),
(6,'9000000001','Fr. Principal','9000000001','Fr. Principal','Profile Updated','Updated executive profile settings (Name, Login ID, Password/Photo).','127.0.0.1','2026-08-09 21:20:41','2026-08-09 21:20:41'),
(7,'System','Self Registration','9349186555','Amalu Mariya Joseph','Registered','Staff registration created for role: Demonstrator with status: Pending','127.0.0.1','2026-08-11 15:56:04','2026-08-11 15:56:04'),
(8,'System','Self Registration','9497336713','Rajesh. P. V','Registered','Staff registration created for role: HOD with status: Pending','127.0.0.1','2026-08-11 15:56:27','2026-08-11 15:56:27'),
(9,'System','Self Registration','9446449292','RAKHI V R','Registered','Staff registration created for role: Lecturer with status: Pending','127.0.0.1','2026-08-11 15:56:48','2026-08-11 15:56:48'),
(10,'System','Self Registration','8943850834','Meenu M CE','Registered','Staff registration created for role: Demonstrator with status: Pending','127.0.0.1','2026-08-11 15:57:35','2026-08-11 15:57:35'),
(11,'System','Self Registration','9400087440','Fr siji thomas p t','Registered','Staff registration created for role: HOD with status: Pending','127.0.0.1','2026-08-11 15:58:06','2026-08-11 15:58:06'),
(12,'System','Self Registration','8281336943','Sita S','Registered','Staff registration created for role: HOD with status: Pending','127.0.0.1','2026-08-11 15:58:47','2026-08-11 15:58:47'),
(13,'9000000001','Fr. Principal','9349186555','Amalu Mariya Joseph','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 16:11:09','2026-08-11 16:11:09'),
(14,'9000000001','Fr. Principal','9400087440','Fr siji thomas p t','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 16:11:19','2026-08-11 16:11:19'),
(15,'9000000001','Fr. Principal','8943850834','Meenu M CE','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 16:11:37','2026-08-11 16:11:37'),
(16,'9000000001','Fr. Principal','9497336713','Rajesh. P. V','Role Changed','Role designation changed from HOD to Lecturer','127.0.0.1','2026-08-11 16:11:41','2026-08-11 16:11:41'),
(17,'9000000001','Fr. Principal','9497336713','Rajesh. P. V','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 16:11:47','2026-08-11 16:11:47'),
(18,'9000000001','Fr. Principal','9446449292','RAKHI V R','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 16:11:57','2026-08-11 16:11:57'),
(19,'9000000001','Fr. Principal','8281336943','Sita S','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 16:12:02','2026-08-11 16:12:02'),
(20,'9000000001','Fr. Principal','CE_2026_2029','Revision 2026 Batch CE_2026_2029','Create R26 Batch','HOD created R26 batch CE_2026_2029 with tutor/mentor assignment, auto-backfilling 0 students.','127.0.0.1','2026-08-11 16:25:16','2026-08-11 16:25:16'),
(21,'9000000001','Fr. Principal','8281336943','Sita S','Role Changed','Role designation changed from HOD to Gen_Dept_Coordinator_Aided','127.0.0.1','2026-08-11 16:43:16','2026-08-11 16:43:16'),
(22,'9946847236','Fr. Antony Varghese CMI','9946847236','Fr. Antony Varghese CMI','Profile Updated','Updated executive profile settings (Name, Login ID, Password/Photo).','127.0.0.1','2026-08-11 16:45:59','2026-08-11 16:45:59'),
(23,'System','Self Registration','9895527950','Bijo M D','Registered','Staff registration created for role: Lecturer with status: Pending','127.0.0.1','2026-08-11 17:23:28','2026-08-11 17:23:28'),
(24,'9400087440','Fr siji thomas p t','9895527950','Bijo M D','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 17:42:36','2026-08-11 17:42:36'),
(25,'9946847236','Fr. Antony Varghese CMI','CE_2026_2029','Batch CE_2026_2029','Tutor Assigned','Tutor set to Bijo M D (9895527950). Previous: 9497336713','127.0.0.1','2026-08-11 21:14:45','2026-08-11 21:14:45'),
(26,'9400087440','Fr siji thomas p t','CE_2026_2029','Batch CE_2026_2029','Tutor Assigned','Tutor set to Bijo M D (9895527950). Previous: 9895527950','127.0.0.1','2026-08-11 21:30:57','2026-08-11 21:30:57'),
(27,'9946847236','Fr. Antony Varghese CMI','9400524401','ANTONY VARGHESE','Registered','Staff registration created for role: HOD with status: Pending','127.0.0.1','2026-08-11 21:50:09','2026-08-11 21:50:09'),
(28,'9946847236','Fr. Antony Varghese CMI','9400524401','ANTONY VARGHESE','Approved','Account status changed to: Approved','127.0.0.1','2026-08-11 21:50:18','2026-08-11 21:50:18'),
(29,'9400087440','Fr siji thomas p t','CE_2024_2027','Batch CE_2024_2027','Batch Created','HOD created batch CE_2024_2027 for admission year 2024. Backfilled 0 student(s).','127.0.0.1','2026-08-11 22:00:51','2026-08-11 22:00:51'),
(30,'9400524401','ANTONY VARGHESE','CE_2025_2028','Batch CE_2025_2028','Batch Created','HOD created batch CE_2025_2028 for admission year 2025. Backfilled 0 student(s).','127.0.0.1','2026-08-11 22:06:05','2026-08-11 22:06:05');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `batch_subjects`
--

DROP TABLE IF EXISTS `batch_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `batch_subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `classroom_id` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_type` varchar(100) NOT NULL,
  `syllabus_revision_code` varchar(20) DEFAULT 'REV2021',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_subjects_classroom_id_foreign` (`classroom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_subjects`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `batch_subjects` WRITE;
/*!40000 ALTER TABLE `batch_subjects` DISABLE KEYS */;
INSERT INTO `batch_subjects` VALUES
(3,'CE_2026_2029',1,'CE-2003A','Chemistry for Engineering Practices','Practicum Courses under Basic Science & Humanities category','REV2026','2026-08-11 16:31:45','2026-08-11 21:21:00'),
(4,'CE_2026_2029',1,'CE-1001','English for Technical Communication','Practicum Courses under Basic Science & Humanities category','REV2026','2026-08-11 21:19:38','2026-08-11 21:20:07'),
(5,'CE_2026_2029',1,'CE-1002','Fundamentals of Engineering Mathematics','Theory Courses','REV2026','2026-08-11 21:21:54','2026-08-11 21:21:54'),
(6,'CE_2026_2029',1,'CE-2002A','Applied Physics for Mechanical, Structural and Industrial Applications','Practicum Courses under Basic Science & Humanities category','REV2026','2026-08-11 21:22:50','2026-08-11 21:22:50'),
(7,'CE_2026_2029',1,'CE-1003','Engineering Graphics','Drawing Courses','REV2026','2026-08-11 21:23:24','2026-08-11 21:23:24'),
(8,'CE_2026_2029',1,'CE-1011','Fundamentals of Civil Engineering','Practicum Courses','REV2026','2026-08-11 21:23:59','2026-08-11 21:23:59'),
(9,'CE_2026_2029',1,'CE-1008','Foundational IT Skills','Laboratory/Workshop Courses','REV2026','2026-08-11 21:24:52','2026-08-11 21:24:52'),
(10,'CE_2026_2029',1,'CE-2009','General Engineering Workshop','Laboratory/Workshop Courses','REV2026','2026-08-11 21:25:20','2026-08-11 21:25:20'),
(11,'CE_2026_2029',1,'CE-1009','Health and Physical Education','Laboratory/Workshop Courses','REV2026','2026-08-11 21:25:55','2026-08-11 21:25:55'),
(12,'CE_2025_2028',5,'5017','Transportation Engineering Lab','Practical / Lab','REV2021','2026-08-11 22:08:10','2026-08-11 22:08:10'),
(13,'CE_2025_2028',5,'5018','Structural Engineering Drawing Lab','Practical / Lab','REV2021','2026-08-11 22:08:38','2026-08-11 22:08:38'),
(14,'CE_2025_2028',5,'5011','Construction Management and Safety Engineering','Theory','REV2021','2026-08-11 22:09:07','2026-08-11 22:09:07'),
(15,'CE_2025_2028',5,'5012','Design of Steel and RCC Structures','Theory','REV2021','2026-08-11 22:10:14','2026-08-11 22:10:14'),
(16,'CE_2025_2028',5,'5013','Transportation Engineering','Theory','REV2021','2026-08-11 22:10:56','2026-08-11 22:10:56'),
(17,'CE_2025_2028',5,'5014C','Precast and Prestressed Concrete','Theory','REV2021','2026-08-11 22:12:22','2026-08-11 22:12:22'),
(18,'CE_2025_2028',5,'5019','Advanced CAD Lab','Practical / Lab','REV2021','2026-08-11 22:13:05','2026-08-11 22:13:05'),
(19,'CE_2025_2028',5,'5008','Seminar','Seminar','REV2021','2026-08-11 22:13:51','2026-08-11 22:13:51'),
(20,'CE_2025_2028',5,'6009','Major Project','Project','REV2021','2026-08-11 22:14:25','2026-08-11 22:14:25'),
(21,'CE_2025_2028',3,'3011','Advanced Surveying','Theory','REV2021','2026-08-11 22:48:50','2026-08-11 22:48:50');
/*!40000 ALTER TABLE `batch_subjects` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('carmel-linx-cache-system_setting_ai_generation_enabled','b:0;',1786476044);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cf_course_file_documents`
--

DROP TABLE IF EXISTS `cf_course_file_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cf_course_file_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_file_id` bigint(20) unsigned NOT NULL,
  `document_number` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `data_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_payload`)),
  PRIMARY KEY (`id`),
  KEY `cf_course_file_documents_course_file_id_foreign` (`course_file_id`),
  CONSTRAINT `cf_course_file_documents_course_file_id_foreign` FOREIGN KEY (`course_file_id`) REFERENCES `cf_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_course_file_documents`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cf_course_file_documents` WRITE;
/*!40000 ALTER TABLE `cf_course_file_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_course_file_documents` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cf_course_files`
--

DROP TABLE IF EXISTS `cf_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cf_course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Draft',
  `attainment_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attainment_settings`)),
  `generated_pdf_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cf_course_files_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `cf_course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cf_course_files` WRITE;
/*!40000 ALTER TABLE `cf_course_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cf_section_a_planning`
--

DROP TABLE IF EXISTS `cf_section_a_planning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cf_section_a_planning` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cf_id` bigint(20) unsigned NOT NULL,
  `gaps_identified` text DEFAULT NULL,
  `bridge_topics` text DEFAULT NULL,
  `faculty_timetable_ref` varchar(255) DEFAULT NULL,
  `class_timetable_ref` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cf_section_a_planning_cf_id_foreign` (`cf_id`),
  CONSTRAINT `cf_section_a_planning_cf_id_foreign` FOREIGN KEY (`cf_id`) REFERENCES `cf_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_a_planning`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cf_section_a_planning` WRITE;
/*!40000 ALTER TABLE `cf_section_a_planning` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_section_a_planning` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cf_section_b_materials`
--

DROP TABLE IF EXISTS `cf_section_b_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cf_section_b_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cf_id` bigint(20) unsigned NOT NULL,
  `nptel_swayam_links` text DEFAULT NULL,
  `other_resources` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cf_section_b_materials_cf_id_foreign` (`cf_id`),
  CONSTRAINT `cf_section_b_materials_cf_id_foreign` FOREIGN KEY (`cf_id`) REFERENCES `cf_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_b_materials`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cf_section_b_materials` WRITE;
/*!40000 ALTER TABLE `cf_section_b_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_section_b_materials` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cf_section_c_assessments`
--

DROP TABLE IF EXISTS `cf_section_c_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cf_section_c_assessments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cf_id` bigint(20) unsigned NOT NULL,
  `evaluation_scheme` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cf_section_c_assessments_cf_id_foreign` (`cf_id`),
  CONSTRAINT `cf_section_c_assessments_cf_id_foreign` FOREIGN KEY (`cf_id`) REFERENCES `cf_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_c_assessments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cf_section_c_assessments` WRITE;
/*!40000 ALTER TABLE `cf_section_c_assessments` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_section_c_assessments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cf_section_d_attainments`
--

DROP TABLE IF EXISTS `cf_section_d_attainments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cf_section_d_attainments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cf_id` bigint(20) unsigned NOT NULL,
  `action_taken_report` text DEFAULT NULL,
  `committee_minutes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cf_section_d_attainments_cf_id_foreign` (`cf_id`),
  CONSTRAINT `cf_section_d_attainments_cf_id_foreign` FOREIGN KEY (`cf_id`) REFERENCES `cf_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_d_attainments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cf_section_d_attainments` WRITE;
/*!40000 ALTER TABLE `cf_section_d_attainments` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_section_d_attainments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `class_logs_attendance`
--

DROP TABLE IF EXISTS `class_logs_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_logs_attendance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `period` int(11) NOT NULL,
  `lesson_plan_id` bigint(20) unsigned DEFAULT NULL,
  `topics_covered` text DEFAULT NULL,
  `present_students` text DEFAULT NULL,
  `absent_students` text DEFAULT NULL,
  `sub_batch` varchar(10) NOT NULL DEFAULT 'Whole',
  `recorded_by` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_logs_attendance_lesson_plan_id_foreign` (`lesson_plan_id`),
  KEY `class_logs_attendance_batch_subject_id_date_index` (`batch_subject_id`,`date`),
  CONSTRAINT `class_logs_attendance_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_logs_attendance_lesson_plan_id_foreign` FOREIGN KEY (`lesson_plan_id`) REFERENCES `lesson_plans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_logs_attendance`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `class_logs_attendance` WRITE;
/*!40000 ALTER TABLE `class_logs_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `class_logs_attendance` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `class_management`
--

DROP TABLE IF EXISTS `class_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_management` (
  `classroom_id` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `batch_year` int(11) NOT NULL,
  `current_semester` int(11) NOT NULL DEFAULT 1,
  `tutor_mobile_no` varchar(15) DEFAULT NULL,
  `mentor_mobile_no` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`classroom_id`),
  KEY `class_management_tutor_mobile_no_foreign` (`tutor_mobile_no`),
  KEY `class_management_mentor_mobile_no_foreign` (`mentor_mobile_no`),
  CONSTRAINT `class_management_mentor_mobile_no_foreign` FOREIGN KEY (`mentor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `class_management_tutor_mobile_no_foreign` FOREIGN KEY (`tutor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_management`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `class_management` WRITE;
/*!40000 ALTER TABLE `class_management` DISABLE KEYS */;
INSERT INTO `class_management` VALUES
('CE_2024_2027','CE',2024,5,'9497336713','8943850834','2026-08-11 22:00:51','2026-08-11 22:00:51'),
('CE_2025_2028','CE',2025,3,NULL,NULL,'2026-08-11 22:06:05','2026-08-11 22:48:50'),
('S2_EEE','EEE',2021,2,NULL,NULL,'2026-08-04 09:57:51','2026-08-04 09:57:51');
/*!40000 ALTER TABLE `class_management` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `course_exit_surveys`
--

DROP TABLE IF EXISTS `course_exit_surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_exit_surveys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `custom_questions` text DEFAULT NULL,
  `faculty_name` varchar(150) DEFAULT NULL,
  `initiated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_exit_surveys_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `course_exit_surveys_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_exit_surveys`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `course_exit_surveys` WRITE;
/*!40000 ALTER TABLE `course_exit_surveys` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_exit_surveys` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `course_files`
--

DROP TABLE IF EXISTS `course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `syllabus_pdf_path` varchar(255) DEFAULT NULL,
  `parsed_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_modules`)),
  `parsed_cos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_cos`)),
  `parsed_copo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_copo`)),
  `parsed_textbooks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_textbooks`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assignment_deadlines` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assignment_deadlines`)),
  `assignment_questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assignment_questions`)),
  `summative_manual_tests` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`summative_manual_tests`)),
  `self_learning_configs` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_files_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `course_files` WRITE;
/*!40000 ALTER TABLE `course_files` DISABLE KEYS */;
INSERT INTO `course_files` VALUES
(2,5,'r26_syllabi/wSXpMFNNHcMQJNpfin1P6zyVh3ePa9pYWP0zBjfd.pdf','\"[{\\\"module_id\\\":\\\"I\\\",\\\"title\\\":\\\"Matrices & Determinants\\\",\\\"hours\\\":15,\\\"content\\\":\\\"Basic concept of a matrix, special types of matrices, Transpose of a matrix Algebra of matrices: Multiplication by, a scalar, Equality, Addition, Subtraction, Matrix Multiplication Determinants, Solution of system of, linear equations involving two and three unknowns by Cramer\'s rule, Minors, cofactors, adjoint and inverse of a 2 X 2 matrix, Problems\\\"},{\\\"module_id\\\":\\\"II\\\",\\\"title\\\":\\\"Trigonometry\\\",\\\"hours\\\":15,\\\"content\\\":\\\"Angles, Trigonometric ratios of acute angle, Trigonometric ratios of standard angles, Pythagorean relations, Trigonometric ratios of any angle, ASTC rule, Reduction formula, Trigonometric ratios of compound angles (A+B, A-B), Problems\\\"},{\\\"module_id\\\":\\\"III\\\",\\\"title\\\":\\\"Coordinate Geometry\\\",\\\"hours\\\":16,\\\"content\\\":\\\"Straight lines: Slope, slope-point form, Angle between lines, Parallel and perpendicular lines, Circles: Equation of a circle, center and radius of the circle, Problems\\\"},{\\\"module_id\\\":\\\"IV\\\",\\\"title\\\":\\\"Differential Calculus\\\",\\\"hours\\\":15,\\\"content\\\":\\\"Concept of limit, Limits by substitution and factorisation, Limits of the form (xn - an) \\\\\\/ (x-a) and sin x \\\\\\/ x, Basic concept of di\\\\ufb00erentiation, standard results (derivatives of a constant k, xn, ex, log x, sin x, cos x, tan x, csc x, sec x, cot x) Rules of di\\\\ufb00erentiation (linearity,, product, and quotient rules), second derivatives, Problems\\\"}]\"','\"[{\\\"id\\\":\\\"CO1\\\",\\\"description\\\":\\\"Apply the concepts of matrices and determinants to solve linear systems of equations involving two and three unknowns.\\\",\\\"cognitive_level\\\":\\\"Apply\\\",\\\"duration\\\":15},{\\\"id\\\":\\\"CO2\\\",\\\"description\\\":\\\"Use trigonometric identities and functions to solve trigonometric problems.\\\",\\\"cognitive_level\\\":\\\"Apply\\\",\\\"duration\\\":15},{\\\"id\\\":\\\"CO3\\\",\\\"description\\\":\\\"Interpret geometric problems related to straight lines and circles using Coordinate geometric concepts.\\\",\\\"cognitive_level\\\":\\\"Apply\\\",\\\"duration\\\":15},{\\\"id\\\":\\\"CO4\\\",\\\"description\\\":\\\"Evaluate limits and derivatives.\\\",\\\"cognitive_level\\\":\\\"Apply\\\",\\\"duration\\\":15}]\"','\"{\\\"credit\\\":4,\\\"l_t_p_r\\\":\\\"3:1:0:0\\\",\\\"cie_marks\\\":40,\\\"ese_marks\\\":60,\\\"total_hours\\\":60,\\\"mappings\\\":{\\\"CO1\\\":{\\\"PO1\\\":\\\"3\\\",\\\"PO2\\\":\\\"-\\\",\\\"PO3\\\":\\\"-\\\",\\\"PO4\\\":\\\"-\\\",\\\"PO5\\\":\\\"-\\\",\\\"PO6\\\":\\\"-\\\",\\\"PO7\\\":\\\"-\\\",\\\"PO8\\\":\\\"-\\\",\\\"PO9\\\":\\\"-\\\",\\\"PO10\\\":\\\"-\\\",\\\"PO11\\\":\\\"-\\\"},\\\"CO2\\\":{\\\"PO1\\\":\\\"3\\\",\\\"PO2\\\":\\\"-\\\",\\\"PO3\\\":\\\"-\\\",\\\"PO4\\\":\\\"-\\\",\\\"PO5\\\":\\\"-\\\",\\\"PO6\\\":\\\"-\\\",\\\"PO7\\\":\\\"-\\\",\\\"PO8\\\":\\\"-\\\",\\\"PO9\\\":\\\"-\\\",\\\"PO10\\\":\\\"-\\\",\\\"PO11\\\":\\\"-\\\"},\\\"CO3\\\":{\\\"PO1\\\":\\\"3\\\",\\\"PO2\\\":\\\"-\\\",\\\"PO3\\\":\\\"-\\\",\\\"PO4\\\":\\\"-\\\",\\\"PO5\\\":\\\"-\\\",\\\"PO6\\\":\\\"-\\\",\\\"PO7\\\":\\\"-\\\",\\\"PO8\\\":\\\"-\\\",\\\"PO9\\\":\\\"-\\\",\\\"PO10\\\":\\\"-\\\",\\\"PO11\\\":\\\"-\\\"},\\\"CO4\\\":{\\\"PO1\\\":\\\"3\\\",\\\"PO2\\\":\\\"-\\\",\\\"PO3\\\":\\\"-\\\",\\\"PO4\\\":\\\"-\\\",\\\"PO5\\\":\\\"-\\\",\\\"PO6\\\":\\\"-\\\",\\\"PO7\\\":\\\"-\\\",\\\"PO8\\\":\\\"-\\\",\\\"PO9\\\":\\\"-\\\",\\\"PO10\\\":\\\"-\\\",\\\"PO11\\\":\\\"-\\\"}}}\"','\"[\\\"Textbook Reference 1\\\",\\\"Textbook Reference 2\\\"]\"','2026-08-11 21:58:19','2026-08-11 22:24:52',NULL,NULL,NULL,NULL),
(3,16,'/storage/syllabi/BSXp5xnljBWEqZnoaA8W2h9A6yGLlTQzmCGl29ks.pdf','[{\"module_id\":\"I\",\"content\":\"Introduction to highway engineering and traffic engineering Role of transportation in the development of nation - IRC - IRC classification of road - Major SH and NH in Kerala. - Road alignment - Factors to be considered in road alignment Traffic Engineering: - Traffic volume study - Traffic intensity studies - OD studies - Road intersection studies - Intersections - types of at grade intersection - Channelizing islands - pedestrian loading islands - Rotary islands - Grade separated intersections - Clover leaf junction - Trumpet junction - Road markings - Traffic signs - Types and purpose of signs - Mandatory, cautionary, informatory and temporary signs.\"},{\"module_id\":\"II\",\"content\":\"Highway Geometrics: Standards cross-sections of national highway in embankment and cutting- various components- Camber - Gradient - Design speed -Sight distance (SSD), road arboriculture- Road drainage Curves: Necessity, types: Horizontal, vertical curves - Extra widening of roads - transition curve- Super elevation: Definition and formula Types of road materials and their Tests - Test on aggregates - Types of Bitumen and its properties, Pavement - Definition, Types, Structural Components of pavement and their functions- Construction of Flexible pavement \\/ Bituminous Road Emulsion, Cutback, Tar, Granular Sub Base (GSB), Wet Mix Macadam (WMM), Bituminous Macadam & Bituminous Concrete (BM & BC), Natural Rubber Modified Bitumen (NRMB). Use of shredded plastic - Rigid pavement, Cement concrete road - methods of construction, Alternate and Continuous Bay Method, Construction joints, filler and sealers, White topping, merits and demerits of concrete roads.\"},{\"module_id\":\"III\",\"content\":\"Railway Engineering Classification of Indian Railways, zones of Indian Railways - Permanent way: Ideal requirement, Components - Rail Gauge - Rail, Rail Joints - requirements, types - Creep of rail: causes and prevention - Sleepers, Ballast - functions and types - Rail fixtures and fastenings - Railway Track Geometrics: Gradient, curves, grade compensation, super elevation, cant deficiency, negative cant, coning of wheel, tilting of rail, Track Cross sections - standard cross section of single and double line in cutting and embankment Points and crossings - Turn out - types, components, functions - Track junctions: crossovers, scissor cross over, diamond crossing, Track signals -principles of interlocking - Station yard: Classifications Overview of Mono Rail and Metro rail CO 4 Outline the different modes of transportation and importance of bridge and tunnel M4.01 Classify bridges and identify their components 4 Understanding M4.02 Identify common tunnel shapes 3 Understanding M4.03 Id\"},{\"module_id\":\"IV\",\"content\":\"\"},{\"module_id\":\"V\",\"content\":\"\"},{\"module_id\":\"VI\",\"content\":\"\"}]','[{\"id\":\"CO1\",\"description\":\"Summarize the components of various transportation systems and collection of traffic\",\"duration\":13,\"cognitive_level\":\"Understanding\"},{\"id\":\"CO2\",\"description\":\"Identity the various material tests and construction methods of road.\",\"duration\":15,\"cognitive_level\":\"Understanding\"},{\"id\":\"CO3\",\"description\":\"Illustrate the geometrical design features of roadways and railways\",\"duration\":15,\"cognitive_level\":\"Applying\"},{\"id\":\"CO4\",\"description\":\"Outline the different modes of transportation and        importance of bridge and tunnel\",\"duration\":15,\"cognitive_level\":\"Understanding\"}]','{\"CO1\":{\"PO1\":3,\"PO2\":2,\"PO3\":1,\"PO4\":null,\"PO5\":null,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null},\"CO2\":{\"PO1\":2,\"PO2\":3,\"PO3\":2,\"PO4\":1,\"PO5\":null,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null},\"CO3\":{\"PO1\":1,\"PO2\":2,\"PO3\":3,\"PO4\":2,\"PO5\":1,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null},\"CO4\":{\"PO1\":null,\"PO2\":1,\"PO3\":2,\"PO4\":3,\"PO5\":2,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null}}','[\"Standard Reference Textbook for the Subject.\"]','2026-08-11 22:16:01','2026-08-11 22:16:01',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `department_notices`
--

DROP TABLE IF EXISTS `department_notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `department_notices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `target_audience` varchar(255) NOT NULL DEFAULT 'All Staff',
  `priority` varchar(255) NOT NULL DEFAULT 'Normal',
  `created_by` varchar(255) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `department_notices_department_index` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_notices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `department_notices` WRITE;
/*!40000 ALTER TABLE `department_notices` DISABLE KEYS */;
/*!40000 ALTER TABLE `department_notices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `department_semester_pass_stats`
--

DROP TABLE IF EXISTS `department_semester_pass_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `department_semester_pass_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch` varchar(50) NOT NULL,
  `academic_year` varchar(20) NOT NULL DEFAULT '2025-2026',
  `semester` varchar(10) NOT NULL DEFAULT 'S5',
  `total_students` int(11) NOT NULL DEFAULT 0,
  `appeared_count` int(11) NOT NULL DEFAULT 0,
  `passed_count` int(11) NOT NULL DEFAULT 0,
  `pass_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `uploaded_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dept_sem_pass_unique` (`branch`,`academic_year`,`semester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_semester_pass_stats`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `department_semester_pass_stats` WRITE;
/*!40000 ALTER TABLE `department_semester_pass_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `department_semester_pass_stats` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `disciplinary_actions`
--

DROP TABLE IF EXISTS `disciplinary_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `disciplinary_actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `reported_by` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplinary_actions_reg_no_foreign` (`reg_no`),
  KEY `disciplinary_actions_reported_by_foreign` (`reported_by`),
  CONSTRAINT `disciplinary_actions_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `disciplinary_actions_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinary_actions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `disciplinary_actions` WRITE;
/*!40000 ALTER TABLE `disciplinary_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `disciplinary_actions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `executive_flash_notices`
--

DROP TABLE IF EXISTS `executive_flash_notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `executive_flash_notices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(100) DEFAULT NULL,
  `sender_role` varchar(50) NOT NULL DEFAULT 'Principal',
  `sender_name` varchar(150) NOT NULL DEFAULT 'Executive Desk',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'Normal',
  `target_audience` varchar(50) NOT NULL DEFAULT 'ALL_CAMPUS',
  `target_department` varchar(50) NOT NULL DEFAULT 'ALL',
  `target_semester` varchar(10) NOT NULL DEFAULT 'ALL',
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_type` varchar(20) NOT NULL DEFAULT 'none',
  `dispatch_type` varchar(20) NOT NULL DEFAULT 'immediate',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `executive_flash_notices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `executive_flash_notices` WRITE;
/*!40000 ALTER TABLE `executive_flash_notices` DISABLE KEYS */;
/*!40000 ALTER TABLE `executive_flash_notices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `extracurricular_activities`
--

DROP TABLE IF EXISTS `extracurricular_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `extracurricular_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `activity_name` varchar(150) NOT NULL,
  `achievement` varchar(100) DEFAULT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 0,
  `verified_by` varchar(15) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `extracurricular_activities_reg_no_foreign` (`reg_no`),
  KEY `extracurricular_activities_verified_by_foreign` (`verified_by`),
  CONSTRAINT `extracurricular_activities_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `extracurricular_activities_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extracurricular_activities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `extracurricular_activities` WRITE;
/*!40000 ALTER TABLE `extracurricular_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `extracurricular_activities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `leave_records`
--

DROP TABLE IF EXISTS `leave_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `leave_date` varchar(100) DEFAULT NULL,
  `no_of_days` varchar(20) DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `parent_informed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `approved_by` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_records_reg_no_foreign` (`reg_no`),
  KEY `leave_records_approved_by_foreign` (`approved_by`),
  CONSTRAINT `leave_records_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `leave_records_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_records`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `leave_records` WRITE;
/*!40000 ALTER TABLE `leave_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_records` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lesson_plan_templates`
--

DROP TABLE IF EXISTS `lesson_plan_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_plan_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject_code` varchar(50) NOT NULL,
  `day_no` int(11) NOT NULL,
  `co_id` varchar(20) DEFAULT NULL,
  `topic_content` text NOT NULL,
  `pedagogy` varchar(100) NOT NULL DEFAULT 'Lecture',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_plan_templates_subject_code_index` (`subject_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_plan_templates`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lesson_plan_templates` WRITE;
/*!40000 ALTER TABLE `lesson_plan_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_plan_templates` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lesson_plans`
--

DROP TABLE IF EXISTS `lesson_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `day_no` int(11) DEFAULT NULL,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `co_id` varchar(10) DEFAULT NULL,
  `topic_content` text NOT NULL,
  `allocated_hours` int(11) NOT NULL DEFAULT 1,
  `proposed_date` date DEFAULT NULL,
  `actual_date` date DEFAULT NULL,
  `actual_hours` int(11) DEFAULT NULL,
  `pedagogy` varchar(255) DEFAULT NULL,
  `mode` varchar(20) DEFAULT 'L',
  `sub_batch` varchar(20) NOT NULL DEFAULT 'Whole',
  `taxonomy` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_plans_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `lesson_plans_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=431 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_plans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lesson_plans` WRITE;
/*!40000 ALTER TABLE `lesson_plans` DISABLE KEYS */;
INSERT INTO `lesson_plans` VALUES
(91,1,3,'CO1','Module I: Atoms and molecules Deﬁne atom',1,'2026-08-10',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(92,2,3,'CO1','Module I: List the f Atoms and molecules protons an mass.',1,'2026-08-11',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(93,3,3,'CO1','Module I: Atoms and molecules Deﬁne the with examp',1,'2026-08-12',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(94,4,3,'CO1','EXP-01: Preparation of standard solutions and demonstration Prepare 0. of endothermic and exothermic reactions. Preparation of standard (Hour 1/1)',1,'2026-08-13',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(95,5,3,'CO1','EXP-02: solutions and demonstration Prepare 0. of endothermic and exothermic reactions. (Hour 1/1)',1,'2026-08-14',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(96,6,3,'CO1','EXP-03: Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions. (Hour 1/1)',1,'2026-08-16',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(97,7,3,'CO1','Module I: Calculate Atoms and molecules particles number of',1,'2026-08-17',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(98,8,3,'CO1','Module I: Solutions Deﬁne the solution w',1,'2026-08-18',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(99,9,3,'CO1','Module I: Solutions Deﬁne the give the e',1,'2026-08-19',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(100,10,3,'CO1','EXP-04: Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions. Demonstra Preparation of standard (Endother solutions and demonstration soda, the (Hour 1/1)',1,'2026-08-20',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(101,11,3,'CO1','EXP-05: of endothermic and ammonium exothermic reactions. Exothermi (e.g., HC in water, (Hour 1/3)',1,'2026-08-22',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(102,12,3,'CO1','EXP-05: of endothermic and ammonium exothermic reactions. Exothermi (e.g., HC in water, (Hour 2/3)',1,'2026-08-23',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(103,13,3,'CO1','Module I: Solutions Solve prob',1,'2026-08-24',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(104,14,3,'CO1','Module I: Thermodynamics- Deﬁne the thermodyna',1,'2026-08-25',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(105,15,3,'CO1','Module I: Thermodynamics- Explain di isolated s',1,'2026-08-26',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(106,16,3,'CO1','EXP-05: of endothermic and ammonium exothermic reactions. Exothermi (e.g., HC in water, (Hour 3/3)',1,'2026-08-28',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(107,17,3,'CO2','EXP-06: Quantitative and Qualitative Standardi analysis sodium ca (Hour 1/3)',1,'2026-08-29',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(108,18,3,'CO2','EXP-06: Quantitative and Qualitative Standardi analysis sodium ca (Hour 2/3)',1,'2026-08-30',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(109,19,3,'CO1','Module I: Thermodynamics- Explain th isobaric',1,'2026-08-31',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(110,20,3,'CO1','Module I: Matrices & Determinants',1,'2026-09-01',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(111,21,3,'CO1','Module I: Thermodynamics- Diﬀerentia processes',1,'2026-09-03',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(112,22,3,'CO2','EXP-06: Quantitative and Qualitative Standardi analysis sodium ca (Hour 3/3)',1,'2026-09-04',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(113,23,3,'CO1','Theory Series Exam 1 (CO1 - Written 1 Hour Test)',1,'2026-09-05',NULL,NULL,'Theory Series Exam (ST)','ST','All Students',NULL,'1 Hour Written Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(114,24,3,'CO2','EXP-07: Quantitative and Qualitative Estimatio analysis hydrochlo (Hour 1/3)',1,'2026-09-06',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(115,25,3,'CO1','Module I: Thermodynamics- Diﬀerentia spontaneou',1,'2026-09-07',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(116,26,3,'CO1','Module I: Thermodynamics- Deﬁne the entropy.',1,'2026-09-09',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(117,27,3,'CO1','Module I: Preparation of standard solutions and demonstration Prepare 0. of endothermic and exothermic reactions. Preparation of standard',1,'2026-09-10',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(118,28,3,'CO2','EXP-07: Quantitative and Qualitative Estimatio analysis hydrochlo (Hour 2/3)',1,'2026-09-11',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(119,29,3,'CO2','EXP-07: Quantitative and Qualitative Estimatio analysis hydrochlo (Hour 3/3)',1,'2026-09-12',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(120,30,3,'CO2','EXP-08: Quantitative and Qualitative Estimatio analysis titrating (Hour 1/3)',1,'2026-09-13',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(121,31,3,'CO1','Module I: solutions and demonstration Prepare 0. of endothermic and exothermic reactions.',1,'2026-09-15',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(122,32,3,'CO1','Module I: Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions.',1,'2026-09-16',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(123,33,3,'CO1','Module I: Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions. Demonstra Preparation of standard (Endother solutions and demonstration soda',1,'2026-09-17',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(124,34,3,'CO2','EXP-08: Quantitative and Qualitative Estimatio analysis titrating (Hour 2/3)',1,'2026-09-18',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(125,35,3,'CO2','EXP-08: Quantitative and Qualitative Estimatio analysis titrating (Hour 3/3)',1,'2026-09-19',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(126,36,3,'CO2','EXP-09: Quantitative and Qualitative Standardi analysis (Hour 1/3)',1,'2026-09-21',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(127,37,3,'CO1','Module I: the',1,'2026-09-22',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(128,38,3,'CO1','Module I: of endothermic and ammonium exothermic reactions. Exothermi (e.g.',1,'2026-09-23',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(129,39,3,'CO2','Module II: Volumetric analysis Deﬁne the standard Explain t',1,'2026-09-24',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(130,40,3,'CO2','EXP-09: Quantitative and Qualitative Standardi analysis (Hour 2/3)',1,'2026-09-25',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(131,41,3,'CO2','EXP-09: Quantitative and Qualitative Standardi analysis (Hour 3/3)',1,'2026-09-27',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(132,42,3,'CO1','Practical Series Exam 1 (CO1+CO2 - 3-Hour Combined Lab Test)',1,'2026-09-28',NULL,NULL,'Practical Series Exam (SP)','SP','Batch A & B',NULL,'3 Hour Practical Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(133,43,3,'CO1','Practical Series Exam 1 (CO1+CO2 - 3-Hour Combined Lab Test)',1,'2026-09-29',NULL,NULL,'Practical Series Exam (SP)','SP','Batch A & B',NULL,'3 Hour Practical Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(134,44,3,'CO1','Practical Series Exam 1 (CO1+CO2 - 3-Hour Combined Lab Test)',1,'2026-09-30',NULL,NULL,'Practical Series Exam (SP)','SP','Batch A & B',NULL,'3 Hour Practical Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(135,45,3,'CO2','Theory Series Exam 2 (CO2 - Written 1 Hour Test)',1,'2026-10-01',NULL,NULL,'Theory Series Exam (ST)','ST','All Students',NULL,'1 Hour Written Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(136,46,3,'CO2','EXP-10: Quantitative and Qualitative Volumetri analysis water sam (Hour 1/3)',1,'2026-10-03',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(137,47,3,'CO2','EXP-10: Quantitative and Qualitative Volumetri analysis water sam (Hour 2/3)',1,'2026-10-04',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(138,48,3,'CO2','EXP-10: Quantitative and Qualitative Volumetri analysis water sam (Hour 3/3)',1,'2026-10-05',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(139,49,3,'CO2','Module II: Volumetric analysis titration base',1,'2026-10-06',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(140,50,3,'CO2','Module II: wea',1,'2026-10-07',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(141,51,3,'CO2','Module II: Volumetric analysis State the normality',1,'2026-10-09',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(142,52,3,'CO2','EXP-11: Quantitative and Qualitative Determine analysis technique paper. (Hour 1/3)',1,'2026-10-10',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(143,53,3,'CO2','EXP-11: Quantitative and Qualitative Determine analysis technique paper. (Hour 2/3)',1,'2026-10-11',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(144,54,3,'CO2','EXP-11: Quantitative and Qualitative Determine analysis technique paper. (Hour 3/3)',1,'2026-10-12',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(145,55,3,'CO2','Module II: Volumetric analysis Solve pro V1N1=V2N2',1,'2026-10-13',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(146,56,3,'CO2','Module II: Diﬀerenti Hardness of water List the Diﬀerenti hardness',1,'2026-10-15',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(147,57,3,'CO2','Module II: Discuss t Water treatment methods methods: technique',1,'2026-10-16',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(148,58,3,'CO3','EXP-12: Experiments based on electrochemical cell Construct conductivity and electrolysis (Hour 1/1)',1,'2026-10-17',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(149,59,3,'CO3','EXP-13: Experiments based on electrochemical cell Construct conductivity and electrolysis (Hour 1/1)',1,'2026-10-18',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(150,60,3,'CO3','EXP-14: Experiments based on Electropl electrochemical cell mixture o conductivity and electrolysis Experiments based on Demonstra (Hour 1/3)',1,'2026-10-19',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(151,61,3,'CO2','Module II: Water treatment methods Draw the method.',1,'2026-10-21',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(152,62,3,'CO2','Module II: Water treatment methods Deﬁne pot',1,'2026-10-22',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(153,63,3,'CO2','Module II: Discuss t Water treatment methods water tre (Screenin steriliza',1,'2026-10-23',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(154,64,3,'CO3','EXP-14: Experiments based on Electropl electrochemical cell mixture o conductivity and electrolysis Experiments based on Demonstra (Hour 2/3)',1,'2026-10-24',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(155,65,3,'CO3','EXP-14: Experiments based on Electropl electrochemical cell mixture o conductivity and electrolysis Experiments based on Demonstra (Hour 3/3)',1,'2026-10-25',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(156,66,3,'CO3','EXP-15: electrochemical cell sample us conductivity and electrolysis (Hour 1/1)',1,'2026-10-27',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(157,67,3,'CO3','Theory Series Exam 3 (CO3 - Written 1 Hour Test)',1,'2026-10-28',NULL,NULL,'Theory Series Exam (ST)','ST','All Students',NULL,'1 Hour Written Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(158,68,3,'CO2','Module II: Water treatment methods Explain d Chlorinat',1,'2026-10-29',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(159,69,3,'CO2','Module II: Water treatment methods Draw the water.',1,'2026-10-30',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(160,70,3,'CO3','EXP-16: Experiments based on electrochemical cell Demonstra conductivity and electrolysis (Hour 1/1)',1,'2026-10-31',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(161,71,3,'CO4','EXP-17: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers (Hour 1/3)',1,'2026-11-02',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(162,72,3,'CO4','EXP-17: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers (Hour 2/3)',1,'2026-11-03',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(163,73,3,'CO2','Module II: Water treatment methods Explain d osmosis a',1,'2026-11-04',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(164,74,3,'CO2','Module II: Deﬁne aci concept w Acids',1,'2026-11-05',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(165,75,3,'CO2','Module II: bases - pH and POH Deﬁne the State the Deﬁne pH',1,'2026-11-06',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(166,76,3,'CO4','EXP-17: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers (Hour 3/3)',1,'2026-11-08',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(167,77,3,'CO4','EXP-18: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers (Hour 1/3)',1,'2026-11-09',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(168,78,3,'CO4','EXP-18: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers (Hour 2/3)',1,'2026-11-10',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(169,79,3,'CO2','Module II: Acids',1,'2026-11-11',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(170,80,3,'CO2','Module II: bases - pH and POH Solve pro',1,'2026-11-12',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(171,81,3,'CO2','Module II: Acids',1,'2026-11-14',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(172,82,3,'CO4','EXP-18: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers (Hour 3/3)',1,'2026-11-15',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(173,83,3,'CO4','EXP-19: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers Experiments are based on (Hour 1/3)',1,'2026-11-16',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(174,84,3,'CO4','EXP-19: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers Experiments are based on (Hour 2/3)',1,'2026-11-17',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(175,85,3,'CO2','Module II: bases - pH and POH Discuss t',1,'2026-11-18',NULL,NULL,'Lecture (L)','L','All Students',NULL,'Lecture Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(176,86,3,'CO3','Practical Series Exam 2 (CO3+CO4 - 3-Hour Combined Lab Test)',1,'2026-11-20',NULL,NULL,'Practical Series Exam (SP)','SP','Batch A & B',NULL,'3 Hour Practical Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(177,87,3,'CO3','Practical Series Exam 2 (CO3+CO4 - 3-Hour Combined Lab Test)',1,'2026-11-21',NULL,NULL,'Practical Series Exam (SP)','SP','Batch A & B',NULL,'3 Hour Practical Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(178,88,3,'CO3','Practical Series Exam 2 (CO3+CO4 - 3-Hour Combined Lab Test)',1,'2026-11-22',NULL,NULL,'Practical Series Exam (SP)','SP','Batch A & B',NULL,'3 Hour Practical Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(179,89,3,'CO4','Theory Series Exam 4 (CO4 - Written 1 Hour Test)',1,'2026-11-23',NULL,NULL,'Theory Series Exam (ST)','ST','All Students',NULL,'1 Hour Written Exam','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(180,90,3,'CO4','EXP-19: Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers Experiments are based on (Hour 3/3)',1,'2026-11-24',NULL,NULL,'Practical Lab (P)','P','Batch A & B',NULL,'Practical Lab Session','Pending','2026-08-11 19:25:57','2026-08-11 19:25:57'),
(305,1,16,'CO1','Introduction to highway engineering and traffic engineering Role of transportation in the development of nation & IRC classification of road & Major SH and NH in Kerala & - Road alignment & Factors to be considered in road alignment Traffic Engineering: & Traffic volume study & Traffic intensity studies & Road intersection studies & Intersections',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(306,2,16,'CO1','Types of at grade intersection',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(307,3,16,'CO1','Channelizing islands',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(308,4,16,'CO1','Pedestrian loading islands',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(309,5,16,'CO1','Rotary islands',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(310,6,16,'CO1','Grade separated intersections',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(311,7,16,'CO1','Clover leaf junction',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(312,8,16,'CO1','Trumpet junction',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(313,9,16,'CO1','Road markings',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(314,10,16,'CO1','Traffic signs',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(315,11,16,'CO1','Types and purpose of signs',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(316,12,16,'CO1','Cautionary',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(317,13,16,'CO1','Informatory and temporary signs',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(318,14,16,'CO2','Highway Geometrics: Standards cross-sections of national highway in embankment and cutting- various components- Camber & Design speed -Sight distance (SSD) & Road arboriculture- Road drainage Curves: Necessity & Types: Horizontal & Vertical curves & Extra widening of roads & Transition curve- Super elevation: Definition and formula Types of road materials and their Tests & Test on aggregates & Types of Bitumen and its properties & Pavement - Definition, Types',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(319,15,16,'CO2','Structural Components of pavement and their functions- Construction of Flexible pavement / Bituminous Road Emulsion, Cutback, Tar',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(320,16,16,'CO2','Granular Sub Base (GSB)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(321,17,16,'CO2','Wet Mix Macadam (WMM)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(322,18,16,'CO2','Bituminous Macadam & Bituminous Concrete (BM & BC)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(323,19,16,'CO2','Natural Rubber Modified Bitumen (NRMB)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(324,20,16,'CO2','Use of shredded plastic',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(325,21,16,'CO2','Rigid pavement',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(326,22,16,'CO2','Cement concrete road',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(327,23,16,'CO2','Methods of construction',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(328,24,16,'CO2','Alternate and Continuous Bay Method',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(329,25,16,'CO2','Construction joints',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(330,26,16,'CO2','Filler and sealers',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(331,27,16,'CO2','White topping',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(332,28,16,'CO2','Merits and demerits of concrete roads',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(333,29,16,'CO3','Railway Engineering Classification of Indian Railways & Zones of Indian Railways & Permanent way: Ideal requirement & Components - Rail Gauge - Rail & Rail Joints - requirements & Types - Creep of rail: causes and prevention - Sleepers & Functions and types & Rail fixtures and fastenings & Railway Track Geometrics: Gradient, curves',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(334,30,16,'CO3','Grade compensation',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(335,31,16,'CO3','Super elevation',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(336,32,16,'CO3','Cant deficiency',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(337,33,16,'CO3','Negative cant',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(338,34,16,'CO3','Coning of wheel',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(339,35,16,'CO3','Tilting of rail',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(340,36,16,'CO3','Track Cross sections',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(341,37,16,'CO3','Standard cross section of single and double line in cutting and embankment Points and crossings',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(342,38,16,'CO3','Components',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(343,39,16,'CO3','Functions - Track junctions: crossovers',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(344,40,16,'CO3','Scissor cross over',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(345,41,16,'CO3','Diamond crossing',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(346,42,16,'CO3','Track signals -principles of interlocking',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(347,43,16,'CO3','Station yard: Classifications Overview of Mono Rail and Metro rail CO 4 Outline the different modes of transportation and importance of bridge and tunnel M4.01 Classify bridges and identify their components 4 Understanding M4.02 Identify common tunnel shapes 3 Understanding M4.03 Id',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(348,44,16,'CO4','Introduction and overview of CO4 concepts',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(349,45,16,'CO4','Fundamental principles and definitions for CO4',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(350,46,16,'CO4','Core theory and key concepts of CO4',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(351,47,16,'CO4','Detailed study: analysis and discussion (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(352,48,16,'CO4','Worked examples and solved problems (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(353,49,16,'CO4','Design considerations and methodology (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(354,50,16,'CO4','Applications and practical usage (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:16','2026-08-11 22:20:16'),
(355,51,16,'CO4','Problem solving session (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(356,52,16,'CO4','Advanced concepts and extensions (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(357,53,16,'CO4','Case studies and real-world scenarios (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(358,54,16,'CO4','Revision, Q&A and doubt clearing (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(359,55,16,'CO4','Tutorial and additional problems (CO4)',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(360,56,16,'CO4','Introduction and overview of CO4 concepts',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(361,57,16,'CO4','Fundamental principles and definitions for CO4',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(362,58,16,'CO4','Core theory and key concepts of CO4',1,NULL,NULL,NULL,'Lecture','L','Whole',NULL,NULL,'Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(363,59,16,NULL,'Series Test - I / Internal Assessment',1,NULL,NULL,NULL,'Test','L','Whole',NULL,'Series Test - I','Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(364,60,16,NULL,'Series Test - II / Internal Assessment',1,NULL,NULL,NULL,'Test','L','Whole',NULL,'Series Test - II','Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(365,61,16,NULL,'Series Test - III / Internal Assessment',1,NULL,NULL,NULL,'Test','L','Whole',NULL,'Series Test - III','Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(366,62,16,NULL,'Series Test - IV / Internal Assessment',1,NULL,NULL,NULL,'Test','L','Whole',NULL,'Series Test - IV','Pending','2026-08-11 22:20:17','2026-08-11 22:20:17'),
(367,1,5,'CO1','Basic concept of a matrix, special types of matrices',1,NULL,NULL,NULL,'Lecture','L','Whole','Remember',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(368,2,5,'CO1','Transpose of a matrix Algebra of matrices: Multiplication by',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(369,3,5,'CO1','a scalar, Equality, Addition, Subtraction, Matrix Multiplication Determinants, Solution of system of',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(370,4,5,'CO1','a scalar, Equality, Addition, Subtraction, Matrix Multiplication Determinants, Solution of system of',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(371,5,5,'CO1','a scalar, Equality, Addition, Subtraction, Matrix Multiplication Determinants, Solution of system of',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(372,6,5,'CO1','linear equations involving two and three unknowns by Cramer\'s rule',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(373,7,5,'CO1','linear equations involving two and three unknowns by Cramer\'s rule',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(374,8,5,'CO1','linear equations involving two and three unknowns by Cramer\'s rule',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(375,9,5,'CO1','Minors, cofactors, adjoint and inverse of a 2 X 2 matrix',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(376,10,5,'CO1','Minors, cofactors, adjoint and inverse of a 2 X 2 matrix',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(377,11,5,'CO1','Minors, cofactors, adjoint and inverse of a 2 X 2 matrix',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(378,12,5,'CO1','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(379,13,5,'CO1','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(380,14,5,'CO1','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(381,15,5,'CO1','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(382,16,5,'CO2','Angles',1,NULL,NULL,NULL,'Lecture','L','Whole','Remember',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(383,17,5,'CO2','Trigonometric ratios of acute angle, Trigonometric ratios of standard angles',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(384,18,5,'CO2','Trigonometric ratios of acute angle, Trigonometric ratios of standard angles',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(385,19,5,'CO2','Trigonometric ratios of acute angle, Trigonometric ratios of standard angles',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(386,20,5,'CO2','Pythagorean relations, Trigonometric ratios of any angle, ASTC rule, Reduction formula',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(387,21,5,'CO2','Pythagorean relations, Trigonometric ratios of any angle, ASTC rule, Reduction formula',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(388,22,5,'CO2','Pythagorean relations, Trigonometric ratios of any angle, ASTC rule, Reduction formula',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(389,23,5,'CO2','Pythagorean relations, Trigonometric ratios of any angle, ASTC rule, Reduction formula',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(390,24,5,'CO2','Trigonometric ratios of compound angles (A+B, A-B)',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(391,25,5,'CO2','Trigonometric ratios of compound angles (A+B, A-B)',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(392,26,5,'CO2','Trigonometric ratios of compound angles (A+B, A-B)',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(393,27,5,'CO2','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(394,28,5,'CO2','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(395,29,5,'CO2','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(396,30,5,'CO2','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(397,31,5,'CO3','Straight lines: Slope, slope-point form',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(398,32,5,'CO3','Straight lines: Slope, slope-point form',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(399,33,5,'CO3','Straight lines: Slope, slope-point form',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(400,34,5,'CO3','Straight lines: Slope, slope-point form',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(401,35,5,'CO3','Angle between lines',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(402,36,5,'CO3','Parallel and perpendicular lines',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(403,37,5,'CO3','Parallel and perpendicular lines',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(404,38,5,'CO3','Parallel and perpendicular lines',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(405,39,5,'CO3','Circles: Equation of a circle, center and radius of the circle',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(406,40,5,'CO3','Circles: Equation of a circle, center and radius of the circle',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(407,41,5,'CO3','Circles: Equation of a circle, center and radius of the circle',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(408,42,5,'CO3','Circles: Equation of a circle, center and radius of the circle',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(409,43,5,'CO3','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(410,44,5,'CO3','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(411,45,5,'CO3','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(412,46,5,'CO3','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(413,47,5,'CO4','Concept of limit, Limits by substitution and factorisation',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(414,48,5,'CO4','Concept of limit, Limits by substitution and factorisation',1,NULL,NULL,NULL,'Lecture','L','Whole','Understand',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(415,49,5,'CO4','Limits of the form (xn - an) / (x-a) and sin x / x',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(416,50,5,'CO4','Limits of the form (xn - an) / (x-a) and sin x / x',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(417,51,5,'CO4','Limits of the form (xn - an) / (x-a) and sin x / x',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(418,52,5,'CO4','Basic concept of diﬀerentiation, standard results (derivatives of a constant k, xn, ex, log x, sin x, cos x, tan x, csc x, sec x, cot x) Rules of diﬀerentiation (linearity,',1,NULL,NULL,NULL,'Lecture','L','Whole','Remember',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(419,53,5,'CO4','product, and quotient rules), second derivatives',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(420,54,5,'CO4','product, and quotient rules), second derivatives',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(421,55,5,'CO4','product, and quotient rules), second derivatives',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(422,56,5,'CO4','product, and quotient rules), second derivatives',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(423,57,5,'CO4','product, and quotient rules), second derivatives',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(424,58,5,'CO4','product, and quotient rules), second derivatives',1,NULL,NULL,NULL,'Lecture','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(425,59,5,'CO4','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(426,60,5,'CO4','Problems',1,NULL,NULL,NULL,'Tutorial','L','Whole','Apply',NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(427,61,5,'CO4','Series Test 1 / Module Evaluation',1,NULL,NULL,NULL,'Exam','L','Whole',NULL,NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(428,62,5,'CO4','Series Test 2 / Module Evaluation',1,NULL,NULL,NULL,'Exam','L','Whole',NULL,NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(429,63,5,'CO4','Series Test 3 / Module Evaluation',1,NULL,NULL,NULL,'Exam','L','Whole',NULL,NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52'),
(430,64,5,'CO4','Series Test 4 / Module Evaluation',1,NULL,NULL,NULL,'Exam','L','Whole',NULL,NULL,'Pending','2026-08-11 22:24:52','2026-08-11 22:24:52');
/*!40000 ALTER TABLE `lesson_plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `mentoring_batches`
--

DROP TABLE IF EXISTS `mentoring_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mentoring_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `classroom_id` varchar(50) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `mentor_no` varchar(15) NOT NULL,
  `batch_label` enum('A','B') NOT NULL,
  `assigned_by` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_batch` (`classroom_id`,`reg_no`),
  KEY `mentoring_batches_reg_no_foreign` (`reg_no`),
  KEY `mentoring_batches_mentor_no_classroom_id_index` (`mentor_no`,`classroom_id`),
  CONSTRAINT `mentoring_batches_mentor_no_foreign` FOREIGN KEY (`mentor_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE,
  CONSTRAINT `mentoring_batches_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mentoring_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `mentoring_batches` WRITE;
/*!40000 ALTER TABLE `mentoring_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `mentoring_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `mid_semester_surveys`
--

DROP TABLE IF EXISTS `mid_semester_surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mid_semester_surveys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `faculty_name` varchar(150) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `custom_questions` text DEFAULT NULL,
  `initiated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `improvements_noted` text DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `action_taken_by_tutor` text DEFAULT NULL,
  `action_taken_by_hod` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mid_semester_surveys_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `mid_semester_surveys_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mid_semester_surveys`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `mid_semester_surveys` WRITE;
/*!40000 ALTER TABLE `mid_semester_surveys` DISABLE KEYS */;
/*!40000 ALTER TABLE `mid_semester_surveys` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2026_06_21_000001_create_staff_profiles_table',1),
(2,'2026_06_21_000002_create_class_management_table',1),
(3,'2026_06_21_000003_create_students_table',1),
(4,'2026_06_21_000004_create_syllabus_registry_table',1),
(5,'2026_06_21_000005_create_question_bank_table',1),
(6,'2026_06_21_000006_create_test_configs_table',1),
(7,'2026_06_21_000007_create_student_responses_table',1),
(8,'2026_06_21_000008_create_academic_marks_table',1),
(9,'2026_06_21_000009_create_tutor_diaries_table',1),
(10,'2026_06_21_000010_create_po_configs_table',1),
(11,'2026_06_21_033735_create_sessions_table',1),
(12,'2026_06_21_043000_create_audit_logs_table',1),
(13,'2026_06_21_060000_create_mentoring_batches_table',1),
(14,'2026_06_21_060001_add_approval_fields_to_tutor_diaries',1),
(15,'2026_06_22_135148_create_batch_subjects_table',2),
(16,'2026_06_22_135226_create_subject_staff_assignments_table',2),
(17,'2026_06_22_150235_create_course_files_table',3),
(18,'2026_06_22_151808_create_lesson_plans_table',4),
(19,'2026_06_22_163744_add_fields_to_lesson_plans_table',5),
(20,'2026_06_22_163818_add_parsed_copo_to_course_files_table',6),
(21,'2026_06_23_000001_add_rubric_to_question_bank',7),
(22,'2026_06_23_021517_add_online_test_fields_to_test_configs_table',8),
(23,'2026_06_23_021527_create_test_attempts_table',8),
(24,'2026_06_23_123643_add_responses_to_test_attempts_table',9),
(25,'2026_06_23_143048_create_student_semester_marks_table',10),
(26,'2026_06_23_143102_create_student_semester_summary_table',10),
(27,'2026_06_23_154303_add_academic_status_to_students_table',11),
(28,'2026_06_23_154312_add_current_semester_to_class_management_table',11),
(29,'2026_06_23_172900_add_placement_and_remarks_to_students_table',12),
(30,'2026_06_24_020119_create_student_attendance_table',13),
(31,'2026_06_24_181303_create_student_task_submissions_table',14),
(32,'2026_06_24_191318_create_mentoring_diary_tables',15),
(33,'2026_06_24_201825_add_verification_status_to_mentoring_tables',16),
(34,'2026_06_24_210434_create_student_board_grades_table',17),
(35,'2026_06_24_215558_create_activity_point_claims_table',18),
(36,'2026_06_24_234927_add_semester_to_activity_point_claims_table',19),
(37,'2026_06_25_070632_create_nba_course_file_tables',20),
(38,'2026_06_25_163807_create_cf_course_file_documents_table',21),
(39,'2026_06_25_183904_add_data_payload_to_cf_course_file_documents',22),
(40,'2026_06_25_200119_add_cis_pdf_path_to_syllabus_registry',23),
(41,'2026_06_25_201043_add_co_po_mapping_to_syllabus_registry',24),
(42,'2026_06_26_102130_create_remedial_tables',25),
(43,'2026_06_26_105405_add_phase3_fields_to_remedial_tables',26),
(44,'2026_06_26_121136_add_attainment_settings_to_cf_course_files_table',27),
(45,'2026_06_26_150351_add_board_result_fields_to_student_board_grades',28),
(46,'2026_06_26_175122_create_password_reset_tokens_table',29),
(47,'2026_06_26_182643_add_syllabus_revision_code_to_batch_subjects',30),
(48,'2026_06_26_200109_add_rejection_note_to_activity_point_claims_table',31),
(49,'2026_06_28_091437_create_student_mentoring_profiles_table',32),
(50,'2026_06_29_072003_add_roll_no_to_students_table',33),
(51,'2026_06_29_072003_create_class_logs_attendance_table',33),
(52,'2026_06_29_174304_add_batch_subject_id_to_question_bank',33),
(53,'2026_06_29_174322_add_batch_subject_id_to_academic_marks',33),
(54,'2026_07_02_184321_create_student_mock_test_attempts_table',33),
(55,'2026_07_03_071820_create_mid_semester_surveys_table',33),
(56,'2026_07_03_071821_create_student_survey_responses_table',33),
(57,'2026_07_03_164010_update_survey_schema_2026_july',33),
(58,'2026_07_03_174744_create_course_exit_surveys_table',33),
(59,'2026_07_05_120000_create_audit_compliance_tables',33),
(60,'2026_07_05_130000_create_sbte_department_audits_table',33),
(61,'2026_07_05_131000_create_staff_professional_activities_table',33),
(62,'2026_07_05_140000_add_sub_batch_to_attendance_and_logs',33),
(63,'2026_07_05_210000_create_academic_calendars_tables',33),
(64,'2026_07_10_222944_create_seminar_evaluations_table',33),
(65,'2026_07_10_225048_create_student_seminar_registrations_table',33),
(66,'2026_07_10_234013_create_seminar_acceptances_table',33),
(67,'2026_07_11_150000_create_practical_evaluations_and_tests_tables',33),
(68,'2026_07_11_160000_add_open_ended_topic_to_practical_evaluations',33),
(69,'2026_07_16_000001_create_lesson_plan_templates_table',33),
(70,'2026_07_17_171200_create_system_settings_table',33),
(71,'2026_07_17_172221_create_cache_table',33),
(72,'2026_07_19_000002_create_r26_class_management_table',33),
(73,'2026_07_19_000003_drop_classroom_foreign_keys',33),
(74,'2026_07_19_124935_add_taxonomy_to_lesson_plans_table',33),
(75,'2026_07_19_172829_add_self_learning_configs_to_course_files_table',33),
(76,'2026_07_19_220322_create_series_exams_table',33),
(77,'2026_07_20_095446_add_custom_questions_to_surveys_table',33),
(78,'2026_07_21_060000_create_r26_course_files_tables',33),
(79,'2026_07_21_210000_create_r2026_practical_evaluations_tables',33),
(80,'2026_07_22_000000_create_r26_student_lab_batches_table',33),
(81,'2026_07_22_100000_create_r26_practical_series_exams_table',33),
(82,'2026_07_22_101000_create_r26_practical_ese_marks_table',33),
(83,'2026_07_22_200000_create_r26_practical_course_files_table',33),
(84,'2026_07_23_000000_add_sub_batch_to_lesson_plans_table',33),
(85,'2026_07_24_000000_create_r26_practicum_tables',33),
(86,'2026_07_25_000000_create_r26_series_exam_qps_table',33),
(87,'2026_07_25_081426_add_ese_theory_grade_to_r26_practicum_ese_marks',33),
(88,'2026_07_25_100000_create_r26_question_bank_table',33),
(89,'2026_07_26_180000_add_semester_to_students_table',34),
(90,'2026_07_29_000000_create_r26_drawing_tables',35),
(91,'2026_07_29_000001_add_series_test_qps_to_r26_drawing_course_files',36),
(92,'2026_07_28_180000_add_mode_to_lesson_plans_table',37),
(93,'2026_07_29_000002_create_r26_health_physical_tables',38),
(94,'2026_07_31_000001_create_staff_leave_requests_table',39),
(95,'2026_07_31_000002_add_ccl_date_to_staff_leave_requests_table',40),
(96,'2026_07_31_000003_create_department_notices_table',41),
(98,'2026_08_04_090000_create_virtual_learning_materials_tables',42),
(99,'2026_08_09_000001_create_department_semester_pass_stats_table',43),
(100,'2026_08_09_000002_create_executive_flash_notices_table',44),
(101,'2026_08_09_000003_create_principal_scheduled_events_table',45),
(102,'2026_08_10_000001_create_sf_attendance_tables',46);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `nba_criteria_documents`
--

DROP TABLE IF EXISTS `nba_criteria_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nba_criteria_documents` (
  `id` char(36) NOT NULL,
  `criteria_no` int(11) NOT NULL,
  `document_name` varchar(150) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Pending',
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nba_criteria_documents`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `nba_criteria_documents` WRITE;
/*!40000 ALTER TABLE `nba_criteria_documents` DISABLE KEYS */;
INSERT INTO `nba_criteria_documents` VALUES
('091fa9aa-7641-446c-8b59-a5b456f8bdc3',3,'Program Outcomes (PO) Attainments Matrix','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('1666bc9b-b74b-4774-8ad5-510557cfd7e8',1,'Program Specific Outcomes (PSOs) Statement Review','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('16df1f4a-b619-4529-adb0-70d7fb02ddf0',9,'Student Support Systems Feedback Log','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('1a9f9406-a0ff-4fe3-9a0e-4e9f4393e05b',7,'Academic Audit Reviews & Feedback Closure','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('34fe4498-1530-444a-ae4f-36abb7eb93b6',3,'Course Outcomes (CO) Attainments','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('4761461a-63cf-42d7-89b1-ed1ffcd1201a',2,'Program Curriculum & Structure Design','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('5cb3bb24-60b4-4fab-8de4-2054bfa5f1b2',8,'First-Year Academics Student-Faculty Ratio','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('73b6ed17-b643-4114-9efd-52a8d9e49a81',5,'Student-Faculty Ratio (SFR) Statement','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('81748511-a69f-482e-a210-e4603111bb75',5,'Faculty Retention & Professional Development Profiles','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('88f19ce0-2f73-4add-bd07-88fe11db50ad',8,'First-Year Continuous Internal Assessment Roster','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('89f64fad-758a-44b5-a853-c0619912e18e',1,'Vision, Mission & Program Educational Objectives (PEOs)','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('9788ce98-5d41-479a-9a53-6ffec54e417f',6,'Technical Support Staff Roster','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('9ebb378d-0fc5-40af-9901-91635d5bf109',4,'Placement, Higher Studies & Entrepreneurship Records','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('9f1cd515-8965-4f6e-a0b3-e57d0927f834',7,'Continuous Attainment Improvement Action Plan','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('a465208d-618a-4c4f-97e1-711696cead7c',9,'Governance Structure, Budget & Financial Resources Audit','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('ad189b1c-9a6f-48c9-81e4-a1126380f644',4,'Student Enrollment Statistics & Success Rate','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('bdd96b89-77e7-4014-9619-6650fec2a980',6,'Laboratory Maintenance Logbooks Audit','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01'),
('ecbfa9da-701a-471b-bce2-150aaedde891',2,'Teaching-Learning Process Methodologies','2026-2027','Pending',NULL,NULL,'2026-08-05 21:42:01','2026-08-05 21:42:01');
/*!40000 ALTER TABLE `nba_criteria_documents` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `po_config`
--

DROP TABLE IF EXISTS `po_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `po_config` (
  `po_id` varchar(10) NOT NULL,
  `po_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`po_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `po_config`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `po_config` WRITE;
/*!40000 ALTER TABLE `po_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `po_config` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `practical_evaluations`
--

DROP TABLE IF EXISTS `practical_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `practical_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `assessor_mobile_no` varchar(50) NOT NULL,
  `micro_project` decimal(5,2) NOT NULL DEFAULT 0.00,
  `open_ended_topic` varchar(255) DEFAULT NULL,
  `attendance_marks` decimal(5,2) NOT NULL DEFAULT 0.00,
  `board_exam_marks` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pract_eval_unique` (`batch_subject_id`,`reg_no`),
  KEY `practical_evaluations_reg_no_foreign` (`reg_no`),
  KEY `practical_evaluations_assessor_mobile_no_foreign` (`assessor_mobile_no`),
  CONSTRAINT `practical_evaluations_assessor_mobile_no_foreign` FOREIGN KEY (`assessor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE,
  CONSTRAINT `practical_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `practical_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `practical_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `practical_evaluations` WRITE;
/*!40000 ALTER TABLE `practical_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `practical_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `practical_experiment_marks`
--

DROP TABLE IF EXISTS `practical_experiment_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `practical_experiment_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `practical_experiment_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `assessor_mobile_no` varchar(50) NOT NULL,
  `prerequisites` decimal(5,2) NOT NULL DEFAULT 0.00,
  `work_done` decimal(5,2) NOT NULL DEFAULT 0.00,
  `result` decimal(5,2) NOT NULL DEFAULT 0.00,
  `rough_record` decimal(5,2) NOT NULL DEFAULT 0.00,
  `fair_record` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_mark` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pract_exp_marks_unique` (`practical_experiment_id`,`reg_no`),
  KEY `practical_experiment_marks_reg_no_foreign` (`reg_no`),
  KEY `practical_experiment_marks_assessor_mobile_no_foreign` (`assessor_mobile_no`),
  CONSTRAINT `practical_experiment_marks_assessor_mobile_no_foreign` FOREIGN KEY (`assessor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE,
  CONSTRAINT `practical_experiment_marks_practical_experiment_id_foreign` FOREIGN KEY (`practical_experiment_id`) REFERENCES `practical_experiments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `practical_experiment_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `practical_experiment_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `practical_experiment_marks` WRITE;
/*!40000 ALTER TABLE `practical_experiment_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `practical_experiment_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `practical_experiments`
--

DROP TABLE IF EXISTS `practical_experiments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `practical_experiments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `experiment_no` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `co_tag` varchar(10) NOT NULL,
  `conducted_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `practical_experiments_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `practical_experiments_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `practical_experiments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `practical_experiments` WRITE;
/*!40000 ALTER TABLE `practical_experiments` DISABLE KEYS */;
/*!40000 ALTER TABLE `practical_experiments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `practical_test_marks`
--

DROP TABLE IF EXISTS `practical_test_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `practical_test_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `practical_test_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `co_tag` varchar(10) NOT NULL,
  `marks_obtained` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pract_test_marks_unique` (`practical_test_id`,`reg_no`,`co_tag`),
  KEY `practical_test_marks_reg_no_foreign` (`reg_no`),
  CONSTRAINT `practical_test_marks_practical_test_id_foreign` FOREIGN KEY (`practical_test_id`) REFERENCES `practical_tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `practical_test_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `practical_test_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `practical_test_marks` WRITE;
/*!40000 ALTER TABLE `practical_test_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `practical_test_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `practical_tests`
--

DROP TABLE IF EXISTS `practical_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `practical_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `test_name` varchar(50) NOT NULL,
  `questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`questions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `practical_tests_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `practical_tests_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `practical_tests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `practical_tests` WRITE;
/*!40000 ALTER TABLE `practical_tests` DISABLE KEYS */;
/*!40000 ALTER TABLE `practical_tests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `principal_scheduled_events`
--

DROP TABLE IF EXISTS `principal_scheduled_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `principal_scheduled_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_category` varchar(50) NOT NULL DEFAULT 'Academic',
  `venue` varchar(255) NOT NULL DEFAULT 'Main Auditorium',
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_full_day` tinyint(1) NOT NULL DEFAULT 0,
  `target_audience` varchar(50) NOT NULL DEFAULT 'ALL_CAMPUS',
  `target_department` varchar(50) NOT NULL DEFAULT 'ALL',
  `target_semester` varchar(10) NOT NULL DEFAULT 'ALL',
  `target_role` varchar(50) NOT NULL DEFAULT 'ALL',
  `special_group_name` varchar(100) DEFAULT NULL,
  `requires_rsvp` tinyint(1) NOT NULL DEFAULT 0,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_type` varchar(20) NOT NULL DEFAULT 'none',
  `dispatch_type` varchar(20) NOT NULL DEFAULT 'immediate',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(100) NOT NULL DEFAULT 'Principal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `principal_scheduled_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `principal_scheduled_events` WRITE;
/*!40000 ALTER TABLE `principal_scheduled_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `principal_scheduled_events` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `question_bank`
--

DROP TABLE IF EXISTS `question_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `question_bank` (
  `question_id` char(36) NOT NULL DEFAULT uuid(),
  `branch_code` varchar(10) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `batch_subject_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `part_type` varchar(5) DEFAULT NULL,
  `cognitive_level` varchar(5) DEFAULT NULL,
  `question_text` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct_answer` text DEFAULT NULL,
  `co_tag` varchar(10) NOT NULL,
  `marks` int(11) NOT NULL,
  `rubric` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rubric`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `question_bank_subject_code_index` (`subject_code`),
  KEY `question_bank_batch_subject_id_index` (`batch_subject_id`),
  CONSTRAINT `question_bank_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `syllabus_registry` (`subject_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_bank`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `question_bank` WRITE;
/*!40000 ALTER TABLE `question_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `question_bank` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_class_management`
--

DROP TABLE IF EXISTS `r26_class_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_class_management` (
  `classroom_id` varchar(50) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `batch_year` int(11) NOT NULL,
  `tutor_mobile_no` varchar(15) DEFAULT NULL,
  `mentor_mobile_no` varchar(15) DEFAULT NULL,
  `current_semester` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`classroom_id`),
  KEY `r26_class_management_tutor_mobile_no_foreign` (`tutor_mobile_no`),
  KEY `r26_class_management_mentor_mobile_no_foreign` (`mentor_mobile_no`),
  CONSTRAINT `r26_class_management_mentor_mobile_no_foreign` FOREIGN KEY (`mentor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `r26_class_management_tutor_mobile_no_foreign` FOREIGN KEY (`tutor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_class_management`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_class_management` WRITE;
/*!40000 ALTER TABLE `r26_class_management` DISABLE KEYS */;
INSERT INTO `r26_class_management` VALUES
('CE_2026_2029','CE',2026,'9895527950','9349186555',1,'2026-08-11 16:25:16','2026-08-11 21:14:45');
/*!40000 ALTER TABLE `r26_class_management` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_course_file_documents`
--

DROP TABLE IF EXISTS `r26_course_file_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_course_file_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `r26_course_file_id` bigint(20) unsigned NOT NULL,
  `document_number` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL,
  `data_payload` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `r26_course_file_documents_r26_course_file_id_foreign` (`r26_course_file_id`),
  CONSTRAINT `r26_course_file_documents_r26_course_file_id_foreign` FOREIGN KEY (`r26_course_file_id`) REFERENCES `r26_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_course_file_documents`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_course_file_documents` WRITE;
/*!40000 ALTER TABLE `r26_course_file_documents` DISABLE KEYS */;
INSERT INTO `r26_course_file_documents` VALUES
(1,1,1,'Class Time table (current semester Program timetable)',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(2,1,2,'Faculty Workload',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(3,1,3,'Student List with register numbers',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(4,1,4,'Course Syllabus with Recommended Books (SITTTR)',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(5,1,5,'Course information sheet',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(6,1,6,'Course outcomes & CO-PO Mappings',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(7,1,7,'Academic calender & Semester Layout',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(8,1,8,'Course Plan / Lesson Planner',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(9,1,9,'Course log and Attendance',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(10,1,10,'Internal Exam Question Papers CO 1,2,3,4 with mark splitup / Scheme',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(11,1,11,'Internal Examination Result Analysis NBA',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(12,1,12,'Weaker student coaching schedule and proof',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(13,1,13,'Teaching and Learning Methods Proof - handouts, capsule notes etc.',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(14,1,14,'Assignment questions with rubrics',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(15,1,15,'Internal Marks - SBTE (CIA)',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(16,1,16,'Grade Sheet - Proof of CO evaluations',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(17,1,17,'External Exam Question Papers / Question bank',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(18,1,18,'SBTE examination result',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(19,1,19,'Attainment of Course Outcome (CO) Co-Po-Pso Map',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(20,1,20,'Attainment of PO/PSO report',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(21,1,21,'Mid semester survey & report',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(22,1,22,'End semester / Course exit survey & report',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(23,1,23,'Internal Examination sample answer scripts',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(24,1,24,'Assignment sample scripts',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23'),
(25,1,25,'Others',0,'',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23');
/*!40000 ALTER TABLE `r26_course_file_documents` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_course_files`
--

DROP TABLE IF EXISTS `r26_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `academic_year` varchar(255) NOT NULL DEFAULT '2026-2027',
  `status` varchar(255) NOT NULL DEFAULT 'Draft',
  `generated_pdf_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `r26_course_files_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `r26_course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_course_files` WRITE;
/*!40000 ALTER TABLE `r26_course_files` DISABLE KEYS */;
INSERT INTO `r26_course_files` VALUES
(1,5,'2026-2027','Draft',NULL,'2026-08-11 21:59:23','2026-08-11 21:59:23');
/*!40000 ALTER TABLE `r26_course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_drawing_course_files`
--

DROP TABLE IF EXISTS `r26_drawing_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_drawing_course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `syllabus_pdf_path` varchar(255) DEFAULT NULL,
  `program` text DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `course_code` varchar(50) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `type_of_course` varchar(255) NOT NULL DEFAULT 'Lab',
  `teaching_scheme` varchar(50) NOT NULL DEFAULT '0:0:3:0',
  `contact_hours` int(11) NOT NULL DEFAULT 45,
  `credits` decimal(4,1) NOT NULL DEFAULT 1.5,
  `cie_marks` int(11) NOT NULL DEFAULT 60,
  `ese_marks` int(11) NOT NULL DEFAULT 40,
  `parsed_cos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_cos`)),
  `parsed_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_modules`)),
  `parsed_exercises` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_exercises`)),
  `parsed_copo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_copo`)),
  `parsed_textbooks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_textbooks`)),
  `self_learning_configs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`self_learning_configs`)),
  `series_test_qps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`series_test_qps`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_drawing_course_files_batch_subject_id_unique` (`batch_subject_id`),
  CONSTRAINT `r26_drawing_course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_drawing_course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_drawing_course_files` WRITE;
/*!40000 ALTER TABLE `r26_drawing_course_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_drawing_course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_drawing_ese_marks`
--

DROP TABLE IF EXISTS `r26_drawing_ese_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_drawing_ese_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `part_a_mcq` decimal(5,2) NOT NULL DEFAULT 0.00,
  `part_b_cad` decimal(5,2) NOT NULL DEFAULT 0.00,
  `part_c_viva` decimal(5,2) NOT NULL DEFAULT 0.00,
  `part_d_record` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_ese_40` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_drawing_ese_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_drawing_ese_marks_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_drawing_ese_marks_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_drawing_ese_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_drawing_ese_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_drawing_ese_marks` WRITE;
/*!40000 ALTER TABLE `r26_drawing_ese_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_drawing_ese_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_drawing_oee_evaluations`
--

DROP TABLE IF EXISTS `r26_drawing_oee_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_drawing_oee_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `originality_relevance` decimal(5,2) NOT NULL DEFAULT 0.00,
  `objectives_plan` decimal(5,2) NOT NULL DEFAULT 0.00,
  `execution_recording` decimal(5,2) NOT NULL DEFAULT 0.00,
  `analysis_presentation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `teamwork_innovation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_drawing_oee_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_drawing_oee_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_drawing_oee_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_drawing_oee_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_drawing_oee_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_drawing_oee_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_drawing_oee_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_drawing_oee_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_drawing_practical_tests`
--

DROP TABLE IF EXISTS `r26_drawing_practical_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_drawing_practical_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `test_no` varchar(20) NOT NULL DEFAULT 'CA1',
  `reg_no` varchar(50) NOT NULL,
  `writeup_procedure` decimal(5,2) NOT NULL DEFAULT 0.00,
  `setup_execution` decimal(5,2) NOT NULL DEFAULT 0.00,
  `observation_result` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_voce` decimal(5,2) NOT NULL DEFAULT 0.00,
  `record_completion` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_40` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_drawing_test_unique` (`batch_subject_id`,`test_no`,`reg_no`),
  KEY `r26_drawing_practical_tests_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_drawing_practical_tests_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_drawing_practical_tests_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_drawing_practical_tests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_drawing_practical_tests` WRITE;
/*!40000 ALTER TABLE `r26_drawing_practical_tests` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_drawing_practical_tests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_drawing_slot_evaluations`
--

DROP TABLE IF EXISTS `r26_drawing_slot_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_drawing_slot_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `exercise_no` varchar(50) NOT NULL,
  `exercise_title` varchar(255) DEFAULT NULL,
  `reg_no` varchar(50) NOT NULL,
  `prep_punctuality` decimal(5,2) NOT NULL DEFAULT 0.00,
  `setup_procedure` decimal(5,2) NOT NULL DEFAULT 0.00,
  `observation_recording` decimal(5,2) NOT NULL DEFAULT 0.00,
  `analysis_interpretation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_voce` decimal(5,2) NOT NULL DEFAULT 0.00,
  `workmanship_discipline` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_drawing_slot_unique` (`batch_subject_id`,`exercise_no`,`reg_no`),
  KEY `r26_drawing_slot_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_drawing_slot_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_drawing_slot_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_drawing_slot_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_drawing_slot_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_drawing_slot_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_drawing_slot_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_health_physical_course_files`
--

DROP TABLE IF EXISTS `r26_health_physical_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_health_physical_course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `syllabus_pdf_path` varchar(255) DEFAULT NULL,
  `program` text DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `course_code` varchar(50) DEFAULT NULL,
  `semester` varchar(20) NOT NULL DEFAULT 'I',
  `type_of_course` varchar(255) NOT NULL DEFAULT 'Health & Physical',
  `teaching_scheme` varchar(50) NOT NULL DEFAULT '0:0:2:0',
  `contact_hours` int(11) NOT NULL DEFAULT 30,
  `credits` decimal(4,1) NOT NULL DEFAULT 1.0,
  `cie_marks` int(11) NOT NULL DEFAULT 60,
  `ese_marks` int(11) NOT NULL DEFAULT 40,
  `parsed_cos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_cos`)),
  `parsed_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_modules`)),
  `parsed_activities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_activities`)),
  `parsed_copo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_copo`)),
  `parsed_eval_scheme` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_eval_scheme`)),
  `parsed_textbooks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_textbooks`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_health_physical_course_files_batch_subject_id_unique` (`batch_subject_id`),
  CONSTRAINT `r26_health_physical_course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_health_physical_course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_health_physical_course_files` WRITE;
/*!40000 ALTER TABLE `r26_health_physical_course_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_health_physical_course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_health_physical_ese_marks`
--

DROP TABLE IF EXISTS `r26_health_physical_ese_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_health_physical_ese_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `fitness_test_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `skill_demo_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `record_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_ese_40` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_hp_ese_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_health_physical_ese_marks_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_health_physical_ese_marks_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_health_physical_ese_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_health_physical_ese_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_health_physical_ese_marks` WRITE;
/*!40000 ALTER TABLE `r26_health_physical_ese_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_health_physical_ese_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_health_physical_evaluations`
--

DROP TABLE IF EXISTS `r26_health_physical_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_health_physical_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `activity_no` varchar(50) NOT NULL,
  `activity_title` varchar(255) DEFAULT NULL,
  `reg_no` varchar(50) NOT NULL,
  `criteria_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`criteria_json`)),
  `c1` decimal(5,2) NOT NULL DEFAULT 0.00,
  `c2` decimal(5,2) NOT NULL DEFAULT 0.00,
  `c3` decimal(5,2) NOT NULL DEFAULT 0.00,
  `c4` decimal(5,2) NOT NULL DEFAULT 0.00,
  `c5` decimal(5,2) NOT NULL DEFAULT 0.00,
  `c6` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_hp_eval_unique` (`batch_subject_id`,`activity_no`,`reg_no`),
  KEY `r26_health_physical_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_health_physical_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_health_physical_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_health_physical_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_health_physical_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_health_physical_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_health_physical_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_health_physical_fitness_tests`
--

DROP TABLE IF EXISTS `r26_health_physical_fitness_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_health_physical_fitness_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `test_no` varchar(20) NOT NULL DEFAULT 'CA1',
  `reg_no` varchar(50) NOT NULL,
  `criteria_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`criteria_json`)),
  `total_score_40` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_hp_test_unique` (`batch_subject_id`,`test_no`,`reg_no`),
  KEY `r26_health_physical_fitness_tests_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_health_physical_fitness_tests_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_health_physical_fitness_tests_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_health_physical_fitness_tests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_health_physical_fitness_tests` WRITE;
/*!40000 ALTER TABLE `r26_health_physical_fitness_tests` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_health_physical_fitness_tests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_open_ended_evaluations`
--

DROP TABLE IF EXISTS `r26_open_ended_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_open_ended_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `project_title` varchar(255) DEFAULT NULL,
  `reg_no` varchar(50) NOT NULL,
  `originality_relevance` decimal(5,2) NOT NULL DEFAULT 0.00,
  `objectives_plan` decimal(5,2) NOT NULL DEFAULT 0.00,
  `execution_recording` decimal(5,2) NOT NULL DEFAULT 0.00,
  `analysis_presentation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `teamwork_innovation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_open_ended_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_open_ended_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_open_ended_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_open_ended_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_open_ended_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_open_ended_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_open_ended_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_open_ended_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practical_course_files`
--

DROP TABLE IF EXISTS `r26_practical_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practical_course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `syllabus_pdf_path` varchar(255) DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `credits` tinyint(4) NOT NULL DEFAULT 1,
  `teaching_scheme` varchar(20) NOT NULL DEFAULT '0:0:2:0',
  `cie_marks` smallint(6) NOT NULL DEFAULT 60,
  `ese_marks` smallint(6) NOT NULL DEFAULT 40,
  `total_hours` smallint(6) NOT NULL DEFAULT 30,
  `parsed_cos` longtext DEFAULT NULL,
  `parsed_copo` longtext DEFAULT NULL,
  `parsed_experiments` longtext DEFAULT NULL,
  `manual_experiments` longtext DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practical_course_files_batch_subject_id_unique` (`batch_subject_id`),
  CONSTRAINT `r26_practical_course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practical_course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practical_course_files` WRITE;
/*!40000 ALTER TABLE `r26_practical_course_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practical_course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practical_ese_marks`
--

DROP TABLE IF EXISTS `r26_practical_ese_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practical_ese_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `ese_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practical_ese_marks_batch_subject_id_reg_no_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_practical_ese_marks_reg_no_foreign` (`reg_no`),
  KEY `r26_practical_ese_marks_batch_subject_id_index` (`batch_subject_id`),
  CONSTRAINT `r26_practical_ese_marks_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practical_ese_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practical_ese_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practical_ese_marks` WRITE;
/*!40000 ALTER TABLE `r26_practical_ese_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practical_ese_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practical_experiment_evaluations`
--

DROP TABLE IF EXISTS `r26_practical_experiment_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practical_experiment_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `experiment_no` varchar(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `reg_no` varchar(50) NOT NULL,
  `prep_punctuality` decimal(5,2) NOT NULL DEFAULT 0.00,
  `setup_procedure` decimal(5,2) NOT NULL DEFAULT 0.00,
  `observation_recording` decimal(5,2) NOT NULL DEFAULT 0.00,
  `analysis_interpretation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_voce` decimal(5,2) NOT NULL DEFAULT 0.00,
  `teamwork_discipline` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_pract_exp_unique` (`batch_subject_id`,`experiment_no`,`reg_no`),
  KEY `r26_practical_experiment_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_practical_experiment_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practical_experiment_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practical_experiment_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practical_experiment_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_practical_experiment_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practical_experiment_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practical_series_evaluations`
--

DROP TABLE IF EXISTS `r26_practical_series_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practical_series_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `series_no` varchar(20) NOT NULL DEFAULT 'Series 1',
  `reg_no` varchar(50) NOT NULL,
  `writeup_procedure` decimal(5,2) NOT NULL DEFAULT 0.00,
  `setup_execution` decimal(5,2) NOT NULL DEFAULT 0.00,
  `observation_result` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_voce` decimal(5,2) NOT NULL DEFAULT 0.00,
  `record_completion` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_40` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_series_pract_unique` (`batch_subject_id`,`series_no`,`reg_no`),
  KEY `r26_practical_series_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_practical_series_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practical_series_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practical_series_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practical_series_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_practical_series_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practical_series_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practical_series_exams`
--

DROP TABLE IF EXISTS `r26_practical_series_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practical_series_exams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `co_tags` text DEFAULT NULL,
  `max_marks` int(11) NOT NULL DEFAULT 40,
  `duration_minutes` int(11) NOT NULL DEFAULT 120,
  `question_outline` longtext DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `r26_practical_series_exams_batch_subject_id_index` (`batch_subject_id`),
  CONSTRAINT `r26_practical_series_exams_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practical_series_exams`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practical_series_exams` WRITE;
/*!40000 ALTER TABLE `r26_practical_series_exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practical_series_exams` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practicum_course_files`
--

DROP TABLE IF EXISTS `r26_practicum_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practicum_course_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `syllabus_pdf_path` varchar(255) DEFAULT NULL,
  `program` text DEFAULT NULL,
  `course_title` varchar(255) DEFAULT NULL,
  `course_code` varchar(50) DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `type_of_course` varchar(255) NOT NULL DEFAULT 'Practicum',
  `teaching_scheme` varchar(50) NOT NULL DEFAULT '3:0:3:0',
  `contact_hours` int(11) NOT NULL DEFAULT 90,
  `credits` decimal(4,1) NOT NULL DEFAULT 4.5,
  `cie_marks` int(11) NOT NULL DEFAULT 40,
  `ese_marks` int(11) NOT NULL DEFAULT 100,
  `parsed_cos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_cos`)),
  `parsed_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_modules`)),
  `parsed_experiments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_experiments`)),
  `parsed_copo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_copo`)),
  `parsed_textbooks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parsed_textbooks`)),
  `self_learning_configs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`self_learning_configs`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `doc_checklist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`doc_checklist`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practicum_course_files_batch_subject_id_unique` (`batch_subject_id`),
  CONSTRAINT `r26_practicum_course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practicum_course_files`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practicum_course_files` WRITE;
/*!40000 ALTER TABLE `r26_practicum_course_files` DISABLE KEYS */;
INSERT INTO `r26_practicum_course_files` VALUES
(1,3,'r26_practicum_syllabi/DZX7l8h6Xop71aterK0UEufUgVaVNEpdUQmcEDCD.pdf','and Communication, Electrical & Electronics Engineering, Electrical Engineering, Electronics Engineering,','Chemistry for Engineering Practices','2003','I','Practicum','3:0:3:0',90,4.5,40,60,'[{\"id\":\"CO1\",\"description\":\"Apply the basic concepts of atoms, molecules, solutions and thermodynamic processes in scienti\\ufb01c and ngineering contexts.\",\"cognitive_level\":\"Apply\"},{\"id\":\"CO2\",\"description\":\"Apply the fundamentals of analytical Chemistry, appropriate water treatment methods and also the basic oncepts of acids and bases to solve engineering problems.\",\"cognitive_level\":\"Apply\"},{\"id\":\"CO3\",\"description\":\"Enable students to understand the basic knowledge of electrochemistry and corrosion and apply it in ngineering systems to solve industrial challenges.\",\"cognitive_level\":\"Apply\"},{\"id\":\"CO4\",\"description\":\"Explore various engineering materials for suitable industrial and domestic applications and explain green hemistry principles promoting sustainable practices.\",\"cognitive_level\":\"Apply\"}]','[{\"module_id\":\"I\",\"title\":\"Matrices & Determinants\",\"hours\":20,\"content\":\"Atoms and molecules De\\ufb01ne atom, List the f Atoms and molecules protons an mass., Atoms and molecules De\\ufb01ne the with examp, Calculate Atoms and molecules particles number of, Solutions De\\ufb01ne the solution w, Solutions De\\ufb01ne the give the e, Solutions Solve prob, Thermodynamics- De\\ufb01ne the thermodyna, Thermodynamics- Explain di isolated s, Thermodynamics- Explain th isobaric,, Thermodynamics- Di\\ufb00erentia processes, Thermodynamics- Di\\ufb00erentia spontaneou, Thermodynamics- De\\ufb01ne the entropy., Preparation of standard solutions and demonstration Prepare 0. of endothermic and exothermic reactions. Preparation of standard, solutions and demonstration Prepare 0. of endothermic and exothermic reactions., Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions., Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions. Demonstra Preparation of standard (Endother solutions and demonstration soda, the, of endothermic and ammonium exothermic reactions. Exothermi (e.g., HC in water,\"},{\"module_id\":\"II\",\"title\":\"Trigonometry\",\"hours\":30,\"content\":\"Volumetric analysis De\\ufb01ne the standard Explain t, Volumetric analysis titration base, wea, Volumetric analysis State the normality, Volumetric analysis Solve pro V1N1=V2N2, Di\\ufb00erenti Hardness of water List the Di\\ufb00erenti hardness, Discuss t Water treatment methods methods: technique, Water treatment methods Draw the method., Water treatment methods De\\ufb01ne pot, Discuss t Water treatment methods water tre (Screenin steriliza, Water treatment methods Explain d Chlorinat, Water treatment methods Draw the water., Water treatment methods Explain d osmosis a, De\\ufb01ne aci concept w Acids ,bases - pH and POH De\\ufb01ne the State the De\\ufb01ne pH, Acids ,bases - pH and POH Solve pro, Acids ,bases - pH and POH Discuss t, Quantitative and Qualitative Standardi analysis sodium ca, Quantitative and Qualitative Estimatio analysis hydrochlo, Quantitative and Qualitative Estimatio analysis titrating, Quantitative and Qualitative Standardi analysis, Quantitative and Qualitative Volumetri analysis water sam, Quantitative and Qualitative Determine analysis technique paper.\"},{\"module_id\":\"III\",\"title\":\"Coordinate Geometry\",\"hours\":20,\"content\":\"De\\ufb01ne oxi Electrochemistry conductor each., Di\\ufb00erenti Electrochemistry conductor conductor, De\\ufb01ne ele Electrochemistry non-elect suitable, Electrochemistry State Far second la, Electrochemistry Solve pro and secon Illustrat, Electrochemistry electroly on mild s re\\ufb01ning o, Electrochemistry Illustrat example a, Classify Electrochemistry secondary each., Corrosion De\\ufb01ne cor rate of c, Explain c Corrosion protectio metallic protectio, Emerging battery Discuss a technologies idea only solid sta, Experiments based on electrochemical cell Construct conductivity and electrolysis, Experiments based on Electropl electrochemical cell mixture o conductivity and electrolysis Experiments based on Demonstra, electrochemical cell sample us conductivity and electrolysis, Experiments based on electrochemical cell Demonstra conductivity and electrolysis\"},{\"module_id\":\"IV\",\"title\":\"Differential Calculus\",\"hours\":26,\"content\":\"De\\ufb01ne an Alloys List the List any brass, br, De\\ufb01ne mon Polymers examples copolymer, Polymers Di\\ufb00erenti thermoset, Discuss t Polymers common po PET, PU,, Name the Polymers process o propertie, Polymers De\\ufb01ne syn synthetic, De\\ufb01ne nan Nanomaterials Discuss t fullerene List the, Metal Organic Frameworks De\\ufb01ne Met (MOFs) three app De\\ufb01ne bio Discuss b, Biomaterials polymeric De\\ufb01ne bio applicati Discuss t, Green Chemistry discuss t chemistry, Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers, Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers Experiments are based on, estimation of metals in alloys Preparati and preparation of polymers, Experiments are based on estimation of metals in alloys Preparati and preparation of polymers\"}]','[{\"experiment_no\":\"EXP-01\",\"title\":\"Preparation of standard solutions and demonstration Prepare 0. of endothermic and exothermic reactions. Preparation of standard\",\"student_learning_outcome\":\"1 N oxalic acid solution P\",\"co_id\":\"CO1\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-02\",\"title\":\"solutions and demonstration Prepare 0. of endothermic and exothermic reactions.\",\"student_learning_outcome\":\"1 N sodium carbonate solution P\",\"co_id\":\"CO1\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-03\",\"title\":\"Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions.\",\"student_learning_outcome\":\".1 M Zinc Sulphate solution\",\"co_id\":\"CO1\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-04\",\"title\":\"Preparation of standard solutions and demonstration Prepare 0 of endothermic and exothermic reactions. Demonstra Preparation of standard (Endother solutions and demonstration soda, the\",\"student_learning_outcome\":\".1 M Magnesium Sulphate solution te endothermic and exothermic reactions mic:The reaction of citric acid and baking reaction of barium hydroxide and\",\"co_id\":\"CO1\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-05\",\"title\":\"of endothermic and ammonium exothermic reactions. Exothermi (e.g., HC in water,\",\"student_learning_outcome\":\"chloride; c: Neutralisation between acids and bases- l and NaOH reaction,) and reaction of NaOH reaction of CaCl2 in water.\",\"co_id\":\"CO1\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-06\",\"title\":\"Quantitative and Qualitative Standardi analysis sodium ca\",\"student_learning_outcome\":\"sation of hydrochloric acid using standard rbonate solution.\",\"co_id\":\"CO2\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-07\",\"title\":\"Quantitative and Qualitative Estimatio analysis hydrochlo\",\"student_learning_outcome\":\"n of sodium hydroxide using standard ric acid.\",\"co_id\":\"CO2\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-08\",\"title\":\"Quantitative and Qualitative Estimatio analysis titrating\",\"student_learning_outcome\":\"n of potassium hydroxide solution by against standard oxalic acid solution.\",\"co_id\":\"CO2\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-09\",\"title\":\"Quantitative and Qualitative Standardi analysis\",\"student_learning_outcome\":\"sation of EDTA using ZnSO4.\",\"co_id\":\"CO2\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-10\",\"title\":\"Quantitative and Qualitative Volumetri analysis water sam\",\"student_learning_outcome\":\"c estimation of total hardness of given ple using standard EDTA solution.\",\"co_id\":\"CO2\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-11\",\"title\":\"Quantitative and Qualitative Determine analysis technique paper.\",\"student_learning_outcome\":\"the pH of solutions using various s- pH meter, Universal indicator and pH test\",\"co_id\":\"CO2\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-12\",\"title\":\"Experiments based on electrochemical cell Construct conductivity and electrolysis\",\"student_learning_outcome\":\"salt bridge\",\"co_id\":\"CO3\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-13\",\"title\":\"Experiments based on electrochemical cell Construct conductivity and electrolysis\",\"student_learning_outcome\":\"Daniel cell\",\"co_id\":\"CO3\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-14\",\"title\":\"Experiments based on Electropl electrochemical cell mixture o conductivity and electrolysis Experiments based on Demonstra\",\"student_learning_outcome\":\"ating\\u2014Coating a steel spoon with Nickel ( f NiCl2 and NiSO4 as electrolyte te the conductivity of a given water\",\"co_id\":\"CO3\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-15\",\"title\":\"electrochemical cell sample us conductivity and electrolysis\",\"student_learning_outcome\":\"ing a conductivity meter\",\"co_id\":\"CO3\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-16\",\"title\":\"Experiments based on electrochemical cell Demonstra conductivity and electrolysis\",\"student_learning_outcome\":\"te electrolysis of water\",\"co_id\":\"CO3\",\"taxonomy\":\"Apply\",\"hours\":1.5},{\"experiment_no\":\"EXP-17\",\"title\":\"Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers\",\"student_learning_outcome\":\"n of copper in brass.\",\"co_id\":\"CO4\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-18\",\"title\":\"Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers\",\"student_learning_outcome\":\"n of zinc in brass.\",\"co_id\":\"CO4\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-19\",\"title\":\"Experiments are based on estimation of metals in alloys Estimatio and preparation of polymers Experiments are based on\",\"student_learning_outcome\":\"n of iron in iron ore\",\"co_id\":\"CO4\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-20\",\"title\":\"estimation of metals in alloys Preparati and preparation of polymers\",\"student_learning_outcome\":\"on of: Urea\\u2013formaldehyde resin\",\"co_id\":\"CO4\",\"taxonomy\":\"Apply\",\"hours\":3},{\"experiment_no\":\"EXP-21\",\"title\":\"Experiments are based on estimation of metals in alloys Preparati and preparation of polymers\",\"student_learning_outcome\":\"on of Phenol\\u2013formaldehyde resin\",\"co_id\":\"CO4\",\"taxonomy\":\"Apply\",\"hours\":3}]','{\"credit\":4.5,\"l_t_p_r\":\"3:0:3:0\",\"cie_marks\":40,\"ese_marks\":60,\"total_hours\":90,\"mappings\":{\"CO1\":{\"PO1\":\"3\",\"PO2\":\"2\",\"PO3\":\"-\",\"PO4\":\"-\",\"PO5\":\"-\",\"PO6\":\"-\",\"PO7\":\"-\",\"PO8\":\"-\",\"PO9\":\"-\",\"PO10\":\"-\",\"PO11\":\"-\"},\"CO2\":{\"PO1\":\"3\",\"PO2\":\"3\",\"PO3\":\"2\",\"PO4\":\"3\",\"PO5\":\"2\",\"PO6\":\"2\",\"PO7\":\"2\",\"PO8\":\"-\",\"PO9\":\"-\",\"PO10\":\"-\",\"PO11\":\"-\"},\"CO3\":{\"PO1\":\"2\",\"PO2\":\"2\",\"PO3\":\"2\",\"PO4\":\"2\",\"PO5\":\"2\",\"PO6\":\"-\",\"PO7\":\"-\",\"PO8\":\"-\",\"PO9\":\"-\",\"PO10\":\"-\",\"PO11\":\"-\"},\"CO4\":{\"PO1\":\"3\",\"PO2\":\"2\",\"PO3\":\"3\",\"PO4\":\"2\",\"PO5\":\"2\",\"PO6\":\"2\",\"PO7\":\"3\",\"PO8\":\"-\",\"PO9\":\"-\",\"PO10\":\"-\",\"PO11\":\"-\"}}}','[\"Textbook Reference 1\",\"Textbook Reference 2\"]','{\"CO1\":{\"assignment\":5,\"mcq\":5},\"CO2\":{\"assignment\":5,\"mcq\":5},\"CO3\":{\"assignment\":5,\"mcq\":5},\"CO4\":{\"assignment\":5,\"mcq\":5}}','2026-08-11 17:49:42','2026-08-11 19:25:57','{\"5\":{\"is_checked\":false,\"remarks\":\"Generated from course metadata\",\"updated_at\":\"2026-08-11 18:18:10\"},\"6\":{\"is_checked\":false,\"remarks\":\"CO-PO matrix mapped\",\"updated_at\":\"2026-08-11 18:18:11\"},\"7\":{\"is_checked\":false,\"remarks\":\"Institutional calendar mapped\",\"updated_at\":\"2026-08-11 18:18:11\"},\"8\":{\"is_checked\":false,\"remarks\":\"Lesson plan generated\",\"updated_at\":\"2026-08-11 18:18:12\"},\"19\":{\"is_checked\":false,\"remarks\":\"CO attainment mapped\",\"updated_at\":\"2026-08-11 18:18:34\"},\"20\":{\"is_checked\":false,\"remarks\":\"PO\\/PSO attainment calculated\",\"updated_at\":\"2026-08-11 18:18:34\"},\"16\":{\"is_checked\":false,\"remarks\":\"Table 2.2 & 3.1 Rubrics mapped\",\"updated_at\":\"2026-08-11 18:18:35\"},\"13\":{\"is_checked\":false,\"remarks\":\"Dynamic pedagogy tracking active\",\"updated_at\":\"2026-08-11 18:18:36\"},\"14\":{\"is_checked\":false,\"remarks\":\"Self-learning scheme configured\",\"updated_at\":\"2026-08-11 18:18:36\"},\"1\":{\"is_checked\":false,\"remarks\":\"Active timetable mapped\",\"updated_at\":\"2026-08-11 18:18:39\"},\"2\":{\"is_checked\":false,\"remarks\":\"Faculty allocation assigned\",\"updated_at\":\"2026-08-11 18:18:40\"}}');
/*!40000 ALTER TABLE `r26_practicum_course_files` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practicum_ese_marks`
--

DROP TABLE IF EXISTS `r26_practicum_ese_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practicum_ese_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `ese_theory_marks` decimal(5,2) NOT NULL DEFAULT 0.00,
  `ese_theory_grade` varchar(10) DEFAULT NULL,
  `ese_practical_marks` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_ese_marks` decimal(5,2) NOT NULL DEFAULT 0.00,
  `theory_absent` tinyint(1) NOT NULL DEFAULT 0,
  `practical_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practicum_ese_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_practicum_ese_marks_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_practicum_ese_marks_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practicum_ese_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practicum_ese_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practicum_ese_marks` WRITE;
/*!40000 ALTER TABLE `r26_practicum_ese_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practicum_ese_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practicum_experiment_evaluations`
--

DROP TABLE IF EXISTS `r26_practicum_experiment_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practicum_experiment_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `experiment_no` varchar(50) NOT NULL,
  `experiment_title` varchar(255) DEFAULT NULL,
  `reg_no` varchar(50) NOT NULL,
  `prep_punctuality` decimal(5,2) NOT NULL DEFAULT 0.00,
  `setup_procedure` decimal(5,2) NOT NULL DEFAULT 0.00,
  `observation_recording` decimal(5,2) NOT NULL DEFAULT 0.00,
  `analysis_interpretation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_voce` decimal(5,2) NOT NULL DEFAULT 0.00,
  `workmanship_discipline` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessor_mobile_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practicum_exp_unique` (`batch_subject_id`,`experiment_no`,`reg_no`),
  KEY `r26_practicum_experiment_evaluations_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_practicum_experiment_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practicum_experiment_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practicum_experiment_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practicum_experiment_evaluations` WRITE;
/*!40000 ALTER TABLE `r26_practicum_experiment_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practicum_experiment_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practicum_series_practical`
--

DROP TABLE IF EXISTS `r26_practicum_series_practical`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practicum_series_practical` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `series_no` varchar(20) NOT NULL DEFAULT 'Series 1',
  `reg_no` varchar(50) NOT NULL,
  `writeup_procedure` decimal(5,2) NOT NULL DEFAULT 0.00,
  `setup_execution` decimal(5,2) NOT NULL DEFAULT 0.00,
  `observation_result` decimal(5,2) NOT NULL DEFAULT 0.00,
  `viva_voce` decimal(5,2) NOT NULL DEFAULT 0.00,
  `record_completion` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_40` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practicum_sp_unique` (`batch_subject_id`,`series_no`,`reg_no`),
  KEY `r26_practicum_series_practical_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_practicum_series_practical_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practicum_series_practical_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practicum_series_practical`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practicum_series_practical` WRITE;
/*!40000 ALTER TABLE `r26_practicum_series_practical` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practicum_series_practical` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_practicum_series_theory`
--

DROP TABLE IF EXISTS `r26_practicum_series_theory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_practicum_series_theory` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `series_no` varchar(20) NOT NULL DEFAULT 'Series 1',
  `reg_no` varchar(50) NOT NULL,
  `part_a_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `part_b_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `part_c_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score_50` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_absent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_practicum_st_unique` (`batch_subject_id`,`series_no`,`reg_no`),
  KEY `r26_practicum_series_theory_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_practicum_series_theory_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_practicum_series_theory_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_practicum_series_theory`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_practicum_series_theory` WRITE;
/*!40000 ALTER TABLE `r26_practicum_series_theory` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_practicum_series_theory` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_question_bank`
--

DROP TABLE IF EXISTS `r26_question_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_question_bank` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject_code` varchar(30) NOT NULL,
  `batch_subject_id` bigint(20) unsigned DEFAULT NULL,
  `series_no` varchar(20) DEFAULT NULL,
  `pattern_type` varchar(40) NOT NULL DEFAULT 'table_4_1_standard',
  `part` varchar(10) NOT NULL DEFAULT 'part_a',
  `q_no` varchar(15) DEFAULT NULL,
  `question_text` text NOT NULL,
  `marks` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `co_tag` varchar(10) NOT NULL DEFAULT 'CO1',
  `bloom_level` varchar(5) NOT NULL DEFAULT 'L1',
  `choice_group` varchar(20) DEFAULT NULL,
  `scheme_key` text DEFAULT NULL,
  `answer_key` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `r26_question_bank_subject_code_index` (`subject_code`),
  KEY `r26_question_bank_batch_subject_id_index` (`batch_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_question_bank`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_question_bank` WRITE;
/*!40000 ALTER TABLE `r26_question_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_question_bank` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_series_exam_qps`
--

DROP TABLE IF EXISTS `r26_series_exam_qps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_series_exam_qps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `series_no` varchar(20) NOT NULL DEFAULT 'Series 1',
  `co_tag` varchar(10) NOT NULL DEFAULT 'CO1',
  `pattern_type` varchar(50) NOT NULL DEFAULT 'table_4_1_standard',
  `max_marks` int(11) NOT NULL DEFAULT 50,
  `duration_minutes` int(11) NOT NULL DEFAULT 120,
  `qp_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`qp_data`)),
  `scheme_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scheme_data`)),
  `answer_key` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answer_key`)),
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_qp_unique` (`batch_subject_id`,`series_no`),
  CONSTRAINT `r26_series_exam_qps_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_series_exam_qps`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_series_exam_qps` WRITE;
/*!40000 ALTER TABLE `r26_series_exam_qps` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_series_exam_qps` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `r26_student_lab_batches`
--

DROP TABLE IF EXISTS `r26_student_lab_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `r26_student_lab_batches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `lab_batch` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `r26_student_lab_batch_unique` (`batch_subject_id`,`reg_no`),
  KEY `r26_student_lab_batches_reg_no_foreign` (`reg_no`),
  CONSTRAINT `r26_student_lab_batches_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `r26_student_lab_batches_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r26_student_lab_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `r26_student_lab_batches` WRITE;
/*!40000 ALTER TABLE `r26_student_lab_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `r26_student_lab_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `remedial_assessment_scores`
--

DROP TABLE IF EXISTS `remedial_assessment_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `remedial_assessment_scores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `assessment_id` char(36) NOT NULL,
  `reg_no` varchar(255) NOT NULL,
  `score` decimal(8,2) NOT NULL,
  `co_scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`co_scores`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remedial_assessment_scores_assessment_id_reg_no_unique` (`assessment_id`,`reg_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remedial_assessment_scores`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `remedial_assessment_scores` WRITE;
/*!40000 ALTER TABLE `remedial_assessment_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `remedial_assessment_scores` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `remedial_assessments`
--

DROP TABLE IF EXISTS `remedial_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `remedial_assessments` (
  `assessment_id` char(36) NOT NULL,
  `room_id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `linked_test_id` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `co_structure` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`co_structure`)),
  `max_marks` int(11) NOT NULL,
  `questions_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`questions_payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`assessment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remedial_assessments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `remedial_assessments` WRITE;
/*!40000 ALTER TABLE `remedial_assessments` DISABLE KEYS */;
/*!40000 ALTER TABLE `remedial_assessments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `remedial_rooms`
--

DROP TABLE IF EXISTS `remedial_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `remedial_rooms` (
  `room_id` char(36) NOT NULL,
  `classroom_id` char(36) NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `created_by_mobile` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remedial_rooms`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `remedial_rooms` WRITE;
/*!40000 ALTER TABLE `remedial_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `remedial_rooms` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `remedial_session_logs`
--

DROP TABLE IF EXISTS `remedial_session_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `remedial_session_logs` (
  `log_id` char(36) NOT NULL,
  `room_id` char(36) NOT NULL,
  `session_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `topic_covered` varchar(255) DEFAULT NULL,
  `attendance_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attendance_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remedial_session_logs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `remedial_session_logs` WRITE;
/*!40000 ALTER TABLE `remedial_session_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `remedial_session_logs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `remedial_students`
--

DROP TABLE IF EXISTS `remedial_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `remedial_students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` char(36) NOT NULL,
  `reg_no` varchar(255) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `remedial_students_room_id_reg_no_unique` (`room_id`,`reg_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remedial_students`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `remedial_students` WRITE;
/*!40000 ALTER TABLE `remedial_students` DISABLE KEYS */;
/*!40000 ALTER TABLE `remedial_students` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sbte_audit_documents`
--

DROP TABLE IF EXISTS `sbte_audit_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sbte_audit_documents` (
  `id` char(36) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `document_name` varchar(150) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Pending',
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sbte_audit_documents`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sbte_audit_documents` WRITE;
/*!40000 ALTER TABLE `sbte_audit_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `sbte_audit_documents` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sbte_department_audits`
--

DROP TABLE IF EXISTS `sbte_department_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sbte_department_audits` (
  `id` char(36) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `nba_accredited` tinyint(1) NOT NULL DEFAULT 0,
  `enrollment_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`enrollment_data`)),
  `academic_perf_no_backlog` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`academic_perf_no_backlog`)),
  `academic_perf_with_backlog` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`academic_perf_with_backlog`)),
  `placement_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`placement_data`)),
  `professional_activities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`professional_activities`)),
  `sfr_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sfr_data`)),
  `infrastructure_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`infrastructure_data`)),
  `vision_mission_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vision_mission_data`)),
  `teaching_learning_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`teaching_learning_data`)),
  `course_files_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`course_files_data`)),
  `faculty_training_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`faculty_training_data`)),
  `fdp_conducted_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fdp_conducted_data`)),
  `consultancy_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`consultancy_data`)),
  `achievements_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`achievements_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sbte_department_audits_academic_year_branch_unique` (`academic_year`,`branch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sbte_department_audits`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sbte_department_audits` WRITE;
/*!40000 ALTER TABLE `sbte_department_audits` DISABLE KEYS */;
/*!40000 ALTER TABLE `sbte_department_audits` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `seminar_acceptances`
--

DROP TABLE IF EXISTS `seminar_acceptances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seminar_acceptances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seminar_registration_id` bigint(20) unsigned NOT NULL,
  `staff_mobile_no` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'accepted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sem_reg_staff_unique` (`seminar_registration_id`,`staff_mobile_no`),
  KEY `seminar_acceptances_staff_mobile_no_foreign` (`staff_mobile_no`),
  CONSTRAINT `seminar_acceptances_seminar_registration_id_foreign` FOREIGN KEY (`seminar_registration_id`) REFERENCES `student_seminar_registrations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seminar_acceptances_staff_mobile_no_foreign` FOREIGN KEY (`staff_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seminar_acceptances`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `seminar_acceptances` WRITE;
/*!40000 ALTER TABLE `seminar_acceptances` DISABLE KEYS */;
/*!40000 ALTER TABLE `seminar_acceptances` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `seminar_evaluations`
--

DROP TABLE IF EXISTS `seminar_evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seminar_evaluations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `assessor_mobile_no` varchar(50) NOT NULL,
  `relevance` decimal(5,2) NOT NULL DEFAULT 0.00,
  `literature` decimal(5,2) NOT NULL DEFAULT 0.00,
  `presentation` decimal(5,2) NOT NULL DEFAULT 0.00,
  `interaction` decimal(5,2) NOT NULL DEFAULT 0.00,
  `report` decimal(5,2) NOT NULL DEFAULT 0.00,
  `attendance` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seminar_eval_unique` (`batch_subject_id`,`reg_no`,`assessor_mobile_no`),
  KEY `seminar_evaluations_reg_no_foreign` (`reg_no`),
  KEY `seminar_evaluations_assessor_mobile_no_foreign` (`assessor_mobile_no`),
  CONSTRAINT `seminar_evaluations_assessor_mobile_no_foreign` FOREIGN KEY (`assessor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE,
  CONSTRAINT `seminar_evaluations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seminar_evaluations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seminar_evaluations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `seminar_evaluations` WRITE;
/*!40000 ALTER TABLE `seminar_evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `seminar_evaluations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `series_exams`
--

DROP TABLE IF EXISTS `series_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `series_exams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `co_tags` text DEFAULT NULL,
  `max_marks` int(11) NOT NULL DEFAULT 25,
  `duration_minutes` int(11) NOT NULL DEFAULT 60,
  `questions` longtext DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `series_exams_batch_subject_id_index` (`batch_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series_exams`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `series_exams` WRITE;
/*!40000 ALTER TABLE `series_exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `series_exams` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sf_campus_geofence_settings`
--

DROP TABLE IF EXISTS `sf_campus_geofence_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sf_campus_geofence_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campus_name` varchar(100) NOT NULL DEFAULT 'Carmel Main Campus',
  `centroid_lat` decimal(10,8) NOT NULL DEFAULT 10.23120000,
  `centroid_lng` decimal(11,8) NOT NULL DEFAULT 76.20450000,
  `radius_meters` int(11) NOT NULL DEFAULT 150,
  `max_accuracy_meters` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_campus_geofence_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sf_campus_geofence_settings` WRITE;
/*!40000 ALTER TABLE `sf_campus_geofence_settings` DISABLE KEYS */;
INSERT INTO `sf_campus_geofence_settings` VALUES
(1,'Carmel polytechnic College Campus punapra',9.43727187,76.34358649,80,30,1,'2026-08-10 18:08:51','2026-08-11 19:46:45');
/*!40000 ALTER TABLE `sf_campus_geofence_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sf_staff_face_registrations`
--

DROP TABLE IF EXISTS `sf_staff_face_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sf_staff_face_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(50) NOT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `staff_name` varchar(150) DEFAULT NULL,
  `face_descriptor` longtext NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sf_staff_face_registrations_staff_id_unique` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_staff_face_registrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sf_staff_face_registrations` WRITE;
/*!40000 ALTER TABLE `sf_staff_face_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `sf_staff_face_registrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sf_staff_time_punches`
--

DROP TABLE IF EXISTS `sf_staff_time_punches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sf_staff_time_punches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(50) NOT NULL,
  `staff_name` varchar(150) DEFAULT NULL,
  `punch_date` date NOT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `in_gps_lat` decimal(10,8) DEFAULT NULL,
  `in_gps_lng` decimal(11,8) DEFAULT NULL,
  `in_gps_distance_meters` int(11) DEFAULT NULL,
  `in_premises_status` enum('INSIDE_PREMISES','OUTSIDE_PREMISES') NOT NULL DEFAULT 'INSIDE_PREMISES',
  `out_gps_lat` decimal(10,8) DEFAULT NULL,
  `out_gps_lng` decimal(11,8) DEFAULT NULL,
  `out_gps_distance_meters` int(11) DEFAULT NULL,
  `out_premises_status` enum('INSIDE_PREMISES','OUTSIDE_PREMISES') NOT NULL DEFAULT 'INSIDE_PREMISES',
  `liveness_type` varchar(20) NOT NULL DEFAULT 'SMILE',
  `liveness_score` decimal(5,2) NOT NULL DEFAULT 0.85,
  `biometric_confidence` decimal(5,2) NOT NULL DEFAULT 95.00,
  `punch_status` varchar(100) DEFAULT 'PRESENT',
  `in_snapshot_url` varchar(255) DEFAULT NULL,
  `out_snapshot_url` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sf_staff_daily_punch_unique` (`staff_id`,`punch_date`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf_staff_time_punches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sf_staff_time_punches` WRITE;
/*!40000 ALTER TABLE `sf_staff_time_punches` DISABLE KEYS */;
/*!40000 ALTER TABLE `sf_staff_time_punches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `staff_leave_requests`
--

DROP TABLE IF EXISTS `staff_leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_leave_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `leave_code` varchar(30) NOT NULL,
  `staff_mobile` varchar(20) NOT NULL,
  `staff_name` varchar(150) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `session_type` varchar(20) NOT NULL DEFAULT 'Full Day',
  `ccl_date` date DEFAULT NULL,
  `total_days` decimal(4,1) NOT NULL DEFAULT 1.0,
  `reason` text NOT NULL,
  `work_arrangement` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`work_arrangement`)),
  `staff_signature_hash` varchar(100) DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `hod_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `hod_mobile` varchar(20) DEFAULT NULL,
  `hod_name` varchar(150) DEFAULT NULL,
  `hod_remarks` text DEFAULT NULL,
  `hod_action_at` datetime DEFAULT NULL,
  `coordinator_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `coordinator_mobile` varchar(20) DEFAULT NULL,
  `coordinator_name` varchar(150) DEFAULT NULL,
  `coordinator_remarks` text DEFAULT NULL,
  `coordinator_action_at` datetime DEFAULT NULL,
  `principal_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `principal_mobile` varchar(20) DEFAULT NULL,
  `principal_name` varchar(150) DEFAULT NULL,
  `principal_remarks` text DEFAULT NULL,
  `principal_action_at` datetime DEFAULT NULL,
  `overall_status` varchar(30) NOT NULL DEFAULT 'Pending_HOD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_leave_requests_leave_code_unique` (`leave_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_leave_requests`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `staff_leave_requests` WRITE;
/*!40000 ALTER TABLE `staff_leave_requests` DISABLE KEYS */;
INSERT INTO `staff_leave_requests` VALUES
(1,'SLV-2026-GL5YNF','9947666371','Dhanush .A','Lecturer','EL','Casual Leave','2026-08-04','2026-08-04','FN',NULL,1.0,'personal','[]','99aea6f4c37c902b5f71d14a9ec7d725cec16392f06f98bff4e4346cea93887c','2026-08-04 17:42:35','Pending',NULL,NULL,NULL,NULL,'Pending',NULL,NULL,NULL,NULL,'Pending',NULL,NULL,NULL,NULL,'Pending_HOD','2026-08-04 17:42:35','2026-08-04 17:42:35');
/*!40000 ALTER TABLE `staff_leave_requests` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `staff_professional_activities`
--

DROP TABLE IF EXISTS `staff_professional_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_professional_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lecturer_mobile_no` varchar(15) NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`details`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_professional_activities_lecturer_mobile_no_foreign` (`lecturer_mobile_no`),
  CONSTRAINT `staff_professional_activities_lecturer_mobile_no_foreign` FOREIGN KEY (`lecturer_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_professional_activities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `staff_professional_activities` WRITE;
/*!40000 ALTER TABLE `staff_professional_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_professional_activities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `staff_profiles`
--

DROP TABLE IF EXISTS `staff_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile_no` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo_url` text DEFAULT NULL,
  `account_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_profiles_mobile_no_unique` (`mobile_no`),
  UNIQUE KEY `staff_profiles_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_profiles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `staff_profiles` WRITE;
/*!40000 ALTER TABLE `staff_profiles` DISABLE KEYS */;
INSERT INTO `staff_profiles` VALUES
(1,'9000000000','Super Admin User','superadmin@carmelpoly.in','Administration','Super_Admin','admin123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),
(2,'9946847236','Fr. Antony Varghese CMI','principal@carmelpoly.in','Administration','Principal','admin123','/storage/avatars/AaUzdTfE94X6WwlzcsKd2AK2US75qxB6Cwkrw4M6.jpg','Approved','2026-06-21 10:48:14','2026-08-11 16:45:59'),
(5,'9000000004','Academic Coordinator','admin@carmelpoly.in','Administration','Admin','admin123',NULL,'Approved','2026-06-21 10:48:14','2026-08-09 19:59:02'),
(94,'9000000002','System Admin','sysadmin@carmelpoly.in','Administration','Admin','admin123',NULL,'Approved','2026-08-02 10:24:02','2026-08-02 10:24:02'),
(95,'9947666371','Dhanush .A','adhanush@gmail.com','EL','Lecturer','123456','/storage/avatars/q708Cv5QFJ1UOO5GJfFo8PReGWt86nRx3bKzIIvj.png','Approved','2026-08-02 19:06:16','2026-08-09 20:18:05'),
(98,'9999999999','Chairman','chairman@carmelpoly.in','Administration','Admin','123456','/storage/avatars/24bIaE98pF4bgA84YCFaSTDhMa1E5tFSiQDB8VTx.jpg','Approved','2026-08-09 00:03:27','2026-08-09 19:58:55'),
(99,'9349186555','Amalu Mariya Joseph','amalu.mariya@carmelpoly.in','CE','Demonstrator','12#December',NULL,'Approved','2026-08-11 15:56:04','2026-08-11 16:11:09'),
(100,'9497336713','Rajesh. P. V','rajesh.p@carmelpoly.in','CE','Lecturer','Carmel13@',NULL,'Approved','2026-08-11 15:56:27','2026-08-11 16:11:47'),
(101,'9446449292','RAKHI V R','rakhvinodrv@gmail.com','GEN_AIDED','Lecturer','AyapaN@2','/storage/avatars/Vdc4FmOIKFTg2DLKYWUGDCkoBiSDJJ4h2Hxq6iHR.jpg','Approved','2026-08-11 15:56:48','2026-08-11 19:31:31'),
(102,'8943850834','Meenu M CE','meenu.m@carmelpoly.in','CE','Demonstrator','Sivasakthy@93','/storage/avatars/I7iRPfQbWPa4A41840Iy3DiRWnfsEVWJW7QrkR9M.jpg','Approved','2026-08-11 15:57:35','2026-08-11 16:11:37'),
(103,'9400087440','Fr siji thomas p t','siji.thomas@carmelpoly.in','CE','HOD','dOiWYwEq',NULL,'Approved','2026-08-11 15:58:06','2026-08-11 16:11:19'),
(104,'8281336943','Sita S','sita.s@carmelpoly.in','GEN_AIDED','Gen_Dept_Coordinator_Aided','sita@CARMEL00',NULL,'Approved','2026-08-11 15:58:47','2026-08-11 16:43:16'),
(105,'9895527950','Bijo M D','bijo.md@carmelpoly.in','CE','Lecturer','Bijo@1983',NULL,'Approved','2026-08-11 17:23:28','2026-08-11 17:42:36'),
(106,'9400524401','ANTONY VARGHESE','antony.varghese@carmelpoly.in','CE','HOD','Carmel@123','/storage/avatars/2gViPCg514UX4urc9tnOJPAqqaTvvRZUj0DXTZAw.png','Approved','2026-08-11 21:50:09','2026-08-12 00:12:26');
/*!40000 ALTER TABLE `staff_profiles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_attendance`
--

DROP TABLE IF EXISTS `student_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_attendance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late') NOT NULL DEFAULT 'Present',
  `sub_batch` varchar(10) NOT NULL DEFAULT 'Whole',
  `lesson_plan_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_attendance_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_attendance_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_attendance`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_attendance` WRITE;
/*!40000 ALTER TABLE `student_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_attendance` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_board_grades`
--

DROP TABLE IF EXISTS `student_board_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_board_grades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `exam_month_year` varchar(50) DEFAULT NULL,
  `chances_taken` int(11) NOT NULL DEFAULT 1,
  `internal_marks` int(11) DEFAULT NULL,
  `external_marks` int(11) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `passed` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `student_board_grades_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_board_grades_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_board_grades`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_board_grades` WRITE;
/*!40000 ALTER TABLE `student_board_grades` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_board_grades` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_course_exit_responses`
--

DROP TABLE IF EXISTS `student_course_exit_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_course_exit_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exit_survey_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(15) NOT NULL,
  `co1_q1` tinyint(4) NOT NULL,
  `co1_q2` tinyint(4) NOT NULL,
  `co2_q3` tinyint(4) NOT NULL,
  `co2_q4` tinyint(4) NOT NULL,
  `co3_q5` tinyint(4) NOT NULL,
  `co3_q6` tinyint(4) NOT NULL,
  `co4_q7` tinyint(4) NOT NULL,
  `co4_q8` tinyint(4) NOT NULL,
  `co4_q9` tinyint(4) NOT NULL,
  `co_overall_q10` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_course_exit_responses_exit_survey_id_foreign` (`exit_survey_id`),
  CONSTRAINT `student_course_exit_responses_exit_survey_id_foreign` FOREIGN KEY (`exit_survey_id`) REFERENCES `course_exit_surveys` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_course_exit_responses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_course_exit_responses` WRITE;
/*!40000 ALTER TABLE `student_course_exit_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_course_exit_responses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_family_details`
--

DROP TABLE IF EXISTS `student_family_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_family_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `education` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_family_details_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_family_details_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_family_details`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_family_details` WRITE;
/*!40000 ALTER TABLE `student_family_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_family_details` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_fee_records`
--

DROP TABLE IF EXISTS `student_fee_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_fee_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `fees_to_pay` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `date_paid` date DEFAULT NULL,
  `total_paid` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_fee_records_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_fee_records_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_fee_records`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_fee_records` WRITE;
/*!40000 ALTER TABLE `student_fee_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_fee_records` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_material_read_receipts`
--

DROP TABLE IF EXISTS `student_material_read_receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_material_read_receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `material_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `smrr_material_reg_unique` (`material_id`,`reg_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_material_read_receipts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_material_read_receipts` WRITE;
/*!40000 ALTER TABLE `student_material_read_receipts` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_material_read_receipts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_mentoring_profiles`
--

DROP TABLE IF EXISTS `student_mentoring_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_mentoring_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(255) NOT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `caste` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `special_category` varchar(255) DEFAULT NULL,
  `reservation` varchar(255) DEFAULT NULL,
  `quota` varchar(255) DEFAULT NULL,
  `is_physically_disabled` tinyint(1) NOT NULL DEFAULT 0,
  `disability_category` varchar(255) DEFAULT NULL,
  `guardian_occupation` varchar(255) DEFAULT NULL,
  `monthly_family_income` varchar(255) DEFAULT NULL,
  `has_vehicle_pass` tinyint(1) NOT NULL DEFAULT 0,
  `vehicle_pass_id` varchar(255) DEFAULT NULL,
  `communication_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_mentoring_profiles_reg_no_unique` (`reg_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_mentoring_profiles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_mentoring_profiles` WRITE;
/*!40000 ALTER TABLE `student_mentoring_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_mentoring_profiles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_mock_test_attempts`
--

DROP TABLE IF EXISTS `student_mock_test_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_mock_test_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(15) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `attempted_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_mock_limit_idx` (`reg_no`,`subject_code`,`attempted_date`),
  CONSTRAINT `student_mock_test_attempts_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_mock_test_attempts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_mock_test_attempts` WRITE;
/*!40000 ALTER TABLE `student_mock_test_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_mock_test_attempts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_prior_education`
--

DROP TABLE IF EXISTS `student_prior_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_prior_education` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `institution` varchar(150) NOT NULL,
  `year_of_completion` varchar(10) DEFAULT NULL,
  `maths_marks` varchar(20) DEFAULT NULL,
  `physics_marks` varchar(20) DEFAULT NULL,
  `chemistry_marks` varchar(20) DEFAULT NULL,
  `total_percentage` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_prior_education_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_prior_education_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_prior_education`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_prior_education` WRITE;
/*!40000 ALTER TABLE `student_prior_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_prior_education` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_responses`
--

DROP TABLE IF EXISTS `student_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_responses` (
  `response_id` char(36) NOT NULL DEFAULT uuid(),
  `reg_no` varchar(50) NOT NULL,
  `test_id` char(36) NOT NULL,
  `question_id` char(36) NOT NULL,
  `selected_option` varchar(10) DEFAULT NULL,
  `descriptive_text` text DEFAULT NULL,
  `marks_obtained` decimal(5,2) NOT NULL DEFAULT 0.00,
  `evaluated_by` varchar(15) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`response_id`),
  UNIQUE KEY `unique_student_response` (`reg_no`,`test_id`,`question_id`),
  KEY `student_responses_test_id_foreign` (`test_id`),
  KEY `student_responses_question_id_foreign` (`question_id`),
  KEY `student_responses_evaluated_by_foreign` (`evaluated_by`),
  KEY `student_responses_reg_no_test_id_index` (`reg_no`,`test_id`),
  CONSTRAINT `student_responses_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `student_responses_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `question_bank` (`question_id`) ON DELETE CASCADE,
  CONSTRAINT `student_responses_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `student_responses_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `test_configs` (`test_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_responses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_responses` WRITE;
/*!40000 ALTER TABLE `student_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_responses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_semester_marks`
--

DROP TABLE IF EXISTS `student_semester_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_semester_marks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `internal_marks` decimal(5,2) DEFAULT NULL,
  `board_marks` decimal(5,2) DEFAULT NULL,
  `total_marks` decimal(5,2) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `attendance_percentage` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_semester_marks_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_semester_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_semester_marks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_semester_marks` WRITE;
/*!40000 ALTER TABLE `student_semester_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_semester_marks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_semester_summary`
--

DROP TABLE IF EXISTS `student_semester_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_semester_summary` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `semester` int(11) NOT NULL,
  `sgpa` decimal(4,2) DEFAULT NULL,
  `cgpa` decimal(4,2) DEFAULT NULL,
  `activity_points` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_semester_summary_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_semester_summary_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_semester_summary`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_semester_summary` WRITE;
/*!40000 ALTER TABLE `student_semester_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_semester_summary` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_seminar_registrations`
--

DROP TABLE IF EXISTS `student_seminar_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_seminar_registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `presentation_date` date NOT NULL,
  `guide_mobile_no` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_seminar_registrations_reg_no_unique` (`reg_no`),
  KEY `student_seminar_registrations_batch_subject_id_foreign` (`batch_subject_id`),
  KEY `student_seminar_registrations_guide_mobile_no_foreign` (`guide_mobile_no`),
  CONSTRAINT `student_seminar_registrations_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_seminar_registrations_guide_mobile_no_foreign` FOREIGN KEY (`guide_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE,
  CONSTRAINT `student_seminar_registrations_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_seminar_registrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_seminar_registrations` WRITE;
/*!40000 ALTER TABLE `student_seminar_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_seminar_registrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_survey_responses`
--

DROP TABLE IF EXISTS `student_survey_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_survey_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` bigint(20) unsigned NOT NULL,
  `reg_no` varchar(15) NOT NULL,
  `pace_score` tinyint(4) DEFAULT NULL,
  `clarity_score` tinyint(4) DEFAULT NULL,
  `interaction_score` tinyint(4) DEFAULT NULL,
  `practicality_score` tinyint(4) DEFAULT NULL,
  `evaluation_score` tinyint(4) DEFAULT NULL,
  `q5_co_communication` tinyint(4) DEFAULT NULL COMMENT 'Q5: Teacher communicates COs and learning goals. 1-3',
  `q6_syllabus_pace` tinyint(4) DEFAULT NULL COMMENT 'Q6: Pace/speed/coverage of syllabus appropriate. 1-3',
  `q7_concept_clarity` tinyint(4) DEFAULT NULL COMMENT 'Q7: Complex concepts clarity + real-world links. 1-3',
  `q8_teaching_tools` tinyint(4) DEFAULT NULL COMMENT 'Q8: Use of PPTs, ICT, animations, demos. 1-3',
  `q9_student_interaction` tinyint(4) DEFAULT NULL COMMENT 'Q9: Encourages questions, manages doubts patiently. 1-3',
  `q10_assessment_alignment` tinyint(4) DEFAULT NULL COMMENT 'Q10: Assessment questions match topics taught. 1-3',
  `q11_evaluation_fairness` tinyint(4) DEFAULT NULL COMMENT 'Q11: Evaluation is fair, timely, transparent. 1-3',
  `q12_slow_learner_support` tinyint(4) DEFAULT NULL COMMENT 'Q12: Remedial guidance for slow learners. 1-3',
  `q13_branch_specific` tinyint(4) DEFAULT NULL COMMENT 'Q13: Branch lab/practical demonstration effectiveness. 1-3 (optional)',
  `q17_difficult_topics` text DEFAULT NULL COMMENT 'Q17: Topics found most difficult',
  `q18_suggestions` text DEFAULT NULL COMMENT 'Q18: Suggestions to improve delivery',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_survey_responses_survey_id_reg_no_unique` (`survey_id`,`reg_no`),
  KEY `student_survey_responses_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_survey_responses_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `student_survey_responses_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `mid_semester_surveys` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_survey_responses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_survey_responses` WRITE;
/*!40000 ALTER TABLE `student_survey_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_survey_responses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `student_task_submissions`
--

DROP TABLE IF EXISTS `student_task_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_task_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(15) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'Assignment',
  `co_tag` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Submitted',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_task_submissions_reg_no_foreign` (`reg_no`),
  CONSTRAINT `student_task_submissions_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_task_submissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `student_task_submissions` WRITE;
/*!40000 ALTER TABLE `student_task_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_task_submissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `reg_no` varchar(50) NOT NULL,
  `adm_no` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `branch` varchar(50) NOT NULL,
  `admission_year` int(11) NOT NULL,
  `admission_type` varchar(50) NOT NULL DEFAULT 'Regular',
  `photo_url` text DEFAULT NULL,
  `roll_no` int(11) DEFAULT NULL,
  `classroom_id` varchar(50) DEFAULT NULL,
  `semester` int(11) NOT NULL DEFAULT 1,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `academic_status` varchar(50) NOT NULL DEFAULT 'Active',
  `status_notes` text DEFAULT NULL,
  `placement_details` text DEFAULT NULL,
  `higher_studies_remark` text DEFAULT NULL,
  `sbte_reg_no` varchar(50) DEFAULT NULL,
  `mentor_mobile_no` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `annual_income` varchar(50) DEFAULT NULL,
  `residential_status` enum('Day Scholar','Hosteller') NOT NULL DEFAULT 'Day Scholar',
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_address` text DEFAULT NULL,
  `guardian_relationship` varchar(50) DEFAULT NULL,
  `guardian_mobile` varchar(20) DEFAULT NULL,
  `scholarships` text DEFAULT NULL,
  `is_fee_waiver` tinyint(1) NOT NULL DEFAULT 0,
  `profile_verified_at` timestamp NULL DEFAULT NULL,
  `profile_verified_by` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`reg_no`),
  UNIQUE KEY `students_adm_no_unique` (`adm_no`),
  UNIQUE KEY `students_email_unique` (`email`),
  KEY `students_mentor_mobile_no_foreign` (`mentor_mobile_no`),
  KEY `students_classroom_id_index` (`classroom_id`),
  KEY `students_profile_verified_by_foreign` (`profile_verified_by`),
  KEY `students_roll_no_index` (`roll_no`),
  CONSTRAINT `students_mentor_mobile_no_foreign` FOREIGN KEY (`mentor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `students_profile_verified_by_foreign` FOREIGN KEY (`profile_verified_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `subject_staff_assignments`
--

DROP TABLE IF EXISTS `subject_staff_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subject_staff_assignments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `staff_mobile_no` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_staff_assignments_batch_subject_id_foreign` (`batch_subject_id`),
  KEY `subject_staff_assignments_staff_mobile_no_foreign` (`staff_mobile_no`),
  CONSTRAINT `subject_staff_assignments_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_staff_assignments_staff_mobile_no_foreign` FOREIGN KEY (`staff_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_staff_assignments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `subject_staff_assignments` WRITE;
/*!40000 ALTER TABLE `subject_staff_assignments` DISABLE KEYS */;
INSERT INTO `subject_staff_assignments` VALUES
(2,3,'9446449292','2026-08-11 16:32:19','2026-08-11 16:32:19'),
(3,7,'9497336713','2026-08-11 21:36:58','2026-08-11 21:36:58'),
(4,9,'8281336943','2026-08-11 21:37:32','2026-08-11 21:37:32'),
(5,8,'9349186555','2026-08-11 21:41:04','2026-08-11 21:41:04'),
(7,5,'9400524401','2026-08-11 21:57:33','2026-08-11 21:57:33');
/*!40000 ALTER TABLE `subject_staff_assignments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `syllabus_registry`
--

DROP TABLE IF EXISTS `syllabus_registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `syllabus_registry` (
  `subject_code` varchar(50) NOT NULL,
  `revision_year` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `co_count` int(11) NOT NULL DEFAULT 6,
  `cis_pdf_path` varchar(255) DEFAULT NULL,
  `co_po_mapping` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`co_po_mapping`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subject_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `syllabus_registry`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `syllabus_registry` WRITE;
/*!40000 ALTER TABLE `syllabus_registry` DISABLE KEYS */;
INSERT INTO `syllabus_registry` VALUES
('5013',2021,'Transportation Engineering',4,NULL,NULL,'2026-08-11 22:16:01','2026-08-11 22:16:01'),
('EL-5041',2026,'Embedded Systems',4,'uploads/cis/1782398229_EL-5041_CIS.pdf','[{\"co\":\"CO1\",\"description\":\"Explain the basics of embedded systems and its architecture\",\"po1\":2,\"po2\":\"\",\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":2,\"pso2\":\"\",\"pso3\":\"\"},{\"co\":\"CO2\",\"description\":\"Make use of AVR Microcontrollers to develop embedded programs using embedded C\",\"po1\":3,\"po2\":3,\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":\"\",\"pso2\":\"\",\"pso3\":\"\"},{\"co\":\"CO3\",\"description\":\"Make use of AVR microcontroller to interface with various peripheral devices.\",\"po1\":3,\"po2\":3,\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":\"\",\"pso2\":\"\",\"pso3\":3},{\"co\":\"CO4\",\"description\":\"Familiarize RTOS\",\"po1\":3,\"po2\":\"\",\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":\"\",\"pso2\":\"\",\"pso3\":\"\"}]','2026-06-22 12:24:48','2026-06-25 14:37:09');
/*!40000 ALTER TABLE `syllabus_registry` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES
('ai_generation_enabled','0','2026-07-26 23:06:15','2026-08-04 15:37:06');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `test_attempts`
--

DROP TABLE IF EXISTS `test_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_attempts` (
  `attempt_id` char(36) NOT NULL DEFAULT uuid(),
  `reg_no` varchar(50) NOT NULL,
  `test_id` char(36) NOT NULL,
  `attempt_number` int(11) NOT NULL DEFAULT 1,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `total_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'in_progress',
  `responses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`responses`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`attempt_id`),
  KEY `test_attempts_reg_no_foreign` (`reg_no`),
  KEY `test_attempts_test_id_foreign` (`test_id`),
  CONSTRAINT `test_attempts_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `test_attempts_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `test_configs` (`test_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_attempts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `test_attempts` WRITE;
/*!40000 ALTER TABLE `test_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_attempts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `test_configs`
--

DROP TABLE IF EXISTS `test_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_configs` (
  `test_id` char(36) NOT NULL DEFAULT uuid(),
  `subject_code` varchar(50) NOT NULL,
  `classroom_id` varchar(50) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `selected_cos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`selected_cos`)),
  `mcq_count` int(11) NOT NULL DEFAULT 0,
  `descriptive_count` int(11) NOT NULL DEFAULT 0,
  `target_percentage` int(11) NOT NULL DEFAULT 50,
  `pass_threshold` int(11) NOT NULL DEFAULT 40,
  `max_attempts` int(11) NOT NULL DEFAULT 1,
  `is_auto_scheduled` tinyint(1) NOT NULL DEFAULT 0,
  `questions_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`questions_payload`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`test_id`),
  KEY `test_configs_subject_code_foreign` (`subject_code`),
  KEY `test_configs_classroom_id_index` (`classroom_id`),
  CONSTRAINT `test_configs_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `syllabus_registry` (`subject_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_configs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `test_configs` WRITE;
/*!40000 ALTER TABLE `test_configs` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_configs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tutor_diaries`
--

DROP TABLE IF EXISTS `tutor_diaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tutor_diaries` (
  `diary_id` char(36) NOT NULL DEFAULT uuid(),
  `reg_no` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(100) NOT NULL,
  `discussion_notes` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `logged_by` varchar(15) DEFAULT NULL,
  `entry_source` enum('Staff','Student') NOT NULL DEFAULT 'Staff',
  `approval_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Approved',
  `approved_by` varchar(15) DEFAULT NULL,
  `student_remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`diary_id`),
  KEY `tutor_diaries_logged_by_foreign` (`logged_by`),
  KEY `tutor_diaries_reg_no_index` (`reg_no`),
  KEY `tutor_diaries_approved_by_foreign` (`approved_by`),
  CONSTRAINT `tutor_diaries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `tutor_diaries_logged_by_foreign` FOREIGN KEY (`logged_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `tutor_diaries_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutor_diaries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tutor_diaries` WRITE;
/*!40000 ALTER TABLE `tutor_diaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `tutor_diaries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `virtual_learning_materials`
--

DROP TABLE IF EXISTS `virtual_learning_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `virtual_learning_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_subject_id` bigint(20) unsigned NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `classroom_id` varchar(50) NOT NULL,
  `room_type` enum('Theory','Practical','Practicum','Drawing') NOT NULL DEFAULT 'Theory',
  `experiment_or_topic_no` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pre_class_instruction` text DEFAULT NULL,
  `material_type` enum('pdf','video','image','document','link') NOT NULL DEFAULT 'pdf',
  `file_path` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `is_pre_class_notice` tinyint(1) NOT NULL DEFAULT 1,
  `target_date` date DEFAULT NULL,
  `uploaded_by` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vlm_subject_topic_idx` (`batch_subject_id`,`experiment_or_topic_no`),
  KEY `vlm_class_date_idx` (`classroom_id`,`target_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtual_learning_materials`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `virtual_learning_materials` WRITE;
/*!40000 ALTER TABLE `virtual_learning_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `virtual_learning_materials` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-11 19:14:43
