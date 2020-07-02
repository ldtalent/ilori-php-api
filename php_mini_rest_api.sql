-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 02, 2020 at 12:53 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_mini_rest_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `db_catalogs`
--

CREATE TABLE `db_catalogs` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `db_catalogs`
--

INSERT INTO `db_catalogs` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Nike', '2020-07-01 14:24:28', '2020-07-01 15:11:24'),
(2, 'Addidas', '2020-07-01 14:24:44', '2020-07-01 14:24:44');

-- --------------------------------------------------------

--
-- Table structure for table `db_products`
--

CREATE TABLE `db_products` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `catalog_id` bigint(20) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `color` text NOT NULL,
  `size` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `db_products`
--

INSERT INTO `db_products` (`id`, `name`, `catalog_id`, `banner`, `price`, `color`, `size`, `created_at`, `updated_at`) VALUES
(1, 'Nike Air', 2, 'public/img/1593652856_Screenshot from 01. NetNaija.com - Standing Up for Sunny (2019) || NetNaija.com.png', 5000, 'Red', 'md', '2020-07-02 01:20:18', '2020-07-02 01:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `db_token`
--

CREATE TABLE `db_token` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `jwt_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `db_token`
--

INSERT INTO `db_token` (`id`, `user_id`, `jwt_token`) VALUES
(1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1OTM1OTcxMjIsImlzcyI6IlBIUF9NSU5JX1JFU1RfQVBJIiwiZXhwIjoxNTkzNjMxNjIwLCJ1c2VyX2lkIjoiMSJ9.p2BKoS9YE_2YV77KTpfALG7CVuwDkueLakVD3gWfMgY'),
(2, 2, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1OTM1OTgyNDQsImlzcyI6IlBIUF9NSU5JX1JFU1RfQVBJIiwiZXhwIjoxNTk0MjAzMDQ0LCJ1c2VyX2lkIjoiMiJ9.zlR7bK3smflGHaRiLULU6jmy5Bt19p1Gqg7dZnupRKc');

-- --------------------------------------------------------

--
-- Table structure for table `db_users`
--

CREATE TABLE `db_users` (
  `id` int(11) NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `db_users`
--

INSERT INTO `db_users` (`id`, `firstName`, `lastName`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Stephen', 'Ilori', 'stephenilori458@gmail.com', '$2y$10$mM/YuCSVgazrjXO75OR1.u83c7/9m9RFC6W8/AsaNbD4.0l/iodCG', '2020-07-01 09:52:02', '2020-07-01 09:52:02'),
(2, 'Stephen', 'Ilori', 'stephenilori658@gmail.com', '$2y$10$LDX7K7iU7X3FRZvAUVESFOSNEpU8zcWganhsBWx7qN6vZlhFz506S', '2020-07-01 10:10:44', '2020-07-01 10:10:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `db_catalogs`
--
ALTER TABLE `db_catalogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_products`
--
ALTER TABLE `db_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_token`
--
ALTER TABLE `db_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_users`
--
ALTER TABLE `db_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `db_catalogs`
--
ALTER TABLE `db_catalogs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `db_products`
--
ALTER TABLE `db_products`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `db_token`
--
ALTER TABLE `db_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `db_users`
--
ALTER TABLE `db_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
