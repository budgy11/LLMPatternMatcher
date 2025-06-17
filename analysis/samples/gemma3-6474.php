

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
            'total' => $price * $quantity
        );
    } else {
        // Item already in the cart, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}


// Example usage:

// 1. Add an item to the cart
// addToCart(1, 'Laptop', 1200, 1);  // Product ID 1, 'Laptop', $1200, Quantity 1

// 2. Add another item to the cart
// addToCart(2, 'Mouse', 25, 2); // Product ID 2, 'Mouse', $25, Quantity 2


// 3. Update the quantity of an existing item
// updateQuantity(1, 3); // Update the quantity of product 1 to 3

// 4. Remove an item from the cart
// removeItemFromCart(2);



// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $item['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . number_format($item['total'], 2) . "</li>";
    }
    echo "</ul>";
}


?>
