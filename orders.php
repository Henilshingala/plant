<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all orders
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($query);

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container">
    <h2 class="mb-4">My Orders</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while($order = $result->fetch_assoc()): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Order #<?php echo $order['id']; ?></h5>
                        <small class="text-muted">Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></small>
                    </div>
                    <div>
                        <span class="badge bg-<?php 
                            echo $order['status'] == 'pending' ? 'warning' : 
                                ($order['status'] == 'processing' ? 'info' : 
                                ($order['status'] == 'shipped' ? 'primary' : 'success')); 
                        ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    // Get order items
                    $items_query = "SELECT oi.*, p.name, p.image 
                                   FROM order_items oi 
                                   JOIN products p ON oi.product_id = p.id 
                                   WHERE oi.order_id = {$order['id']}";
                    $items_result = $conn->query($items_query);
                    ?>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($item = $items_result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/products/<?php echo $item['image']; ?>" 
                                                     alt="<?php echo $item['name']; ?>" 
                                                     class="img-fluid me-3" 
                                                     style="max-width: 80px; height: auto;">
                                                <?php echo $item['name']; ?>
                                            </div>
                                        </td>
                                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-end">
                        <a href="order_confirmation.php?id=<?php echo $order['id']; ?>" 
                           class="btn btn-success btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Order #<?php echo $order['id']; ?></span>
                        <span class="badge bg-<?php echo getStatusColor($order['status']); ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    <?php if (isAdmin()): ?>
                        <form action="update_order_status.php" method="POST" class="mt-2">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <div class="input-group">
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">
            You haven't placed any orders yet. <a href="products.php">Start shopping</a>
        </div>
    <?php endif; ?>
</div>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.table img {
    border-radius: 4px;
    object-fit: cover;
}

.badge {
    padding: 0.5em 1em;
}
</style>

<?php require_once 'includes/footer.php'; ?> 