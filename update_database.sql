-- Add image column to categories table
ALTER TABLE categories ADD COLUMN image VARCHAR(255) AFTER description;

-- Add image column to products table
ALTER TABLE products ADD COLUMN image VARCHAR(255) AFTER stock;

-- Add address-related columns to users table
ALTER TABLE users 
ADD COLUMN city VARCHAR(100) AFTER address,
ADD COLUMN state VARCHAR(100) AFTER city,
ADD COLUMN zip_code VARCHAR(20) AFTER state;

-- Create payment_methods table
CREATE TABLE IF NOT EXISTS payment_methods (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    payment_type ENUM('razorpay') NOT NULL,
    payment_details JSON,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Add payment-related columns to orders table
ALTER TABLE orders 
ADD COLUMN payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending' AFTER user_id,
ADD COLUMN razorpay_order_id VARCHAR(255) AFTER payment_status,
ADD COLUMN razorpay_payment_id VARCHAR(255) AFTER razorpay_order_id,
ADD COLUMN razorpay_signature VARCHAR(255) AFTER razorpay_payment_id; 