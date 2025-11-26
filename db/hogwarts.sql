-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Ноя 26 2025 г., 19:14
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hogwarts`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `category_id` int UNSIGNED NOT NULL,
  `category_name` varchar(300) NOT NULL,
  `category_image` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `category_icon` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `category_parent_id` int UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `modules`
--

CREATE TABLE `modules` (
  `modules_id` int UNSIGNED NOT NULL,
  `modules_name` varchar(300) NOT NULL,
  `modules_order` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `orders_id` int UNSIGNED NOT NULL,
  `orders_user` int UNSIGNED NOT NULL,
  `orders_status` varchar(300) NOT NULL DEFAULT 'none',
  `orders_date` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `ordersItem`
--

CREATE TABLE `ordersItem` (
  `ordersItem_id` int NOT NULL,
  `ordersItem_order` int UNSIGNED NOT NULL,
  `ordersItem_product` int UNSIGNED NOT NULL,
  `ordersItem_quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_category` int UNSIGNED NOT NULL,
  `product_rating` float UNSIGNED NOT NULL,
  `product_supplier` int UNSIGNED NOT NULL,
  `product_price` float UNSIGNED NOT NULL,
  `product_description` varchar(500) NOT NULL,
  `product_image` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int UNSIGNED NOT NULL,
  `supplier_name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `user_id` int UNSIGNED NOT NULL,
  `user_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_surname` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_phone` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_password` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`modules_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_id`),
  ADD KEY `orders_user` (`orders_user`);

--
-- Индексы таблицы `ordersItem`
--
ALTER TABLE `ordersItem`
  ADD PRIMARY KEY (`ordersItem_id`),
  ADD KEY `ordersConfirmed_order` (`ordersItem_order`),
  ADD KEY `ordersConfirmed_product` (`ordersItem_product`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_supplier` (`product_supplier`),
  ADD KEY `product_category` (`product_category`);

--
-- Индексы таблицы `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `modules`
--
ALTER TABLE `modules`
  MODIFY `modules_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ordersItem`
--
ALTER TABLE `ordersItem`
  MODIFY `ordersItem_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`orders_user`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `ordersItem`
--
ALTER TABLE `ordersItem`
  ADD CONSTRAINT `ordersitem_ibfk_1` FOREIGN KEY (`ordersItem_order`) REFERENCES `orders` (`orders_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordersitem_ibfk_2` FOREIGN KEY (`ordersItem_product`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`product_supplier`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`product_category`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
