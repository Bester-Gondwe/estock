-- Upgrade script for existing eStock databases
-- Run after backing up: mysql -u root estock < sql/upgrade.sql

USE `estock`;

ALTER TABLE `products`
  MODIFY `product_name` varchar(150) NOT NULL,
  MODIFY `product_description` text NOT NULL,
  MODIFY `product_price` decimal(12,2) NOT NULL,
  MODIFY `created_at` timestamp NOT NULL DEFAULT current_timestamp();

-- Add columns only if missing (run individually if your MySQL version lacks IF NOT EXISTS)
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `sku` varchar(64) DEFAULT NULL AFTER `quantity`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `low_stock_threshold` int(11) NOT NULL DEFAULT 5 AFTER `sku`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() AFTER `created_at`;

ALTER TABLE `orders`
  MODIFY `order_status` varchar(20) NOT NULL DEFAULT 'Pending',
  MODIFY `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  MODIFY `order_date` timestamp NOT NULL DEFAULT current_timestamp();

ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `shipping_address` varchar(255) DEFAULT NULL AFTER `amount`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_method` varchar(50) DEFAULT 'Cash on Delivery' AFTER `shipping_address`;
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `notes` text DEFAULT NULL AFTER `payment_method`;

ALTER TABLE `order_details` ADD COLUMN IF NOT EXISTS `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `quantity`;

ALTER TABLE `users` MODIFY `email` varchar(100) NOT NULL;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` varchar(30) DEFAULT NULL AFTER `passwd`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` varchar(255) DEFAULT NULL AFTER `phone`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `created_at` timestamp NOT NULL DEFAULT current_timestamp() AFTER `address`;

ALTER TABLE `categories` MODIFY `category_name` varchar(100) NOT NULL;

CREATE TABLE IF NOT EXISTS `inventory_movements` (
  `movement_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `change_qty` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`movement_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
