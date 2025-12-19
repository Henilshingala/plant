# Plant Store - E-Commerce Website

## 📋 Project Overview
**Plant Store** is a full-featured **PHP-based e-commerce platform** for selling plants and gardening products online. This comprehensive web application includes user authentication, shopping cart, order management, payment integration, and a complete product catalog system.

## 🛠️ Technology Stack
- **Backend**: PHP 7.x+
- **Database**: MySQL (hosted on InfinityFree)
- **Frontend**: HTML5, CSS3, JavaScript
- **Payment**: Payment gateway integration
- **Hosting**: InfinityFree (free hosting)
- **Architecture**: MVC-like structure

## ✨ Key Features

### 1. **User Management**
- User registration and login
- Password hashing with PHP `password_hash()`
- Session management
- User profile management
- Order history
- Address management

### 2. **Product Catalog**
- Product categories
- Product listings
- Product details pages
- Product search functionality
- Product images
- Stock management
- Price management

### 3. **Shopping Cart**
- Add/remove items
- Update quantities
- Cart persistence (session/database)
- Real-time cart count
- Cart total calculations
- Duplicate item handling

### 4. **Order Management**
- Checkout process
- Order creation
- Order tracking
- Order history
- Order status updates
- Shipping address management

### 5. **Payment Integration**
- Payment gateway processing
- Payment verification
- Transaction records
- Order confirmation
- Multiple payment methods support

### 6. **Security Features**
- Password hashing and verification
- SQL injection prevention (prepared statements)
- XSS protection (`real_escape_string`)
- CSRF token generation and validation
- Session security
- Input sanitization

## 📁 Project Structure
```
plant-main/
├── config.php                   # Database & app configuration (215 lines)
├── index.php                    # Homepage
├── products.php                 # Product listing
├── product.php                  # Product details
├── cart.php                     # Shopping cart
├── checkout.php                 # Checkout page
├── order_confirmation.php       # Order success page
├── orders.php                   # Order history
├── profile.php                  # User profile
├── login.php                    # Login page
├── register.php                 # Registration page
├── logout.php                   # Logout handler
├── about.php                    # About us page
├── contact.php                  # Contact page
│
├── Processing Files:
├── add_to_cart.php             # Add to cart handler
├── remove_from_cart.php        # Remove from cart
├── update_cart_quantity.php    # Update cart quantity
├── process_payment.php         # Payment processing
├── verify_payment.php          # Payment verification
├── update_profile.php          # Profile update handler
├── update_address.php          # Address update handler
├── update_order_status.php     # Admin order status update
├── update_stock.php            # Stock management
│
├── Setup Files:
├── database.sql                # Database schema
├── sample_data.sql             # Sample data
├── sample_data.php             # Sample data generator
├── create_placeholders.php     # Create placeholder images
├── update_database.sql         # Database updates
│
├── Structure:
├── includes/                   # Reusable components
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
├── assets/                     # Static assets
│   ├── css/
│   ├── js/
│   └── images/
├── config/                     # Additional configs
└── composer.json              # PHP dependencies

```

## 🚀 Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional)

### Local Setup

1. **Navigate to project**
   ```bash
   cd plant-main/plant-main
   ```

2. **Create MySQL Database**
   ```sql
   CREATE DATABASE if0_38602234_plant;
   ```

3. **Import Database Schema**
   ```bash
   mysql -u your_user -p if0_38602234_plant < database.sql
   ```

4. **Import Sample Data (Optional)**
   ```bash
   mysql -u your_user -p if0_38602234_plant < sample_data.sql
   ```
   OR run:
   ```bash
   php sample_data.php
   ```

5. **Update Database Configuration**
   Edit `config.php`:
   ```php
   $host = 'localhost';
   $username = 'your_username';
   $password = 'your_password';
   $database = 'your_database_name';
   ```

6. **Start PHP Server**
   ```bash
   php -S localhost:8000
   ```

7. **Access Website**
   ```
   http://localhost:8000
   ```

## 🗄️ Database Configuration

### Current Configuration (config.php)
```php
// InfinityFree Hosting
$host = 'sql100.infinityfree.com';
$username = 'if0_38602234';
$password = 'Future404';
$database = 'if0_38602234_plant';
```

⚠️ **IMPORTANT**: Change these credentials for production!

### Database Tables
Based on the functions in config.php:
```sql
- users          # User accounts
- products       # Product catalog
- categories     # Product categories
- cart           # Shopping cart items
- orders         # Customer orders
- order_items    # Order details
```

## 🔐 Security Features

### Implemented Security:

1. **Password Security**
   ```php
   hashPassword($password)     // Using PASSWORD_DEFAULT
   verifyPassword($password, $hash)
   ```

2. **SQL Injection Prevention**
   ```php
   $stmt->prepare("SELECT ...");
   $stmt->bind_param("i", $user_id);
   ```

3. **Input Sanitization**
   ```php
   sanitize($input)  // Using real_escape_string
   ```

4. **CSRF Protection**
   ```php
   generateCSRFToken()
   validateCSRFToken($token)
   ```

5. **Session Security**
   - Automatic session start
   - Secure session handling
   - Login verification

