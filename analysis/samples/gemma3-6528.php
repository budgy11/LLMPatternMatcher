

<?php
session_start();

// This is the cart array.  It will hold all the items and their quantities.
$cart = array();

// Functions to handle cart operations
function addToCart($product_id, $quantity = 1)
{
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = 0;
    }
    $cart[$product_id] += $quantity;
}

function getCart()
{
    return $cart;
}

function clearCart()
{
    $cart = array();
}

function cartTotal($cart)
{
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        // Assuming you have a way to get product prices.  This is a placeholder.
        $price = getProductPrice($product_id); 
        $totalForProduct = $price * $quantity;
        $totalForProduct = $price * $quantity;
        $total += $totalForProduct;
    }
    return $total;
}

// --- Example Usage / Cart Interaction (Demonstration) ---

// 1. Adding Items to the Cart
$product1_id = 1;
$product1_name = "T-Shirt";
$product1_price = 20;

$product2_id = 2;
$product2_name = "Jeans";
$product2_price = 50;

// Add a T-Shirt to the cart (quantity 2)
addToCart($product1_id, 2);

// Add some Jeans to the cart (quantity 1)
addToCart($product2_id, 1);

// 2. Displaying the Cart Contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        // Display product details (replace with your product data retrieval)
        $product_name = getProductName($product_id);  // Replace with your method
        echo "<li>$product_name (Quantity: $quantity) - Price: $product_price</li>";
    }
    echo "</ul>";
    echo "<p>Total: " . number_format(cartTotal($cart)) . "</p>";  // Use cartTotal()
}

// 3. Clearing the Cart (Example)
// clearCart();
// echo "<p>Cart cleared.</p>";

?>
