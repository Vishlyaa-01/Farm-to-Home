-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 07:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farm2home`
--

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `buyer_id` int(11) NOT NULL,
  `buyer_name` varchar(30) NOT NULL,
  `buyer_age` int(11) NOT NULL,
  `buyer_gender` enum('M','F','O') DEFAULT NULL,
  `buyer_address` varchar(200) NOT NULL,
  `city` varchar(15) DEFAULT NULL,
  `state` varchar(15) DEFAULT NULL,
  `pin_code` varchar(6) NOT NULL CHECK (`pin_code` regexp '^[0-9]{6}$'),
  `buyer_aadhar_no` varchar(12) NOT NULL CHECK (`buyer_aadhar_no` regexp '^[0-9]{12}$'),
  `buyer_pan_no` varchar(10) NOT NULL CHECK (`buyer_pan_no` regexp '^[A-Z]{5}[0-9]{4}[A-Z]{1}$'),
  `buyer_contact_no` varchar(15) NOT NULL CHECK (`buyer_contact_no` regexp '^[6-9][0-9]{9}$'),
  `buyer_mail_id` varchar(40) NOT NULL,
  `time_of_registration` timestamp NOT NULL DEFAULT current_timestamp(),
  `buyer_username` varchar(20) NOT NULL,
  `buyer_password` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`buyer_id`, `buyer_name`, `buyer_age`, `buyer_gender`, `buyer_address`, `city`, `state`, `pin_code`, `buyer_aadhar_no`, `buyer_pan_no`, `buyer_contact_no`, `buyer_mail_id`, `time_of_registration`, `buyer_username`, `buyer_password`) VALUES
(15, 'Aditya Shinde', 40, 'M', 'Khedekar chal, Shivajinagar Gaonthan', 'Pune', 'Maharashtra', '411221', '663254415289', 'PLIYH1198H', '9845782044', 'aditya512@gmail.com', '2025-11-08 16:49:47', 'Aditya', 'aditya@512'),
(16, 'Manisha Bhegde', 36, 'F', 'Swami Vivekanand Society, Pashan Gaon', 'Pune', 'Maharashtra', '411021', '663358425175', 'BHYTA9921H', '9062034768', 'manishabhegde27@gmail.com', '2025-11-08 16:52:52', 'Manisha', 'Manisha@27');

-- --------------------------------------------------------

--
-- Table structure for table `crop`
--

CREATE TABLE `crop` (
  `crop_id` int(11) NOT NULL,
  `crop_category` enum('Vegetable','Fruit','Cereal','Pulses','Oilseed','Spices','Mixed') NOT NULL DEFAULT 'Mixed',
  `crop_name` varchar(30) NOT NULL,
  `crop_quantity_in_kg` decimal(10,2) NOT NULL CHECK (`crop_quantity_in_kg` > 0),
  `crop_price_per_kg` decimal(10,2) NOT NULL CHECK (`crop_price_per_kg` > 0),
  `crop_description` text DEFAULT NULL,
  `crop_status` enum('available','sold') NOT NULL DEFAULT 'available',
  `crop_harvest_date` date NOT NULL,
  `best_before_days` int(11) NOT NULL CHECK (`best_before_days` > 0),
  `farmer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crop`
--

INSERT INTO `crop` (`crop_id`, `crop_category`, `crop_name`, `crop_quantity_in_kg`, `crop_price_per_kg`, `crop_description`, `crop_status`, `crop_harvest_date`, `best_before_days`, `farmer_id`) VALUES
(19, '', 'Potato', 99.00, 40.00, '-', 'available', '2025-10-22', 4, 19),
(20, '', 'Chiku', 40.00, 60.00, 'Good in taste', 'available', '2025-10-20', 5, 23),
(21, '', 'Peru', 90.00, 50.00, '-', 'available', '2025-10-18', 10, 23),
(22, '', 'Soyabean', 300.00, 70.00, 'Best Refined soyabean', 'available', '2025-10-21', 15, 23),
(23, '', 'Tomato', 196.00, 60.00, 'Good Quality and fresh', 'available', '2025-11-03', 5, 24),
(24, '', 'Onion', 280.00, 40.00, 'Best in quality and price', 'available', '2025-10-31', 5, 24),
(25, 'Cereal', 'Basmati Rice', 650.00, 65.00, 'Best basmati rice for biryani and pulav', 'available', '2025-10-28', 20, 24);

-- --------------------------------------------------------

--
-- Table structure for table `farm`
--

CREATE TABLE `farm` (
  `farm_id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `farm_survey_number` varchar(20) NOT NULL CHECK (`farm_survey_number` regexp '^[0-9A-Za-z/-]+$'),
  `farm_land_in_acers` decimal(7,2) NOT NULL CHECK (`farm_land_in_acers` > 0),
  `farm_location` varchar(50) NOT NULL,
  `is_organic_certified` enum('Y','N') NOT NULL DEFAULT 'N',
  `water_resource` enum('Well','Borewell','Canal','River','Pond','Tank','Other') NOT NULL DEFAULT 'Other',
  `farm_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farm`
