<?php
require_once 'includes/header.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $cart_id => $quantity) {
            $cart_id = (int)$cart_id;
            $quantity = (int)$quantity;
            
            if ($quantity > 0) {
                $update_query = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id";
                $conn->query($update_query);
            } else {
                $delete_query = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
                $conn->query($delete_query);
            }
        }
        header("Location: cart.php");
        exit();
    } elseif (isset($_POST['checkout'])) {
        header("Location: checkout.php");
        exit();
    }
}

// Get cart items with product details
$query = "SELECT c.*, p.name, p.price, p.image, p.stock 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = $user_id";
$result = $conn->query($query);

$cart_items = [];
$total = 0;

if ($result) {
    while ($item = $result->fetch_assoc()) {
        $cart_items[] = $item;
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<div class="container my-4">
    <h1 class="mb-4">Shopping Cart</h1>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">
            Your cart is empty. <a href="products.php">Continue shopping</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="/mp/assets/images/products/<?php echo $item['image']; ?>" 
                                         alt="<?php echo $item['name']; ?>" 
                                         class="img-thumbnail me-3" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <h5 class="mb-0"><?php echo $item['name']; ?></h5>
                                        <small class="text-muted">Stock: <?php echo $item['stock']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <form action="update_cart_quantity.php" method="POST" class="d-inline">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                                    </form>
                                    <span class="mx-2"><?php echo $item['quantity']; ?></span>
                                    <form action="update_cart_quantity.php" method="POST" class="d-inline">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to remove this item?')">
                                    Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>₹<?php echo number_format($total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="products.php" class="btn btn-outline-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?> 