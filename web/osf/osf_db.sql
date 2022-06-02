-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2020 at 04:03 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `osf_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_approved_reservations`
--

CREATE TABLE `tbl_approved_reservations` (
  `approved_reservation_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `approved_date` date NOT NULL,
  `approved_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cancelled_reservations`
--

CREATE TABLE `tbl_cancelled_reservations` (
  `cancelled_reservation_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `cancelled_date` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_closed_reservations`
--

CREATE TABLE `tbl_closed_reservations` (
  `closed_reservation_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `closed_date` date NOT NULL,
  `closed_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_favorites`
--

CREATE TABLE `tbl_favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `space_id` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `content` varchar(100) NOT NULL,
  `date` varchar(50) NOT NULL,
  `notified_by` int(11) NOT NULL,
  `is_seen` int(11) NOT NULL DEFAULT '0',
  `is_seen2` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications_admin`
--

CREATE TABLE `tbl_notifications_admin` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notification_type_id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `content` varchar(100) NOT NULL,
  `date` varchar(50) NOT NULL,
  `is_seen` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_type`
--

CREATE TABLE `tbl_notification_type` (
  `id` int(11) NOT NULL,
  `notification_type_id` int(11) NOT NULL,
  `notification_type_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_notification_type`
--

INSERT INTO `tbl_notification_type` (`id`, `notification_type_id`, `notification_type_desc`) VALUES
(1, 1, 'Reservation'),
(2, 2, 'Confirmation'),
(3, 3, 'Feedback'),
(4, 4, 'Registration');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ratings`
--

CREATE TABLE `tbl_ratings` (
  `rating_id` int(11) NOT NULL,
  `space_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rate` float(11,1) NOT NULL,
  `comment` text NOT NULL,
  `date_rated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rejected_reservations`
--

CREATE TABLE `tbl_rejected_reservations` (
  `rejected_reservation_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `rejected_reason` text NOT NULL,
  `rejected_date` date NOT NULL,
  `rejected_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rent_history`
--

CREATE TABLE `tbl_rent_history` (
  `rent_history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `space_id` int(11) NOT NULL,
  `date_stayed` date NOT NULL,
  `date_left` date NOT NULL,
  `length_of_stay` int(11) NOT NULL,
  `rent_history_status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rent_history_status`
--

CREATE TABLE `tbl_rent_history_status` (
  `rent_history_status_id` int(11) NOT NULL,
  `rent_history_status_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_rent_history_status`
--

INSERT INTO `tbl_rent_history_status` (`rent_history_status_id`, `rent_history_status_desc`) VALUES
(1, 'Currently Staying'),
(2, 'Moved Out');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reservations`
--

CREATE TABLE `tbl_reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `space_id` int(11) NOT NULL,
  `reservation_status_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `date_reserved` date NOT NULL,
  `date_of_visit` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reservation_status`
--

CREATE TABLE `tbl_reservation_status` (
  `reservation_status_id` int(11) NOT NULL,
  `reservation_status_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_reservation_status`
--

INSERT INTO `tbl_reservation_status` (`reservation_status_id`, `reservation_status_desc`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Cancelled'),
(4, 'Rejected'),
(5, 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `role_id` int(11) NOT NULL,
  `role_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`role_id`, `role_desc`) VALUES
(1, 'Space Owner'),
(2, 'Renter'),
(3, 'System Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_spaces`
--

CREATE TABLE `tbl_spaces` (
  `space_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `space_type_id` int(11) NOT NULL,
  `space_category_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` double(11,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_spaces`
--

INSERT INTO `tbl_spaces` (`space_id`, `owner_id`, `status_id`, `space_type_id`, `space_category_id`, `image`, `name`, `description`, `price`, `capacity`, `address`, `contact`, `lat`, `lng`, `date_added`) VALUES
(23, 54, 1, 3, 7, '24068613_148268302466854_7975197253041105528_o.jpg', 'VIVIEN HOTEL', 'Nearby hotel in Mactan Airport', 1926.00, 2, 'Maximo V. Patalinghug Jr. Avenue, Lapu-Lapu City, Cebu', '(032) 411 0404', 10.29795, 123.96373, '2020-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_space_categories`
--

CREATE TABLE `tbl_space_categories` (
  `space_category_id` int(11) NOT NULL,
  `space_type_id` int(11) NOT NULL,
  `space_category_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_space_categories`
--

INSERT INTO `tbl_space_categories` (`space_category_id`, `space_type_id`, `space_category_desc`) VALUES
(1, 1, '1 Storey'),
(2, 1, '2 Storey'),
(3, 1, '3 Storey'),
(4, 2, '1 Bed'),
(5, 2, '2 Beds'),
(6, 2, 'Double Deck Bed'),
(7, 3, 'Single Bed'),
(8, 3, 'Double Bed'),
(9, 3, 'Triple Bed'),
(10, 4, 'Office'),
(11, 4, 'Industrial'),
(12, 4, 'Multifamily'),
(13, 5, 'Wedding'),
(14, 5, 'Birthday Parties'),
(15, 5, 'Debut');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_space_status`
--

CREATE TABLE `tbl_space_status` (
  `status_id` int(11) NOT NULL,
  `status_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_space_status`
--

INSERT INTO `tbl_space_status` (`status_id`, `status_desc`) VALUES
(1, 'Available'),
(2, 'Reserved'),
(3, 'Rented');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_space_types`
--

CREATE TABLE `tbl_space_types` (
  `space_type_id` int(11) NOT NULL,
  `space_type_desc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_space_types`
--

INSERT INTO `tbl_space_types` (`space_type_id`, `space_type_desc`) VALUES
(1, 'House'),
(2, 'Room'),
(3, 'Hotel'),
(4, 'Commercial Building'),
(5, 'Events Space');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `vkey` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `image`, `fname`, `lname`, `address`, `email`, `contact`, `username`, `password`, `vkey`) VALUES
(1, '', 'Jodelle', 'Formentera', 'Basak Tamiya, Lapu-Lapu City', 'jodelle@gmail.com', '(032) 324 2355', 'admin', '21232f297a57a5a743894a0e4a801fc3', ''),
(54, '', 'Kevin', 'Tabayocyoc', '3306 Clays Mill Road, Suite 106', 'kevindale2017@gmail.com', '09363088069', 'kevindale2017', '7f72313a8033452502a5cff5092ac074', '3cddd5dae428a69f91851e56a91baa45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_roles`
--

CREATE TABLE `tbl_user_roles` (
  `user_role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_roles`
--

INSERT INTO `tbl_user_roles` (`user_role_id`, `user_id`, `role_id`, `created_date`, `is_active`, `is_verified`) VALUES
(42, 1, 3, '2020-11-23', 1, 1),
(57, 54, 1, '2020-11-23', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_approved_reservations`
--
ALTER TABLE `tbl_approved_reservations`
  ADD PRIMARY KEY (`approved_reservation_id`);

--
-- Indexes for table `tbl_cancelled_reservations`
--
ALTER TABLE `tbl_cancelled_reservations`
  ADD PRIMARY KEY (`cancelled_reservation_id`);

--
-- Indexes for table `tbl_closed_reservations`
--
ALTER TABLE `tbl_closed_reservations`
  ADD PRIMARY KEY (`closed_reservation_id`);

--
-- Indexes for table `tbl_favorites`
--
ALTER TABLE `tbl_favorites`
  ADD PRIMARY KEY (`favorite_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tbl_notifications_admin`
--
ALTER TABLE `tbl_notifications_admin`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tbl_notification_type`
--
ALTER TABLE `tbl_notification_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `tbl_rejected_reservations`
--
ALTER TABLE `tbl_rejected_reservations`
  ADD PRIMARY KEY (`rejected_reservation_id`);

--
-- Indexes for table `tbl_rent_history`
--
ALTER TABLE `tbl_rent_history`
  ADD PRIMARY KEY (`rent_history_id`);

--
-- Indexes for table `tbl_rent_history_status`
--
ALTER TABLE `tbl_rent_history_status`
  ADD PRIMARY KEY (`rent_history_status_id`);

--
-- Indexes for table `tbl_reservations`
--
ALTER TABLE `tbl_reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `tbl_reservation_status`
--
ALTER TABLE `tbl_reservation_status`
  ADD PRIMARY KEY (`reservation_status_id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tbl_spaces`
--
ALTER TABLE `tbl_spaces`
  ADD PRIMARY KEY (`space_id`);

--
-- Indexes for table `tbl_space_categories`
--
ALTER TABLE `tbl_space_categories`
  ADD PRIMARY KEY (`space_category_id`);

--
-- Indexes for table `tbl_space_status`
--
ALTER TABLE `tbl_space_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `tbl_space_types`
--
ALTER TABLE `tbl_space_types`
  ADD PRIMARY KEY (`space_type_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_user_roles`
--
ALTER TABLE `tbl_user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_approved_reservations`
--
ALTER TABLE `tbl_approved_reservations`
  MODIFY `approved_reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_cancelled_reservations`
--
ALTER TABLE `tbl_cancelled_reservations`
  MODIFY `cancelled_reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_closed_reservations`
--
ALTER TABLE `tbl_closed_reservations`
  MODIFY `closed_reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbl_favorites`
--
ALTER TABLE `tbl_favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tbl_notifications_admin`
--
ALTER TABLE `tbl_notifications_admin`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_notification_type`
--
ALTER TABLE `tbl_notification_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_rejected_reservations`
--
ALTER TABLE `tbl_rejected_reservations`
  MODIFY `rejected_reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_rent_history`
--
ALTER TABLE `tbl_rent_history`
  MODIFY `rent_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_rent_history_status`
--
ALTER TABLE `tbl_rent_history_status`
  MODIFY `rent_history_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_reservations`
--
ALTER TABLE `tbl_reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `tbl_reservation_status`
--
ALTER TABLE `tbl_reservation_status`
  MODIFY `reservation_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_spaces`
--
ALTER TABLE `tbl_spaces`
  MODIFY `space_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_space_categories`
--
ALTER TABLE `tbl_space_categories`
  MODIFY `space_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_space_status`
--
ALTER TABLE `tbl_space_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_space_types`
--
ALTER TABLE `tbl_space_types`
  MODIFY `space_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tbl_user_roles`
--
ALTER TABLE `tbl_user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
