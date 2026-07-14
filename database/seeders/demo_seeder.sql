-- Demo seed data
-- Password for demo users: password123

INSERT IGNORE INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `passwd`, `phone`, `address`) VALUES
(1, 'Demo', 'Customer', 'customer@estock.test', '$2y$10$twFrP9oyiMSyH64LPZ0iResmx4rYRfN9FXxfJ.tLhUtg2LmBuzR3y', '+265999000001', 'Lilongwe, Malawi'),
(2, 'Demo', 'Merchant', 'merchant@estock.test', '$2y$10$twFrP9oyiMSyH64LPZ0iResmx4rYRfN9FXxfJ.tLhUtg2LmBuzR3y', '+265999000002', 'Blantyre, Malawi');

INSERT IGNORE INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2);

INSERT IGNORE INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Electronics'),
(2, 'Fashion'),
(3, 'Home & Living'),
(4, 'Groceries');

INSERT IGNORE INTO `products` (`product_id`, `product_name`, `product_description`, `product_price`, `category_id`, `quantity`, `sku`, `low_stock_threshold`, `user_id`) VALUES
(1, 'Wireless Bluetooth Headphones', 'Comfortable over-ear headphones with noise cancellation and 30-hour battery life.', 45000.00, 1, 25, 'EL-HEAD-001', 5, 2),
(2, 'Smart LED Desk Lamp', 'Adjustable brightness LED lamp with USB charging port.', 18500.00, 1, 40, 'EL-LAMP-002', 5, 2),
(3, 'Classic Cotton T-Shirt', 'Soft 100% cotton unisex t-shirt available in multiple sizes.', 8500.00, 2, 60, 'FA-TSHIRT-003', 10, 2),
(4, 'Ceramic Coffee Mug Set', 'Set of 4 durable ceramic mugs — dishwasher safe.', 12000.00, 3, 30, 'HL-MUG-004', 5, 2),
(5, 'Organic Honey 500g', 'Pure organic honey sourced from local farms.', 6500.00, 4, 50, 'GR-HONEY-005', 8, 2);
