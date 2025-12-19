<?php
require_once 'includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header("Location: products.php");
    exit();
}

// Get product details
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = $product_id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();

// Get related products (products from the same category)
$related_query = "SELECT * FROM products 
                WHERE category_id = {$product['category_id']} 
                AND id != $product_id 
                AND stock > 0 
                LIMIT 4";
$related_result = $conn->query($related_query);

$related_products = [];
if ($related_result) {
    while ($related = $related_result->fetch_assoc()) {
        $related_products[] = $related;
    }
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }

    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && $quantity <= $product['stock']) {
        $user_id = $_SESSION['user_id'];
        
        // Check if product already in cart
        $check_query = "SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id";
        $check_result = $conn->query($check_query);
        
        if ($check_result->num_rows > 0) {
            $cart_item = $check_result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            
            if ($new_quantity <= $product['stock']) {
                $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = {$cart_item['id']}";
                $conn->query($update_query);
            }
        } else {
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
            $conn->query($insert_query);
        }
        
        header("Location: cart.php");
        exit();
    }
}
?>

<div class="container my-4">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-4">
            <div class="product-image-container">
                <img src="/mp/assets/images/products/<?php echo $product['image']; ?>" 
                     alt="<?php echo $product['name']; ?>" 
                     class="img-fluid product-detail-image">
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-md-8">
            <h1 class="mb-3"><?php echo $product['name']; ?></h1>
            <p class="text-success h3 mb-3">₹<?php echo number_format($product['price'], 2); ?></p>
            <p class="text-muted mb-4"><?php echo $product['description']; ?></p>
            
            <?php if ($product['stock'] > 0): ?>
                <form action="add_to_cart.php" method="POST" class="mb-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-success">Add to Cart</button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">This product is currently out of stock.</div>
            <?php endif; ?>
            
            <div class="product-details mt-4">
                <h4>Product Details</h4>
                <ul class="list-unstyled">
                    <li><strong>Category:</strong> <?php echo $product['category_name']; ?></li>
                    <li><strong>Stock:</strong> <?php echo $product['stock']; ?> available</li>
                    <?php if (isset($product['care_level']) && !empty($product['care_level'])): ?>
                        <li><strong>Care Level:</strong> <?php echo $product['care_level']; ?></li>
                    <?php endif; ?>
                    <?php if (isset($product['light_requirements']) && !empty($product['light_requirements'])): ?>
                        <li><strong>Light Requirements:</strong> <?php echo $product['light_requirements']; ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="related-products mt-5">
        <h2 class="mb-4">Related Products</h2>
        <div class="row">
            <?php foreach ($related_products as $related): ?>
                <?php if ($related['id'] != $product['id']): ?>
                <div class="col-md-3">
                    <div class="card product-card h-100">
                        <img src="/mp/assets/images/products/<?php echo $related['image']; ?>" 
                             class="card-img-top" 
                             alt="<?php echo $related['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $related['name']; ?></h5>
                            <p class="card-text text-success">₹<?php echo number_format($related['price'], 2); ?></p>
                            <a href="product.php?id=<?php echo $related['id']; ?>" 
                               class="btn btn-outline-success w-100">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 