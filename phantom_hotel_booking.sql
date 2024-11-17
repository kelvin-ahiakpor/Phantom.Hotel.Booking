-- Users Table
CREATE TABLE `hb_users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL, -- Hashed password
    `phone_number` VARCHAR(15),
    `user_type` ENUM('guest', 'owner', 'admin') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hotels Table
CREATE TABLE `hb_hotels` (
    `hotel_id` INT AUTO_INCREMENT PRIMARY KEY,
    `hotel_name` VARCHAR(255) NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price_per_night` DECIMAL(10,2) NOT NULL,
    `availability` BOOLEAN DEFAULT TRUE,
    `owner_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`owner_id`) REFERENCES `hb_users`(`user_id`) ON DELETE SET NULL
);

-- Hotel Images Table
CREATE TABLE `hb_hotel_images` (
    `image_id` INT AUTO_INCREMENT PRIMARY KEY,
    `hotel_id` INT NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels`(`hotel_id`) ON DELETE CASCADE
);

-- Rooms Table
CREATE TABLE `hb_rooms` (
    `room_id` INT AUTO_INCREMENT PRIMARY KEY,
    `hotel_id` INT NOT NULL,
    `room_type` VARCHAR(100) NOT NULL,
    `capacity` INT NOT NULL,
    `price_per_night` DECIMAL(10,2) NOT NULL,
    `availability` BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels`(`hotel_id`) ON DELETE CASCADE
);

-- Amenities Table
CREATE TABLE `hb_amenities` (
    `amenity_id` INT AUTO_INCREMENT PRIMARY KEY,
    `amenity_name` VARCHAR(100) NOT NULL UNIQUE
);

-- Hotel Amenities Table (Many-to-Many)
CREATE TABLE `hb_hotel_amenities` (
    `hotel_id` INT NOT NULL,
    `amenity_id` INT NOT NULL,
    PRIMARY KEY (`hotel_id`, `amenity_id`),
    FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels`(`hotel_id`) ON DELETE CASCADE,
    FOREIGN KEY (`amenity_id`) REFERENCES `hb_amenities`(`amenity_id`) ON DELETE CASCADE
);

-- Guests Table
CREATE TABLE `hb_guests` (
    `guest_id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `room_id` INT NOT NULL,
    `check_in_date` DATE NOT NULL,
    `check_out_date` DATE NOT NULL,
    `status` ENUM('Checked In', 'Checked Out', 'VIP') NOT NULL,
    FOREIGN KEY (`room_id`) REFERENCES `hb_rooms`(`room_id`) ON DELETE CASCADE
);

-- Bookings Table
CREATE TABLE `hb_bookings` (
    `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `hotel_id` INT NOT NULL,
    `room_id` INT NOT NULL,
    `check_in_date` DATE NOT NULL,
    `check_out_date` DATE NOT NULL,
    `total_price` DECIMAL(10,2) NOT NULL,
    `status` ENUM('confirmed', 'pending', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `hb_users`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels`(`hotel_id`) ON DELETE CASCADE,
    FOREIGN KEY (`room_id`) REFERENCES `hb_rooms`(`room_id`) ON DELETE CASCADE
);

-- Reviews Table
CREATE TABLE `hb_reviews` (
    `review_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `hotel_id` INT NOT NULL,
    `rating` INT CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `hb_users`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`hotel_id`) REFERENCES `hb_hotels`(`hotel_id`) ON DELETE CASCADE
);

-- Payments Table
CREATE TABLE `hb_payments` (
    `payment_id` INT AUTO_INCREMENT PRIMARY KEY,
    `booking_id` INT NOT NULL,
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `amount` DECIMAL(10,2) NOT NULL,
    `payment_method` ENUM('credit_card', 'paypal') NOT NULL,
    `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    FOREIGN KEY (`booking_id`) REFERENCES `hb_bookings`(`booking_id`) ON DELETE CASCADE
);