--

INSERT INTO `farm` (`farm_id`, `farmer_id`, `farm_survey_number`, `farm_land_in_acers`, `farm_location`, `is_organic_certified`, `water_resource`, `farm_type`) VALUES
(4, 19, 'CHAN0121', 120.00, 'Chande Gaon', 'Y', 'River', 'Mixed'),
(6, 23, 'SSWO219', 60.00, 'Nande, sus highway side road', 'Y', 'Well', 'Crop-based'),
(7, 24, 'TEIS2310', 129.00, 'Savargaon, near tikona fort road', 'N', 'River', 'Mixed');

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

CREATE TABLE `farmer` (
  `farmer_id` int(11) NOT NULL,
  `farmer_name` varchar(30) NOT NULL,
  `farmer_age` int(11) NOT NULL,
  `farmer_gender` enum('M','F','O') DEFAULT NULL,
  `farmer_address` varchar(200) NOT NULL,
  `city` varchar(15) DEFAULT NULL,
  `state` varchar(15) DEFAULT NULL,
  `pin_code` varchar(6) NOT NULL CHECK (`pin_code` regexp '^[0-9]{6}$'),
  `farmer_aadhar_no` varchar(12) NOT NULL CHECK (`farmer_aadhar_no` regexp '^[0-9]{12}$'),
  `farmer_pan_no` varchar(10) NOT NULL CHECK (`farmer_pan_no` regexp '^[A-Z]{5}[0-9]{4}[A-Z]{1}$'),
  `farmer_contact_no` varchar(15) NOT NULL CHECK (`farmer_contact_no` regexp '^[6-9][0-9]{9}$'),
  `farmer_mail_id` varchar(40) NOT NULL,
  `time_of_registration` timestamp NOT NULL DEFAULT current_timestamp(),
  `farmer_username` varchar(20) NOT NULL,
  `farmer_password` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`farmer_id`, `farmer_name`, `farmer_age`, `farmer_gender`, `farmer_address`, `city`, `state`, `pin_code`, `farmer_aadhar_no`, `farmer_pan_no`, `farmer_contact_no`, `farmer_mail_id`, `time_of_registration`, `farmer_username`, `farmer_password`) VALUES
