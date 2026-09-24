-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: carmel_linx_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academic_marks`
--

DROP TABLE IF EXISTS `academic_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academic_marks` (
  `mark_id` char(36) NOT NULL DEFAULT uuid(),
  `reg_no` varchar(50) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
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
  CONSTRAINT `academic_marks_entered_by_foreign` FOREIGN KEY (`entered_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `academic_marks_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE,
  CONSTRAINT `academic_marks_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `syllabus_registry` (`subject_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_marks`
--

LOCK TABLES `academic_marks` WRITE;
/*!40000 ALTER TABLE `academic_marks` DISABLE KEYS */;
INSERT INTO `academic_marks` VALUES ('5113885a-710c-11f1-931c-283926634940','25EL1001','EL-5041','Online Test','CO2',3,2.00,NULL,NULL,'2026-06-26 03:08:16'),('79c504c9-6fe6-11f1-931c-283926634940','25EL1001','EL-5041','Online Test','CO3',3,0.00,NULL,NULL,'2026-06-24 16:05:10'),('8d3d652d-6fc6-11f1-a16f-283926634940','25EL1001','EL-5041','Online Test','CO4',10,2.00,NULL,NULL,'2026-06-26 03:05:22'),('a215ef7a-f1b5-4cc4-bbb6-a91bb67b635e','25EL1001','EL-5041','Assignment','CO1',10,5.00,NULL,'2026-06-22 12:25:58','2026-06-22 12:25:58'),('a215ef7a-f4fc-4637-b9ad-21675fb2acb5','25EL1001','EL-5041','Assignment','CO2',10,6.00,NULL,'2026-06-22 12:25:58','2026-06-22 12:25:58'),('a215ef7a-f653-462c-80ef-dc9b9cb43a54','25EL1001','EL-5041','Assignment','CO3',10,7.00,NULL,'2026-06-22 12:25:58','2026-06-22 12:25:58'),('a215ef7a-f741-46e5-bd99-4a069f8d5b3a','25EL1001','EL-5041','Assignment','CO4',10,2.00,NULL,'2026-06-22 12:25:58','2026-06-22 12:25:58'),('a217c6f4-634a-45fd-a0ff-b4e308d3f278','25EL1001','EL-5041','Written Test','CO1',20,10.00,NULL,'2026-06-23 10:24:18','2026-06-23 19:58:54'),('a2189474-21ce-4201-9d25-4426683aec19','25EL1001','EL-5041','Written Test','CO3',50,6.00,NULL,'2026-06-23 19:58:55','2026-06-23 19:58:55'),('a2189474-229c-4199-a121-656f81826f5b','25EL1001','EL-5041','Written Test','CO4',50,10.00,NULL,'2026-06-23 19:58:55','2026-06-23 19:58:55');
/*!40000 ALTER TABLE `academic_marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_point_claims`
--

DROP TABLE IF EXISTS `activity_point_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `activity_point_claims` WRITE;
/*!40000 ALTER TABLE `activity_point_claims` DISABLE KEYS */;
INSERT INTO `activity_point_claims` VALUES ('2c016a46-3215-45f6-ad07-b1abf9edfef0','25EL1001',3,'Sports & Games','District Level FIrst','Level III - State/Univ',4,4,'Verified',NULL,NULL,'9100000001','2026-06-26 14:29:09','2026-06-26 03:11:50','2026-06-26 14:29:09'),('720975c8-bcbc-4e95-9684-0c9cd672568a','25EL1001',1,'NCC','District Level FIrst','Level II - Zonal',5,0,'Rejected',NULL,NULL,'9100000001','2026-06-26 14:29:13','2026-06-24 18:13:05','2026-06-26 14:29:13'),('849f006a-59a0-48a9-8c16-b633dd9b3aca','25EL1001',3,'Leadership & Management','aciv','Level I - College',10,0,'Rejected','The category quota maximum is 3',NULL,'9100000001','2026-06-26 14:35:30','2026-06-26 14:34:35','2026-06-26 14:35:30'),('87aee8eb-535b-4987-b06d-291c8d667dc1','25EL1001',3,'Sports & Games','District Level FIrst','Level II - Zonal',5,3,'Verified',NULL,NULL,'9100000001','2026-06-28 12:17:58','2026-06-28 12:15:23','2026-06-28 12:17:58');
/*!40000 ALTER TABLE `activity_point_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,'9000000002','HOD Electronics','EL_2025_2028','Batch EL_2025_2028','Tutor Assigned','Tutor set to Tutor Electronics (9000000003). Previous: 9000000003','127.0.0.1','2026-06-21 20:24:13','2026-06-21 20:24:13'),(2,'9000000002','HOD Electronics','EL_2025_2028','Batch EL_2025_2028','Tutor Removed','Tutor removed. Previous: 9000000003','127.0.0.1','2026-06-21 20:32:29','2026-06-21 20:32:29'),(3,'9000000002','HOD Electronics','EL_2025_2028','Batch EL_2025_2028','Mentor Removed','Mentor removed. Previous: 9000000003','127.0.0.1','2026-06-21 20:32:37','2026-06-21 20:32:37'),(4,'9000000002','HOD Electronics','EL_2025_2028','Batch EL_2025_2028','Tutor Assigned','Tutor set to Lecturer 1 EL (9100000001). Previous: None','127.0.0.1','2026-06-21 20:43:14','2026-06-21 20:43:14'),(5,'9000000002','HOD Electronics','EL_2025_2028','Batch EL_2025_2028','Mentor Assigned','Mentor set to Demonstrator 1 EL (9100000006). Previous: None','127.0.0.1','2026-06-21 20:43:21','2026-06-21 20:43:21'),(6,'9000000002','HOD Electronics','EL_2026_2029','Batch EL_2026_2029','Batch Created','HOD created batch EL_2026_2029 for admission year 2026. Backfilled 0 student(s).','127.0.0.1','2026-06-26 11:31:42','2026-06-26 11:31:42'),(7,'System','Self Registration','26CE1001L','Fr. ANTONY','Registered','Student registration created with status: Pending','127.0.0.1','2026-06-26 12:30:41','2026-06-26 12:30:41'),(8,'System','Self Registration','26CE1000L','GAUTHAM','Registered','Student registration created with status: Pending','127.0.0.1','2026-06-26 12:41:26','2026-06-26 12:41:26'),(9,'6000000002','HOD Civil Engineering','CE_2026_2029','Batch CE_2026_2029','Batch Created','HOD created batch CE_2026_2029 for admission year 2026. Backfilled 2 student(s).','127.0.0.1','2026-06-26 13:11:15','2026-06-26 13:11:15');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_subjects`
--

DROP TABLE IF EXISTS `batch_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  KEY `batch_subjects_classroom_id_foreign` (`classroom_id`),
  CONSTRAINT `batch_subjects_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `class_management` (`classroom_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_subjects`
--

LOCK TABLES `batch_subjects` WRITE;
/*!40000 ALTER TABLE `batch_subjects` DISABLE KEYS */;
INSERT INTO `batch_subjects` VALUES (1,'EL_2025_2028',3,'EL-5041','EMBEDDED SYSTEMS','Theory','REV2021','2026-06-22 08:48:25','2026-06-22 08:48:25'),(2,'EL_2025_2028',1,'EL101','Basic Electronics','Theory','REV2021','2026-06-23 12:07:06','2026-06-23 12:07:06'),(3,'EL_2025_2028',1,'MA101','Mathematics I','Theory','REV2021','2026-06-23 12:07:06','2026-06-23 12:07:06'),(4,'EL_2025_2028',2,'EL102','Advanced Electronics','Theory','REV2021','2026-06-23 12:07:06','2026-06-23 12:07:06'),(5,'EL_2025_2028',3,'EL-3041','Electric Circuits','Theory','REV2021','2026-06-23 12:15:50','2026-06-23 12:15:50'),(6,'EL_2025_2028',3,'EL-3048','DIGITAL ELECTRONICS LAB','Practical / Lab','REV2021','2026-06-26 13:24:16','2026-06-26 13:24:16');
/*!40000 ALTER TABLE `batch_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_course_file_documents`
--

DROP TABLE IF EXISTS `cf_course_file_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `cf_course_file_documents` WRITE;
/*!40000 ALTER TABLE `cf_course_file_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `cf_course_file_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_course_files`
--

DROP TABLE IF EXISTS `cf_course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_course_files`
--

LOCK TABLES `cf_course_files` WRITE;
/*!40000 ALTER TABLE `cf_course_files` DISABLE KEYS */;
INSERT INTO `cf_course_files` VALUES (1,1,'2026-2027','Complete',NULL,'storage/course_files/CourseFile_EL-5041_1.pdf','2026-06-25 10:56:18','2026-06-28 13:24:29'),(2,5,'2026-2027','Draft',NULL,NULL,'2026-06-25 10:56:18','2026-06-25 10:56:18');
/*!40000 ALTER TABLE `cf_course_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_section_a_planning`
--

DROP TABLE IF EXISTS `cf_section_a_planning`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_a_planning`
--

LOCK TABLES `cf_section_a_planning` WRITE;
/*!40000 ALTER TABLE `cf_section_a_planning` DISABLE KEYS */;
INSERT INTO `cf_section_a_planning` VALUES (1,1,NULL,NULL,NULL,NULL,'2026-06-25 14:37:09','2026-06-25 14:37:09');
/*!40000 ALTER TABLE `cf_section_a_planning` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_section_b_materials`
--

DROP TABLE IF EXISTS `cf_section_b_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_b_materials`
--

LOCK TABLES `cf_section_b_materials` WRITE;
/*!40000 ALTER TABLE `cf_section_b_materials` DISABLE KEYS */;
INSERT INTO `cf_section_b_materials` VALUES (1,1,NULL,NULL,'2026-06-25 14:37:09','2026-06-25 14:37:09');
/*!40000 ALTER TABLE `cf_section_b_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_section_c_assessments`
--

DROP TABLE IF EXISTS `cf_section_c_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cf_section_c_assessments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cf_id` bigint(20) unsigned NOT NULL,
  `evaluation_scheme` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cf_section_c_assessments_cf_id_foreign` (`cf_id`),
  CONSTRAINT `cf_section_c_assessments_cf_id_foreign` FOREIGN KEY (`cf_id`) REFERENCES `cf_course_files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_c_assessments`
--

LOCK TABLES `cf_section_c_assessments` WRITE;
/*!40000 ALTER TABLE `cf_section_c_assessments` DISABLE KEYS */;
INSERT INTO `cf_section_c_assessments` VALUES (1,1,NULL,'2026-06-25 14:37:09','2026-06-25 14:37:09');
/*!40000 ALTER TABLE `cf_section_c_assessments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cf_section_d_attainments`
--

DROP TABLE IF EXISTS `cf_section_d_attainments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cf_section_d_attainments`
--

LOCK TABLES `cf_section_d_attainments` WRITE;
/*!40000 ALTER TABLE `cf_section_d_attainments` DISABLE KEYS */;
INSERT INTO `cf_section_d_attainments` VALUES (1,1,NULL,NULL,'2026-06-25 14:37:09','2026-06-25 14:37:09');
/*!40000 ALTER TABLE `cf_section_d_attainments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_management`
--

DROP TABLE IF EXISTS `class_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `class_management` WRITE;
/*!40000 ALTER TABLE `class_management` DISABLE KEYS */;
INSERT INTO `class_management` VALUES ('CE_2026_2029','CE',2026,1,NULL,NULL,'2026-06-26 13:11:15','2026-06-26 13:11:15'),('EL_2025_2028','EL',2025,3,'9100000001','9100000006','2026-06-21 10:48:14','2026-06-21 20:43:21'),('EL_2026_2029','EL',2026,1,NULL,NULL,'2026-06-26 11:31:42','2026-06-26 11:31:42');
/*!40000 ALTER TABLE `class_management` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_files`
--

DROP TABLE IF EXISTS `course_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  PRIMARY KEY (`id`),
  KEY `course_files_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `course_files_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_files`
--

LOCK TABLES `course_files` WRITE;
/*!40000 ALTER TABLE `course_files` DISABLE KEYS */;
INSERT INTO `course_files` VALUES (1,1,'/storage/syllabi/9UcEsoKkdLrmkKPJQtNT3Zd3MoR9wHbK758s7IZa.pdf','[{\"module_id\":\"I\",\"content\":\"Embedded Systems - Definition, difference from general purpose computers - Classification of embedded systems, Application areas, Components of embedded system hardware, and Software embedded into the system.\"},{\"module_id\":\"II\",\"content\":\"AVR Microcontroller Architecture - Comparison of AVR family members and Selection of a microcontroller, ATMega32- Simplified Block diagram of ATmega32 microcontroller.\"}]','[{\"id\":\"CO1\",\"description\":\"Explain the basics of embedded systems and its architecture.\",\"duration\":13,\"cognitive_level\":\"Understanding\"},{\"id\":\"CO2\",\"description\":\"Make use of AVR Microcontrollers to develop embedded programs using embedded C.\",\"duration\":16,\"cognitive_level\":\"Applying\"},{\"id\":\"CO3\",\"description\":\"Make use of AVR microcontroller to interface with various peripheral devices.\",\"duration\":19,\"cognitive_level\":\"Applying\"},{\"id\":\"CO4\",\"description\":\"Familiarize RTOS.\",\"duration\":10,\"cognitive_level\":\"Understanding\"}]','{\"CO1\":{\"PO1\":2,\"PO2\":null,\"PO3\":null,\"PO4\":null,\"PO5\":null,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null},\"CO2\":{\"PO1\":3,\"PO2\":3,\"PO3\":null,\"PO4\":null,\"PO5\":null,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null},\"CO3\":{\"PO1\":3,\"PO2\":3,\"PO3\":null,\"PO4\":null,\"PO5\":null,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null},\"CO4\":{\"PO1\":3,\"PO2\":null,\"PO3\":null,\"PO4\":null,\"PO5\":null,\"PO6\":null,\"PO7\":null,\"PO8\":null,\"PO9\":null,\"PO10\":null,\"PO11\":null,\"PO12\":null}}','[\"The 8051 Microcontroller and Embedded Systems - Muhammad Ali Mazidi\",\"Embedded C - Michael J. Pont\"]','2026-06-22 09:54:43','2026-06-25 17:36:53','{\"CO1\":{\"start\":\"2026-06-24\",\"due\":\"2026-06-30\",\"locked\":true},\"CO2\":{\"start\":\"2026-06-24\",\"due\":\"2026-07-06\",\"locked\":true}}','{\"CO1\":[\"1. Identify the constraints and challenges typically faced during embedded system design.\",\"2. Analyze the real-world applications of embedded systems in the automotive industry.\",\"3. Explain the fundamental hardware and software components of a typical embedded system.\"],\"CO2\":[\"1. Describe the function of the watchdog timer in the context of system reliability.\",\"2. Analyze the pinout and architecture of the 8051 microcontroller compared to AVR.\",\"3. Explain the difference between Harvard and Von Neumann architectures with examples.\"],\"CO3\":[\"1. Explain the working principle and interfacing of a Seven Segment Display.\",\"2. Explain the role of an optocoupler when interfacing high-power devices to a microcontroller.\",\"3. Illustrate the interfacing of a 4x4 keypad matrix with a microcontroller.\"],\"CO4\":[\"1. Define a Real-Time Operating System (RTOS) and explain how it differs from a general-purpose OS.\",\"2. Give an example of priority inversion in RTOS and how it can be resolved.\",\"3. Write a short note on memory management techniques in embedded operating systems.\"]}','{\"CO1\":{\"total_marks\":20,\"part_a\":{\"q_count\":4,\"marks_per_q\":1,\"total_marks\":4,\"questions\":[{\"q\":\"Define power constraints.\",\"level\":\"R\",\"marks\":1},{\"q\":\"Define embedded systems.\",\"level\":\"U\",\"marks\":1},{\"q\":\"List two applications of embedded systems.\",\"level\":\"R\",\"marks\":1},{\"q\":\"What is a microcontroller?\",\"level\":\"U\",\"marks\":1}]},\"part_b\":{\"q_count\":3,\"marks_per_q\":3,\"total_marks\":9,\"questions\":[{\"q\":\"Explain the components of an embedded system.\",\"level\":\"U\",\"marks\":3},{\"q\":\"Explain the memory architecture of a generic microcontroller.\",\"level\":\"U\",\"marks\":3},{\"q\":\"Discuss the role of communication interfaces.\",\"level\":\"A\",\"marks\":3}]},\"part_c\":{\"q_count\":1,\"marks_per_q\":7,\"total_marks\":7,\"questions\":[{\"q\":\"Describe the design challenges and metrics in embedded systems with real-world automotive examples.\",\"level\":\"A\",\"marks\":7}]},\"date_of_exam\":\"2026-06-10\",\"is_locked\":true,\"created_at\":\"2026-06-22T19:04:46+00:00\"},\"CO2\":{\"total_marks\":20,\"part_a\":{\"q_count\":4,\"marks_per_q\":1,\"total_marks\":4,\"questions\":[{\"q\":\"Define watchdog timer.\",\"ans\":[\"A hardware timer that automatically resets the microcontroller if the software hangs or fails to execute properly.\"],\"level\":\"U\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]},{\"q\":\"List the ports in Atmega32.\",\"ans\":[\"PORTA, PORTB, PORTC, PORTD.\",\"Each port is 8-bit wide and bidirectional.\"],\"level\":\"U\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]},{\"q\":\"What is the AVR family?\",\"ans\":[\"A family of 8-bit RISC microcontrollers developed by Atmel.\",\"Features a modified Harvard architecture.\"],\"level\":\"R\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]},{\"q\":\"What is the AVR family?\",\"ans\":[\"A family of 8-bit RISC microcontrollers developed by Atmel.\",\"Features a modified Harvard architecture.\"],\"level\":\"R\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]}]},\"part_b\":{\"q_count\":3,\"marks_per_q\":3,\"total_marks\":9,\"questions\":[{\"q\":\"Discuss the memory organization of Atmega32.\",\"ans\":[\"32KB of In-System Programmable Flash (for program code).\",\"1KB EEPROM (for non-volatile data storage).\",\"2KB Internal SRAM (for variables and stack).\"],\"level\":\"U\",\"marks\":3,\"rubric\":[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]},{\"q\":\"Explain the criteria for selecting a microcontroller.\",\"ans\":[\"Processing power (8-bit vs 32-bit, clock speed).\",\"Memory requirements (Flash, RAM size).\",\"Number of I\\/O pins and specific peripherals (ADC, Timers, UART).\",\"Power consumption and cost.\"],\"level\":\"A\",\"marks\":3,\"rubric\":[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]},{\"q\":\"Discuss the memory organization of Atmega32.\",\"ans\":[\"32KB of In-System Programmable Flash (for program code).\",\"1KB EEPROM (for non-volatile data storage).\",\"2KB Internal SRAM (for variables and stack).\"],\"level\":\"U\",\"marks\":3,\"rubric\":[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]}]},\"part_c\":{\"q_count\":1,\"marks_per_q\":7,\"total_marks\":7,\"questions\":[{\"q\":\"Draw and explain the complete internal architecture and block diagram of the Atmega32.\",\"ans\":[\"Draw block diagram showing ALU, Registers, Flash, SRAM, EEPROM, and Peripherals.\",\"Explain the Harvard architecture (separate data and instruction buses).\",\"Detail the role of the General Purpose Working Registers (R0-R31).\",\"Explain the status register (SREG) and its flags (C, Z, N, V, S, H, T, I).\"],\"level\":\"A\",\"marks\":7,\"rubric\":[{\"desc\":\"Definition \\/ Concept statement\",\"mark\":1},{\"desc\":\"Explanation with supporting points (3 points)\",\"mark\":3},{\"desc\":\"Application \\/ Analysis \\/ Design (3 pts)\",\"mark\":3}]}]},\"is_locked\":true,\"created_at\":\"2026-06-23T01:22:30+00:00\"},\"CO3\":{\"total_marks\":20,\"part_a\":{\"q_count\":4,\"marks_per_q\":1,\"total_marks\":4,\"questions\":[{\"q\":\"Define PWM.\",\"ans\":[\"Pulse Width Modulation.\",\"A technique used to encode a message into a pulsing signal, controlling average power delivered to a load (e.g., motor speed).\"],\"level\":\"U\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]},{\"q\":\"What is a Seven Segment Display?\",\"ans\":[\"An electronic display device for displaying decimal numerals.\",\"Comprises seven LED segments arranged in a figure-8 pattern.\"],\"level\":\"R\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]},{\"q\":\"What is a Seven Segment Display?\",\"ans\":[\"An electronic display device for displaying decimal numerals.\",\"Comprises seven LED segments arranged in a figure-8 pattern.\"],\"level\":\"R\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]},{\"q\":\"Define PWM.\",\"ans\":[\"Pulse Width Modulation.\",\"A technique used to encode a message into a pulsing signal, controlling average power delivered to a load (e.g., motor speed).\"],\"level\":\"R\",\"marks\":1,\"rubric\":[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]}]},\"part_b\":{\"q_count\":3,\"marks_per_q\":3,\"total_marks\":9,\"questions\":[{\"q\":\"Write an algorithm to interface an LCD.\",\"ans\":[\"Initialize the LCD by sending commands (e.g., 8-bit mode, 2 lines).\",\"Set RS=0, RW=0, and send command data to data lines, pulse EN.\",\"Set RS=1, RW=0, and send character data to data lines, pulse EN to write text.\"],\"level\":\"A\",\"marks\":3,\"rubric\":[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]},{\"q\":\"Explain the working of an optocoupler.\",\"ans\":[\"An electronic component that transfers electrical signals between two isolated circuits using light.\",\"Prevents high voltages from affecting the system receiving the signal.\",\"Contains an LED and a phototransistor.\"],\"level\":\"A\",\"marks\":3,\"rubric\":[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]},{\"q\":\"Explain the working of an optocoupler.\",\"ans\":[\"An electronic component that transfers electrical signals between two isolated circuits using light.\",\"Prevents high voltages from affecting the system receiving the signal.\",\"Contains an LED and a phototransistor.\"],\"level\":\"U\",\"marks\":3,\"rubric\":[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]}]},\"part_c\":{\"q_count\":1,\"marks_per_q\":7,\"total_marks\":7,\"questions\":[{\"q\":\"Explain the detailed working principle and interfacing of a DC motor using an L293D driver with AVR.\",\"ans\":[\"Explain the need for a motor driver (microcontroller cannot provide enough current).\",\"Describe the L293D dual H-bridge motor driver IC.\",\"Draw the circuit diagram connecting AVR, L293D, and the DC Motor.\",\"Explain how setting IN1 and IN2 controls the direction (forward, reverse, stop).\",\"Explain how PWM on the EN pin controls the speed.\"],\"level\":\"A\",\"marks\":7,\"rubric\":[{\"desc\":\"Definition \\/ Concept statement\",\"mark\":1},{\"desc\":\"Explanation with supporting points (3 points)\",\"mark\":3},{\"desc\":\"Application \\/ Analysis \\/ Design (3 pts)\",\"mark\":3}]}]},\"date_of_exam\":\"2026-07-23\",\"is_locked\":true,\"created_at\":\"2026-06-25T23:05:44+05:30\"}}');
/*!40000 ALTER TABLE `course_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciplinary_actions`
--

DROP TABLE IF EXISTS `disciplinary_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinary_actions`
--

LOCK TABLES `disciplinary_actions` WRITE;
/*!40000 ALTER TABLE `disciplinary_actions` DISABLE KEYS */;
INSERT INTO `disciplinary_actions` VALUES (6,'25EL1001','2026-06-28','Test incident from fix','Counselled','9100000001','2026-06-28 11:54:58','2026-06-28 12:17:28');
/*!40000 ALTER TABLE `disciplinary_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extracurricular_activities`
--

DROP TABLE IF EXISTS `extracurricular_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `extracurricular_activities` WRITE;
/*!40000 ALTER TABLE `extracurricular_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `extracurricular_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_records`
--

DROP TABLE IF EXISTS `leave_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_records`
--

LOCK TABLES `leave_records` WRITE;
/*!40000 ALTER TABLE `leave_records` DISABLE KEYS */;
INSERT INTO `leave_records` VALUES (1,'25EL1001',3,'2026-06-25 to 2026-06-26','2','Test reason',1,'Approved','9100000001','2026-06-28 11:01:56','2026-06-28 11:53:06'),(2,'25EL1001',3,'2026-06-24 to 2026-06-25','1.5','Fever',1,'Approved','9100000001','2026-06-28 12:24:25','2026-06-28 12:24:51');
/*!40000 ALTER TABLE `leave_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_plans`
--

DROP TABLE IF EXISTS `lesson_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `remarks` varchar(255) DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_plans_batch_subject_id_foreign` (`batch_subject_id`),
  CONSTRAINT `lesson_plans_batch_subject_id_foreign` FOREIGN KEY (`batch_subject_id`) REFERENCES `batch_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_plans`
--

LOCK TABLES `lesson_plans` WRITE;
/*!40000 ALTER TABLE `lesson_plans` DISABLE KEYS */;
INSERT INTO `lesson_plans` VALUES (121,NULL,1,'CO1','Describe embedded system (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(122,NULL,1,'CO1','Classify embedded systems (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(123,NULL,1,'CO1','Distinguish Hardware components (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(124,NULL,1,'CO1','Distinguish Software components (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(125,NULL,1,'CO1','Describe the basic blocks (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(126,NULL,1,'CO1','Memory, Sensors, Actuators (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(127,NULL,1,'CO1','I/O sub-systems (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(128,NULL,1,'CO1','Communication Interfaces (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(129,NULL,1,'CO1','Describe embedded system (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(130,NULL,1,'CO1','Classify embedded systems (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(131,NULL,1,'CO1','Distinguish Hardware components (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(132,NULL,1,'CO1','Distinguish Software components (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(133,NULL,1,'CO1','Describe the basic blocks (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(134,NULL,1,'CO2','Familiarize AVR controllers family members (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(135,NULL,1,'CO2','Criteria to select a microcontroller (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(136,NULL,1,'CO2','Explain block diagram of Atmega32 (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(137,NULL,1,'CO2','Illustrate Registers, Memory organization (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(138,NULL,1,'CO2','Status register, Program counter (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(139,NULL,1,'CO2','Timers in AVR (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(140,NULL,1,'CO2','Embedded C programs for logic operations (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(141,NULL,1,'CO2','Time delay calculation (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(142,NULL,1,'CO2','Interrupts handling (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(143,NULL,1,'CO2','Familiarize AVR controllers family members (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(144,NULL,1,'CO2','Criteria to select a microcontroller (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(145,NULL,1,'CO2','Explain block diagram of Atmega32 (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(146,NULL,1,'CO2','Illustrate Registers, Memory organization (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(147,NULL,1,'CO2','Status register, Program counter (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(148,NULL,1,'CO2','Timers in AVR (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(149,NULL,1,'CO2','Embedded C programs for logic operations (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(150,NULL,1,'CO3','Need for interfacing (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(151,NULL,1,'CO3','Types of interfacing devices (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(152,NULL,1,'CO3','Interfacing of LED (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(153,NULL,1,'CO3','Push button, Relay (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(154,NULL,1,'CO3','Optocoupler with AVR (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(155,NULL,1,'CO3','Sensors and Seven segment Display (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(156,NULL,1,'CO3','LCD and Keyboard interfacing (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(157,NULL,1,'CO3','DC motor, Servo motor and stepper motor (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(158,NULL,1,'CO3','Need for interfacing (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(159,NULL,1,'CO3','Types of interfacing devices (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(160,NULL,1,'CO3','Interfacing of LED (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(161,NULL,1,'CO3','Push button, Relay (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(162,NULL,1,'CO3','Optocoupler with AVR (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(163,NULL,1,'CO3','Sensors and Seven segment Display (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(164,NULL,1,'CO3','LCD and Keyboard interfacing (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(165,NULL,1,'CO3','DC motor, Servo motor and stepper motor (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(166,NULL,1,'CO3','Need for interfacing (Part 3)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(167,NULL,1,'CO3','Types of interfacing devices (Part 3)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(168,NULL,1,'CO3','Interfacing of LED (Part 3)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(169,NULL,1,'CO4','Familiarize RTOS (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(170,NULL,1,'CO4','Tasks, Threads (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(171,NULL,1,'CO4','Multiprocessing and Multitasking (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(172,NULL,1,'CO4','Task Scheduling (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(173,NULL,1,'CO4','Inter-process Communication (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(174,NULL,1,'CO4','Shared memory (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(175,NULL,1,'CO4','Message passing (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:49','2026-06-22 11:37:49'),(176,NULL,1,'CO4','RTOS Examples (Part 1)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:50','2026-06-22 11:37:50'),(177,NULL,1,'CO4','Familiarize RTOS (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:50','2026-06-22 11:37:50'),(178,NULL,1,'CO4','Tasks, Threads (Part 2)',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:50','2026-06-22 11:37:50'),(179,NULL,1,NULL,'Internal Assessment Test 1',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:50','2026-06-22 11:37:50'),(180,NULL,1,NULL,'Internal Assessment Test 2',1,NULL,NULL,NULL,NULL,NULL,'Pending','2026-06-22 11:37:50','2026-06-22 11:37:50');
/*!40000 ALTER TABLE `lesson_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mentoring_batches`
--

DROP TABLE IF EXISTS `mentoring_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `mentoring_batches_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `class_management` (`classroom_id`) ON DELETE CASCADE,
  CONSTRAINT `mentoring_batches_mentor_no_foreign` FOREIGN KEY (`mentor_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE,
  CONSTRAINT `mentoring_batches_reg_no_foreign` FOREIGN KEY (`reg_no`) REFERENCES `students` (`reg_no`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mentoring_batches`
--

LOCK TABLES `mentoring_batches` WRITE;
/*!40000 ALTER TABLE `mentoring_batches` DISABLE KEYS */;
INSERT INTO `mentoring_batches` VALUES (1,'EL_2025_2028','25EL1001','9100000001','A','9100000001','2026-06-24 14:35:01','2026-06-24 14:35:01'),(2,'EL_2025_2028','EL2025001','9100000006','B','9100000001','2026-06-28 10:28:15','2026-06-28 10:28:15'),(3,'EL_2025_2028','EL2025002','9100000001','A','9100000001','2026-06-28 10:28:26','2026-06-28 10:28:26'),(4,'EL_2025_2028','EL2025003','9100000001','A','9100000001','2026-06-28 10:28:29','2026-06-28 10:28:29'),(5,'EL_2025_2028','EL2025004','9100000006','B','9100000001','2026-06-28 10:28:31','2026-06-28 10:28:31'),(6,'EL_2025_2028','EL2025005','9100000006','B','9100000001','2026-06-28 10:28:32','2026-06-28 10:28:32'),(7,'EL_2025_2028','EL2025007','9100000001','A','9100000001','2026-06-28 10:28:37','2026-06-28 10:28:37'),(8,'EL_2025_2028','EL2025008','9100000006','B','9100000001','2026-06-28 10:28:39','2026-06-28 10:28:39'),(9,'EL_2025_2028','EL2025009','9100000001','A','9100000001','2026-06-28 10:28:40','2026-06-28 10:28:40'),(10,'EL_2025_2028','EL2025010','9100000006','B','9100000001','2026-06-28 10:28:41','2026-06-28 10:28:41'),(11,'EL_2025_2028','EL2025006','9100000001','A','9100000001','2026-06-28 10:28:42','2026-06-28 10:28:42');
/*!40000 ALTER TABLE `mentoring_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_06_21_000001_create_staff_profiles_table',1),(2,'2026_06_21_000002_create_class_management_table',1),(3,'2026_06_21_000003_create_students_table',1),(4,'2026_06_21_000004_create_syllabus_registry_table',1),(5,'2026_06_21_000005_create_question_bank_table',1),(6,'2026_06_21_000006_create_test_configs_table',1),(7,'2026_06_21_000007_create_student_responses_table',1),(8,'2026_06_21_000008_create_academic_marks_table',1),(9,'2026_06_21_000009_create_tutor_diaries_table',1),(10,'2026_06_21_000010_create_po_configs_table',1),(11,'2026_06_21_033735_create_sessions_table',1),(12,'2026_06_21_043000_create_audit_logs_table',1),(13,'2026_06_21_060000_create_mentoring_batches_table',1),(14,'2026_06_21_060001_add_approval_fields_to_tutor_diaries',1),(15,'2026_06_22_135148_create_batch_subjects_table',2),(16,'2026_06_22_135226_create_subject_staff_assignments_table',2),(17,'2026_06_22_150235_create_course_files_table',3),(18,'2026_06_22_151808_create_lesson_plans_table',4),(19,'2026_06_22_163744_add_fields_to_lesson_plans_table',5),(20,'2026_06_22_163818_add_parsed_copo_to_course_files_table',6),(21,'2026_06_23_000001_add_rubric_to_question_bank',7),(22,'2026_06_23_021517_add_online_test_fields_to_test_configs_table',8),(23,'2026_06_23_021527_create_test_attempts_table',8),(24,'2026_06_23_123643_add_responses_to_test_attempts_table',9),(25,'2026_06_23_143048_create_student_semester_marks_table',10),(26,'2026_06_23_143102_create_student_semester_summary_table',10),(27,'2026_06_23_154303_add_academic_status_to_students_table',11),(28,'2026_06_23_154312_add_current_semester_to_class_management_table',11),(29,'2026_06_23_172900_add_placement_and_remarks_to_students_table',12),(30,'2026_06_24_020119_create_student_attendance_table',13),(31,'2026_06_24_181303_create_student_task_submissions_table',14),(32,'2026_06_24_191318_create_mentoring_diary_tables',15),(33,'2026_06_24_201825_add_verification_status_to_mentoring_tables',16),(34,'2026_06_24_210434_create_student_board_grades_table',17),(35,'2026_06_24_215558_create_activity_point_claims_table',18),(36,'2026_06_24_234927_add_semester_to_activity_point_claims_table',19),(37,'2026_06_25_070632_create_nba_course_file_tables',20),(38,'2026_06_25_163807_create_cf_course_file_documents_table',21),(39,'2026_06_25_183904_add_data_payload_to_cf_course_file_documents',22),(40,'2026_06_25_200119_add_cis_pdf_path_to_syllabus_registry',23),(41,'2026_06_25_201043_add_co_po_mapping_to_syllabus_registry',24),(42,'2026_06_26_102130_create_remedial_tables',25),(43,'2026_06_26_105405_add_phase3_fields_to_remedial_tables',26),(44,'2026_06_26_121136_add_attainment_settings_to_cf_course_files_table',27),(45,'2026_06_26_150351_add_board_result_fields_to_student_board_grades',28),(46,'2026_06_26_175122_create_password_reset_tokens_table',29),(47,'2026_06_26_182643_add_syllabus_revision_code_to_batch_subjects',30),(48,'2026_06_26_200109_add_rejection_note_to_activity_point_claims_table',31),(49,'2026_06_28_091437_create_student_mentoring_profiles_table',32);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('carmel.linx@carmelpoly.in','cI2FGigE89LKoQ884Kf9hNA0TjYpnsX8x3idDC7XfrdIqovqwgYZIqjagTtqKmCC','2026-06-26 12:42:20');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `po_config`
--

DROP TABLE IF EXISTS `po_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `po_config` WRITE;
/*!40000 ALTER TABLE `po_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `po_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `question_bank`
--

DROP TABLE IF EXISTS `question_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_bank` (
  `question_id` char(36) NOT NULL DEFAULT uuid(),
  `branch_code` varchar(10) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
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
  CONSTRAINT `question_bank_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `syllabus_registry` (`subject_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_bank`
--

LOCK TABLES `question_bank` WRITE;
/*!40000 ALTER TABLE `question_bank` DISABLE KEYS */;
INSERT INTO `question_bank` VALUES ('0078a100-cea6-47fd-9c59-1fc26d130587','EL','EL-5041','Descriptive',NULL,NULL,'Define PWM.','[]','[\"Pulse Width Modulation.\",\"A technique used to encode a message into a pulsing signal, controlling average power delivered to a load (e.g., motor speed).\"]','CO3',1,'[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('155885f7-f8b4-44ae-bf06-a506f06fab9a','EL','EL-5041','Descriptive',NULL,NULL,'Explain the working of an optocoupler.','[]','[\"An electronic component that transfers electrical signals between two isolated circuits using light.\",\"Prevents high voltages from affecting the system receiving the signal.\",\"Contains an LED and a phototransistor.\"]','CO3',3,'[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('4eafe467-3b8f-4ecc-b9e9-7e6fba139453','EL','EL-5041','Descriptive',NULL,NULL,'Explain the working of an optocoupler.','[]','[\"An electronic component that transfers electrical signals between two isolated circuits using light.\",\"Prevents high voltages from affecting the system receiving the signal.\",\"Contains an LED and a phototransistor.\"]','CO3',3,'[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('747dba58-4430-4e5b-98be-94013c14d087','EL','EL-5041','Descriptive',NULL,NULL,'Define PWM.','[]','[\"Pulse Width Modulation.\",\"A technique used to encode a message into a pulsing signal, controlling average power delivered to a load (e.g., motor speed).\"]','CO3',1,'[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('9a74cd38-fad7-4a68-a9fe-c5f747765ec2','EL','EL-5041','Descriptive',NULL,NULL,'What is a Seven Segment Display?','[]','[\"An electronic display device for displaying decimal numerals.\",\"Comprises seven LED segments arranged in a figure-8 pattern.\"]','CO3',1,'[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('ce799452-9dc7-498d-8277-75865cc589c1','EL','EL-5041','Descriptive',NULL,NULL,'Write an algorithm to interface an LCD.','[]','[\"Initialize the LCD by sending commands (e.g., 8-bit mode, 2 lines).\",\"Set RS=0, RW=0, and send command data to data lines, pulse EN.\",\"Set RS=1, RW=0, and send character data to data lines, pulse EN to write text.\"]','CO3',3,'[{\"desc\":\"Key definition \\/ concept\",\"mark\":1},{\"desc\":\"Explanation \\/ relevant points (2 points @ 1 mark each)\",\"mark\":2}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('d7751530-ffd2-4186-a579-16d4603c7d50','EL','EL-5041','Descriptive',NULL,NULL,'Explain the detailed working principle and interfacing of a DC motor using an L293D driver with AVR.','[]','[\"Explain the need for a motor driver (microcontroller cannot provide enough current).\",\"Describe the L293D dual H-bridge motor driver IC.\",\"Draw the circuit diagram connecting AVR, L293D, and the DC Motor.\",\"Explain how setting IN1 and IN2 controls the direction (forward, reverse, stop).\",\"Explain how PWM on the EN pin controls the speed.\"]','CO3',7,'[{\"desc\":\"Definition \\/ Concept statement\",\"mark\":1},{\"desc\":\"Explanation with supporting points (3 points)\",\"mark\":3},{\"desc\":\"Application \\/ Analysis \\/ Design (3 pts)\",\"mark\":3}]','2026-06-25 17:35:44','2026-06-25 17:35:44'),('ffde6093-8627-42ba-935c-af7839ea57ff','EL','EL-5041','Descriptive',NULL,NULL,'What is a Seven Segment Display?','[]','[\"An electronic display device for displaying decimal numerals.\",\"Comprises seven LED segments arranged in a figure-8 pattern.\"]','CO3',1,'[{\"desc\":\"Correct definition \\/ answer\",\"mark\":1}]','2026-06-25 17:35:44','2026-06-25 17:35:44');
/*!40000 ALTER TABLE `question_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remedial_assessment_scores`
--

DROP TABLE IF EXISTS `remedial_assessment_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `remedial_assessment_scores` WRITE;
/*!40000 ALTER TABLE `remedial_assessment_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `remedial_assessment_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remedial_assessments`
--

DROP TABLE IF EXISTS `remedial_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `remedial_assessments` WRITE;
/*!40000 ALTER TABLE `remedial_assessments` DISABLE KEYS */;
INSERT INTO `remedial_assessments` VALUES ('5fa4d257-c42d-47bd-85cd-059bc8757524','4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','Written Test',NULL,'CO1 - Embedded systems architecture',NULL,20,NULL,'2026-06-26 05:19:09','2026-06-26 05:19:09'),('bd16b974-da4d-42d3-ba10-0a8672ef8a4b','4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','Online Test',NULL,'online remedi 1',NULL,20,NULL,'2026-06-26 05:19:54','2026-06-26 05:19:54');
/*!40000 ALTER TABLE `remedial_assessments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remedial_rooms`
--

DROP TABLE IF EXISTS `remedial_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `remedial_rooms` WRITE;
/*!40000 ALTER TABLE `remedial_rooms` DISABLE KEYS */;
INSERT INTO `remedial_rooms` VALUES ('4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL_2025_2028','EL-5041','9100000001','active','2026-06-26 05:04:12','2026-06-26 05:04:12');
/*!40000 ALTER TABLE `remedial_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remedial_session_logs`
--

DROP TABLE IF EXISTS `remedial_session_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `remedial_session_logs` WRITE;
/*!40000 ALTER TABLE `remedial_session_logs` DISABLE KEYS */;
INSERT INTO `remedial_session_logs` VALUES ('341ef01f-db47-48bc-aeef-d8dae6e4e84d','4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','2026-06-26',NULL,60,'test log remedial 1','[\"EL2025001\",\"EL2025002\",\"EL2025003\",\"EL2025004\",\"EL2025006\",\"EL2025008\",\"EL2025009\",\"EL2025010\"]','2026-06-26 05:12:12','2026-06-26 05:12:12'),('518433ee-faad-41fa-8f6e-72defb833b90','4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','2026-06-26',NULL,60,'test log remedial 1','[\"EL2025001\",\"EL2025002\",\"EL2025003\",\"EL2025004\",\"EL2025005\",\"EL2025006\",\"EL2025007\",\"EL2025009\",\"EL2025010\"]','2026-06-26 05:09:01','2026-06-26 05:09:01');
/*!40000 ALTER TABLE `remedial_session_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remedial_students`
--

DROP TABLE IF EXISTS `remedial_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remedial_students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` char(36) NOT NULL,
  `reg_no` varchar(255) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `remedial_students_room_id_reg_no_unique` (`room_id`,`reg_no`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remedial_students`
--

LOCK TABLES `remedial_students` WRITE;
/*!40000 ALTER TABLE `remedial_students` DISABLE KEYS */;
INSERT INTO `remedial_students` VALUES (1,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025001','2026-06-26 05:04:12'),(2,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025002','2026-06-26 05:04:12'),(3,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025003','2026-06-26 05:04:12'),(4,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025004','2026-06-26 05:04:12'),(5,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025005','2026-06-26 05:04:12'),(6,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025006','2026-06-26 05:04:12'),(7,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025007','2026-06-26 05:04:12'),(8,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025008','2026-06-26 05:04:12'),(9,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025009','2026-06-26 05:04:12'),(10,'4ae4813d-dff4-4adb-bf2b-25ec25ee5ef6','EL2025010','2026-06-26 05:04:12');
/*!40000 ALTER TABLE `remedial_students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_profiles`
--

DROP TABLE IF EXISTS `staff_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_profiles`
--

LOCK TABLES `staff_profiles` WRITE;
/*!40000 ALTER TABLE `staff_profiles` DISABLE KEYS */;
INSERT INTO `staff_profiles` VALUES (1,'9000000000','Super Admin User','superadmin@carmelpoly.in','Administration','Super_Admin','admin123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(2,'9000000001','Dr. Principal','principal@carmelpoly.in','Administration','Principal','admin123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(3,'9000000002','HOD Electronics','hod.el@carmelpoly.in','EL','HOD','hod123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(5,'9000000004','Academic Admin','admin@carmelpoly.in','Administration','Admin','admin123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(7,'9000000006','Lab Demonstrator','demonstrator@carmelpoly.in','EL','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(8,'9000000007','Trade Instructor User','instructor@carmelpoly.in','EL','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(9,'8000000002','HOD Computer Engineering','hod.ct@carmelpoly.in','CT','HOD','hod123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(10,'7000000002','HOD Automobile Engineering','hod.au@carmelpoly.in','AU','HOD','hod123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(11,'6000000002','HOD Civil Engineering','hod.ce@carmelpoly.in','CE','HOD','hod123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(12,'5000000002','HOD Mechanical Engineering','hod.me@carmelpoly.in','ME','HOD','hod123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(13,'4000000002','HOD Electrical Engineering','hod.eee@carmelpoly.in','EEE','HOD','hod123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(14,'9100000001','Lecturer 1 EL','lecturer1.EL@carmelpoly.in','EL','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(15,'9100000002','Lecturer 2 EL','lecturer2.EL@carmelpoly.in','EL','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(16,'9100000003','Lecturer 3 EL','lecturer3.EL@carmelpoly.in','EL','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(17,'9100000004','Lecturer 4 EL','lecturer4.EL@carmelpoly.in','EL','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(18,'9100000005','Lecturer 5 EL','lecturer5.EL@carmelpoly.in','EL','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(19,'9100000006','Demonstrator 1 EL','demonstrator1.EL@carmelpoly.in','EL','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(20,'9100000007','Demonstrator 2 EL','demonstrator2.EL@carmelpoly.in','EL','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(21,'9100000008','Demonstrator 3 EL','demonstrator3.EL@carmelpoly.in','EL','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(22,'9100000009','Tradesman 1 EL','tradesman1.EL@carmelpoly.in','EL','Tradesman','tradesman123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(23,'9100000010','Trade Instructor 1 EL','tradeinstructor1.EL@carmelpoly.in','EL','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(24,'9100000021','Lecturer 1 CT','lecturer1.CT@carmelpoly.in','CT','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(25,'9100000022','Lecturer 2 CT','lecturer2.CT@carmelpoly.in','CT','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(26,'9100000023','Lecturer 3 CT','lecturer3.CT@carmelpoly.in','CT','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(27,'9100000024','Lecturer 4 CT','lecturer4.CT@carmelpoly.in','CT','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(28,'9100000025','Lecturer 5 CT','lecturer5.CT@carmelpoly.in','CT','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(29,'9100000026','Demonstrator 1 CT','demonstrator1.CT@carmelpoly.in','CT','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(30,'9100000027','Demonstrator 2 CT','demonstrator2.CT@carmelpoly.in','CT','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(31,'9100000028','Demonstrator 3 CT','demonstrator3.CT@carmelpoly.in','CT','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(32,'9100000029','Tradesman 1 CT','tradesman1.CT@carmelpoly.in','CT','Tradesman','tradesman123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(33,'9100000030','Trade Instructor 1 CT','tradeinstructor1.CT@carmelpoly.in','CT','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(34,'9100000041','Lecturer 1 AU','lecturer1.AU@carmelpoly.in','AU','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(35,'9100000042','Lecturer 2 AU','lecturer2.AU@carmelpoly.in','AU','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(36,'9100000043','Lecturer 3 AU','lecturer3.AU@carmelpoly.in','AU','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(37,'9100000044','Lecturer 4 AU','lecturer4.AU@carmelpoly.in','AU','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(38,'9100000045','Lecturer 5 AU','lecturer5.AU@carmelpoly.in','AU','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(39,'9100000046','Demonstrator 1 AU','demonstrator1.AU@carmelpoly.in','AU','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(40,'9100000047','Demonstrator 2 AU','demonstrator2.AU@carmelpoly.in','AU','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(41,'9100000048','Demonstrator 3 AU','demonstrator3.AU@carmelpoly.in','AU','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(42,'9100000049','Tradesman 1 AU','tradesman1.AU@carmelpoly.in','AU','Tradesman','tradesman123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(43,'9100000050','Trade Instructor 1 AU','tradeinstructor1.AU@carmelpoly.in','AU','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(44,'9100000061','Lecturer 1 CE','lecturer1.CE@carmelpoly.in','CE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(45,'9100000062','Lecturer 2 CE','lecturer2.CE@carmelpoly.in','CE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(46,'9100000063','Lecturer 3 CE','lecturer3.CE@carmelpoly.in','CE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(47,'9100000064','Lecturer 4 CE','lecturer4.CE@carmelpoly.in','CE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(48,'9100000065','Lecturer 5 CE','lecturer5.CE@carmelpoly.in','CE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(49,'9100000066','Demonstrator 1 CE','demonstrator1.CE@carmelpoly.in','CE','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(50,'9100000067','Demonstrator 2 CE','demonstrator2.CE@carmelpoly.in','CE','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(51,'9100000068','Demonstrator 3 CE','demonstrator3.CE@carmelpoly.in','CE','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(52,'9100000069','Tradesman 1 CE','tradesman1.CE@carmelpoly.in','CE','Tradesman','tradesman123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(53,'9100000070','Trade Instructor 1 CE','tradeinstructor1.CE@carmelpoly.in','CE','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(54,'9100000081','Lecturer 1 ME','lecturer1.ME@carmelpoly.in','ME','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(55,'9100000082','Lecturer 2 ME','lecturer2.ME@carmelpoly.in','ME','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(56,'9100000083','Lecturer 3 ME','lecturer3.ME@carmelpoly.in','ME','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(57,'9100000084','Lecturer 4 ME','lecturer4.ME@carmelpoly.in','ME','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(58,'9100000085','Lecturer 5 ME','lecturer5.ME@carmelpoly.in','ME','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(59,'9100000086','Demonstrator 1 ME','demonstrator1.ME@carmelpoly.in','ME','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(60,'9100000087','Demonstrator 2 ME','demonstrator2.ME@carmelpoly.in','ME','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(61,'9100000088','Demonstrator 3 ME','demonstrator3.ME@carmelpoly.in','ME','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(62,'9100000089','Tradesman 1 ME','tradesman1.ME@carmelpoly.in','ME','Tradesman','tradesman123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(63,'9100000090','Trade Instructor 1 ME','tradeinstructor1.ME@carmelpoly.in','ME','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(64,'9100000101','Lecturer 1 EEE','lecturer1.EEE@carmelpoly.in','EEE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(65,'9100000102','Lecturer 2 EEE','lecturer2.EEE@carmelpoly.in','EEE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(66,'9100000103','Lecturer 3 EEE','lecturer3.EEE@carmelpoly.in','EEE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(67,'9100000104','Lecturer 4 EEE','lecturer4.EEE@carmelpoly.in','EEE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(68,'9100000105','Lecturer 5 EEE','lecturer5.EEE@carmelpoly.in','EEE','Lecturer','lecturer123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(69,'9100000106','Demonstrator 1 EEE','demonstrator1.EEE@carmelpoly.in','EEE','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(70,'9100000107','Demonstrator 2 EEE','demonstrator2.EEE@carmelpoly.in','EEE','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(71,'9100000108','Demonstrator 3 EEE','demonstrator3.EEE@carmelpoly.in','EEE','Demonstrator','demo123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(72,'9100000109','Tradesman 1 EEE','tradesman1.EEE@carmelpoly.in','EEE','Tradesman','tradesman123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(73,'9100000110','Trade Instructor 1 EEE','tradeinstructor1.EEE@carmelpoly.in','EEE','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(74,'9000000101','General Aided Coord','aided.coord@carmelpoly.in','GEN_AIDED','Gen_Dept_Coordinator_Aided','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(75,'9000000102','General Aided Lecturer 1','aided.lecturer1@carmelpoly.in','GEN_AIDED','Lecturer','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(76,'9000000103','General Aided Lecturer 2','aided.lecturer2@carmelpoly.in','GEN_AIDED','Lecturer','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(77,'9000000104','General Aided Lecturer 3','aided.lecturer3@carmelpoly.in','GEN_AIDED','Lecturer','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(78,'9000000201','General SF Coord','sf.coord@carmelpoly.in','GEN_SF','Gen_Dept_Coordinator_Self_Finance','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(79,'9000000202','General SF Lecturer 1','sf.lecturer1@carmelpoly.in','GEN_SF','Lecturer','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(80,'9000000203','General SF Lecturer 2','sf.lecturer2@carmelpoly.in','GEN_SF','Lecturer','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(81,'9000000204','General SF Lecturer 3','sf.lecturer3@carmelpoly.in','GEN_SF','Lecturer','staff123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(82,'5000000099','Workshop Superintendent User','workshop.superintendent@carmelpoly.in','ME','Workshop_Superintendent','workshop123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(83,'5000000101','Workshop Instructor 1','workshop.inst1@carmelpoly.in','ME','Workshop_Instructor','workshop123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(84,'5000000102','Workshop Instructor 2','workshop.inst2@carmelpoly.in','ME','Workshop_Instructor','workshop123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(85,'5000000111','Workshop Trade Instructor 1','workshop.trade1@carmelpoly.in','ME','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(86,'5000000112','Workshop Trade Instructor 2','workshop.trade2@carmelpoly.in','ME','Trade_Instructor','trade123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(87,'5000000121','Workshop Lab Assistant 1','workshop.lab1@carmelpoly.in','ME','Laboratory_Assistant','lab123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14'),(88,'5000000122','Workshop Lab Assistant 2','workshop.lab2@carmelpoly.in','ME','Laboratory_Assistant','lab123',NULL,'Approved','2026-06-21 10:48:14','2026-06-21 10:48:14');
/*!40000 ALTER TABLE `staff_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_attendance`
--

DROP TABLE IF EXISTS `student_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_attendance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(50) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late') NOT NULL DEFAULT 'Present',
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

LOCK TABLES `student_attendance` WRITE;
/*!40000 ALTER TABLE `student_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_board_grades`
--

DROP TABLE IF EXISTS `student_board_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_board_grades`
--

LOCK TABLES `student_board_grades` WRITE;
/*!40000 ALTER TABLE `student_board_grades` DISABLE KEYS */;
INSERT INTO `student_board_grades` VALUES (1,'25EL1001',1,'EL101','a','2026-06-28 11:13:14','2026-06-28 11:13:14','2026-08',1,NULL,NULL,NULL,1),(2,'25EL1001',1,'MA101','a','2026-06-28 11:13:14','2026-06-28 11:13:14','2026-11',1,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `student_board_grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_family_details`
--

DROP TABLE IF EXISTS `student_family_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_family_details`
--

LOCK TABLES `student_family_details` WRITE;
/*!40000 ALTER TABLE `student_family_details` DISABLE KEYS */;
INSERT INTO `student_family_details` VALUES (12,'25EL1001','TER BRO','bro','6th','nil','nl','2026-06-28 11:13:14','2026-06-28 11:13:14'),(13,'25EL1001','TEST SIS','sis','10th','nil','nil','2026-06-28 11:13:14','2026-06-28 11:13:14');
/*!40000 ALTER TABLE `student_family_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_fee_records`
--

DROP TABLE IF EXISTS `student_fee_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `student_fee_records` WRITE;
/*!40000 ALTER TABLE `student_fee_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_fee_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_mentoring_profiles`
--

DROP TABLE IF EXISTS `student_mentoring_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `student_mentoring_profiles` WRITE;
/*!40000 ALTER TABLE `student_mentoring_profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_mentoring_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_prior_education`
--

DROP TABLE IF EXISTS `student_prior_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_prior_education`
--

LOCK TABLES `student_prior_education` WRITE;
/*!40000 ALTER TABLE `student_prior_education` DISABLE KEYS */;
INSERT INTO `student_prior_education` VALUES (7,'25EL1001','cbse','ghdtr','2024',NULL,NULL,NULL,'90','2026-06-28 11:13:14','2026-06-28 11:13:14');
/*!40000 ALTER TABLE `student_prior_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_responses`
--

DROP TABLE IF EXISTS `student_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `student_responses` WRITE;
/*!40000 ALTER TABLE `student_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_semester_marks`
--

DROP TABLE IF EXISTS `student_semester_marks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_semester_marks`
--

LOCK TABLES `student_semester_marks` WRITE;
/*!40000 ALTER TABLE `student_semester_marks` DISABLE KEYS */;
INSERT INTO `student_semester_marks` VALUES (1,'25EL1001',1,'MAT101','Engineering Mathematics I',45.00,88.00,133.00,'S',95.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(2,'25EL1001',1,'PHY101','Engineering Physics',42.00,80.00,122.00,'S',92.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(3,'25EL1001',1,'CS101','Introduction to Computing',48.00,95.00,143.00,'S',98.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(4,'25EL1001',1,'ENG101','Professional Communication',40.00,75.00,115.00,'S',88.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(5,'25EL1001',1,'EE101','Basic Electrical Engg',44.00,82.00,126.00,'S',90.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(6,'25EL1001',2,'MAT102','Engineering Mathematics II',48.00,92.00,140.00,'S',96.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(7,'25EL1001',2,'CHM101','Engineering Chemistry',46.00,85.00,131.00,'S',94.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(8,'25EL1001',2,'CS102','Data Structures in C',50.00,98.00,148.00,'S',100.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(9,'25EL1001',2,'ME101','Basic Mechanical Engg',45.00,86.00,131.00,'S',91.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(10,'25EL1001',2,'EC101','Basic Electronics Engg',47.00,89.00,136.00,'S',93.00,'2026-06-23 09:02:01','2026-06-23 09:02:01'),(11,'25EL1001',1,'EL101','Basic Electronics',40.00,80.00,120.00,'A+',NULL,'2026-06-23 12:07:06','2026-06-23 12:07:06'),(12,'25EL1001',1,'MA101','Mathematics I',35.00,60.00,95.00,'B',NULL,'2026-06-23 12:07:06','2026-06-23 12:07:06'),(13,'25EL1001',2,'EL102','Advanced Electronics',45.00,70.00,115.00,'A',NULL,'2026-06-23 12:07:06','2026-06-23 12:07:06'),(14,'EL2025001',1,'EL101','Basic Electronics',NULL,NULL,NULL,'A+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(15,'EL2025001',1,'MA101','Mathematics I',NULL,NULL,NULL,'C+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(16,'EL2025001',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'B+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(17,'EL2025002',1,'EL101','Basic Electronics',NULL,NULL,NULL,'C',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(18,'EL2025002',1,'MA101','Mathematics I',NULL,NULL,NULL,'C',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(19,'EL2025002',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'B+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(20,'EL2025003',1,'EL101','Basic Electronics',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(21,'EL2025003',1,'MA101','Mathematics I',NULL,NULL,NULL,'A+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(22,'EL2025003',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'C',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(23,'EL2025004',1,'EL101','Basic Electronics',NULL,NULL,NULL,'A+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(24,'EL2025004',1,'MA101','Mathematics I',NULL,NULL,NULL,'C',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(25,'EL2025004',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'A+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(26,'EL2025005',1,'EL101','Basic Electronics',NULL,NULL,NULL,'A',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(27,'EL2025005',1,'MA101','Mathematics I',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(28,'EL2025005',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'A',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(29,'EL2025006',1,'EL101','Basic Electronics',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(30,'EL2025006',1,'MA101','Mathematics I',NULL,NULL,NULL,'C+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(31,'EL2025006',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'C+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(32,'EL2025007',1,'EL101','Basic Electronics',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(33,'EL2025007',1,'MA101','Mathematics I',NULL,NULL,NULL,'A',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(34,'EL2025007',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(35,'EL2025008',1,'EL101','Basic Electronics',NULL,NULL,NULL,'A',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(36,'EL2025008',1,'MA101','Mathematics I',NULL,NULL,NULL,'A',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(37,'EL2025008',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'C+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(38,'EL2025009',1,'EL101','Basic Electronics',NULL,NULL,NULL,'A+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(39,'EL2025009',1,'MA101','Mathematics I',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(40,'EL2025009',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'C+',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(41,'EL2025010',1,'EL101','Basic Electronics',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(42,'EL2025010',1,'MA101','Mathematics I',NULL,NULL,NULL,'B',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(43,'EL2025010',2,'EL102','Advanced Electronics',NULL,NULL,NULL,'C',NULL,'2026-06-23 12:09:53','2026-06-23 12:09:53');
/*!40000 ALTER TABLE `student_semester_marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_semester_summary`
--

DROP TABLE IF EXISTS `student_semester_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_semester_summary`
--

LOCK TABLES `student_semester_summary` WRITE;
/*!40000 ALTER TABLE `student_semester_summary` DISABLE KEYS */;
INSERT INTO `student_semester_summary` VALUES (3,'EL2025001',1,7.60,NULL,20,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(4,'EL2025001',2,7.20,7.40,35,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(5,'EL2025002',1,8.60,NULL,9,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(6,'EL2025002',2,8.50,8.55,16,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(7,'EL2025003',1,8.00,NULL,14,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(8,'EL2025003',2,6.80,7.40,19,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(9,'EL2025004',1,9.40,NULL,6,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(10,'EL2025004',2,7.70,8.55,16,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(11,'EL2025005',1,9.00,NULL,15,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(12,'EL2025005',2,7.80,8.40,27,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(13,'EL2025006',1,8.30,NULL,18,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(14,'EL2025006',2,9.00,8.65,32,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(15,'EL2025007',1,7.30,NULL,14,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(16,'EL2025007',2,6.50,6.90,28,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(17,'EL2025008',1,8.50,NULL,12,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(18,'EL2025008',2,6.80,7.65,23,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(19,'EL2025009',1,8.10,NULL,20,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(20,'EL2025009',2,6.80,7.45,33,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(21,'EL2025010',1,9.10,NULL,15,'2026-06-23 12:09:53','2026-06-23 12:09:53'),(22,'EL2025010',2,9.20,9.15,25,'2026-06-23 12:09:53','2026-06-23 12:09:53');
/*!40000 ALTER TABLE `student_semester_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_task_submissions`
--

DROP TABLE IF EXISTS `student_task_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `student_task_submissions` WRITE;
/*!40000 ALTER TABLE `student_task_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_task_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `classroom_id` varchar(50) DEFAULT NULL,
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
  CONSTRAINT `students_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `class_management` (`classroom_id`) ON DELETE SET NULL,
  CONSTRAINT `students_mentor_mobile_no_foreign` FOREIGN KEY (`mentor_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL,
  CONSTRAINT `students_profile_verified_by_foreign` FOREIGN KEY (`profile_verified_by`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES ('25EL1001','A20251001','Test Student','student@carmelpoly.in','student123','9999999999','EL',2025,'Regular',NULL,'EL_2025_2028','Approved','Active',NULL,NULL,NULL,'25EL1234','9100000001','2026-06-21 10:48:14','2026-06-27 03:50:50','300000','Hosteller','test father','PUNNAPRA','father','343232234234','na',0,NULL,NULL),('26CE1000L','1000','GAUTHAM','carmel.linx@carmelpoly.in','1234',NULL,'CE',2026,'LET','/storage/avatars/Alr8DJKaaizk7WOPf1840F5cvdY69owl6ajahD6f.jpg','CE_2026_2029','Pending','Active',NULL,NULL,NULL,NULL,NULL,'2026-06-26 12:41:26','2026-06-26 13:11:15',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('26CE1001L','1001','Fr. ANTONY','admission2026@carmelpoly.in','1234',NULL,'CE',2026,'LET','/storage/avatars/fBVoJmFM4Y4MZ0twzHs5IFCb4COGyEu8ehlukopQ.jpg','CE_2026_2029','Pending','Active',NULL,NULL,NULL,NULL,NULL,'2026-06-26 12:30:41','2026-06-26 13:11:15',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025001','ADM6528001','Rahul Kumar','rahul1@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.stud123','9800000001','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025001','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000006','2026-06-23 12:09:53','2026-06-28 10:28:15',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025002','ADM4880002','Sneha Nair','sneha2@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000002','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025002','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000001','2026-06-23 12:09:53','2026-06-28 10:28:26',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025003','ADM5411003','Arun Menon','arun3@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000003','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025003','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000001','2026-06-23 12:09:53','2026-06-28 10:28:29',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025004','ADM6461004','Priya Pillai','priya4@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000004','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025004','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000006','2026-06-23 12:09:53','2026-06-28 10:28:31',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025005','ADM6348005','Kiran Krishnan','kiran5@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000005','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025005','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000006','2026-06-23 12:09:53','2026-06-28 10:28:32',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025006','ADM7298006','Anjali Varma','anjali6@example.com','stud123','9800000006','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025006','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000001','2026-06-23 12:09:53','2026-06-28 10:28:42',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025007','ADM3716007','Deepak Iyer','deepak7@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000007','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025007','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000001','2026-06-23 12:09:53','2026-06-28 10:28:37',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025008','ADM3034008','Meera George','meera8@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000008','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025008','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000006','2026-06-23 12:09:53','2026-06-28 10:28:39',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025009','ADM4049009','Vijay Thomas','vijay9@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000009','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025009','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000001','2026-06-23 12:09:53','2026-06-28 10:28:40',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL),('EL2025010','ADM1476010','Divya Joseph','divya10@example.com','$2y$12$hOdc8IcXAUfpBektGY.o9OvCKYk2ZjmkAeKBzVOgjAL60pt5iNG6.','9800000010','EL',2025,'Regular','https://i.pravatar.cc/150?u=EL2025010','EL_2025_2028','Approved','Active',NULL,NULL,NULL,NULL,'9100000006','2026-06-23 12:09:53','2026-06-28 10:28:41',NULL,'Day Scholar',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_staff_assignments`
--

DROP TABLE IF EXISTS `subject_staff_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_staff_assignments`
--

LOCK TABLES `subject_staff_assignments` WRITE;
/*!40000 ALTER TABLE `subject_staff_assignments` DISABLE KEYS */;
INSERT INTO `subject_staff_assignments` VALUES (1,1,'9100000001','2026-06-22 09:00:51','2026-06-22 09:00:51'),(2,5,'9100000001','2026-06-23 12:15:59','2026-06-23 12:15:59'),(3,6,'9100000006','2026-06-26 13:24:35','2026-06-26 13:24:35'),(4,6,'9100000003','2026-06-26 13:24:35','2026-06-26 13:24:35');
/*!40000 ALTER TABLE `subject_staff_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `syllabus_registry`
--

DROP TABLE IF EXISTS `syllabus_registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `syllabus_registry` WRITE;
/*!40000 ALTER TABLE `syllabus_registry` DISABLE KEYS */;
INSERT INTO `syllabus_registry` VALUES ('EL-5041',2026,'Embedded Systems',4,'uploads/cis/1782398229_EL-5041_CIS.pdf','[{\"co\":\"CO1\",\"description\":\"Explain the basics of embedded systems and its architecture\",\"po1\":2,\"po2\":\"\",\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":2,\"pso2\":\"\",\"pso3\":\"\"},{\"co\":\"CO2\",\"description\":\"Make use of AVR Microcontrollers to develop embedded programs using embedded C\",\"po1\":3,\"po2\":3,\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":\"\",\"pso2\":\"\",\"pso3\":\"\"},{\"co\":\"CO3\",\"description\":\"Make use of AVR microcontroller to interface with various peripheral devices.\",\"po1\":3,\"po2\":3,\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":\"\",\"pso2\":\"\",\"pso3\":3},{\"co\":\"CO4\",\"description\":\"Familiarize RTOS\",\"po1\":3,\"po2\":\"\",\"po3\":\"\",\"po4\":\"\",\"po5\":\"\",\"po6\":\"\",\"po7\":\"\",\"po8\":\"\",\"po9\":\"\",\"po10\":\"\",\"po11\":\"\",\"pso1\":\"\",\"pso2\":\"\",\"pso3\":\"\"}]','2026-06-22 12:24:48','2026-06-25 14:37:09');
/*!40000 ALTER TABLE `syllabus_registry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_attempts`
--

DROP TABLE IF EXISTS `test_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  `questions_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
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

LOCK TABLES `test_attempts` WRITE;
/*!40000 ALTER TABLE `test_attempts` DISABLE KEYS */;
INSERT INTO `test_attempts` VALUES ('1fe1b722-710b-11f1-931c-283926634940','25EL1001','b3d10873-7106-11f1-931c-283926634940',1,'2026-06-26 02:59:44','2026-06-26 02:59:58',2.00,'completed','[\"Priority inheritance\",\"Watchdog\",\"Running\",\"Synchronizing tasks\\/protecting resources\",null,\"Running\",\"Tasks are scheduled randomly\",\"First Come First Serve\",\"Switching hardware ports\",\"Increasing clock speed\"]','2026-06-26 02:59:44','2026-06-26 02:59:58'),('4e76b518-710c-11f1-931c-283926634940','25EL1001','3e8f389e-7108-11f1-931c-283926634940',1,'2026-06-26 03:08:11','2026-06-26 03:08:16',2.00,'completed','[\"16 MHz\",\"Overflow Flag\",\"32 KB\"]','2026-06-26 03:08:11','2026-06-26 03:08:16');
/*!40000 ALTER TABLE `test_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_configs`
--

DROP TABLE IF EXISTS `test_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  CONSTRAINT `test_configs_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `class_management` (`classroom_id`) ON DELETE CASCADE,
  CONSTRAINT `test_configs_subject_code_foreign` FOREIGN KEY (`subject_code`) REFERENCES `syllabus_registry` (`subject_code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_configs`
--

LOCK TABLES `test_configs` WRITE;
/*!40000 ALTER TABLE `test_configs` DISABLE KEYS */;
INSERT INTO `test_configs` VALUES ('3e8f389e-7108-11f1-931c-283926634940','EL-5041','EL_2025_2028','Online MCQ Test - CO2','2026-06-25 21:30:00','2026-06-26 18:30:00',30,'[\"CO2\"]',3,0,50,40,1,1,'[{\"q\":\"What is the maximum operating frequency of Atmega32?\",\"options\":[\"1 MHz\",\"8 MHz\",\"16 MHz\",\"32 MHz\"],\"ans\":\"16 MHz\",\"co\":\"CO2\"},{\"q\":\"Which flag is set when an arithmetic operation results in a zero?\",\"options\":[\"Carry Flag\",\"Zero Flag\",\"Sign Flag\",\"Overflow Flag\"],\"ans\":\"Zero Flag\",\"co\":\"CO2\"},{\"q\":\"What is the size of Flash memory in Atmega32?\",\"options\":[\"8 KB\",\"16 KB\",\"32 KB\",\"64 KB\"],\"ans\":\"32 KB\",\"co\":\"CO2\"}]',1,'2026-06-26 02:39:07','2026-06-26 02:39:07'),('b3d10873-7106-11f1-931c-283926634940','EL-5041','EL_2025_2028','card test MCQ ID test','2026-06-25 22:30:00','2026-06-27 06:30:00',30,'[\"CO4\"]',10,0,50,40,1,1,'[{\"q\":\"A mutex is similar to a binary semaphore but includes...\",\"options\":[\"Priority inheritance\",\"Multiple counts\",\"Faster execution\",\"Less memory usage\"],\"ans\":\"Priority inheritance\",\"co\":\"CO4\"},{\"q\":\"Which of the following is a type of IPC?\",\"options\":[\"Message Queues\",\"ADC\",\"PWM\",\"Watchdog\"],\"ans\":\"Message Queues\",\"co\":\"CO4\"},{\"q\":\"Which state is a task in when it is first created but not yet scheduled?\",\"options\":[\"Running\",\"Ready\",\"Blocked\",\"Suspended\"],\"ans\":\"Ready\",\"co\":\"CO4\"},{\"q\":\"What is a semaphore used for?\",\"options\":[\"Speeding up execution\",\"Synchronizing tasks\\/protecting resources\",\"Memory allocation\",\"Storing task context\"],\"ans\":\"Synchronizing tasks\\/protecting resources\",\"co\":\"CO4\"},{\"q\":\"What is the core function of an RTOS?\",\"options\":[\"Providing a GUI\",\"File management\",\"Meeting real-time deadlines\",\"Network routing\"],\"ans\":\"Meeting real-time deadlines\",\"co\":\"CO4\"},{\"q\":\"A task in an RTOS that is waiting for a timer to expire is in which state?\",\"options\":[\"Running\",\"Ready\",\"Blocked\",\"Suspended\"],\"ans\":\"Blocked\",\"co\":\"CO4\"},{\"q\":\"What does preemptive scheduling mean?\",\"options\":[\"Tasks run until completion\",\"Higher priority tasks can interrupt lower priority tasks\",\"Tasks are scheduled randomly\",\"Tasks share CPU equally\"],\"ans\":\"Higher priority tasks can interrupt lower priority tasks\",\"co\":\"CO4\"},{\"q\":\"Which scheduling algorithm runs tasks for a fixed time slice?\",\"options\":[\"Rate Monotonic\",\"Earliest Deadline First\",\"Round Robin\",\"First Come First Serve\"],\"ans\":\"Round Robin\",\"co\":\"CO4\"},{\"q\":\"What is context switching?\",\"options\":[\"Changing power states\",\"Saving current task state and loading another\",\"Switching hardware ports\",\"Updating firmware\"],\"ans\":\"Saving current task state and loading another\",\"co\":\"CO4\"},{\"q\":\"Priority inversion is solved by...\",\"options\":[\"Priority inheritance\",\"Round robin scheduling\",\"Disabling interrupts\",\"Increasing clock speed\"],\"ans\":\"Priority inheritance\",\"co\":\"CO4\"}]',1,'2026-06-26 02:28:04','2026-06-26 02:28:04');
/*!40000 ALTER TABLE `test_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tutor_diaries`
--

DROP TABLE IF EXISTS `tutor_diaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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

LOCK TABLES `tutor_diaries` WRITE;
/*!40000 ALTER TABLE `tutor_diaries` DISABLE KEYS */;
INSERT INTO `tutor_diaries` VALUES ('2271520f-03e3-4bcb-b825-c7d0d5335fa3','25EL1001','2026-06-03','Mentoring','inside container meeting recorsd','testing',NULL,'9100000001','Staff','Approved',NULL,NULL,'2026-06-28 12:21:41','2026-06-28 12:21:41'),('676953e5-5bfe-4e67-8c4c-80c20c91eae9','25EL1001','2026-06-18','Mentoring','test meetng 1','completers',NULL,'9100000001','Staff','Approved',NULL,NULL,'2026-06-28 11:45:24','2026-06-28 11:45:24');
/*!40000 ALTER TABLE `tutor_diaries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-28 20:15:40
