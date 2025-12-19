<?php
require_once 'includes/header.php';

// Get category filter
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Build query
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.stock > 0";

if ($category_id > 0) {
    $query .= " AND p.category_id = $category_id";
}

$query .= " ORDER BY p.created_at DESC";
$result = $conn->query($query);

// Get categories for filter
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);
?>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-md-4">
        <form method="GET" class="d-flex">
            <select name="category" class="form-select me-2" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php while($category = $categories_result->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo $category['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>
</div>

<!-- Products Grid -->
<div class="row">
    <?php if ($result->num_rows > 0): ?>
        <?php while($product = $result->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <div class="card product-card h-100">
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
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                No products found in this category.
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?> 