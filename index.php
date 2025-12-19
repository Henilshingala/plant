<?php
require_once 'includes/header.php';

// Get one product from each category, using date to rotate products
$current_day = date('z'); // Day of the year (0-365)

$query = "SELECT p.*, c.name as category_name, 
          ROW_NUMBER() OVER (PARTITION BY c.id ORDER BY p.id) as product_num,
          COUNT(*) OVER (PARTITION BY c.id) as total_products
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE p.stock > 0";

$result = $conn->query($query);
$featured_products = [];

while ($row = $result->fetch_assoc()) {
    $category_id = $row['category_id'];
    $total_products = $row['total_products'];
    
    // Use current day to rotate products, wrapping around if needed
    $featured_product_num = ($current_day % $total_products) + 1;
    
    if ($row['product_num'] == $featured_product_num) {
        $featured_products[] = $row;
    }
}
?>

<!-- Hero Section -->
<div class="hero-section position-relative mb-5">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-50 py-5">
            <div class="col-md-6">
                <h1 class="display-4 text-white">Welcome to Plant Shop</h1>
                <p class="lead text-white">Discover our beautiful collection of plants for your home and garden.</p>
                <a href="products.php" class="btn btn-success btn-lg">Browse All Products</a>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container">
    <h2 class="text-center mb-4">Today's Featured Plants</h2>
    <div class="row">
        <?php foreach ($featured_products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card h-100">
                    <div class="badge bg-success position-absolute top-0 end-0 m-2">Featured</div>
                    <img src="assets/images/products/<?php echo $product['image']; ?>" 
                         class="card-img-top product-image" 
                         alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text text-muted"><?php echo $product['category_name']; ?></p>
                        <p class="card-text">₹<?php echo number_format($product['price'], 2); ?></p>
                        <p class="card-text"><small class="text-muted">Stock: <?php echo $product['stock']; ?></small></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" 
                           class="btn btn-success">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Categories Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Browse Categories</h2>
    <div class="row">
        <?php
        $categories_query = "SELECT * FROM categories";
        $categories_result = $conn->query($categories_query);
        while ($category = $categories_result->fetch_assoc()):
        ?>
            <div class="col-md-4 mb-4">
                <div class="card category-card">
                    <img src="assets/images/categories/<?php echo $category['image']; ?>" 
                         class="card-img-top category-image" 
                         alt="<?php echo $category['name']; ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $category['name']; ?></h5>
                        <a href="products.php?category=<?php echo $category['id']; ?>" 
                           class="btn btn-outline-success">View Products</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<style>
.hero-section {
    position: relative;
    overflow: hidden;
    background-color: #f8f9fa;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('assets/images/bg.webp');
    background-size: cover;
    background-position: center;
    filter: blur(8px);
    transform: scale(1.1);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.min-vh-50 {
    min-height: 50vh;
}

.hero-section .container {
    z-index: 1;
}

.hero-section h1 {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-section .lead {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}
</style>

<?php require_once 'includes/footer.php'; ?> 