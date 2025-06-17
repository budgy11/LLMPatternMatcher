
</body>
</html>


<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
            'total_price' => $price * $quantity // Calculate total price
        );
    } else {
        // If the item already exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['total_price'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}


// Example Usage (Simulating a product and adding it to the cart)

// Product details
$product_id = 1;
$product_name = "Awesome T-Shirt";
$price = 25.00;

// Add the product to the cart
addToCart($product_id, $product_name, $price);

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . $item_data['total_price'];
        echo " <button onclick=\"updateQuantity('" . $item_id . "', 1)">+</button> ";
        echo " <button onclick=\"updateQuantity('" . $item_id . "', -1)\">-</button> ";
        echo " <button onclick=\"removeCartItem('" . $item_id . "')\">Remove</button>";
        echo "</li>";
    }
    echo "</ul>";
}

?>
