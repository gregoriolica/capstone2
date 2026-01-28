-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 11:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jam_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_requests`
--

CREATE TABLE `appointment_requests` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `chief_complaint` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `responded_by` int(11) DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_requests`
--

INSERT INTO `appointment_requests` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `chief_complaint`, `status`, `responded_by`, `response_date`, `created_at`, `updated_at`) VALUES
(1, 6, 4, '2025-11-19', '14:00:00', 'Immunization', 'approved', 5, '2025-11-14 12:47:24', '2025-11-14 12:47:02', '2025-11-14 12:47:24'),
(2, 12, 8, '2025-11-26', '14:30:00', 'Immunization', 'approved', 11, '2025-11-14 23:50:13', '2025-11-14 23:47:39', '2025-11-14 23:50:13'),
(3, 12, 9, '2025-11-24', '18:45:00', 'Family Planning', 'approved', 11, '2025-11-14 23:50:11', '2025-11-14 23:48:11', '2025-11-14 23:50:11'),
(4, 12, 10, '2025-11-27', '10:30:00', 'Ultrasound', 'approved', 11, '2025-11-14 23:50:15', '2025-11-14 23:48:45', '2025-11-14 23:50:15'),
(5, 13, 9, '2025-11-26', '14:30:00', 'Implant', 'approved', 11, '2025-11-15 18:13:13', '2025-11-15 18:12:44', '2025-11-15 18:13:13'),
(6, 15, 8, '2025-11-25', '14:20:00', 'Ultrasound', 'approved', 11, '2025-11-16 23:05:40', '2025-11-16 23:03:16', '2025-11-16 23:05:40'),
(7, 15, 9, '2025-11-28', '11:20:00', 'Family Planning', 'approved', 11, '2025-11-16 23:05:54', '2025-11-16 23:04:24', '2025-11-16 23:05:54'),
(8, 15, 10, '2025-11-30', '10:00:00', 'Immunization', 'approved', 11, '2025-11-16 23:06:02', '2025-11-16 23:04:41', '2025-11-16 23:06:02');

-- --------------------------------------------------------

--
-- Table structure for table `billing_balance`
--

