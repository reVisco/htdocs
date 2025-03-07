-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 09:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ict_assets_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `batch_order_id` varchar(255) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `item_id`, `user_id`, `batch_order_id`, `date_added`) VALUES
(1, 6, 1, '1', '2024-03-20 17:14:14'),
(2, 8, 1, '0', '2024-03-21 15:25:37'),
(3, 9, 1, '0', '2024-03-21 15:26:40'),
(4, 11, 1, '1', '2025-02-25 15:55:13'),
(5, 12, 1, '1', '2025-02-25 16:06:36'),
(6, 13, 1, '1', '2025-02-25 16:12:58'),
(7, 14, 1, '1234', '2025-02-26 04:34:13'),
(8, 15, 1, '1234', '2025-02-26 04:34:13'),
(9, 16, 1, '1', '2025-02-27 16:03:19'),
(16, 23, 1, '0', '2025-03-07 02:55:38'),
(17, 24, 1, '0', '2025-03-07 02:55:38'),
(18, 25, 1, '0', '2025-03-07 02:55:38'),
(19, 26, 1, '0', '2025-03-07 02:55:38'),
(20, 27, 1, '0', '2025-03-07 02:55:38'),
(21, 28, 1, '0', '2025-03-07 03:00:15'),
(22, 29, 1, '0', '2025-03-07 03:00:16'),
(23, 30, 1, '0', '2025-03-07 03:00:16'),
(24, 31, 1, '0', '2025-03-07 03:00:16'),
(25, 32, 1, '0', '2025-03-07 03:00:16');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `qr_code_data` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `property` varchar(255) DEFAULT NULL,
  `order_batch_number` varchar(255) DEFAULT NULL,
  `model_number` varchar(50) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `warranty_coverage` int(11) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `item_type` int(11) DEFAULT NULL,
  `item_details` text DEFAULT NULL,
  `status_description` text DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `justification_of_purchase` text DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `po_number` varchar(255) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `pr_number` varchar(255) DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `delivery_receipt` varchar(255) DEFAULT NULL,
  `items_received_by` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `qr_code_data`, `item_name`, `property`, `order_batch_number`, `model_number`, `serial_number`, `warranty_coverage`, `brand`, `item_type`, `item_details`, `status_description`, `unit_price`, `justification_of_purchase`, `delivery_date`, `supplier_name`, `po_number`, `po_date`, `pr_number`, `invoice_no`, `delivery_receipt`, `items_received_by`, `remarks`, `date_added`) VALUES