### Validation Functions:
```php
validateEmail($email)          // Email validation
validatePhone($phone)          // Phone format (10 digits)
validatePrice($price)          // Price validation
validateQuantity($quantity)    // Quantity validation
```

## 🛒 Shopping Cart Features

### Cart Functions (config.php)

```php
// Add product to cart
addToCart($product_id, $quantity = 1)

// Update cart quantity
updateCartQuantity($product_id, $quantity)

// Remove from cart
removeFromCart($product_id)

// Get cart count
getCartCount()
```

### Cart Behavior:
- Duplicate items: Quantity increases
- Session-based for guests
- Database-stored for logged-in users
- Real-time updates

## 📦 Order Management

### Order Functions

```php
// Create new order
createOrder($user_id, $total_amount, $shipping_address)

// Add order items
addOrderItem($order_id, $product_id, $quantity, $price)
```

### Order Flow:
1. Customer adds items to cart
2. Proceeds to checkout
3. Enters shipping address
4. Completes payment
5. Order created in database
6. Order confirmation displayed
7. Order tracked in user account

## 🌿 Product Management

### Product Functions

```php
// Get single product
getProduct($product_id)

// Get products with filters
getProducts($category_id = null, $search = null)

// Get categories
getCategories()

// Get category
getCategory($category_id)
```

### Product Features:
- Category filtering
- Search functionality
- Stock tracking
- Price management
- Image handling

## 💳 Payment Integration

### Payment Files:
- `process_payment.php`: Initiate payment
- `verify_payment.php`: Verify payment status

### Payment Flow:
1. Customer completes checkout
2. Payment gateway called
3. Payment processed
4. Verification callback
5. Order status updated
6. Confirmation sent

## 🎨 Frontend Structure

### Page Templates:
- **includes/header.php**: Common header
- **includes/footer.php**: Common footer
- **includes/sidebar.php**: Sidebar navigation

### Assets Organization:
```
assets/
├── css/
│   ├── style.css
│   └── responsive.css
├── js/
│   ├── main.js
│   └── cart.js
└── images/
    ├── products/
    ├── categories/
    └── placeholders/
```

## 🔧 Utility Files

### sample_data.php
- Populate database with test data
- Create sample products
- Generate categories
- Add test users

### create_placeholders.php
- Generate placeholder images
- Create product image placeholders
- Testing without real images

## 📱 Responsive Design
- Mobile-friendly layout
- Touch-optimized cart
- Responsive product grids
- Mobile checkout process

## 🎯 Use Cases

### Perfect For:
- Plant nurseries
- Garden centers
- Online plant stores
- Gardening product retailers
- Succulents and cacti shops
- Indoor plant sellers

### Product Types:
- Indoor plants
- Outdoor plants
- Seeds
- Pots and planters
- Soil and fertilizers
- Gardening tools
- Plant care products

## 📊 Key Features Breakdown

### User Features ✅
- Registration and login
- Profile management
- Order history
- Address book
- Wishlist (potential)

### Shopping Features ✅
- Browse products
- Search and filter
- Add to cart
- Checkout
- Multiple payment methods

### Admin Features 🔧
- Product management (update_stock.php)
- Order status updates (update_order_status.php)
- Inventory control

## 🚨 Important Notes

### Before Production:
- ⚠️ **Change database credentials!**
- ⚠️ Update `$password = 'Future404'` in config.php
- ⚠️ Enable HTTPS
- ⚠️ Configure proper error handling
- ⚠️ Set up backup system
- ⚠️ Implement rate limiting
- ⚠️ Add email verification
- ⚠️ Configure payment gateway properly

### Security Checklist:
- [ ] Change database password
- [ ] Enable HTTPS/SSL
- [ ] Update session configuration
- [ ] Implement rate limiting
- [ ] Add CAPTCHA on forms
- [ ] Configure error logging
- [ ] Set up database backups
- [ ] Review file permissions

## 🌐 Deployment

### InfinityFree Hosting (Current)
- Free hosting platform
- MySQL included
- PHP support
- FTP access

### Migration to Production:
1. Export database
2. Update config.php
3. Upload files via FTP
4. Import database
5. Test all functionality
6. Configure domain

## 📈 Enhancement Ideas

### Future Features:
- 🔍 Advanced search with autocomplete
- ⭐ Product reviews and ratings
- 📸 Multiple product images
- 🏷️ Discount codes and coupons
- 📧 Email notifications
- 📊 Admin dashboard
- 📱 Mobile app API
- 💬 Live chat support
- 📦 Order tracking
- 🌱 Plant care guides
- 📅 Wishlist functionality
- 🔔 Low stock alerts

## 💡  Best Practices

### Code Quality:
- Use prepared statements
- Implement error handling
- Validate all inputs
- Sanitize outputs
- Follow coding standards

### Performance:
- Optimize database queries
- Index frequently queried columns
- Cache product listings
- Compress images
- Minify CSS/JS

---

**Business**: Plant & Garden E-Commerce
**Technology**: PHP + MySQL
**Features**: Full shopping cart, user auth, payments
**Hosting**: InfinityFree (current)
**Status**: Production-ready with security updates needed

**🌿 Beautiful Plants Delivered to Your Door!**

⚠️ **SECURITY WARNING**: Update database credentials and implement additional security measures before deploying to production!
