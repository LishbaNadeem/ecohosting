<?php
// Database configuration constants (temporary, to connect to MySQL without selecting database first)
$host = 'localhost';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("MySQL Connection failed: " . $conn->connect_error);
}

// 1. Create database if it doesn't exist
$dbName = 'ecohosting_db';
$sql = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbName);

// 2. Create users table
$tableUsers = "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";
if ($conn->query($tableUsers) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// 3. Create packages table
$tablePackages = "CREATE TABLE IF NOT EXISTS `packages` (
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
) ENGINE=InnoDB;";
if ($conn->query($tablePackages) === TRUE) {
    echo "Table 'packages' created successfully.<br>";
} else {
    echo "Error creating table 'packages': " . $conn->error . "<br>";
}

// Seed packages table if empty
$checkPackages = "SELECT COUNT(*) as count FROM `packages`";
$result = $conn->query($checkPackages);
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $seedPackages = "INSERT INTO `packages` (`name`, `type`, `price`, `space`, `bandwidth`, `backup`, `domain`, `databases_qty`, `icon`) VALUES
    ('Shared Hosting', 'Shared', 4.67, '2 TB of space', 'unlimited bandwidth', 'full backup systems', 'free domain', 'unlimited database', 'price1.svg'),
    ('Dedicated Hosting', 'Dedicated', 99.00, '10 TB of space', 'unlimited bandwidth', 'full backup systems', 'free domain', 'unlimited database', 'price2.svg'),
    ('Cloud Hosting', 'Cloud', 29.90, '5 TB of space', 'unlimited bandwidth', 'full backup systems', 'free domain', 'unlimited database', 'price3.svg')";
    
    if ($conn->query($seedPackages) === TRUE) {
        echo "Seeded database packages successfully.<br>";
    } else {
        echo "Error seeding packages: " . $conn->error . "<br>";
    }
} else {
    echo "Packages already seeded.<br>";
}

// 4. Create orders table
$tableOrders = "CREATE TABLE IF NOT EXISTS `orders` (
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
) ENGINE=InnoDB;";
if ($conn->query($tableOrders) === TRUE) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating table 'orders': " . $conn->error . "<br>";
}

// Add columns if table already exists without them
$alterCols = [
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `transaction_id` VARCHAR(255) DEFAULT NULL",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_method` VARCHAR(50) DEFAULT 'Card'",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_status` VARCHAR(50) DEFAULT 'Paid'",
    "ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `amount_paid` DECIMAL(10,2) DEFAULT 0.00"
];
foreach ($alterCols as $alterSql) {
    @$conn->query($alterSql);
}

// 5. Create contact_queries table
$tableQueries = "CREATE TABLE IF NOT EXISTS `contact_queries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;";
if ($conn->query($tableQueries) === TRUE) {
    echo "Table 'contact_queries' created successfully.<br>";
} else {
    echo "Error creating table 'contact_queries': " . $conn->error . "<br>";
}

$conn->close();
echo "Database setup finished.";
?>
