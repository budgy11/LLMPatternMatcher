

<?php
session_start();

// Session variables for the cart
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (isset($_SESSION['cart'][$product_id])) {
        // Item already exists in the cart, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Function to calculate the cart total
function calculateCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}


// --- Example Usage / Dummy Data (Simulating product data) ---
$products = array(
    1 => array('name' => 'T-Shirt', 'price' => 20),
    2 => array('name' => 'Jeans', 'price' => 50),
    3 => array('name' => 'Hat', 'price' => 15)
);

// --- Add some items to the cart ---
addToCart(1, $products[1]['name'], $products[1]['price']); // Add 1 T-Shirt
addToCart(2, $products[2]['name'], $products[2]['price']); // Add 1 Jeans
addToCart(1, $products[1]['name'], $products[1]['price'], 2); // Add 2 T-Shirts
addToCart(3, $products[3]['name'], $products[3]['price']);  // Add 1 Hat


// --- Display the cart contents ---
$cart_contents = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . calculateCartTotal() . "</strong></p>";
}

// --- Example of updating quantity ---
//updateQuantity(1, 3);  // Change the quantity of T-Shirts to 3
//echo "<p>Updated T-Shirt quantity to 3.</p>";
//echo "<p>Updated Total: $" . calculateCartTotal() . "</p>";

// --- Example of removing an item ---
//removeFromCart(2); // Remove Jeans
//echo "<p>Removed Jeans from cart.</p>";
//echo "<p>Updated Total: $" . calculateCartTotal() . "</p>";

?>
