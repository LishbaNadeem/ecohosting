-- Database structure for ecohosting_db

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `packages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `space` VARCHAR(50) NOT NULL,
    `bandwidth` VARCHAR(50) NOT NULL,
    `backup` VARCHAR(50) NOT NULL,
    `domain` VARCHAR(50) NOT NULL,
    `databases_qty` VARCHAR(50) NOT NULL,
    `icon` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed packages table if empty
INSERT INTO `packages` (`id`, `name`, `type`, `price`, `space`, `bandwidth`, `backup`, `domain`, `databases_qty`, `icon`) VALUES
(1, 'Shared Hosting', 'Shared', 4.67, '2 TB of space', 'unlimited bandwidth', 'full backup systems', 'free domain', 'unlimited database', 'price1.svg'),
(2, 'Dedicated Hosting', 'Dedicated', 99.00, '10 TB of space', 'unlimited bandwidth', 'full backup systems', 'free domain', 'unlimited database', 'price2.svg'),
(3, 'Cloud Hosting', 'Cloud', 29.90, '5 TB of space', 'unlimited bandwidth', 'full backup systems', 'free domain', 'unlimited database', 'price3.svg')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `package_id` INT NOT NULL,
    `domain_name` VARCHAR(255) NOT NULL,
    `transaction_id` VARCHAR(255) DEFAULT NULL,
    `payment_method` VARCHAR(50) DEFAULT 'Card',
    `payment_status` VARCHAR(50) DEFAULT 'Paid',
    `amount_paid` DECIMAL(10,2) DEFAULT 0.00,
    `status` VARCHAR(50) DEFAULT 'Active',
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`package_id`) REFERENCES `packages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `contact_queries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
