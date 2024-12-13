-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 13, 2023 at 05:07 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `pid` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int NOT NULL,
  `placed_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(7, 2, 'Jun Mark', '9283613627', 'jmritual.student@gmail.com', 'Gcash', '8888, Blk 6 Lot 25, Purok 2, Balintawak, Lipa, Batangas, Philippines - 4227', 'Sweet & Spicy Pork (60 x 2) - Goto (60 x 1) - Halo-Halo (30 x 1) - ', 210, '2023-12-10 01:39:22', 'completed'),
(8, 2, 'Jun Mark', '9283613627', 'jmritual.student@gmail.com', 'Gcash', '8888, Blk 6 Lot 25, Purok 2, Balintawak, Lipa, Batangas, Philippines - 4227', 'Sweet & Spicy Pork (60 x 1) - Goto (60 x 1) - ', 120, '2023-12-12 01:56:54', 'pending'),
(9, 2, 'Jun Mark', '9283613627', 'jmritual.student@gmail.com', 'Cash on delivery', '8888, Blk 6 Lot 25, Purok 2, Balintawak, Lipa, Batangas, Philippines - 4227', 'Goto (60 x 1) - ', 60, '2023-12-12 02:02:04', 'pending'),
(10, 2, 'Jun Mark', '9283613627', 'jmritual.student@gmail.com', 'Cash on delivery', '8888, Blk 6 Lot 25, Purok 2, Balintawak, Lipa, Batangas, Philippines - 4227', 'Goto (60 x 1) - Lomi (60 x 2) - Sweet & Spicy Pork (60 x 2) - ', 300, '2023-12-13 02:59:40', 'pending'),
(11, 2, 'Jun Mark', '9283613627', 'jmritual.student@gmail.com', 'Cash on delivery', '8888, Blk 6 Lot 25, Purok 2, Balintawak, Lipa, Batangas, Philippines - 4227', 'Sweet & Spicy Pork (60 x 2) - Leche Flan (70 x 1) - ', 190, '2023-12-13 03:07:52', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`) VALUES
(5, 'Sweet & Spicy Pork', 'Main Dish', 60, 'Sweet.And.Spicy.Pork.jpg'),
(6, 'Goto', 'Main Dish', 60, 'Goto.png'),
(7, 'Halo-Halo', 'Dessert', 30, 'Halo_halo.jpg'),
(8, 'Lomi', 'Main Dish', 60, 'Lomi.jpg'),
(10, 'Coke', 'Drink', 25, 'coke.png'),
(11, 'Royal', 'Drink', 25, 'royal.png'),
(12, 'Sprite', 'Drink', 25, 'sprite.png'),
(13, 'Leche Flan', 'Dessert', 70, 'lecheflan.jpg'),
(14, 'Pork Adobo', 'Main dish', 60, 'PorkAdobo.png'),
(15, 'Sisig', 'Main dish', 75, 'sisig.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `address`) VALUES
(2, 'Jun Mark', 'jmritual.student@gmail.com', '9283613627', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '8888, Blk 6 Lot 25, Purok 2, Balintawak, Lipa, Batangas, Philippines - 4227');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
