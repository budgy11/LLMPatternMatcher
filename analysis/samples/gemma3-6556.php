

<?php
session_start();

// Example product information (replace with your actual database or product data)
$product_name = "Awesome T-Shirt";
$product_price = 25.00;
$product_id = 123; // Unique product ID

// Check if the cart is initialized (i.e., if it exists in the session)
if (!isset($_SESSION['cart'])) {
    // Initialize an empty cart array if it doesn't exist
    $_SESSION['cart'] = array();
}

// 1. Add Product to Cart

// Check if the product ID is already in the cart
if (isset($_POST['action']) == 'add' && $_POST['product_id'] == $product_id) {
    // Increment the quantity of the product in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add the product to the cart with quantity 1
        $_SESSION['cart'][$product_id] = array('quantity' => 1);
    }
}

// 2. Display Cart Contents (for demonstration)

// Calculate the total price
$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        $total_price += $product_data['quantity'] * $product_price;
    }
}

echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        echo "<li>Product: " . $product_name . "<br>";
        echo "  Quantity: " . $product_data['quantity'] . "<br>";
        echo "  Price: $" . number_format($product_price, 2) . "<br>";
        echo "  Total for this item: $" . number_format($product_data['quantity'] * $product_price, 2) . "<br>";
        echo "  <a href='cart_update.php?product_id=" . $product_id . "&action=remove'>Remove</a><br>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format($total_price, 2) . "</p>";
}

?>
