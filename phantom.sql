-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: sql8.freesqldatabase.com
-- Generation Time: Nov 26, 2024 at 10:03 PM
-- Server version: 5.5.62-0ubuntu0.14.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sql8746154`
--

-- --------------------------------------------------------

--
-- Table structure for table `hb_amenities`
--

CREATE TABLE `hb_amenities` (
  `amenity_id` int(11) NOT NULL,
  `amenity_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hb_bookings`
--

CREATE TABLE `hb_bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('confirmed','pending','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `guests` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hb_bookings`
--

INSERT INTO `hb_bookings` (`booking_id`, `user_id`, `hotel_id`, `room_id`, `check_in_date`, `check_out_date`, `total_price`, `status`, `created_at`, `guests`) VALUES
(2, 7, 2, 4, '2024-11-23', '2024-11-29', '1200.00', 'cancelled', '2024-11-22 01:07:23', 2),
(3, 7, 3, 6, '2024-12-12', '2024-12-20', '2000.00', 'cancelled', '2024-11-22 01:08:35', 1),
(4, 7, 1, 1, '2024-11-23', '2024-11-30', '2100.00', 'cancelled', '2024-11-22 02:18:58', 3),
(5, 7, 2, 4, '2024-11-24', '2024-11-26', '400.00', 'cancelled', '2024-11-22 12:50:59', 2),
(7, 7, 3, 6, '2024-11-27', '2024-11-29', '500.00', 'pending', '2024-11-22 13:02:15', 2),
(8, 7, 1, 1, '2024-11-17', '2024-11-24', '2100.00', 'cancelled', '2024-11-22 13:22:56', 2),
(9, 7, 1, 1, '2024-11-08', '2024-11-23', '4500.00', 'cancelled', '2024-11-22 13:49:19', 2),
(10, 7, 1, 2, '2024-11-23', '2024-11-28', '1750.00', 'cancelled', '2024-11-22 14:18:11', 2),
(11, 7, 2, 4, '2024-11-24', '2024-11-27', '600.00', 'cancelled', '2024-11-23 18:17:44', 2),
(14, 7, 2, 4, '2024-11-27', '2024-11-29', '400.00', 'pending', '2024-11-26 00:38:10', 2),
(16, 7, 1, 1, '2024-11-27', '2024-11-29', '600.00', 'pending', '2024-11-26 18:33:51', 2);

-- --------------------------------------------------------

--
-- Table structure for table `hb_guests`
--

CREATE TABLE `hb_guests` (
  `guest_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `status` enum('Checked In','Checked Out','VIP') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hb_hotels`
--

CREATE TABLE `hb_hotels` (
  `hotel_id` int(11) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` text,
  `availability` tinyint(1) DEFAULT '1',
  `owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hb_hotels`
--

INSERT INTO `hb_hotels` (`hotel_id`, `hotel_name`, `location`, `address`, `description`, `availability`, `owner_id`, `created_at`) VALUES
(1, 'HU AT KO OLINA', 'Ko Olina, Hawaii', '123 Hawaii St, Ko Olina', 'Experience luxury in the heart of Hawaii', 1, 1, '2024-11-21 00:48:29'),
(2, 'ANGUILLA', 'Caribbean Islands', '456 Caribbean Blvd, Anguilla', 'Paradise found in the Caribbean', 1, 2, '2024-11-21 00:48:29'),
(3, 'THE OCEAN CLUB', 'Bahamas', '789 Paradise Rd, Bahamas', 'Connect with true Bahamian beauty and enjoy remarkable seclusion at this legendary Caribbean hideaway.', 1, 6, '2024-11-21 00:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `hb_hotel_amenities`
--

CREATE TABLE `hb_hotel_amenities` (
  `amenity_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `wifi` tinyint(1) DEFAULT '0',
  `pool` tinyint(1) DEFAULT '0',
  `spa` tinyint(1) DEFAULT '0',
  `restaurant` tinyint(1) DEFAULT '0',
  `valet` tinyint(1) DEFAULT '0',
  `concierge` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hb_hotel_amenities`
--

INSERT INTO `hb_hotel_amenities` (`amenity_id`, `hotel_id`, `wifi`, `pool`, `spa`, `restaurant`, `valet`, `concierge`) VALUES
(1, 1, 3, 1, 1, 0, 1, 0),
(2, 3, 1, 1, 0, 1, 0, 1),
(3, 2, 0, 0, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hb_hotel_images`
--

CREATE TABLE `hb_hotel_images` (
  `image_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hb_hotel_images`
--

INSERT INTO `hb_hotel_images` (`image_id`, `hotel_id`, `image_url`) VALUES
(83, 1, 'uploads/674643ce98b5b.jpg'),
(84, 1, 'uploads/674643ce98f70.jpg'),
(85, 1, 'uploads/674643ce99047.jpg'),
(86, 2, 'uploads/67464426a7a93.jpg'),
(87, 2, 'uploads/67464426a7c2d.jpg'),
(88, 2, 'uploads/67464426a7d3f.jpg'),
(89, 3, 'uploads/674645525b466.jpg'),
(90, 3, 'uploads/674645525b584.jpg'),
(91, 3, 'uploads/674645525b6b7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hb_payments`
--

CREATE TABLE `hb_payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','paypal') NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hb_reviews`
--

CREATE TABLE `hb_reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hb_rooms`
--

CREATE TABLE `hb_rooms` (
  `room_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_type` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `availability` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hb_rooms`
--

INSERT INTO `hb_rooms` (`room_id`, `hotel_id`, `room_type`, `capacity`, `price_per_night`, `availability`) VALUES
(1, 1, 'Deluxe Suite', 2, '300.00', 1),
(2, 1, 'Ocean View Room', 3, '350.00', 1),
(3, 1, 'Presidential Suite', 4, '500.00', 1),
(4, 2, 'Standard Room', 2, '200.00', 1),
(5, 2, 'Luxury Suite', 3, '400.00', 1),
(6, 3, 'Garden View Room', 2, '250.00', 1),
(7, 3, 'Beachfront Room', 4, '600.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hb_users`
--

CREATE TABLE `hb_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `user_type` enum('guest','owner','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hb_users`
--

INSERT INTO `hb_users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `user_type`, `created_at`) VALUES
(1, 'Kelvin', 'Ahiakpor', 'kelvin@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', '0505538564', 'owner', '2024-11-20 21:09:12'),
(2, 'Denis', 'Aggyratus', 'denis@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', NULL, 'owner', '2024-11-20 21:09:13'),
(3, 'Vera', 'Anthonio', 'vera@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', NULL, 'admin', '2024-11-20 21:09:14'),
(4, 'Bryan', 'Hans-Ampiah', 'bryan@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', NULL, 'admin', '2024-11-20 21:09:15'),
(5, 'Nana', 'Ntim', 'nana@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', NULL, 'guest', '2024-11-20 21:09:16'),
(6, 'Emmanuel ', 'Bart-Plange', 'emmanuel@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', NULL, 'owner', '2024-11-26 21:32:53'),
(7, 'Freddie', 'Gibbs', 'freddie@phantom.com', '$2y$10$W3lvBTgR3LW4Cnj4T5s7CedIgqbbRMnlKyCLO2wkJGV5qUl3bnvE2', '0505538564', 'guest', '2024-11-20 23:34:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hb_amenities`
--
ALTER TABLE `hb_amenities`
  ADD PRIMARY KEY (`amenity_id`),
  ADD UNIQUE KEY `amenity_name` (`amenity_name`);

--
-- Indexes for table `hb_bookings`
--
ALTER TABLE `hb_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `hb_guests`
--
ALTER TABLE `hb_guests`
  ADD PRIMARY KEY (`guest_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `hb_hotels`
--
ALTER TABLE `hb_hotels`
  ADD PRIMARY KEY (`hotel_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `hb_hotel_amenities`
--
ALTER TABLE `hb_hotel_amenities`
  ADD PRIMARY KEY (`amenity_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hb_hotel_images`
--
ALTER TABLE `hb_hotel_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hb_payments`
--
ALTER TABLE `hb_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `hb_reviews`
--
ALTER TABLE `hb_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hb_rooms`
--
ALTER TABLE `hb_rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hb_users`
--
ALTER TABLE `hb_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hb_amenities`
--
ALTER TABLE `hb_amenities`
  MODIFY `amenity_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hb_bookings`
--
ALTER TABLE `hb_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `hb_guests`
--
ALTER TABLE `hb_guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hb_hotels`
--
ALTER TABLE `hb_hotels`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `hb_hotel_amenities`
--
ALTER TABLE `hb_hotel_amenities`
  MODIFY `amenity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `hb_hotel_images`
--
ALTER TABLE `hb_hotel_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `hb_payments`
--
ALTER TABLE `hb_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hb_reviews`
--
ALTER TABLE `hb_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hb_rooms`
--
ALTER TABLE `hb_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `hb_users`
--
ALTER TABLE `hb_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `hb_bookings`
--
ALTER TABLE `hb_bookings`
  ADD CONSTRAINT `hb_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `hb_users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hb_bookings_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hb_bookings_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `hb_rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `hb_guests`
--
ALTER TABLE `hb_guests`
  ADD CONSTRAINT `hb_guests_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `hb_rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `hb_hotels`
--
ALTER TABLE `hb_hotels`
  ADD CONSTRAINT `hb_hotels_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `hb_users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `hb_hotel_amenities`
--
ALTER TABLE `hb_hotel_amenities`
  ADD CONSTRAINT `hb_hotel_amenities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- Constraints for table `hb_hotel_images`
--
ALTER TABLE `hb_hotel_images`
  ADD CONSTRAINT `hb_hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- Constraints for table `hb_payments`
--
ALTER TABLE `hb_payments`
  ADD CONSTRAINT `hb_payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `hb_bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `hb_reviews`
--
ALTER TABLE `hb_reviews`
  ADD CONSTRAINT `hb_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `hb_users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hb_reviews_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- Constraints for table `hb_rooms`
--
ALTER TABLE `hb_rooms`
  ADD CONSTRAINT `hb_rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels` (`hotel_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
