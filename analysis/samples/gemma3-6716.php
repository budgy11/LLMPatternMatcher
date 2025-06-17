

<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If the product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity,
            'name' => $product_name,
            'price' => $price
        );
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to get the cart items
function getCartItems() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// --- Example Usage (Simulating a product ID, quantity, etc.) ---

// Add a product to the cart
addToCart(123, 2, "Awesome T-Shirt", 20); // Product ID 123, quantity 2, name "Awesome T-Shirt", price 20

// Update the quantity of product 123 to 5
updateCartQuantity(123, 5);

// Get the cart items
$cart_items = getCartItems();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// Calculate and display the total cart value
$total = calculateCartTotal();
echo "<p>Total Cart Value: $" . $total . "</p>";

// Remove product 123
removeCartItem(123);

// Get and display cart items after removing an item
$cart_items = getCartItems();
echo "<h2>Cart Items After Removal:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}
?>
