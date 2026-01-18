-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jan 18, 2026 at 04:42 PM
-- Server version: 12.0.2-MariaDB-ubu2404
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `salonId` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  `specialistId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `startsAt` datetime NOT NULL,
  `endsAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `salonId`, `serviceId`, `specialistId`, `customerId`, `startsAt`, `endsAt`) VALUES
(1, 1, 1, 12, 10, '2026-01-15 09:00:00', '2026-01-15 10:00:00'),
(2, 1, 1, 12, 9, '2026-01-16 10:00:00', '2026-01-16 11:00:00'),
(3, 1, 1, 12, 9, '2026-01-15 10:00:00', '2026-01-15 11:00:00'),
(5, 1, 1, 11, 7, '2026-01-14 10:00:00', '2026-01-14 11:00:00'),
(6, 1, 1, 12, 10, '2026-01-31 09:00:00', '2026-01-31 10:00:00'),
(7, 1, 1, 12, 10, '2026-01-30 09:00:00', '2026-01-30 10:00:00'),
(8, 1, 1, 12, 10, '2026-01-14 13:00:00', '2026-01-14 14:00:00'),
(9, 1, 1, 12, 10, '2026-01-16 12:15:00', '2026-01-16 13:15:00'),
(10, 1, 1, 12, 10, '2026-01-15 11:00:00', '2026-01-15 12:00:00'),
(13, 1, 4, 15, 7, '2026-01-15 09:00:00', '2026-01-15 14:00:00'),
(14, 1, 4, 15, 7, '2026-01-16 09:00:00', '2026-01-16 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `salons`
--

CREATE TABLE `salons` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `salons`
--

INSERT INTO `salons` (`id`, `ownerId`, `name`, `type`, `address`, `city`, `phone`, `email`, `created_at`) VALUES
(1, 5, 'black skin tattoo', 'Tattoo', 'Kleine Houtstraat 20', 'Haarlem', '0638999473', 'blackskinhaarlem@gmail.com', '2026-01-04 13:31:59'),
(3, 8, 'masculine masculinity', 'Barber', 'Grote Markt 3', 'Haarlememeer', '06000000000', 'mmm@gmail.com', '2026-01-04 14:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `salonServices`
--

CREATE TABLE `salonServices` (
  `id` int(11) NOT NULL,
  `salonId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `durationMinutes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `salonServices`
--

INSERT INTO `salonServices` (`id`, `salonId`, `name`, `price`, `durationMinutes`) VALUES
(1, 1, 'small tattoo (0-5cm)', 100.00, 60),
(4, 1, 'large tattoo (10-20cm)', 200.00, 300),
(5, 1, 'tiny tattoo', 50.00, 30);

-- --------------------------------------------------------

--
-- Table structure for table `specialistSalonServices`
--

CREATE TABLE `specialistSalonServices` (
  `serviceId` int(11) NOT NULL,
  `specialistId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `specialistSalonServices`
--

INSERT INTO `specialistSalonServices` (`serviceId`, `specialistId`) VALUES
(1, 11),
(1, 12),
(4, 11),
(4, 15),
(5, 11),
(5, 12),
(5, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` varchar(25) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salonId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `firstName`, `lastName`, `email`, `phone`, `password`, `salonId`) VALUES
(3, 'customer', 'Kate', 'Jo', 'katejo@gmail.com', '07567368423', '$2y$12$CvifMSUkWQTAL..JcEvnMeaodUzu.8JsTh6fvfUkOFqO3TOaLEZnW', NULL),
(4, 'customer', 'Maria', 'Jo', 'mariajo@gmail.com', '0493973432', '$2y$12$TMtS2HAKh2EHrLje04vIqemERLWPZP3fE2ctdu.2HMITPOUWE4tm.', NULL),
(5, 'owner', 'Kira', 'K', 'kirak@gmail.com', '8436876873', '$2y$12$O7nR9Kdb5kylP3uuWVz3NukjFLqcngAodYA0q4SWADwT37DHSiFMG', 1),
(6, 'customer', 'Anya', 'Lora', 'anyalora@gmail.com', '982342828347', '$2y$12$CWnJ2Uy6n4xEvlS5jDTuB.R2MXCTQO7lQu.1obJvmGfUui4sjfTXi', NULL),
(7, 'customer', 'Anna', 'Maria', 'annamaria@gmail.com', '048329749', '$2y$12$aEcXos8I7WclMG0pUE57J.jN9YNStTFnTJ/xwbUns0T9gUMgFq1k6', NULL),
(8, 'owner', 'Alla', 'Mira', 'allamira@gmail.com', '983985489', '$2y$12$VVGX.pxPKbaL818HG0yB0uKA4geRzwpBTx3j3Yyjs/g4CyPVdLxum', 3),
(9, 'receptionist', 'Kate', 'M', 'katem@gmail.com', '03498395739', '$2y$12$EX298UDCwAFAsMPPt0eKMOgfHt/Y4CFanNbWnBVE3njTmilUELSKG', 1),
(10, 'customer', 'Ana', 'Mena', 'anamena@gmail.com', '08493753', NULL, NULL),
(11, 'specialist', 'Renata', 'Gibson', 'renatagibson@gmil.com', '0458395893', '$2y$12$99dbPnmW8bJujV/iTD15Q.pNxfcsvFCodzqvAugBd5cJiNbeiZseu', 1),
(12, 'specialist', 'John', 'Gibson', 'johngibson@gmil.com', '0458395893', '$2y$12$ErVpiOUpxRLjr5P/r/I8GuLSGyjS.LYBH2fk5O.2swpoVbeMKC0k2', 1),
(15, 'specialist', 'Kira', 'Magdalena', 'kiram@gmail.com', '9437297349', '$2y$12$AncA/rr4HMLxEu0P2uHmqexZj7WF3SeExYjvGe1p8gwE2HBwBw/oq', 1),
(16, 'customer', 'Jo', 'Jo', 'jojo@gmail.com', '8837458723', '$2y$12$aRMPpGOZXYmvWKFB4mCm4ueGQ/.RTj44Y0v.Uxe7bL275l/ldou6u', NULL),
(18, 'customer', 'New<h1>', 'Customer', 'newcustomer@gmail.com', '397587348', '$2y$12$kLLNd9hFe4AMwV.o6T6rnOuxJXkgGg7cysqLpTvsMk9Ew.6bZxUVi', NULL),
(19, 'customer', 'Another', 'Customer', 'anothercustomer@gmail.com', '74357237', '$2y$12$BKUf6R/CZt1nqcdIbKoiOustQajA1dBorGlIUZO995jwBWi99hEo2', NULL),
(20, 'customer', 'Sad', 'Customer', 'sadcustomer', '874328748', '$2y$12$Psz/kJDRkOJgHFx02qoAxOIQIZvfL9/b/LYKf7BPiijH05An47IQa', NULL),
(23, 'receptionist', 'new', 'new', 'new@new.new', '0209403242389', '$2y$12$gLY/uDOOGaRqQF6jeMQPOeAnGTWdt1.Han6NnJ5eLNg2L6olEjYb6', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_salon_starts_at` (`salonId`,`startsAt`),
  ADD KEY `idx_specialist_starts_at` (`specialistId`,`startsAt`),
  ADD KEY `idx_customer_starts_at` (`customerId`,`startsAt`),
  ADD KEY `serviceId` (`serviceId`);

--
-- Indexes for table `salons`
--
ALTER TABLE `salons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_salon_users` (`ownerId`);

--
-- Indexes for table `salonServices`
--
ALTER TABLE `salonServices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_salon_services_salon` (`salonId`);

--
-- Indexes for table `specialistSalonServices`
--
ALTER TABLE `specialistSalonServices`
  ADD PRIMARY KEY (`serviceId`,`specialistId`),
  ADD KEY `idx_service` (`serviceId`),
  ADD KEY `specialistSalonServices_ibfk_2` (`specialistId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_salon` (`salonId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `salons`
--
ALTER TABLE `salons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `salonServices`
--
ALTER TABLE `salonServices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`salonId`) REFERENCES `salons` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`serviceId`) REFERENCES `salonServices` (`id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`specialistId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`customerId`) REFERENCES `users` (`id`);

--
-- Constraints for table `salons`
--
ALTER TABLE `salons`
  ADD CONSTRAINT `fk_salon_users` FOREIGN KEY (`ownerId`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `salonServices`
--
ALTER TABLE `salonServices`
  ADD CONSTRAINT `fk_salon_services_salon` FOREIGN KEY (`salonId`) REFERENCES `salons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `specialistSalonServices`
--
ALTER TABLE `specialistSalonServices`
  ADD CONSTRAINT `specialistSalonServices_ibfk_1` FOREIGN KEY (`serviceId`) REFERENCES `salonServices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `specialistSalonServices_ibfk_2` FOREIGN KEY (`specialistId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_salon` FOREIGN KEY (`salonId`) REFERENCES `salons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