CREATE TABLE `billing_balance` (
  `patient_id` int(11) NOT NULL,
  `total_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing_balance`
--

INSERT INTO `billing_balance` (`patient_id`, `total_balance`, `last_updated`) VALUES
(3, 430.00, '2025-11-19 13:56:56'),
(4, 2850.00, '2025-11-19 13:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `billing_transactions`
--

CREATE TABLE `billing_transactions` (
  `transaction_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `transaction_type` enum('charge','discount','payment') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('senior','pwd','philhealth','custom') DEFAULT NULL,
  `applied_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing_transactions`
--

INSERT INTO `billing_transactions` (`transaction_id`, `service_id`, `patient_id`, `transaction_type`, `description`, `amount`, `discount_type`, `applied_by`, `created_at`) VALUES
(1, 4, 3, 'charge', 'Admission Fee', 400.00, NULL, 11, '2025-11-16 15:07:32'),
(2, 4, 3, 'charge', 'Admission', 400.00, NULL, 11, '2025-11-16 15:08:13'),
(3, 5, 4, 'charge', 'Ultrasound', 1200.00, NULL, 11, '2025-11-16 15:56:55'),
(4, 6, 4, 'charge', 'Labor Room Fee', 200.00, NULL, 11, '2025-11-16 15:57:40'),
(5, 7, 4, 'charge', 'Ultrasound', 1200.00, NULL, 11, '2025-11-16 15:58:29'),
(6, 4, 3, 'charge', '123', 250.00, NULL, 11, '2025-11-16 16:00:28'),
(7, 4, 3, 'payment', '1233', -250.00, NULL, 11, '2025-11-16 16:01:42'),
(8, 4, 3, 'payment', 'partial payment', -350.00, NULL, 11, '2025-11-16 16:59:24'),
(9, 8, 4, 'charge', 'IV Insertion', 250.00, NULL, 11, '2025-11-19 13:55:34'),
(10, 4, 3, 'payment', 'low cash', -20.00, NULL, 11, '2025-11-19 13:56:56');

-- --------------------------------------------------------

--
-- Table structure for table `clerk_services`
--

CREATE TABLE `clerk_services` (
  `service_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `service_amount` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clerk_services`
--

INSERT INTO `clerk_services` (`service_id`, `patient_id`, `doctor_id`, `service_date`, `service_type`, `service_amount`, `notes`, `created_at`, `updated_at`) VALUES
(4, 3, 9, '2025-11-21', 'Admission Fee', 400.00, 'Fever', '2025-11-16 15:07:32', '2025-11-16 15:07:32'),
(5, 4, 10, '2025-11-17', 'Ultrasound', 1200.00, 'asfasfadafad', '2025-11-16 15:56:55', '2025-11-16 15:56:55'),
(6, 4, 9, '2025-11-16', 'Labor Room Fee', 200.00, 'okay', '2025-11-16 15:57:40', '2025-11-16 15:57:40'),
(7, 4, 10, '2025-11-16', 'Ultrasound', 1200.00, 'okay', '2025-11-16 15:58:29', '2025-11-16 15:58:29'),
(8, 4, 10, '2025-11-20', 'IV Insertion', 250.00, 'no veins', '2025-11-19 13:55:34', '2025-11-19 13:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialization` text DEFAULT NULL,
  `schedule` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `user_id`, `specialization`, `schedule`) VALUES
(3, 8, 'Pediatrics', 'Wednesday - 08:00AM - 09:00AM'),
(4, 9, 'OB-GYNE', 'Monday - 08:00AM - 09:00AM'),
(5, 10, 'OB-GYNE', 'Thursday - 08:00AM - 09:00AM'),
(6, 16, 'Surgery', 'Tuesday - 08:00AM - 09:00AM');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_services`
--

CREATE TABLE `doctor_services` (
  `doctor_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `doctor_service_summary`
-- (See below for the actual view)
--
CREATE TABLE `doctor_service_summary` (
`doctor_id` int(11)
,`first_name` varchar(50)
,`last_name` varchar(50)
,`specialization` text
,`schedule` text
,`service_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `history_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `epilepsy_seizure` tinyint(1) DEFAULT 0,
  `epilepsy_notes` text DEFAULT NULL,
  `severe_headache_dizziness` tinyint(1) DEFAULT 0,
  `headache_notes` text DEFAULT NULL,
  `visual_disturbance` tinyint(1) DEFAULT 0,
  `vision_notes` text DEFAULT NULL,
  `yellowish_conjunctivitis` tinyint(1) DEFAULT 0,
  `conjunctivitis_notes` text DEFAULT NULL,
  `enlarged_thyroid` tinyint(1) DEFAULT 0,
  `thyroid_notes` text DEFAULT NULL,
  `severe_chest_pain` tinyint(1) DEFAULT 0,
  `chest_pain_notes` text DEFAULT NULL,
  `shortness_of_breath` tinyint(1) DEFAULT 0,
  `shortness_breath_notes` text DEFAULT NULL,
  `breast_axillary_masses` tinyint(1) DEFAULT 0,
  `breast_mass_notes` text DEFAULT NULL,
  `nipple_discharge` tinyint(1) DEFAULT 0,
  `nipple_discharge_notes` text DEFAULT NULL,
  `systolic_140_above` tinyint(1) DEFAULT 0,
  `systolic_notes` text DEFAULT NULL,
  `diastolic_90_above` tinyint(1) DEFAULT 0,
  `diastolic_notes` text DEFAULT NULL,
  `history_cva` tinyint(1) DEFAULT 0,
  `family_history_notes` text DEFAULT NULL,
  `abdominal_mass` tinyint(1) DEFAULT 0,
  `abdomen_mass_notes` text DEFAULT NULL,
  `history_gallbladder_disease` tinyint(1) DEFAULT 0,
  `gallbladder_notes` text DEFAULT NULL,
  `history_liver_disease` tinyint(1) DEFAULT 0,
  `liver_notes` text DEFAULT NULL,
  `uterine_mass` tinyint(1) DEFAULT 0,
  `uterine_mass_notes` text DEFAULT NULL,
  `vaginal_discharge` tinyint(1) DEFAULT 0,
  `vaginal_discharge_notes` text DEFAULT NULL,
  `intermenstrual_bleeding` tinyint(1) DEFAULT 0,
  `intermenstrual_bleeding_notes` text DEFAULT NULL,
  `postcoital_bleeding` tinyint(1) DEFAULT 0,
  `postcoital_bleeding_notes` text DEFAULT NULL,
  `severe_varicosities` tinyint(1) DEFAULT 0,
  `varicosities_notes` text DEFAULT NULL,
  `leg_swelling_pain` tinyint(1) DEFAULT 0,
  `leg_pain_notes` text DEFAULT NULL,
  `yellowish_skin` tinyint(1) DEFAULT 0,
  `yellowish_skin_notes` text DEFAULT NULL,
  `history_smoking` tinyint(1) DEFAULT 0,
  `smoking_notes` text DEFAULT NULL,
  `history_allergies` tinyint(1) DEFAULT 0,
  `allergies_notes` text DEFAULT NULL,
  `history_drug_intake` tinyint(1) DEFAULT 0,
  `drug_intake_notes` text DEFAULT NULL,
  `history_std` tinyint(1) DEFAULT 0,
  `std_notes` text DEFAULT NULL,
  `history_multiple_partners` tinyint(1) DEFAULT 0,
  `multiple_partners_notes` text DEFAULT NULL,
  `bleeding_tendencies` tinyint(1) DEFAULT 0,
  `bleeding_tendencies_notes` text DEFAULT NULL,
  `history_anemia` tinyint(1) DEFAULT 0,
  `anemia_notes` text DEFAULT NULL,
  `history_diabetes` tinyint(1) DEFAULT 0,
  `diabetes_notes` text DEFAULT NULL,
  `sti_multiple_partners` tinyint(1) DEFAULT 0,
  `sti_multiple_partners_notes` text DEFAULT NULL,
  `sti_women_discharge` tinyint(1) DEFAULT 0,
  `sti_women_discharge_notes` text DEFAULT NULL,
  `sti_women_itching_sores` tinyint(1) DEFAULT 0,
  `sti_women_itching_notes` text DEFAULT NULL,
  `sti_women_pain_burning` tinyint(1) DEFAULT 0,
  `sti_women_pain_notes` text DEFAULT NULL,
  `sti_women_treated_sti` tinyint(1) DEFAULT 0,
  `sti_women_treated_notes` text DEFAULT NULL,
  `sti_men_pain_burning` tinyint(1) DEFAULT 0,
  `sti_men_pain_notes` text DEFAULT NULL,
  `sti_men_open_sores` tinyint(1) DEFAULT 0,
  `sti_men_sores_notes` text DEFAULT NULL,
  `sti_men_pus_penis` tinyint(1) DEFAULT 0,
  `sti_men_pus_notes` text DEFAULT NULL,
  `sti_men_swollen_genitals` tinyint(1) DEFAULT 0,
  `sti_men_swollen_notes` text DEFAULT NULL,
  `sti_men_treated_sti` tinyint(1) DEFAULT 0,
  `sti_men_treated_notes` text DEFAULT NULL,
  `doctor_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`history_id`, `patient_id`, `staff_id`, `visit_date`, `epilepsy_seizure`, `epilepsy_notes`, `severe_headache_dizziness`, `headache_notes`, `visual_disturbance`, `vision_notes`, `yellowish_conjunctivitis`, `conjunctivitis_notes`, `enlarged_thyroid`, `thyroid_notes`, `severe_chest_pain`, `chest_pain_notes`, `shortness_of_breath`, `shortness_breath_notes`, `breast_axillary_masses`, `breast_mass_notes`, `nipple_discharge`, `nipple_discharge_notes`, `systolic_140_above`, `systolic_notes`, `diastolic_90_above`, `diastolic_notes`, `history_cva`, `family_history_notes`, `abdominal_mass`, `abdomen_mass_notes`, `history_gallbladder_disease`, `gallbladder_notes`, `history_liver_disease`, `liver_notes`, `uterine_mass`, `uterine_mass_notes`, `vaginal_discharge`, `vaginal_discharge_notes`, `intermenstrual_bleeding`, `intermenstrual_bleeding_notes`, `postcoital_bleeding`, `postcoital_bleeding_notes`, `severe_varicosities`, `varicosities_notes`, `leg_swelling_pain`, `leg_pain_notes`, `yellowish_skin`, `yellowish_skin_notes`, `history_smoking`, `smoking_notes`, `history_allergies`, `allergies_notes`, `history_drug_intake`, `drug_intake_notes`, `history_std`, `std_notes`, `history_multiple_partners`, `multiple_partners_notes`, `bleeding_tendencies`, `bleeding_tendencies_notes`, `history_anemia`, `anemia_notes`, `history_diabetes`, `diabetes_notes`, `sti_multiple_partners`, `sti_multiple_partners_notes`, `sti_women_discharge`, `sti_women_discharge_notes`, `sti_women_itching_sores`, `sti_women_itching_notes`, `sti_women_pain_burning`, `sti_women_pain_notes`, `sti_women_treated_sti`, `sti_women_treated_notes`, `sti_men_pain_burning`, `sti_men_pain_notes`, `sti_men_open_sores`, `sti_men_sores_notes`, `sti_men_pus_penis`, `sti_men_pus_notes`, `sti_men_swollen_genitals`, `sti_men_swollen_notes`, `sti_men_treated_sti`, `sti_men_treated_notes`, `doctor_note`, `created_at`, `updated_at`) VALUES
(1, 3, 10, '2026-01-26', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', 0, '', '', '2026-01-26 08:59:20', '2026-01-26 08:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `obstetrical_history`
--

CREATE TABLE `obstetrical_history` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `full_term` int(11) DEFAULT 0,
  `premature` int(11) DEFAULT 0,
  `abortions` int(11) DEFAULT 0,
  `living_children` int(11) DEFAULT 0,
  `last_delivery_date` date DEFAULT NULL,
  `last_delivery_type` varchar(50) DEFAULT NULL,
  `past_menstrual_period` date DEFAULT NULL,
  `menstrual_character` text DEFAULT NULL,
  `hydatidiform_mole` tinyint(1) DEFAULT 0,
  `hydatidiform_mole_notes` text DEFAULT NULL,
  `ectopic_pregnancy` tinyint(1) DEFAULT 0,
  `ectopic_pregnancy_notes` text DEFAULT NULL,
  `doctor_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int(11) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `channel` enum('sms','email') DEFAULT 'sms',
  `purpose` enum('register','verify') DEFAULT 'register',
  `status` enum('pending','used','expired') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `delivery_status` enum('sent','failed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_requests`
--

INSERT INTO `otp_requests` (`id`, `contact`, `otp_code`, `channel`, `purpose`, `status`, `created_at`, `expires_at`, `used_at`, `ip_address`, `delivery_status`) VALUES
(1, '09278413932', '446859', 'sms', 'register', 'used', '2025-11-14 10:07:18', '2025-11-14 03:12:18', '2025-11-14 10:07:44', NULL, NULL),
(2, '09278413932', '421331', 'sms', 'register', 'used', '2025-11-14 12:43:14', '2025-11-14 05:48:14', '2025-11-14 12:43:20', NULL, NULL),
(3, '09278413932', '326741', 'sms', 'register', 'used', '2025-11-14 23:44:03', '2025-11-14 16:49:03', '2025-11-14 23:44:20', NULL, NULL),
(4, '09569803004', '330635', 'sms', 'register', 'used', '2025-11-15 18:11:18', '2025-11-15 11:16:18', '2025-11-15 18:11:25', NULL, NULL),
(5, '09156061510', '555360', 'sms', 'register', 'used', '2025-11-16 00:12:56', '2025-11-15 17:17:56', '2025-11-16 00:13:01', NULL, NULL),
(6, '09156061510', '888129', 'sms', 'register', 'used', '2025-11-16 00:13:13', '2025-11-15 17:18:13', '2025-11-16 00:13:17', NULL, NULL),
(7, '09156067510', '210898', 'sms', 'register', 'used', '2025-11-16 23:00:59', '2025-11-16 16:05:59', '2025-11-16 23:01:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_records`
--

CREATE TABLE `patient_records` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `status` enum('Single','Married','Widowed','Separated') DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `patient_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `emergency_name` varchar(100) DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `emergency_address` varchar(255) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_records`
--

INSERT INTO `patient_records` (`patient_id`, `user_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `age`, `gender`, `status`, `contact_number`, `occupation`, `address`, `patient_image`, `created_at`, `updated_at`, `emergency_name`, `emergency_contact`, `emergency_address`, `relationship`) VALUES
(1, 12, 'Gelica', 'Cruz', 'Gregorio', '2001-05-17', 24, 'Female', 'Single', '09156061510', 'Student', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', 'uploads/1769585912_FB_IMG_1695901694297.jpg', '2025-11-14 15:47:14', '2026-01-28 07:38:32', 'Lio G Calipay', '09156061510', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', 'Other'),
(2, 13, 'Yma', 'Cruz', 'Gregorio', '2001-05-18', 24, 'Female', 'Single', '09569803004', 'Student', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', NULL, '2025-11-15 10:12:21', '2025-11-15 10:12:21', 'Nestor S. Calipay Jr.', '09569803004', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', 'Friend'),
(3, 14, 'Lio', 'G', 'Calipay', '2001-04-18', 24, 'Male', 'Single', '09156061510', 'S', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', NULL, '2025-11-15 16:14:01', '2025-11-15 16:14:01', 'Nestor S. Calipay Jr.', '09569803004', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', 'Parent'),
(4, 15, 'Angelica', 'Cruz', 'Gregorio', '1999-04-18', 26, 'Female', 'Single', '09156067510', 'Student', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', NULL, '2025-11-16 15:02:21', '2025-11-16 15:02:21', 'Andrew Andwerson', '09999999999', '#33A P.Francisco Street, Francisco Compound, Mabolo III, Bacoor City, Cavite', 'Friend');

-- --------------------------------------------------------

--
-- Table structure for table `patient_risk_assessment`
--

CREATE TABLE `patient_risk_assessment` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `risk_level` enum('not_set','low','high') DEFAULT 'not_set',
  `remarks` text DEFAULT NULL,
  `assessed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_risk_assessment`
--

INSERT INTO `patient_risk_assessment` (`id`, `patient_id`, `staff_id`, `risk_level`, `remarks`, `assessed_at`) VALUES
(1, 3, 10, 'low', '', '2026-01-26 09:00:31'),
(2, 3, 10, 'high', '', '2026-01-26 09:00:33'),
(3, 1, 10, 'low', '', '2026-01-26 09:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `patient_status`
--

CREATE TABLE `patient_status` (
  `status_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `status_label_id` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `physical_examination_record`
--

CREATE TABLE `physical_examination_record` (
  `record_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `exam_date` datetime DEFAULT current_timestamp(),
  `doctor_note` text DEFAULT NULL,
  `conjunctiva_pale` tinyint(1) DEFAULT 0,
  `conjunctiva_yellowish` tinyint(1) DEFAULT 0,
  `neck_enlarged_thyroid` tinyint(1) DEFAULT 0,
  `neck_enlarged_lymph_nodes` tinyint(1) DEFAULT 0,
  `breast_left_mass` tinyint(1) DEFAULT 0,
  `breast_left_nipple_discharge` tinyint(1) DEFAULT 0,
  `breast_left_skin_dimpling` tinyint(1) DEFAULT 0,
  `breast_left_axillary_nodes` tinyint(1) DEFAULT 0,
  `breast_right_mass` tinyint(1) DEFAULT 0,
  `breast_right_nipple_discharge` tinyint(1) DEFAULT 0,
  `breast_right_skin_dimpling` tinyint(1) DEFAULT 0,
  `breast_right_axillary_nodes` tinyint(1) DEFAULT 0,
  `thorax_abnormal_heart_sounds` tinyint(1) DEFAULT 0,
  `thorax_abnormal_breath_sounds` tinyint(1) DEFAULT 0,
  `abdomen_enlarged_liver` tinyint(1) DEFAULT 0,
  `abdomen_mass` tinyint(1) DEFAULT 0,
  `abdomen_tenderness` tinyint(1) DEFAULT 0,
  `extremities_edema` tinyint(1) DEFAULT 0,
  `extremities_varicosities` tinyint(1) DEFAULT 0,
  `perineum_scars` tinyint(1) DEFAULT 0,
  `perineum_warts` tinyint(1) DEFAULT 0,
  `perineum_reddish` tinyint(1) DEFAULT 0,
  `perineum_lacerations` tinyint(1) DEFAULT 0,
  `vagina_congested` tinyint(1) DEFAULT 0,
  `vagina_bartholins_cyst` tinyint(1) DEFAULT 0,
  `vagina_warts` tinyint(1) DEFAULT 0,
  `vagina_skenes_discharge` tinyint(1) DEFAULT 0,
  `vagina_rectocele` tinyint(1) DEFAULT 0,
  `vagina_cystocele` tinyint(1) DEFAULT 0,
  `cervix_congested` tinyint(1) DEFAULT 0,
  `cervix_erosion` tinyint(1) DEFAULT 0,
  `cervix_consistency` enum('Soft','Firm') DEFAULT NULL,
  `uterus_position` enum('Mid','Anteflexed','Retroflexed') DEFAULT NULL,
  `uterus_size` enum('Small','Normal','Large') DEFAULT NULL,
  `adnexa_mass` tinyint(1) DEFAULT 0,
  `adnexa_tenderness` tinyint(1) DEFAULT 0,
  `uterine_depth_cm` decimal(4,1) DEFAULT NULL,
  `examiner` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physical_examination_record`
--

INSERT INTO `physical_examination_record` (`record_id`, `patient_id`, `staff_id`, `exam_date`, `doctor_note`, `conjunctiva_pale`, `conjunctiva_yellowish`, `neck_enlarged_thyroid`, `neck_enlarged_lymph_nodes`, `breast_left_mass`, `breast_left_nipple_discharge`, `breast_left_skin_dimpling`, `breast_left_axillary_nodes`, `breast_right_mass`, `breast_right_nipple_discharge`, `breast_right_skin_dimpling`, `breast_right_axillary_nodes`, `thorax_abnormal_heart_sounds`, `thorax_abnormal_breath_sounds`, `abdomen_enlarged_liver`, `abdomen_mass`, `abdomen_tenderness`, `extremities_edema`, `extremities_varicosities`, `perineum_scars`, `perineum_warts`, `perineum_reddish`, `perineum_lacerations`, `vagina_congested`, `vagina_bartholins_cyst`, `vagina_warts`, `vagina_skenes_discharge`, `vagina_rectocele`, `vagina_cystocele`, `cervix_congested`, `cervix_erosion`, `cervix_consistency`, `uterus_position`, `uterus_size`, `adnexa_mass`, `adnexa_tenderness`, `uterine_depth_cm`, `examiner`) VALUES
(1, 2, 3, '2025-11-16 00:00:00', 'rest', 0, 1, 0, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Firm', 'Anteflexed', 'Small', 0, 0, 6.0, '');

-- --------------------------------------------------------

--
-- Table structure for table `pregnancy_tracker`
--

CREATE TABLE `pregnancy_tracker` (
  `tracker_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `lmp_date` date NOT NULL,
  `edc_date` date NOT NULL,
  `aog_weeks` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pregnancy_tracker`
--

INSERT INTO `pregnancy_tracker` (`tracker_id`, `patient_id`, `lmp_date`, `edc_date`, `aog_weeks`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-08-20', '2026-05-27', NULL, '', '2025-11-16 19:22:41', '2025-11-16 19:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `service_catalog`
--

CREATE TABLE `service_catalog` (
  `service_type_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `default_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_catalog`
--

INSERT INTO `service_catalog` (`service_type_id`, `service_name`, `default_price`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Prenatal Checkup', 500.00, 'Routine prenatal examination and vitals monitoring', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(2, 'Ultrasound', 10000.00, 'Standard fetal ultrasound imaging', 1, '2025-10-27 06:56:37', '2025-11-19 13:41:28'),
(3, 'Labor Room Fee', 2500.00, 'Facility use for delivery and monitoring', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(4, 'Newborn Screening', 800.00, 'Standard newborn screening tests', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(5, 'Admission Fee', 800.00, 'Initial patient intake and record setup', 1, '2025-10-27 06:56:37', '2025-11-16 15:12:25'),
(6, 'Postnatal Checkup', 600.00, 'Follow-up examination after delivery', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(7, 'IV Insertion', 250.00, 'Intravenous line setup for medication or fluids', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(8, 'Delivery Fee (Normal)', 3500.00, 'Professional fee for normal vaginal delivery', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(9, 'Delivery Fee (Cesarean)', 6500.00, 'Professional fee for cesarean section', 1, '2025-10-27 06:56:37', '2025-10-27 06:56:37'),
(10, 'Family Planning Counseling', 7500.00, 'Consultation and education on family planning options', 1, '2025-10-27 06:56:37', '2025-11-17 09:41:25');

-- --------------------------------------------------------

--
-- Table structure for table `status_reference`
--

CREATE TABLE `status_reference` (
  `label_id` int(11) NOT NULL,
  `status_label` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('patient','staff','admin','midwife','clerk') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `contact`, `password`, `role`, `date_created`, `date_updated`, `status`) VALUES
(3, 'admin1', 'admin1', 'admin@jam', '', '$2y$10$6eJ3dJydZlToE1PLOlrqaeSR10VZvH/31JsCLPVlru1.32OqpDUu.', 'admin', '2025-11-14 02:13:59', '2025-11-14 02:13:59', 'active'),
(8, 'Rowena', 'Cunanan', 'cunananjam@jamclinic', '639999999999', '$2y$10$Ucvqv44o2oN4MzS8MNS0gOwjeoBgL703RPuHcg3CKog0srnqQy//a', 'staff', '2025-11-14 15:24:42', '2025-11-14 15:24:42', 'active'),
(9, 'Marites', 'Bacunata', 'bacunatajam@jamclinic', '639999999999', '$2y$10$/biGYm3uAPKo.EQ3Fjo/D.caJiTP7d5o1HREx7nPrC6gZIidTX9gy', 'staff', '2025-11-14 15:29:17', '2025-11-14 15:29:17', 'active'),
(10, 'Xiemera', 'Sanchez', 'sanchezjam@jamclinic', '639999999999', '$2y$10$OqkB4DcsAdKRqiTcAVYK3u2COEKqXSrzsz7ylmiKcci7FMvxtYMq6', 'staff', '2025-11-14 15:35:17', '2025-11-14 15:35:17', 'active'),
(11, 'Lica', 'Gregorio', 'gregorioclerk@jamclinic', '639999999999', '$2y$10$EpYnM71vxNiTEIYZE5nShOltsULZoGCsHYVXFk6srEi6gYeezOhWy', 'clerk', '2025-11-14 15:39:56', '2025-11-14 15:39:56', 'active'),
(12, 'Gelica', 'Gregorio', 'lica17gregorio@gmail.com', '09278413932', '$2y$10$5O7CPe3qvW7TKdP4EAu6yu23hVQGOpMrsyj.HmeTJMdYsUPWXG/NK', 'patient', '2025-11-14 15:44:20', '2025-11-14 15:44:20', 'active'),
(13, 'Yma', 'Gregorio', 'rivervampxx@gmail.com', '09569803004', '$2y$10$HqQJt2R0SNcGnDDouH6zN.Zjqr2PU8CKi1j5e9qAZdGEwtOB4aWA2', 'patient', '2025-11-15 10:11:26', '2025-11-15 10:11:26', 'active'),
(14, 'Lio', 'Calipay', 'gelicagregorio19@gmail.com', '09156061510', '$2y$10$xIDoMxtF2d4pa4UTijtC7.RdnyrufR0CvQRpfUoUPGJ5ihp/Dvr4y', 'patient', '2025-11-15 16:13:17', '2025-11-15 16:13:17', 'active'),
(15, 'Angelica', 'Gregorio', 'gelica17gregorio@gmail.com', '09156067510', '$2y$10$5/73ftwW5CIU1Liz3Ih1Y.emRCq25bgx87/XK7IfOg5Cpk1CZ8/Fa', 'patient', '2025-11-16 15:01:10', '2025-11-16 15:01:10', 'active'),
(16, 'Sarah', 'Poliquit', 'poliquitjam@jamclinic', '639999999999', '$2y$10$fVOJrPMv/9nss2amkDLAtuHAbhM5R94pmH9YNOQTgjqhP/PyJ5NPe', 'staff', '2025-11-16 15:10:52', '2025-11-16 15:10:52', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `vaw_risk_assessment`
--

CREATE TABLE `vaw_risk_assessment` (
  `assessment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `history_domestic_violence` tinyint(1) DEFAULT 0,
  `note_history_domestic_violence` text DEFAULT NULL,
  `unpleasant_relationship` tinyint(1) DEFAULT 0,
  `note_unpleasant_relationship` text DEFAULT NULL,
  `partner_disapproves_visit` tinyint(1) DEFAULT 0,
  `note_partner_disapproves_visit` text DEFAULT NULL,
  `partner_disagrees_fp` tinyint(1) DEFAULT 0,
  `note_partner_disagrees_fp` text DEFAULT NULL,
  `referred_to_dswd` tinyint(1) DEFAULT 0,
  `referred_to_wcpu` tinyint(1) DEFAULT 0,
  `referred_to_ngos` tinyint(1) DEFAULT 0,
  `referred_to_others` tinyint(1) DEFAULT 0,
  `other_referral_note` text DEFAULT NULL,
  `doctor_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit_analytics`
--

CREATE TABLE `visit_analytics` (
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `blood_pressure` varchar(15) DEFAULT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `fundal_height` decimal(5,2) DEFAULT NULL,
  `fetal_heart_tone` int(11) DEFAULT NULL,
  `fetal_position` varchar(50) DEFAULT NULL,
  `chief_complaint` text DEFAULT NULL,
  `doctor_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `doctor_service_summary`
--
DROP TABLE IF EXISTS `doctor_service_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `doctor_service_summary`  AS SELECT `d`.`doctor_id` AS `doctor_id`, `u`.`first_name` AS `first_name`, `u`.`last_name` AS `last_name`, `d`.`specialization` AS `specialization`, `d`.`schedule` AS `schedule`, `s`.`service_name` AS `service_name` FROM (((`doctor` `d` join `users` `u` on(`d`.`user_id` = `u`.`id`)) join `doctor_services` `ds` on(`d`.`doctor_id` = `ds`.`doctor_id`)) join `service_catalog` `s` on(`ds`.`service_type_id` = `s`.`service_type_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_requests`
--
ALTER TABLE `appointment_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_balance`
--
ALTER TABLE `billing_balance`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `billing_transactions`
--
ALTER TABLE `billing_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `applied_by` (`applied_by`);

--
-- Indexes for table `clerk_services`
--
ALTER TABLE `clerk_services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `doctor_services`
--
ALTER TABLE `doctor_services`
  ADD PRIMARY KEY (`doctor_id`,`service_type_id`),
  ADD KEY `service_type_id` (`service_type_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_medical_history_patient` (`patient_id`);

--
-- Indexes for table `obstetrical_history`
--
ALTER TABLE `obstetrical_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_obstetrical_history_patient` (`patient_id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_status` (`contact`,`status`),
  ADD KEY `idx_expiry` (`expires_at`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `fk_patient_user` (`user_id`);

--
-- Indexes for table `patient_risk_assessment`
--
ALTER TABLE `patient_risk_assessment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_risk_patient` (`patient_id`);

--
-- Indexes for table `patient_status`
--
ALTER TABLE `patient_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `status_label_id` (`status_label_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `physical_examination_record`
--
ALTER TABLE `physical_examination_record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `fk_physical_exam_patient` (`patient_id`);

--
-- Indexes for table `pregnancy_tracker`
--
ALTER TABLE `pregnancy_tracker`
  ADD PRIMARY KEY (`tracker_id`),
  ADD UNIQUE KEY `patient_id` (`patient_id`);

--
-- Indexes for table `service_catalog`
--
ALTER TABLE `service_catalog`
  ADD PRIMARY KEY (`service_type_id`),
  ADD UNIQUE KEY `service_name` (`service_name`);

--
-- Indexes for table `status_reference`
--
ALTER TABLE `status_reference`
  ADD PRIMARY KEY (`label_id`),
  ADD UNIQUE KEY `status_label` (`status_label`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vaw_risk_assessment`
--
ALTER TABLE `vaw_risk_assessment`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `fk_vaw_patient` (`patient_id`);

--
-- Indexes for table `visit_analytics`
--
ALTER TABLE `visit_analytics`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `fk_visit_patient` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_requests`
--
ALTER TABLE `appointment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `billing_transactions`
--
ALTER TABLE `billing_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `clerk_services`
--
ALTER TABLE `clerk_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `obstetrical_history`
--
ALTER TABLE `obstetrical_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patient_risk_assessment`
--
ALTER TABLE `patient_risk_assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_status`
--
ALTER TABLE `patient_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `physical_examination_record`
--
ALTER TABLE `physical_examination_record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pregnancy_tracker`
--
ALTER TABLE `pregnancy_tracker`
  MODIFY `tracker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_catalog`
--
ALTER TABLE `service_catalog`
  MODIFY `service_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `status_reference`
--
ALTER TABLE `status_reference`
  MODIFY `label_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vaw_risk_assessment`
--
ALTER TABLE `vaw_risk_assessment`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit_analytics`
--
ALTER TABLE `visit_analytics`
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_balance`
--
ALTER TABLE `billing_balance`
  ADD CONSTRAINT `billing_balance_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `billing_transactions`
--
ALTER TABLE `billing_transactions`
  ADD CONSTRAINT `billing_transactions_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `clerk_services` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `billing_transactions_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `billing_transactions_ibfk_3` FOREIGN KEY (`applied_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `clerk_services`
--
ALTER TABLE `clerk_services`
  ADD CONSTRAINT `clerk_services_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `clerk_services_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_services`
--
ALTER TABLE `doctor_services`
  ADD CONSTRAINT `doctor_services_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_services_ibfk_2` FOREIGN KEY (`service_type_id`) REFERENCES `service_catalog` (`service_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `fk_medical_history_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `obstetrical_history`
--
ALTER TABLE `obstetrical_history`
  ADD CONSTRAINT `fk_obstetrical_history_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_risk_assessment`
--
ALTER TABLE `patient_risk_assessment`
  ADD CONSTRAINT `fk_risk_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_status`
--
ALTER TABLE `patient_status`
  ADD CONSTRAINT `patient_status_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`),
  ADD CONSTRAINT `patient_status_ibfk_2` FOREIGN KEY (`status_label_id`) REFERENCES `status_reference` (`label_id`),
  ADD CONSTRAINT `patient_status_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `physical_examination_record`
--
ALTER TABLE `physical_examination_record`
  ADD CONSTRAINT `fk_physical_exam_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pregnancy_tracker`
--
ALTER TABLE `pregnancy_tracker`
  ADD CONSTRAINT `pregnancy_tracker_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`);

--
-- Constraints for table `vaw_risk_assessment`
--
ALTER TABLE `vaw_risk_assessment`
  ADD CONSTRAINT `fk_vaw_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit_analytics`
--
ALTER TABLE `visit_analytics`
  ADD CONSTRAINT `fk_visit_patient` FOREIGN KEY (`patient_id`) REFERENCES `patient_records` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
