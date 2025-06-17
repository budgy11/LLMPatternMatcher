
<!--
Add to Cart Form (add_to_cart.php)
<!DOCTYPE html>
<html>
<head>
    <title>Add to Cart</title>
</head>
<body>

    <h1>Add Product to Cart</h1>

    <form method="post" action="shopping_cart.php">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1">
        <button type="submit">Add to Cart</button>
    </form>

</body>
</html>
-->


<?php
session_start();

// Configuration
$items = array(
    "Product 1" => array("price" => 10.00, "quantity" => 1),
    "Product 2" => array("price" => 20.00, "quantity" => 1),
    "Product 3" => array("price" => 5.00, "quantity" => 2)
);

// Cart initialization
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

// Add item to cart
function add_to_cart($product_name, $quantity = 1) {
    global $items;

    if (isset($items[$product_name])) {
        if (isset($_SESSION['cart'][$product_name])) {
            $_SESSION['cart'][$product_name]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_name] = array("price" => $items[$product_name]['price'], "quantity" => $quantity);
        }
    } else {
        echo "<p style='color:red;'>Product '$product_name' not found.</p>";
    }
}


// Calculate total cart value
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Cart Actions based on user input ---

// Add item to cart (handled via form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        add_to_cart($_POST['product_name'], $_POST['quantity']);
    }
}

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $product_name = $_POST['remove_item'];
    if (isset($_SESSION['cart'][$product_name])) {
        unset($_SESSION['cart'][$product_name]);
    }
}

// Clear the entire cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}

// --- Display Cart Content ---

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['quantity'] . " x " . $item['price'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format(calculate_total(), 2) . "</strong></p>";

    // Remove Item Button (for each item)
    echo "<form method='post'>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<label for='remove_" . $item['price'] . "'>Remove " . $item['price'] . "</label> ";
        echo "<input type='submit' name='remove_item' value='Remove' class='remove-button' id='remove_" . $item['price'] . "'>";
    }
    echo "</form>";

    // Clear Cart Button
    echo "<form method='post'>";
    echo "<input type='submit' name='clear_cart' value='Clear Cart'>";
    echo "</form>";
}
?>
