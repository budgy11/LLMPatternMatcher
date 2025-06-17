    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php
session_start();

// Configuration (Adjust these as needed)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00, 'quantity' => 1],
    2 => ['name' => 'Jeans', 'price' => 50.00, 'quantity' => 1],
    3 => ['name' => 'Hat', 'price' => 15.00, 'quantity' => 1],
];

// Cart initialization
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Helper Functions
function add_to_cart($product_id, $quantity) {
    global $products;

    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

function get_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return round($total, 2);
}

function display_cart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . get_cart_total() . "</strong></p>";
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    add_to_cart($product_id, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Handle Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = (int)$_GET['remove_from_cart'];
    remove_from_cart($product_id);
    header("Location: cart.php");
    exit();
}

// Handle Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    update_cart_quantity($product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Display the cart
?>
