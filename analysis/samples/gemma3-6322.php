

<?php
session_start();

// Function to add an item to the cart
function addToCart($productId, $productName, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart as an empty array
    }

    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'quantity' => $quantity
        ];
    } else {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    }
}

// Function to update quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to get the cart items
function getCartItems() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalItemPrice = $item['price'] * $item['quantity'];
            $total += $totalItemPrice;
        }
    }
    return $total;
}

//  Example Usage (Simulating a product and adding to the cart)
$productId = 123;
$productName = "Awesome T-Shirt";
$price = 25.00;

addToCart($productId, $productName, $price);
$cartItems = getCartItems();

echo "<h2>Cart Items:</h2>";
if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $item) {
        echo "<li>" . $item['product_name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
}

echo "<p>Total Cart Value: $" . number_format(calculateTotal(), 2) . "</p>";


//  Example: Updating quantity
updateQuantity($productId, 2);
echo "<p>Updated cart after adding another shirt.</p>";
$cartItems = getCartItems();
echo "<h2>Cart Items (after update):</h2>";
foreach ($cartItems as $item) {
    echo "<li>" . $item['product_name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
}


// Example: Removing an item
removeCartItem($productId);
echo "<p>Item removed from cart.</p>";
$cartItems = getCartItems();
echo "<h2>Cart Items (after remove):</h2>";
if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    foreach ($cartItems as $item) {
        echo "<li>" . $item['product_name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
}

?>
