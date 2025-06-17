

<?php
session_start();

// 1. Define Cart Data
$cart = []; // Initialize an empty cart array

// 2. Add Items to the Cart
// Example: Add a product with ID 1 and quantity 2
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate quantity (optional but recommended)
    if (is_numeric($quantity) && $quantity > 0) {
        $quantity = (int)$quantity; // Convert to integer
    } else {
        $quantity = 1; // Default to 1 if invalid input
    }


    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] += $quantity;
    } else {
        $cart[$product_id] = ['quantity' => $quantity, 'name' => 'Product ' . $product_id]; // Added product name for context
    }
}


// 3. Display the Cart (for demonstration)
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Shopping Cart</title>";
echo "</head>";
echo "<body>";

echo "<h1>Shopping Cart</h1>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_info) {
        echo "<li>";
        echo "Product ID: " . $product_id . "<br>";
        echo "Product Name: " . $product_info['name'] . "<br>";
        echo "Quantity: " . $product_info['quantity'] . "<br>";
        //  Optional:  Add a link to remove the item from the cart
        echo "<a href='cart.php?remove=" . $product_id . "'>Remove</a>";
        echo "</li>";
    }
    echo "</ul>";
}

echo "<br>";
echo "<a href='checkout.php'>Checkout</a>"; // Add a link to the checkout page

echo "</body>";
echo "</html>";


// 4.  Checkout Page (example) -  Simplified
if (isset($_GET['checkout'])) {
    echo "<h2>Checkout</h2>";
    echo "<p>Thank you for your order! (This is a placeholder)</p>";
    // In a real application, you'd process the order data here
    // and interact with a database or payment gateway.
}

?>