(4, '4-NA', 'Cat6 Cable', 'ACES', '1', 'NA', 'NA', 5, 'FIBERCORE', 1, 'FIBERCORE UTP CABLE CAT6 OUTDOOR', 'working', 13300.00, 'For the implementation and deployment of cctv in botolan', '2024-01-03', 'Blue Sapphire Telecoms Consultancy', 'PO261671', '2023-12-07', 'PR353528', 'SI5940', 'DR6717', 'ALVIN', 'NA', '2025-02-25 16:05:04'),
(6, '6-NA', 'Cat6 Cable', 'ACES', '1', 'NA', 'NA', 5, 'FIBERCORE', 1, 'test1', 'working2', 13300.00, 'test4', '0000-00-00', 'Blue Sapphire Telecoms Consultancy', 'PO261671', '0000-00-00', 'PR353528', 'SI5940', 'DR6717', 'ALVIN', 'test3', '2025-02-25 16:05:04'),
(7, '7-', 'sdf', 'ACES', '', '', '', 0, '', 0, '', '0', 0.00, '', '0000-00-00', '', '', '0000-00-00', '', '', '', '', '', '2025-02-25 16:05:04'),
(8, '8-WP016RTJ', 'Harddrive', 'AB1', 'same as po number', 'no model number', 'WP016RTJ', 24, 'SEAGATE SKYHAWK', 2, 'Seagate Skyhawk HDD (Internal), for NVR, 10TB', '0', 14850.00, 'For kitchen additional CCTV and Spare Unit', '2024-01-10', 'Adaptive Network Solutions, INC.', 'PO259067', '2023-10-16', 'PR332860', 'SI2415', 'DR01086', 'ALVIN', '', '2025-02-25 16:05:04'),
(9, '9-WP016RTJ', 'Harddrive', 'AB1', 'same as po number', 'no model number', 'WP016RTJ', 24, 'SEAGATE SKYHAWK', 2, 'Seagate Skyhawk HDD (Internal), for NVR, 10TB', 'working', 14850.00, 'For kitchen additional CCTV and Spare Unit', '2024-01-10', 'Adaptive Network Solutions, INC.', 'PO259067', '2023-10-16', 'PR332860', 'SI2415', 'DR01086', 'ALVIN', '', '2025-02-25 16:05:04'),
(10, NULL, 'AOC Monitor', 'ACES', '1', '24G2E', 'EPG1234', 8, 'AOC', 2, 'Monitor', 'Monitor', 9450.00, 'Need Bigger screen', '2025-02-28', 'Easy PC', 'PO259067', '2025-02-25', 'PR332860', 'SI2415', 'DR01086', 'user', 'none', '2025-02-25 16:05:04'),
(11, '11-AOC Monitor', 'AOC Monitor', 'ACES', '1', '24G2E', 'EPG1234', 8, 'AOC', 2, 'Monitor', 'Working', 9450.00, 'Need Bigger screen', '2025-02-28', 'Easy PC', 'PO259067', '2025-02-25', 'PR332860', 'SI2415', 'DR01086', NULL, 'none', '2025-02-25 16:05:04'),
(12, '12-AOC Monitor', 'AOC Monitor', 'ACES', '1', '24G2E', 'EPG1234', 8, 'AOC', 3, 'Monitor', 'Working', 9450.00, 'Bigger Screen', '2025-02-28', 'Easy PC', 'PO259067', '2025-02-24', 'PR353528', 'SI5940', 'DR6717', 'user', 'asdfasdf', '2025-02-25 16:06:36'),
(13, '13-AOC Monitor', 'AOC Monitor', 'ACES', '1', '24G2E', 'EPG1235', 8, 'AOC', 3, 'Monitor', 'Working', 9450.00, 'Bigger Screen', '2025-02-28', 'Easy PC', 'PO259067', '2025-02-24', 'PR353528', 'SI5940', 'DR6717', NULL, 'asdfasdf', '2025-02-25 16:12:58'),
(14, '14-Iphone 16 Pro Max', 'Iphone 16 Pro Max', 'AVLCI', '1234', 'SSMEI12312', 'MEI2414', 18, 'Apple', 1, 'Smart Phone', 'Cellphone', 96000.00, 'Need advanced phone', '2025-02-28', 'Apple', 'PO2323', '2025-02-26', 'PR2323', '1254', 'OR22324', 'user', 'None', '2025-02-26 04:34:13'),
(15, '15-Iphone 16 Pro Max', 'Iphone 16 Pro Max', 'AVLCI', '1234', 'SSMEI12312', 'MEI1232', 18, 'Apple', 1, 'Smart Phone', 'Cellphone', 96000.00, 'Need advanced phone', '2025-02-28', 'Apple', 'PO2323', '2025-02-26', 'PR2323', '1254', 'OR22324', 'user', 'None', '2025-02-26 04:34:13'),
(16, '16-Samsung TV', 'Samsung TV', 'ACES', '1', '', 'MEI2414', 0, '', 0, '', '', 0.00, '', '0000-00-00', '', 'PO261671', '0000-00-00', '', '', '', '', '', '2025-02-27 16:03:18'),
(23, '23-', '', 'AC3', '', '', 'K84081163', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 02:55:38'),
(24, '24-', '', 'AC3', '', '', 'K84081229', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 02:55:38'),
(25, '25-', '', 'AC3', '', '', 'K90337451', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 02:55:38'),
(26, '26-', '', 'AC3', '', '', 'K84081179', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 02:55:38'),
(27, '27-', '', 'AC3', '', '', 'K90337478', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 02:55:38'),
(28, '28-', '', 'AC3', '', '', 'K84081163', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 03:00:15'),
(29, '29-', '', 'AC3', '', '', 'K84081229', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 03:00:15'),
(30, '30-', '', 'AC3', '', '', 'K90337451', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 03:00:16'),
(31, '31-', '', 'AC3', '', '', 'K84081179', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 03:00:16'),
(32, '32-', '', 'AC3', '', '', 'K90337478', 0, 'HIKVISION', 0, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', '', 650.00, '', '0000-00-00', 'ANY NETWORK SYSTEM INC.', 'PO244309', '2022-12-20', 'PR327815', 'SI10279', 'DR0101', 'ALVIN', 'FOR SHIPMENT', '2025-03-07 03:00:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `first_name`, `last_name`, `email`, `password`, `registration_time`) VALUES
(1, 'user1', 'John', 'Doe', 'user1@gmail.com', '$2y$10$XPcGJDkgrZoKGTf6CRgd4Oq.M864FtPhgQTSaLRp1o/m6vF532GWS', '2024-03-19 10:13:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
