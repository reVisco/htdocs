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
-- Database: `ict_inventory_updated`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`) VALUES
(7, 'HIKVISION'),
(8, 'LENOVO');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(6, 'ICT'),
(7, 'ICT'),
(8, 'ICT');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `invoice_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `invoice_number`) VALUES
(8, 'SI10279'),
(9, 'SI10279'),
(10, '12525'),
(11, '12525');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_details` varchar(255) NOT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `item_type_id` int(11) DEFAULT NULL,
  `property_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `qr_code_data` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_details`, `serial_number`, `brand_id`, `item_type_id`, `property_id`, `status`, `location_id`, `qr_code_data`) VALUES
(510, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', 'K84081163', 7, NULL, 18, NULL, 8, '510'),
(511, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', 'K84081229', 7, NULL, 18, NULL, 8, '511'),
(512, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', 'K90337451', 7, NULL, 18, NULL, 8, '512'),
(513, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', 'K84081179', 7, NULL, 18, NULL, 8, '513'),
(514, 'DOME CAMERA 2MP | MODEL: DS-2CE56D0T-IR', 'K90337478', 7, NULL, 18, NULL, 8, '514'),
(515, '\"IDEAPAD IP3 - 14LC6 SLIM3 (82KT00WLPH) RYZEN 3 5300U 8GBDDR4 512GB M.2 PCIE SSD  WIN 11 HOME SL 64BIT | LENOVO BAG FREE\"', 'SPF3QA9JK', 8, NULL, 19, NULL, 10, '515');

-- --------------------------------------------------------

--
-- Table structure for table `item_issuances`
--

CREATE TABLE `item_issuances` (
  `issuance_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `issued_by` int(11) DEFAULT NULL,
  `issued_to` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `date_issued` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_issuances`
--

INSERT INTO `item_issuances` (`issuance_id`, `item_id`, `issued_by`, `issued_to`, `department_id`, `date_issued`) VALUES
(5, 510, 13, NULL, 7, NULL),
(6, 511, 13, NULL, 7, NULL),
(7, 512, 13, NULL, 7, NULL),
(8, 513, 13, NULL, 7, NULL),
(9, 514, 13, NULL, 7, NULL),
(10, 515, 13, 14, 8, '2023-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `item_purchases`
--

CREATE TABLE `item_purchases` (
  `item_purchase_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL,
  `pr_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `uom` varchar(20) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `justification_of_purchase` text DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_purchases`
--

