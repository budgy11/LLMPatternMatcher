
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>

    <h1>Online Store</h1>

    <h2>Products</h2>
    <ul>
        <li><a href="?action=view_cart&product_id=1">Laptop</a> - $1200</li>
        <li><a href="?action=view_cart&product_id=2">Mouse</a> - $25</li>
        <li><a href="?action=view_cart&product_id=3">Keyboard</a> - $75</li>
        <li><a href="?action=view_cart&product_id=4">Monitor</a> - $300</li>
    </ul>

    <form method="post" action="cart.php" >
        <h2>Add to Cart</h2>
        <label for="product_id">Product ID:</label>
        <select name="product_id" id="product_id">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
        <input type="submit" name="action" value="add_to_cart">
    </form>

</body>
</html>


<?php
session_start();

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------- Functions -------------------

/**
 * Adds an item to the shopping cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0; // Initialize count for new products
    }
    $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function update_cart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


/**
 * Removes an item from the shopping cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Calculates the subtotal for a single item in the cart.
 *
 * @param int $product_id The ID of the product.
 * @return float
 */
function calculate_subtotal($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return $_SESSION['cart'][$product_id] * $product_price[$product_id]; // Assuming $product_price is defined
    }
    return 0;
}

/**
 * Calculates the total price of the cart.
 *
 * @return float
 */
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $total_item = calculate_subtotal($product_id);
        $total += $total_item;
    }
    return $total;
}

/**
 *  Gets the product price
 * @param int $product_id
 * @return float
 */
function get_product_price($product_id) {
    // Assuming you have a product price array (e.g., $product_price) defined elsewhere
    // This is just a placeholder - you'll need to populate this
    // For example:
    // $product_price = [
    //     1 => 10.00,
    //     2 => 20.00,
    //     3 => 15.00,
    // ];
    return $product_price[$product_id];
}

// ------------------- Example Product Data (Replace with your actual data) -------------------
$product_price = [
    1 => 10.00,
    2 => 20.00,
    3 => 15.00,
];



// ------------------- Handling Actions -------------------

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// Update Cart (Quantity Change)
if (isset($_POST['update_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    update_cart($product_id, $quantity);
}

// Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = (int)$_GET['remove_from_cart'];
    remove_from_cart($product_id);
}



// ------------------- Cart Display -------------------

// Output Cart Contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_name = "Product " . $product_id; // Replace with your actual product name retrieval
        echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Price: $" . get_product_price($product_id) . " - Subtotal: $" . calculate_subtotal($product_id) . " <button onclick=\"remove_item(" . $product_id . ")\" class=\"remove\">Remove</button></li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculate_total() . "</strong></p>";
}



// ------------------- JavaScript for Remove Button (Optional) -------------------

echo "<script>
function remove_item(product_id) {
  window.location.href = 'cart.php?remove_from_cart=" . $product_id . "'";
}
</script>";

?>
