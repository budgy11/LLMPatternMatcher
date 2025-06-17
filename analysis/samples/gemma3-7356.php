
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

    <h1>Purchase</h1>

    <form method="post" action="">
        <label for="cart_id">Cart ID:</label>
        <input type="number" id="cart_id" name="cart_id" required>
        <br><br>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>
        <br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required>
        <br><br>

        <label for="shipping_address">Shipping Address:</label>
        <textarea id="shipping_address" name="shipping_address" rows="4" cols="50" required></textarea>
        <br><br>

        <button type="submit">Place Order</button>
    </form>

</body>
</html>


<?php

// Sample product data (replace with your database connection)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

$cart = []; // Initialize an empty cart

// Function to add an item to the cart
function addToCart($productId, $cart) {
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $cart[] = $product;
        return true;
    } else {
        return false; // Product not found
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . "</li>";
    }
    echo "</ul>";

    // Calculate total
    $total = array_sum(array_column($cart, 'price'));
    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}

// Function to handle the purchase (simplified)
function purchase($cart) {
    if (empty($cart)) {
        echo "<p>Please add items to your cart before proceeding to purchase.</p>";
        return;
    }

    // In a real application, you'd process payment, update inventory, etc.
    echo "<p>Your order has been placed!</p>";
    echo "<p>Thank you for your purchase.</p>";
}

// ---  Example Usage/Routing  ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Example: Add item to cart
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        addToCart($productId, $cart);
        echo "<p>Item added to cart.</p>";
    }

    // Display the cart
    displayCart($cart);

    // Example: Handle purchase
    if (isset($_POST['purchase_order'])) {
        purchase($cart);
    }

} else {
    // Initial display of the cart
    displayCart($cart);
}

?>