INSERT INTO `item_purchases` (`item_purchase_id`, `item_id`, `po_id`, `pr_id`, `invoice_id`, `qty`, `uom`, `unit_price`, `total_amount`, `justification_of_purchase`, `delivery_date`, `received_by`, `remarks`, `created_by`) VALUES
(505, 510, 9, 9, 9, 1, '0', 650.00, 650.00, NULL, '2023-01-09', 13, '0', 1),
(506, 511, 9, 9, 9, 1, '0', 650.00, 650.00, NULL, '2023-01-09', 13, '0', 1),
(507, 512, 9, 9, 9, 1, '0', 650.00, 650.00, NULL, '2023-01-09', 13, '0', 1),
(508, 513, 9, 9, 9, 1, '0', 650.00, 650.00, NULL, '2023-01-09', 13, '0', 1),
(509, 514, 9, 9, 9, 1, '0', 650.00, 650.00, NULL, '2023-01-09', 13, '0', 1),
(510, 515, 11, 11, 11, 1, '0', 29095.00, 29095.00, NULL, '2023-01-05', 13, '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `item_types`
--

CREATE TABLE `item_types` (
  `item_type_id` int(11) NOT NULL,
  `item_type_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `location_name`) VALUES
(7, 'FOR SHIPMENT'),
(8, 'FOR SHIPMENT'),
(9, 'FOR ISSUANCE TO AGB'),
(10, 'FOR ISSUANCE TO AGB');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `user_id`, `action`, `timestamp`) VALUES
(1, 1, 'Deleted items with item_id from 4 to 503', '2025-03-06 21:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

CREATE TABLE `personnel` (
  `person_id` int(11) NOT NULL,
  `person_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`person_id`, `person_name`) VALUES
(13, 'ALVIN'),
(14, 'ENDORSED TO ROY NOTO');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `property_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `property_name`) VALUES
(1, 'ACES'),
(2, 'AVLCI'),
(3, 'APZ'),
(4, 'AC3'),
(5, 'AB1'),
(6, 'APW'),
(7, 'SPR'),
(8, 'AHR'),
(9, 'ABH'),
(10, 'SIARGAO'),
(11, 'AGL'),
(12, 'ACHI'),
(13, 'NEXGEN'),
(14, 'CHRDY'),
(15, 'AGB'),
(16, 'AC3'),
(17, 'AC3'),
(18, 'AC3'),
(19, 'AGB');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `po_id` int(11) NOT NULL,
  `po_number` varchar(20) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`po_id`, `po_number`, `po_date`, `supplier_id`) VALUES
(8, 'PO244309', '2022-12-20', 8),
(9, 'PO244309', '2022-12-20', 9),
(10, 'PO243629', '2022-12-09', 10),
(11, 'PO243629', '2022-12-09', 11);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `pr_id` int(11) NOT NULL,
  `pr_number` varchar(20) DEFAULT NULL,
  `done_or_no_pr` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`pr_id`, `pr_number`, `done_or_no_pr`) VALUES
(8, 'PR327815', 'Done'),
(9, 'PR327815', 'Done'),
(10, 'PR358082', 'Done'),
(11, 'PR358082', 'Done');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`) VALUES
(8, 'ANY NETWORK SYSTEM INC.'),
(9, 'ANY NETWORK SYSTEM INC.'),
(10, 'PC EXPRESS (URQUAN INC.)'),
(11, 'PC EXPRESS (URQUAN INC.)');

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
(1, 'john_doe', 'John', 'Doe', 'john.doe@example.com', '$2y$10$UV2KpFsP91LyML9uYiwgAOlYkkW4Fgx10lQUw4DWB/wf5lckoyq0y', '2025-03-06 19:42:18');

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `warranty_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `warranty_ends` date DEFAULT NULL,
  `warranty_slip_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warranties`
--

INSERT INTO `warranties` (`warranty_id`, `item_id`, `warranty_ends`, `warranty_slip_no`) VALUES
(3, 515, '2025-01-05', '4838027');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `idx_departments_department_id` (`department_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `idx_invoices_invoice_id` (`invoice_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_items_brand_id` (`brand_id`),
  ADD KEY `idx_items_item_type_id` (`item_type_id`),
  ADD KEY `idx_items_property_id` (`property_id`),
  ADD KEY `idx_items_location_id` (`location_id`);

--
-- Indexes for table `item_issuances`
--
ALTER TABLE `item_issuances`
  ADD PRIMARY KEY (`issuance_id`),
  ADD KEY `issued_by` (`issued_by`),
  ADD KEY `issued_to` (`issued_to`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `idx_item_issuances_item_id` (`item_id`);

--
-- Indexes for table `item_purchases`
--
ALTER TABLE `item_purchases`
  ADD PRIMARY KEY (`item_purchase_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `pr_id` (`pr_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `received_by` (`received_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_item_purchases_item_id` (`item_id`);

--
-- Indexes for table `item_types`
--
ALTER TABLE `item_types`
  ADD PRIMARY KEY (`item_type_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`person_id`),
  ADD KEY `idx_personnel_person_id` (`person_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`po_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `idx_purchase_orders_po_id` (`po_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`pr_id`),
  ADD KEY `idx_purchase_requests_pr_id` (`pr_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `idx_suppliers_supplier_id` (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`warranty_id`),
  ADD KEY `idx_warranties_item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=516;

--
-- AUTO_INCREMENT for table `item_issuances`
--
ALTER TABLE `item_issuances`
  MODIFY `issuance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item_purchases`
--
ALTER TABLE `item_purchases`
  MODIFY `item_purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- AUTO_INCREMENT for table `item_types`
--
ALTER TABLE `item_types`
  MODIFY `item_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `pr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `warranty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`item_type_id`) REFERENCES `item_types` (`item_type_id`),
  ADD CONSTRAINT `items_ibfk_3` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`),
  ADD CONSTRAINT `items_ibfk_4` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`);

--
-- Constraints for table `item_issuances`
--
ALTER TABLE `item_issuances`
  ADD CONSTRAINT `item_issuances_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `item_issuances_ibfk_2` FOREIGN KEY (`issued_by`) REFERENCES `personnel` (`person_id`),
  ADD CONSTRAINT `item_issuances_ibfk_3` FOREIGN KEY (`issued_to`) REFERENCES `personnel` (`person_id`),
  ADD CONSTRAINT `item_issuances_ibfk_4` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `item_purchases`
--
ALTER TABLE `item_purchases`
  ADD CONSTRAINT `item_purchases_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `item_purchases_ibfk_2` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`),
  ADD CONSTRAINT `item_purchases_ibfk_3` FOREIGN KEY (`pr_id`) REFERENCES `purchase_requests` (`pr_id`),
  ADD CONSTRAINT `item_purchases_ibfk_4` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `item_purchases_ibfk_5` FOREIGN KEY (`received_by`) REFERENCES `personnel` (`person_id`),
  ADD CONSTRAINT `item_purchases_ibfk_6` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `warranties`
--
ALTER TABLE `warranties`
  ADD CONSTRAINT `warranties_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
