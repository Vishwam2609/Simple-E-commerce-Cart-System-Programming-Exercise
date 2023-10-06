-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2023 at 09:00 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `happyway`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `u_email` varchar(25) NOT NULL,
  `p_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(64) NOT NULL,
  `u_email` varchar(25) NOT NULL,
  `p_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `category` varchar(25) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(10) NOT NULL,
  `price` int(6) NOT NULL,
  `discount` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `name` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`u_email`,`p_id`),
  ADD KEY `cart_product` (`p_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_user` (`u_email`),
  ADD KEY `order_product` (`p_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_product` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_user` FOREIGN KEY (`u_email`) REFERENCES `users` (`email`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_product` FOREIGN KEY (`p_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_user` FOREIGN KEY (`u_email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


INSERT INTO `products` (`name`, `category`, `description`, `image`, `price`, `discount`) VALUES
('Product 1', 'Millet Snacks', 'This is product 1 of Millet Snacks category', '2.jpg', '100', '10'),
('Product 2', 'Millet Snacks', 'This is product 2 of Millet Snacks category', '2.jpg', '110', '10'),
('Product 3', 'Millet Snacks', 'This is product 3 of Millet Snacks category', '2.jpg', '120', '10'),
('Product 4', 'Millet Snacks', 'This is product 4 of Millet Snacks category', '2.jpg', '130', '10'),
('Product 5', 'Millet Snacks', 'This is product 5 of Millet Snacks category', '2.jpg', '140', '10'),
('Product 6', 'Millet Snacks', 'This is product 6 of Millet Snacks category', '2.jpg', '150', '10'),
('Product 7', 'GoLive Millet', 'This is product 1 of GoLive Millet category', '4.jpg', '100', '8'),
('Product 8', 'GoLive Millet', 'This is product 2 of GoLive Millet category', '4.jpg', '110', '8'),
('Product 9', 'GoLive Millet', 'This is product 3 of GoLive Millet category', '4.jpg', '120', '8'),
('Product 10', 'GoLive Millet', 'This is product 4 of GoLive Millet category', '4.jpg', '130', '8'),
('Product 11', 'GoLive Millet', 'This is product 5 of GoLive Millet category', '4.jpg', '140', '8'),
('Product 12', 'GoLive Millet', 'This is product 6 of GoLive Millet category', '4.jpg', '150', '8');