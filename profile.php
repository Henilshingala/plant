<?php
session_start();
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// Get order statistics
$stats_query = "SELECT 
                COUNT(*) as total_orders,
                SUM(total_amount) as total_spent,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_orders,
                SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped_orders,
                SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders
                FROM orders 
                WHERE user_id = $user_id";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get recent orders
$recent_orders_query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
$recent_orders_result = $conn->query($recent_orders_query);

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container">
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-success"></i>
                    </div>
                    <h4 class="card-title"><?php echo htmlspecialchars($user['full_name']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit Profile
                    </button>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Delivery Address</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Street:</strong> <?php echo htmlspecialchars($user['address'] ?? 'Not set'); ?></p>
                    <p class="mb-1"><strong>City:</strong> <?php echo htmlspecialchars($user['city'] ?? 'Not set'); ?></p>
                    <p class="mb-1"><strong>State:</strong> <?php echo htmlspecialchars($user['state'] ?? 'Not set'); ?></p>
                    <p class="mb-1"><strong>ZIP:</strong> <?php echo htmlspecialchars($user['zip_code'] ?? 'Not set'); ?></p>
                    <button class="btn btn-outline-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                        Edit Address
                    </button>
                </div>
            </div>
        </div>

        <!-- Order Statistics -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card">
                                <h3><?php echo $stats['total_orders']; ?></h3>
                                <p class="text-muted">Total Orders</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card">
                                <h3>₹<?php echo number_format($stats['total_spent'], 2); ?></h3>
                                <p class="text-muted">Total Spent</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card">
                                <h3><?php echo $stats['pending_orders']; ?></h3>
                                <p class="text-muted">Pending</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card">
                                <h3><?php echo $stats['delivered_orders']; ?></h3>
                                <p class="text-muted">Delivered</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($order = $recent_orders_result->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $order['status'] == 'pending' ? 'warning' : 
                                                    ($order['status'] == 'processing' ? 'info' : 
                                                    ($order['status'] == 'shipped' ? 'primary' : 'success')); 
                                            ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="order_confirmation.php?id=<?php echo $order['id']; ?>" 
                                               class="btn btn-success btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_profile.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Delivery Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_address.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Street Address</label>
                        <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" class="form-control" name="zip_code" value="<?php echo htmlspecialchars($user['zip_code'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card h3 {
    color: #198754;
    margin-bottom: 5px;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table img {
    max-width: 50px;
    height: auto;
}

.badge {
    padding: 0.5em 1em;
}
</style>

<?php require_once 'includes/footer.php'; ?> 