(19, 'Suresh Lokhande', 42, 'M', 'Sasar Wasti Rajguru Nagar, Chande Gaon, Sus', 'Pune', 'Maharashtra', '411026', '545936411278', 'YUNIL8706H', '9960030407', 'suresh412@gmail.com', '2025-11-05 07:30:45', 'Suresh', '$2y$10$JZnRHD4NTOGAIoIhPriGP./SN.AxRqJkyAgSV3G8S7JT643yteOlK'),
(23, 'Ramdev Kale', 58, 'M', 'Tharkude wasti,Nande Gaon', 'Pune', 'Maharashtra', '412563', '845651232032', 'KKIAL9918H', '7099586421', 'ram01@gmail.com', '2025-11-08 16:08:03', 'Ram', '$2y$10$qNxHSdyc93vO2nHm8f5sSOd3RTT/vTZGZt7/OkSSzfehm793bclia'),
(24, 'Dilip Patil', 65, 'M', 'Sawargaon, Paud Mulshi', 'Pune', 'Maharashtra', '415869', '998521258745', 'MKIPO5521H', '8099684235', 'patil1960@gmail.com', '2025-11-08 16:11:30', 'Patil', '$2y$10$F1Ki1VddeHtW4lONyENoMOCXu70ESppo7fOo1TlEbCAkaMmctlV5a');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `name`, `email`, `subject`, `message`, `submitted_at`) VALUES
(18, 'Suyash Mudnde', 'munde01@gmail.com', 'About website', 'Your website is very easy to handle', '2025-11-06 06:52:43'),
(19, 'Omkar Gaikwad', 'omkar2005@gmail.com', 'For farmer', 'The vegetables i recived are so fresh and good quality', '2025-11-06 12:16:25'),
(20, 'Sahil Sayyad', 'sahil01@gmail.com', 'Good delivery', 'I ordered fruits from this website, which is delivered in perfect timing', '2025-11-23 15:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `crop_id` int(11) DEFAULT NULL,
  `order_qty` float DEFAULT NULL,
  `price_per_kg` float DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `order_status` varchar(20) DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `buyer_id`, `crop_id`, `order_qty`, `price_per_kg`, `total_price`, `order_status`, `order_date`) VALUES
(14, 15, 20, 3, 60, 180, 'Pending', '2025-11-26 05:10:12'),
(15, 15, 21, 5, 50, 250, 'Pending', '2025-11-26 05:10:12'),
(18, 16, 23, 2, 60, 120, 'Pending', '2025-11-26 05:30:53'),
(19, 16, 24, 5, 40, 200, 'delivered', '2025-11-26 05:30:53'),
(20, 16, 25, 10, 65, 650, 'Pending', '2025-11-26 05:30:53'),
(21, 16, 23, 2, 60, 120, 'Pending', '2025-11-26 05:33:16'),
(24, 15, 25, 20, 65, 1300, 'delivered', '2025-11-26 13:12:15'),
(25, 15, 25, 10, 65, 650, 'delivered', '2025-11-26 13:12:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`buyer_id`),
  ADD UNIQUE KEY `buyer_aadhar_no` (`buyer_aadhar_no`),
  ADD UNIQUE KEY `buyer_pan_no` (`buyer_pan_no`),
  ADD UNIQUE KEY `buyer_contact_no` (`buyer_contact_no`),
  ADD UNIQUE KEY `buyer_mail_id` (`buyer_mail_id`),
  ADD UNIQUE KEY `buyer_username` (`buyer_username`),
  ADD UNIQUE KEY `buyer_password` (`buyer_password`);

--
-- Indexes for table `crop`
--
ALTER TABLE `crop`
  ADD PRIMARY KEY (`crop_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `farm`
--
ALTER TABLE `farm`
  ADD PRIMARY KEY (`farm_id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `farmer`
--
ALTER TABLE `farmer`
  ADD PRIMARY KEY (`farmer_id`),
  ADD UNIQUE KEY `farmer_aadhar_no` (`farmer_aadhar_no`),
  ADD UNIQUE KEY `farmer_pan_no` (`farmer_pan_no`),
  ADD UNIQUE KEY `farmer_contact_no` (`farmer_contact_no`),
  ADD UNIQUE KEY `farmer_mail_id` (`farmer_mail_id`),
  ADD UNIQUE KEY `farmer_username` (`farmer_username`),
  ADD UNIQUE KEY `farmer_password` (`farmer_password`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `buyer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `crop`
--
ALTER TABLE `crop`
  MODIFY `crop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `farm`
--
ALTER TABLE `farm`
  MODIFY `farm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `farmer`
--
ALTER TABLE `farmer`
  MODIFY `farmer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `crop`
--
ALTER TABLE `crop`
  ADD CONSTRAINT `crop_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmer` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `farm`
--
ALTER TABLE `farm`
  ADD CONSTRAINT `farm_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmer` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
